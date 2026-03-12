<div class="container-fluid py-4"> 

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h3 class="mb-0 text-primary fw-bold">Hola, <?= htmlspecialchars($usuario) ?></h3>
            <?php 
                $bgRol = (strtolower($rolTexto) === 'admin' || strtolower($rolTexto) === 'administrador') ? 'bg-danger' : 'bg-info';
            ?>
            <span class="badge <?= $bgRol ?> bg-opacity-10 text-<?= $bgRol === 'bg-danger' ? 'danger' : 'info' ?> border border-<?= $bgRol === 'bg-danger' ? 'danger' : 'info' ?> rounded-pill px-3">
                <i class="bi bi-shield-lock me-1"></i> <?= htmlspecialchars($rolTexto) ?>
            </span>
        </div>
        <p class="text-muted mb-0 mt-1">
           Resumen de actividad para <strong><?= htmlspecialchars($empresa) ?></strong>
        </p>
    </div>
    <div>
        <a href="/marketing/public/campañas/nueva" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Crear Campaña
        </a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl"> 
      <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Total Leads</small>
                  <h3 class="mb-0 fw-bold text-dark"><?= (int)$leads ?></h3>
              </div>
              <div class="text-info opacity-50"><i class="bi bi-people-fill fs-1"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Campañas Activas</small>
                  <h3 class="mb-0 fw-bold text-dark"><?= (int)$campaniasActivas ?></h3>
                  <small class="text-success" style="font-size: 0.75rem;">De <?= (int)$campaniasTotal ?> totales</small>
              </div>
              <div class="text-success opacity-50"><i class="bi bi-megaphone-fill fs-1"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Inversión Activa</small>
                  <h3 class="mb-0 fw-bold text-dark">$<?= number_format($presupuestoActivo, 0) ?></h3>
                  <small class="text-warning text-dark" style="font-size: 0.75rem;">Presupuesto en curso</small>
              </div>
              <div class="text-warning opacity-50"><i class="bi bi-currency-dollar fs-1"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
        <div class="card-body p-3">
           <div class="d-flex justify-content-between align-items-center">
              <div>
                  <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Estrategias</small>
                  <h3 class="mb-0 fw-bold text-dark"><?= (int)$estrategiasTotal ?></h3>
                  <small class="text-primary" style="font-size: 0.75rem;"><?= (int)$estrategiasAprobadas ?> aprobadas</small>
              </div>
              <div class="text-primary opacity-50"><i class="bi bi-list-check fs-1"></i></div>
           </div>
        </div>
      </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl">
      <div class="card shadow-sm border-0 border-start border-4 border-secondary h-100">
        <div class="card-body p-3">
           <div class="d-flex justify-content-between align-items-center">
              <div>
                  <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Servicios</small>
                  <h3 class="mb-0 fw-bold text-dark"><?= (int)$serviciosActivos ?></h3>
                  <small class="text-muted" style="font-size: 0.75rem;">En catálogo</small>
              </div>
              <div class="text-secondary opacity-50"><i class="bi bi-box-seam fs-1"></i></div>
           </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Actividad Reciente</h6>
            <a href="/marketing/public/campañas" class="btn btn-sm btn-light text-primary fw-bold">Ver todo</a>
            </div>
            <div class="card-body p-0">
            <?php if (!empty($ultimasCampanias)): ?>
                <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">Campaña</th>
                        <th>Estado</th>
                        <th class="text-center">Avance</th>
                        <th class="text-end pe-4"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ultimasCampanias as $c): ?>
                        <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;"><?= htmlspecialchars($c['nombre']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($c['servicio'] ?? 'General') ?></small>
                        </td>
                        <td>
                            <?php 
                                $estadoClass = match(strtolower($c['estado'])) {
                                    'activo', 'activa' => 'success',
                                    'pausado', 'pausada' => 'warning text-dark',
                                    'finalizado', 'finalizada' => 'secondary',
                                    default => 'light text-dark border'
                                };
                            ?>
                            <span class="badge bg-<?= $estadoClass ?> rounded-pill">
                            <?= htmlspecialchars(ucfirst($c['estado'] ?? '-')) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span class="fw-bold text-dark"><?= (int)($c['total_estrategias'] ?? 0) ?></span>
                                <span class="text-muted" style="font-size:0.7rem;">tácticas</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <a class="btn btn-sm btn-outline-secondary border-0"
                            href="/marketing/public/campañas/estrategias?id=<?= (int)$c['id'] ?>">
                            <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="text-muted mb-2"><i class="bi bi-inbox fs-2"></i></div>
                    <p class="text-muted small">No hay campañas recientes.</p>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bar-chart-line me-2"></i>Top Canales (Leads)</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($topCanales)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topCanales as $canal): ?>
                            <?php 
                                $nombre = htmlspecialchars($canal['canal']);
                                $total = (int)$canal['total'];
                                $icon = 'bi-chat-left';
                                if(stripos($nombre, 'facebook')!==false) $icon='bi-facebook text-primary';
                                elseif(stripos($nombre, 'whatsapp')!==false) $icon='bi-whatsapp text-success';
                                elseif(stripos($nombre, 'instagram')!==false) $icon='bi-instagram text-danger';
                                elseif(stripos($nombre, 'linkedin')!==false) $icon='bi-linkedin text-info';
                            ?>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="<?= $icon ?> fs-5 me-3"></i>
                                    <span class="fw-medium text-dark"><?= $nombre ?></span>
                                </div>
                                <span class="badge bg-light text-dark border rounded-pill px-3"><?= $total ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted small">
                        Registra leads para ver estadísticas de canales.
                    </div>
                <?php endif; ?>
                
                <div class="mt-3 pt-3 border-top text-center">
                    <a href="/marketing/public/clientes" class="text-decoration-none small fw-bold">Ir a Gestión de Leads &rarr;</a>
                </div>
            </div>
        </div>
    </div>
  </div>

</div>