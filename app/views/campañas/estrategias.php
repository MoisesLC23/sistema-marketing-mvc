<?php
$progreso = $progreso ?? 0;
?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="/marketing/public/campañas">Campañas</a></li>
                    <li class="breadcrumb-item active">Estrategia</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-primary mb-0"><?= htmlspecialchars($campania['nombre']) ?></h3>
        </div>
        
        <div class="d-flex gap-2">
            <?php if (!$plan): ?>
                <form method="POST" action="/marketing/public/campañas/generar" id="form-generar-plan">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                    <input type="hidden" name="campaña_id" value="<?= (int)$campania['id'] ?>">
                    <button type="submit" class="btn btn-success text-white shadow-sm fw-bold">
                        <i class="bi bi-stars me-1"></i> Generar Plan Maestro
                    </button>
                </form>
            <?php else: ?>
                 <form method="POST" action="/marketing/public/campañas/exportar-pdf" target="_blank">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$campania['id'] ?>">
                    <button class="btn btn-outline-danger shadow-sm"><i class="bi bi-file-pdf-fill"></i> PDF</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($plan): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold m-0 text-success"><i class="bi bi-check2-circle me-2"></i>Progreso de Ejecución</h6>
                <span class="badge bg-success" id="progreso-texto"><?= $progreso ?>%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                     id="progreso-barra"
                     role="progressbar" 
                     style="width: <?= $progreso ?>%"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($plan): ?>
        
        <div class="row g-4">
            
            <div class="col-lg-8">
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded shadow-sm h-100">
                            <small class="text-uppercase text-muted fw-bold" style="font-size:0.7rem">Arquetipo</small>
                            <div class="fw-bold text-primary"><?= htmlspecialchars($plan['analisis_estrategico']['arquetipo_marca']??'') ?></div>
                            <small class="text-muted d-block mt-2 fst-italic">"<?= htmlspecialchars($plan['analisis_estrategico']['mensaje_clave_uvp']??'') ?>"</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded shadow-sm h-100">
                            <small class="text-uppercase text-muted fw-bold" style="font-size:0.7rem">Frecuencia Sugerida</small>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($plan['estrategia_contenidos']['frecuencia_semanal']??'') ?></div>
                            <div class="mt-2">
                                <?php foreach($plan['estrategia_contenidos']['pilares_contenido']??[] as $p): ?>
                                    <span class="badge bg-light text-dark border me-1"><?= htmlspecialchars($p) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-envelope-paper me-2"></i>Estrategia de Nutrición</h5>
                <div class="card shadow-sm border-0 mb-4 border-start border-4 border-warning">
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            <?= htmlspecialchars($plan['puente_nutricion']['descripcion'] ?? 'Secuencia para madurar leads.') ?>
                        </p>
                        <div class="timeline-steps">
                            <?php foreach($plan['puente_nutricion']['secuencia_emails']??[] as $email): ?>
                                <div class="d-flex mb-3 align-items-center">
                                    <div class="me-3 text-center" style="min-width: 60px;">
                                        <div class="badge bg-warning text-dark rounded-pill w-100"><?= htmlspecialchars($email['dia']) ?></div>
                                    </div>
                                    <div class="bg-light p-2 rounded w-100">
                                        <strong class="d-block text-dark small">📧 <?= htmlspecialchars($email['asunto']) ?></strong>
                                        <small class="text-muted" style="font-size: 0.75rem;">Obj: <?= htmlspecialchars($email['objetivo']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-secondary mb-3">Parrilla de Contenidos</h5>
                <div class="row masonry-grid mb-4" data-masonry='{"percentPosition": true }'>
                    <?php foreach ($plan['parrilla_contenidos'] ?? [] as $post): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100 hover-shadow">
                                <div class="card-header bg-white pt-3 pb-0 border-0 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($post['canal']) ?></span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.65rem;"><?= htmlspecialchars($post['etapa']) ?></span>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-bold small mb-2 text-primary"><?= htmlspecialchars($post['titulo_idea']) ?></h6>
                                    <div class="p-2 bg-light rounded border mb-2 position-relative">
                                        <small class="d-block text-muted fst-italic copy-text" style="white-space: pre-line; font-size: 0.8rem;"><?= htmlspecialchars($post['copy_caption']) ?></small>
                                        <button class="btn btn-sm btn-white position-absolute top-0 end-0 m-1 p-0 px-1 border shadow-sm" onclick="copiarAlPortapapeles(this)" title="Copiar"><i class="bi bi-clipboard"></i></button>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-image me-1"></i> <?= htmlspecialchars($post['sugerencia_visual']) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0 fw-bold text-success small"><i class="bi bi-cash me-2"></i>Presupuesto Estimado</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-striped mb-0 small">
                                    <tbody>
                                        <?php foreach($plan['presupuesto_recomendado']['distribucion'] ?? [] as $d): ?>
                                            <tr>
                                                <td class="ps-3"><?= htmlspecialchars($d['concepto']) ?></td>
                                                <td class="text-end pe-3">$<?= number_format((float)$d['monto'], 0) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="fw-bold">
                                            <td class="ps-3">TOTAL</td>
                                            <td class="text-end pe-3">$<?= number_format((float)($plan['presupuesto_recomendado']['total_usd']??0), 2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0 fw-bold text-info small"><i class="bi bi-graph-up me-2"></i>KPIs de Éxito</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush small">
                                    <?php foreach($plan['kpis_metricas'] ?? [] as $kpi): ?>
                                        <li class="list-group-item px-0 py-2 border-bottom-0 d-flex justify-content-between align-items-center">
                                            <span class="text-muted w-50"><?= htmlspecialchars($kpi['kpi']) ?></span>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill"><?= htmlspecialchars($kpi['meta']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-3 text-success"><i class="bi bi-calendar-check me-2"></i>Cronograma Semanal</h5>
                    
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-header bg-white border-bottom">
                            <small class="text-muted">Marca las tareas completadas:</small>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 80vh; overflow-y: auto;">
                            <?php if (empty($estrategias)): ?>
                                <div class="p-3 text-muted small">Genera el plan para ver tus tareas.</div>
                            <?php else: ?>
                                <?php foreach($estrategias as $e): ?>
                                    <label class="list-group-item d-flex gap-3 align-items-start py-3 cursor-pointer action-item">
                                        <input class="form-check-input flex-shrink-0 mt-1 task-checkbox" 
                                               type="checkbox" 
                                               value="<?= $e['id'] ?>" 
                                               <?= !empty($e['completada']) && $e['completada'] ? 'checked' : '' ?>
                                               style="width: 1.2em; height: 1.2em;">
                                        
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold text-dark lh-sm"><?= htmlspecialchars($e['titulo']) ?></small>
                                            </div>
                                            <span class="badge bg-white text-secondary border d-inline-block mb-1" style="font-size: 0.6rem;"><?= htmlspecialchars($e['canal']) ?></span>
                                            <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;"><?= htmlspecialchars($e['propuesta']) ?></small>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php else: ?>
        <div class="text-center py-5 my-5">
            <h2 class="fw-bold">Generar Plan Maestro</h2>
            <p class="text-muted">Obtén estrategia, nutrición y cronograma en segundos.</p>
            <form method="POST" action="/marketing/public/campañas/generar" id="form-generar-plan-empty">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                <input type="hidden" name="campaña_id" value="<?= (int)$campania['id'] ?>">
                <button type="submit" class="btn btn-primary btn-lg shadow fw-bold px-5 rounded-pill">
                    <i class="bi bi-magic me-2"></i> Generar Ahora
                </button>
            </form>
        </div>
    <?php endif; ?>

</div>

<div class="modal fade" id="modalGenerandoPlan" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg text-center p-5">
      <div class="spinner-border text-primary mb-3" role="status"></div>
      <h5 class="fw-bold">Creando Estrategia...</h5>
      <p class="small text-muted">Esto puede tomar hasta 1 minuto.</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.task-checkbox');
    const progressBar = document.getElementById('progreso-barra');
    const progressText = document.getElementById('progreso-texto');

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            actualizarProgreso();
            const id = this.value;
            const completada = this.checked ? 1 : 0;
            const formData = new FormData();
            formData.append('id', id);
            formData.append('completada', completada);
            fetch('/marketing/public/campañas/toggle-completada', { method: 'POST', body: formData });
        });
    });

    function actualizarProgreso() {
        const total = checkboxes.length;
        const checked = document.querySelectorAll('.task-checkbox:checked').length;
        const percent = total > 0 ? Math.round((checked / total) * 100) : 0;
        if(progressBar) {
            progressBar.style.width = percent + '%';
            progressText.innerText = percent + '%';
        }
    }
    
    if (typeof Masonry !== 'undefined') {
        new Masonry('.masonry-grid', { percentPosition: true });
    }
});

const forms = document.querySelectorAll('form[action*="/generar"]');
forms.forEach(f => f.addEventListener('submit', () => {
    new bootstrap.Modal(document.getElementById('modalGenerandoPlan')).show();
}));

function copiarAlPortapapeles(btn) {
    const txt = btn.parentElement.querySelector('.copy-text').innerText;
    navigator.clipboard.writeText(txt);
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check text-success"></i>';
    setTimeout(() => btn.innerHTML = orig, 1500);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>