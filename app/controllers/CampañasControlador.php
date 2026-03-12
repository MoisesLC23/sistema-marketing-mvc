<?php
require_once __DIR__.'/../middleware/csrf.php';
require_once __DIR__ . '/../lib/OpenAIClient.php';
require_once __DIR__ . '/../models/Campaña.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class CampañasControlador {
    private $modelo;

    public function __construct($pdo){
        $this->modelo = new Campaña($pdo);
    }

    /* ===== LISTA ===== */
    public function index(){
        $q = trim($_GET['q'] ?? '');
        $perPage = 7; 
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $total = $this->modelo->contar($q);
        $totalPages = ($total > 0) ? (int)ceil($total / $perPage) : 1;
        if ($page > $totalPages) { $page = $totalPages; $offset = ($page - 1) * $perPage; }

        $campanias = $this->modelo->listar($q, $perPage, $offset);

        $titulo = "Campañas de Marketing";
        $active = "campañas";
        $queryStringBase = http_build_query(['q' => $q]);

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/campañas/lista.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== NUEVA ===== */
    public function nueva(){
        $servicios = $this->modelo->obtenerServiciosActivos();
        $titulo = "Nueva campaña"; $active = "campañas";
        $c = ['id' => null, 'nombre' => '', 'objetivo_tipo' => 'aumentar_ventas', 'objetivo_detalle' => '', 'publico_objetivo' => '', 'presupuesto' => 0, 'fecha_inicio' => date('Y-m-d'), 'fecha_fin' => '', 'servicio_id' => '', 'estado' => 'planificada', 'notas' => '', 'redes_sociales' => json_encode([], JSON_UNESCAPED_UNICODE)];
        
        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/campañas/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== GUARDAR ===== */
    public function guardar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Error: Método no permitido.");
        if (!csrf_check($_POST['csrf'] ?? '')) die("Error: Token inválido.");

        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'objetivo_tipo' => trim($_POST['objetivo_tipo'] ?? 'aumentar_ventas'),
            'objetivo_detalle' => trim($_POST['objetivo_detalle'] ?? ''),
            'publico_objetivo' => $this->construirPublicoObjetivoDesdePost(),
            'presupuesto' => $this->construirPresupuestoDesdePost(),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_fin' => $_POST['fecha_fin'] ?? '',
            'servicio_id' => (int)($_POST['servicio_id'] ?? 0) ?: null,
            'estado' => 'planificada',
            'notas' => trim($_POST['notas'] ?? ''),
            'redes_sociales' => $this->construirRedesDesdePost(),
            'creado_por' => $_SESSION['usuario_id'] ?? null
        ];

        $errores = [];
        if ($datos['nombre'] === '') $errores[] = "El nombre es obligatorio.";
        if ($datos['fecha_inicio'] === '') $errores[] = "Fecha inicio obligatoria.";
        
        // Validación básica de fechas
        if ($datos['fecha_inicio'] !== '' && $datos['fecha_fin'] !== '') {
            if ($datos['fecha_fin'] <= $datos['fecha_inicio']) $errores[] = "La fecha fin debe ser posterior a la de inicio.";
        }

        if (!empty($errores)) {
            $error = implode('<br>', $errores);
            $servicios = $this->modelo->obtenerServiciosActivos();
            $titulo = "Nueva campaña"; $active = "campañas";
            $c = array_merge(['id'=>null], $datos);
            include __DIR__ . '/../views/partials/header.php'; include __DIR__ . '/../views/campañas/form.php'; include __DIR__ . '/../views/partials/footer.php';
            return;
        }

        try {
            $nuevoId = $this->modelo->crear($datos);
            if ($nuevoId) { header("Location: /marketing/public/campañas/estrategias?id=" . $nuevoId); exit; }
        } catch (PDOException $e) { die("Error BD: " . $e->getMessage()); }
    }

    /* ===== EDITAR ===== */
    public function editar(){
        $id = (int)$_GET['id'];
        $c = $this->modelo->obtenerPorId($id);
        if (!$c) { http_response_code(404); exit('Campaña no encontrada'); }
        $servicios = $this->modelo->obtenerServiciosActivos();
        $titulo = "Editar campaña"; $active = "campañas";
        include __DIR__ . '/../views/partials/header.php'; include __DIR__ . '/../views/campañas/form.php'; include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== ACTUALIZAR ===== */
    public function actualizar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }
        $id = (int)$_POST['id'];
        
        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'objetivo_tipo' => $_POST['objetivo_tipo'],
            'objetivo_detalle' => $_POST['objetivo_detalle'],
            'publico_objetivo' => $this->construirPublicoObjetivoDesdePost(),
            'presupuesto' => $this->construirPresupuestoDesdePost(),
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'servicio_id' => (int)$_POST['servicio_id'] ?: null,
            'notas' => $_POST['notas'],
            'redes_sociales' => $this->construirRedesDesdePost()
        ];

        $this->modelo->actualizar($id, $datos);
        header("Location: /marketing/public/campañas"); exit;
    }

    /* ===== ELIMINAR / ESTADO ===== */
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit; }
        $this->modelo->eliminar((int)$_POST['id']);
        header("Location: /marketing/public/campañas"); exit;
    }

    public function cambiarEstado(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit; }
        $this->modelo->cambiarEstado((int)$_POST['id'], $_POST['estado']);
        header("Location: /marketing/public/campañas"); exit;
    }

    /* ===== ESTRATEGIAS (VISTA) ===== */
    public function estrategias(){
        $id = (int)($_GET['id'] ?? 0);
        $campania = $this->modelo->obtenerDetalle($id);
        if (!$campania) { http_response_code(404); exit('Campaña no encontrada'); }

        $estrategias = $this->modelo->obtenerEstrategias($id);
        $total = count($estrategias); 
        $ok = 0; 
        foreach($estrategias as $e) if(!empty($e['completada']) && $e['completada']) $ok++;
        $progreso = $total>0 ? round(($ok/$total)*100) : 0;

        $row = $this->modelo->obtenerPlanReciente($id);
        
        // --- DEPURACIÓN: Asegurar que el JSON se lea bien ---
        $plan = null;
        if ($row && !empty($row['plan_json'])) {
            $plan = json_decode($row['plan_json'], true);
        }

        $errorIA = $_SESSION['error_ia'] ?? null; unset($_SESSION['error_ia']);
        $titulo = "Plan de Marketing"; $active = "campañas";
        include __DIR__ . '/../views/partials/header.php'; include __DIR__ . '/../views/campañas/estrategias.php'; include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== GENERADOR IA (SUPER PROMPT COPYWRITING) ===== */
    public function generar(){
        set_time_limit(300);
        if ($_SERVER['REQUEST_METHOD']!=='POST') exit;
        
        $cid = (int)$_POST['campaña_id'];
        $config = require __DIR__ . '/../config/config.php';
        $apiKey = $config['openai']['api_key'] ?? null;
        
        if(empty($apiKey)){ 
            $_SESSION['error_ia']="Falta API Key"; 
            header("Location: /marketing/public/campañas/estrategias?id=".$cid); exit; 
        }

        $cfg = $this->modelo->obtenerConfiguracionEmpresa();
        $camp = $this->modelo->obtenerPorId($cid);
        
        $duracionStr="4 semanas";
        $numSemanas=4;
        if($camp['fecha_inicio']&&$camp['fecha_fin']){
            $d1=new DateTime($camp['fecha_inicio']); $d2=new DateTime($camp['fecha_fin']);
            $numSemanas=ceil($d1->diff($d2)->days/7); 
            $numSemanas=max(2, min(12, $numSemanas)); 
            $duracionStr="$numSemanas semanas";
        }

        $servicioNombre = $this->modelo->obtenerNombreServicio($camp['servicio_id']) ?: 'General';
        $fort = $this->limpiarDatoContexto($cfg['fortalezas_json']??'');
        $deb  = $this->limpiarDatoContexto($cfg['debilidades_json']??'');
        $comp = $this->limpiarDatoContexto($cfg['competidores_json']??'');

        $empresaInfo="Empresa: {$cfg['nombre_empresa']}. Misión: {$cfg['mision']}. Valores: {$cfg['valores']}.";
        $foda="FODA: Fort: $fort. Deb: $deb.";
        $mercado="Comp: $comp. Público: {$cfg['publico_objetivo']}.";
        $campInfo="Campaña: {$camp['nombre']}. Obj: {$camp['objetivo_tipo']} ({$camp['objetivo_detalle']}). Serv: {$servicioNombre}. Presupuesto: {$camp['presupuesto']} USD. Duración: $duracionStr.";

        // --- PROMPT MEJORADO PARA COPYWRITING ---
        $systemPrompt = <<<EOT
Eres un CMO experto en Copywriting Estratégico. Crea un PLAN DE MARKETING en JSON VÁLIDO.

INSTRUCCIONES CLAVE:
1. Devuelve SOLAMENTE el objeto JSON. No uses markdown.
2. CRONOGRAMA: Semana a semana consecutiva sin saltos hasta cubrir $duracionStr.
3. PRESUPUESTO: Usa claves "concepto" y "monto".

4. PARRILLA DE CONTENIDOS (MÁXIMA IMPORTANCIA):
   - Redacta "copy_caption" LARGOS, ATRACTIVOS y PERSUASIVOS (mínimo 3 líneas, idealmente párrafos bien estructurados). NO uses frases cortas como "Visita nuestra web".
   - DETECTA EL TONO SEGÚN EL PÚBLICO OBJETIVO:
     * Si el público son JÓVENES (18-30): Usa un tono fresco, enérgico, tutea, usa emojis 🔥🚀😎 y hashtags de tendencia. Sé directo y apela a la experiencia.
     * Si el público son ADULTOS/EMPRESAS (B2B o +35): Usa un tono profesional pero cercano, enfocado en beneficios, seguridad, ROI y confianza. Usa emojis sobrios ✅📉🤝.
   - Aplica fórmulas de copywriting (AIDA: Atención, Interés, Deseo, Acción) en cada caption.

ESTRUCTURA JSON:
{
  "analisis_estrategico": { 
    "diagnostico_situacional": "texto", 
    "arquetipo_marca": "texto", 
    "mensaje_clave_uvp": "texto", 
    "buyer_persona": { "nombre": "", "perfil": "", "dolores": [], "deseos": [] } 
  },
  "estrategia_contenidos": { "frecuencia_semanal": "texto", "pilares_contenido": [] },
  "embudo_ventas": { 
    "tofu": { "objetivo": "", "tactica_clave": "", "racional_tactico": "" },
    "mofu": { "objetivo": "", "tactica_clave": "", "racional_tactico": "" },
    "bofu": { "objetivo": "", "tactica_clave": "", "racional_tactico": "" }
  },
  "puente_nutricion": { "descripcion": "", "secuencia_emails": [ { "dia": "", "asunto": "", "objetivo": "" } ] },
  "cronograma_tactico": [ 
      { 
        "semana": "Semana 1", 
        "foco": "...", 
        "acciones": [ 
            { "accion": "Tarea específica", "canal": "..." } 
        ] 
      }
  ],
  "parrilla_contenidos": [ { "etapa": "TOFU/MOFU/BOFU", "canal": "", "formato": "", "titulo_idea": "Título Gancho", "copy_caption": "Texto completo del post...", "sugerencia_visual": "", "objetivo_post": "" } ],
  "presupuesto_recomendado": { 
      "total_usd": {$camp['presupuesto']}, 
      "distribucion": [ { "concepto": "Item", "monto": 0 } ], 
      "nota_estrategica": "" 
  },
  "kpis_metricas": [ { "kpi": "Indicador", "meta": "Valor", "frecuencia": "", "formula": "" } ]
}
EOT;
        $userPrompt = "CONTEXTO:\n$empresaInfo\n$foda\n$mercado\n\nCAMPAÑA:\n$campInfo\n\nGenera el plan JSON detallado. Recuerda: Copys largos y tono adaptado al público.";

        try {
            $client = new OpenAIClient($apiKey);
            $response = $client->chatCompletions([
                'model' => 'gpt-4o', 
                'messages' => [['role'=>'system','content'=>$systemPrompt], ['role'=>'user','content'=>$userPrompt]],
                'temperature' => 0.75, 'max_tokens' => 4000, 'response_format' => ['type' => 'json_object']
            ]);

            $content = $response['choices'][0]['message']['content'] ?? '';
            
            // --- LIMPIEZA CRÍTICA ---
            $content = str_replace(['```json', '```'], '', $content);
            $content = trim($content);

            $plan = json_decode($content, true);
            if (!$plan) throw new Exception("La IA no devolvió un JSON válido. Intenta de nuevo.");

            $this->modelo->guardarPlan($cid, json_encode($plan, JSON_UNESCAPED_UNICODE));
            $this->modelo->limpiarEstrategias($cid);
            
            $orden = 1;
            if (!empty($plan['cronograma_tactico'])) {
                foreach ($plan['cronograma_tactico'] as $sem) {
                    foreach ($sem['acciones'] as $acc) {
                        $semanaTexto = $sem['semana'] ?? "Semana $orden";
                        $accionTexto = $acc['accion'] ?? 'Realizar acción estratégica';
                        
                        $titulo    = "[$semanaTexto] $accionTexto";
                        $propuesta = "Foco: " . ($sem['foco'] ?? 'General');
                        $canal     = $acc['canal'] ?? 'Gestión';
                        $cta       = "Marcar listo";
                        
                        $this->modelo->insertarEstrategia($cid, $titulo, $propuesta, $canal, $cta, $orden++);
                    }
                }
            }
            header("Location: /marketing/public/campañas/estrategias?id=".$cid); exit;
        } catch (\Throwable $e) {
            $_SESSION['error_ia'] = "Error: " . $e->getMessage();
            header("Location: /marketing/public/campañas/estrategias?id=".$cid); exit;
        }
    }

    /* ===== AJAX ===== */
    public function toggleCompletada(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $this->modelo->toggleCompletada((int)$_POST['id'], (int)$_POST['completada']);
        echo json_encode(['success' => true]); exit;
    }

    /* ===== EXPORTAR PDF ===== */
    public function exportarPdf() {
        $id = (int)($_POST['id'] ?? 0);
        $c = $this->modelo->obtenerPorId($id);
        $p = $this->modelo->obtenerPlanReciente($id);
        
        if(!$p || empty($p['plan_json'])) die("Error: No se encontró un plan guardado en la base de datos.");
        
        $plan = json_decode($p['plan_json'], true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error crítico: El plan guardado tiene un formato incorrecto.");
        }
        
        ob_start();
        ?>
        <html><head><style>
            body{font-family:sans-serif;font-size:11px;color:#333}
            h1{color:#2c3e50;border-bottom:2px solid #eee;margin-bottom:15px}
            h2{color:#2980b9;margin-top:25px;font-size:14px;border-bottom:1px solid #ddd;padding-bottom:5px}
            .box{background:#f9f9f9;padding:10px;border:1px solid #eee;margin-bottom:10px}
            table{width:100%;border-collapse:collapse;margin-top:10px}
            th,td{border:1px solid #ccc;padding:6px;text-align:left}th{background:#eee}
            .highlight{background:#e8f6f3;padding:8px;border-left:3px solid #1abc9c;margin:5px 0}
        </style></head><body>
            <h1>Plan Maestro: <?= htmlspecialchars($c['nombre']) ?></h1>
            
            <?php if(empty($plan['analisis_estrategico'])): ?>
                <div style="color:red; padding:20px; border:1px solid red;"><h3>Error de Datos</h3><p>El plan está incompleto.</p></div>
            <?php else: ?>

            <h2>1. Análisis Estratégico</h2>
            <div class="box">
                <strong>Diagnóstico:</strong> <?= nl2br(htmlspecialchars($plan['analisis_estrategico']['diagnostico_situacional']??'')) ?><br><br>
                <strong>Arquetipo:</strong> <?= htmlspecialchars($plan['analisis_estrategico']['arquetipo_marca']??'') ?><br>
                <strong>UVP:</strong> <?= htmlspecialchars($plan['analisis_estrategico']['mensaje_clave_uvp']??'') ?>
            </div>
            
            <?php if(!empty($plan['embudo_ventas'])): ?>
            <h2>2. Embudo de Ventas</h2>
            <table>
                <tr><th>Etapa</th><th>Objetivo</th><th>Táctica Clave</th></tr>
                <tr><td>TOFU</td><td><?= htmlspecialchars($plan['embudo_ventas']['tofu']['objetivo']??'') ?></td><td><?= htmlspecialchars($plan['embudo_ventas']['tofu']['tactica_clave']??'') ?></td></tr>
                <tr><td>MOFU</td><td><?= htmlspecialchars($plan['embudo_ventas']['mofu']['objetivo']??'') ?></td><td><?= htmlspecialchars($plan['embudo_ventas']['mofu']['tactica_clave']??'') ?></td></tr>
                <tr><td>BOFU</td><td><?= htmlspecialchars($plan['embudo_ventas']['bofu']['objetivo']??'') ?></td><td><?= htmlspecialchars($plan['embudo_ventas']['bofu']['tactica_clave']??'') ?></td></tr>
            </table>
            <?php endif; ?>

            <h2>3. Parrilla de Contenidos</h2>
            <?php foreach($plan['parrilla_contenidos'] ?? [] as $post): ?>
                <div class="box" style="page-break-inside: avoid;">
                    <strong>[<?= htmlspecialchars($post['etapa']) ?>] <?= htmlspecialchars($post['titulo_idea']) ?></strong> (<?= htmlspecialchars($post['canal']) ?>)<br>
                    <div style="background:#fff;border:1px solid #ddd;padding:5px;margin-top:5px;font-style:italic">
                        <?= nl2br(htmlspecialchars($post['copy_caption'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <h2>4. Cronograma Táctico</h2>
            <?php foreach($plan['cronograma_tactico']??[] as $sem): ?>
                <div class="highlight"><strong><?= htmlspecialchars($sem['semana']) ?> - <?= htmlspecialchars($sem['foco']) ?></strong></div>
                <ul>
                <?php foreach($sem['acciones'] as $ac): ?>
                    <li><?= htmlspecialchars($ac['accion']) ?> (<em><?= htmlspecialchars($ac['canal']) ?></em>)</li>
                <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>

            <h2>5. Presupuesto & KPIs</h2>
            <table>
                <tr><th>Concepto</th><th>Monto</th></tr>
                <?php foreach($plan['presupuesto_recomendado']['distribucion']??[] as $d): 
                    $concepto = $d['concepto'] ?? $d['item'] ?? $d['nombre'] ?? 'N/A';
                    $monto = $d['monto'] ?? $d['costo'] ?? 0;
                ?>
                <tr><td><?= htmlspecialchars($concepto) ?></td><td>$<?= htmlspecialchars($monto) ?></td></tr>
                <?php endforeach; ?>
            </table>
            <br>
            <table>
                <tr><th>KPI</th><th>Meta</th></tr>
                <?php foreach($plan['kpis_metricas']??[] as $k): 
                     $kpiName = $k['kpi'] ?? $k['indicador'] ?? $k['nombre'] ?? 'N/A';
                     $meta = $k['meta'] ?? $k['valor'] ?? '-';
                ?>
                <tr><td><?= htmlspecialchars($kpiName) ?></td><td><?= htmlspecialchars($meta) ?></td></tr>
                <?php endforeach; ?>
            </table>

            <?php endif; ?>
        </body></html>
        <?php
        $html = ob_get_clean();
        require_once __DIR__ . '/../../vendor/autoload.php';
        $dompdf = new Dompdf(['isRemoteEnabled'=>true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Plan_Maestro.pdf", ['Attachment'=>false]);
        exit;
    }

    private function construirPublicoObjetivoDesdePost(): string { $sel = $_POST['publico_objetivo_sel']??''; $otro = trim($_POST['publico_objetivo_otro']??''); return ($sel==='otro') ? ($otro!==''?$otro:'') : $sel; }
    private function construirPresupuestoDesdePost(): float { $sel = $_POST['presupuesto_sel']??''; $otro = (float)($_POST['presupuesto_otro']??0); return match($sel){ '500-1000'=>750.0,'1000-2000'=>1500.0,'2000-4000'=>3000.0,'4000-8000'=>6000.0, default=>max(0,$otro) }; }
    private function construirRedesDesdePost(): ?string { $r = $_POST['redes_sociales']??[]; if(!is_array($r))$r=[]; $r = array_values(array_filter(array_map('trim',$r))); return $r?json_encode($r,JSON_UNESCAPED_UNICODE):null; }
    private function limpiarDatoContexto($d){ if(empty($d))return'No esp.'; $j=json_decode($d,true); if(json_last_error()===JSON_ERROR_NONE&&is_array($j)){ if(isset($j[0])&&is_array($j[0])){$t=[];foreach($j as $i)$t[]=implode(": ",array_map('strval',$i));return implode("; ",$t);} return implode(", ",$j); } return trim(strip_tags($d)); }
}