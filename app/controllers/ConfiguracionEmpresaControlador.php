<?php
require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../models/Empresa.php';

class ConfiguracionEmpresaControlador {
    private $modelo;

    public function __construct($pdo) {
        $this->modelo = new Empresa($pdo);
    }

    public function form() {
        $row = $this->modelo->obtener();

        $cfg = [
            'id'               => $row['id']               ?? null,
            'nombre_empresa'   => $row['nombre_empresa']   ?? '',
            'rubro_sector'     => $row['rubro_sector']     ?? '',
            'publico_objetivo' => $row['publico_objetivo'] ?? '',
            'anios_mercado'    => $row['anios_mercado']    ?? '',
            'ubicacion'        => $row['ubicacion']        ?? '',
            'url_web'          => $row['url_web']          ?? '',
            'whatsapp'         => $row['whatsapp']         ?? '',
            'email_contacto'   => $row['email_contacto']   ?? '',
            'competidores_json'=> $row['competidores_json']?? null,
            
            'acerca_nosotros'  => $row['acerca_nosotros']  ?? '',
            'mision'           => $row['mision']           ?? '',
            'vision'           => $row['vision']           ?? '',
            'valores'          => $row['valores']          ?? '',
            
            'fortalezas_json'    => $row['fortalezas_json']    ?? null,
            'debilidades_json'   => $row['debilidades_json']   ?? null,
            'oportunidades_json' => $row['oportunidades_json'] ?? null,
            'amenazas_json'      => $row['amenazas_json']      ?? null,
        ];

        $competidores_crudos = '';
        if ($cfg['competidores_json']) {
            $arr = json_decode($cfg['competidores_json'], true);
            if (is_array($arr)) {
                $competidores_crudos = implode("\n", array_map(function($c){
                    return ($c['nombre'] ?? '') . ' | ' . ($c['descripcion'] ?? '') . ' | ' . ($c['url'] ?? '');
                }, $arr));
            }
        }

        $fortalezas_crudas    = $cfg['fortalezas_json']    ? implode("\n", json_decode($cfg['fortalezas_json'], true)) : '';
        $debilidades_crudas   = $cfg['debilidades_json']   ? implode("\n", json_decode($cfg['debilidades_json'], true)) : '';
        $oportunidades_crudas = $cfg['oportunidades_json'] ? implode("\n", json_decode($cfg['oportunidades_json'], true)) : '';
        $amenazas_crudas      = $cfg['amenazas_json']      ? implode("\n", json_decode($cfg['amenazas_json'], true)) : '';

        $titulo = "Configuración de la empresa";
        $active = "configuracion-empresa";

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/configuracion_empresa/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $id = (int)($_POST['id'] ?? 0);

        $datos = [
            'nombre_empresa'   => trim($_POST['nombre_empresa']   ?? ''),
            'rubro_sector'     => trim($_POST['rubro_sector']     ?? ''),
            'publico_objetivo' => trim($_POST['publico_objetivo'] ?? ''),
            'anios_mercado'    => trim($_POST['anios_mercado']    ?? ''),
            'ubicacion'        => trim($_POST['ubicacion']        ?? ''),
            'url_web'          => trim($_POST['url_web']          ?? ''),
            'whatsapp'         => trim($_POST['whatsapp']         ?? ''),
            'email_contacto'   => trim($_POST['email_contacto']   ?? ''),
            'acerca_nosotros'  => trim($_POST['acerca_nosotros']  ?? ''),
            'mision'           => trim($_POST['mision']           ?? ''),
            'vision'           => trim($_POST['vision']           ?? ''),
            'valores'          => trim($_POST['valores']          ?? '')
        ];

        $competidores_crudos  = trim($_POST['competidores_crudos']  ?? '');
        $fortalezas_crudas    = trim($_POST['fortalezas_crudas']    ?? '');
        $debilidades_crudas   = trim($_POST['debilidades_crudas']   ?? '');
        $oportunidades_crudas = trim($_POST['oportunidades_crudas'] ?? '');
        $amenazas_crudas      = trim($_POST['amenazas_crudas']      ?? '');

        if ($datos['nombre_empresa'] === '') {

            $this->form(); 
            return;
        }

        $competidores = [];
        if ($competidores_crudos !== '') {
            foreach (preg_split('/\r\n|\r|\n/', $competidores_crudos) as $line) {
                $p = array_map('trim', explode('|', $line));
                if (!empty($p[0])) {
                    $competidores[] = [
                        'nombre'      => $p[0],
                        'descripcion' => $p[1] ?? '',
                        'url'         => $p[2] ?? '',
                    ];
                }
            }
        }
        $datos['competidores_json'] = $competidores ? json_encode($competidores, JSON_UNESCAPED_UNICODE) : null;

        $toJson = function($txt){
            $arr = [];
            foreach (preg_split('/\r\n|\r|\n/', trim($txt)) as $line) {
                $line = trim($line);
                if ($line !== '') $arr[] = $line;
            }
            return $arr ? json_encode($arr, JSON_UNESCAPED_UNICODE) : null;
        };

        $datos['fortalezas_json']    = $toJson($fortalezas_crudas);
        $datos['debilidades_json']   = $toJson($debilidades_crudas);
        $datos['oportunidades_json'] = $toJson($oportunidades_crudas);
        $datos['amenazas_json']      = $toJson($amenazas_crudas);

        if ($id) {
            $this->modelo->actualizar($id, $datos);
        } else {
            $this->modelo->crear($datos);
        }

        header("Location: /marketing/public/configuracion-empresa");
        exit;
    }
}