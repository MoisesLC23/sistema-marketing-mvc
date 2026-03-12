<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary fw-bold mb-0">Campañas de Marketing</h3>
            <p class="text-muted small mb-0">Gestiona tus planes estratégicos.</p>
        </div>
        <a class="btn btn-primary shadow-sm" href="/marketing/public/campañas/nueva">
            <i class="bi bi-plus-lg me-1"></i> Nueva campaña
        </a>
    </div>

    <!-- Buscador -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form class="row g-2 align-items-center" method="GET" action="/marketing/public/campañas">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre, servicio u objetivo..." 
                               value="<?= htmlspecialchars($q ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 fw-bold" type="submit">Buscar</button>
                </div>
                <?php if(!empty($q)): ?>
                <div class="col-md-2">
                    <a href="/marketing/public/campañas" class="btn btn-link text-decoration-none text-muted w-100">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (!empty($campanias)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small" style="width: 5%;">ID</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 25%;">Nombre</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Servicio</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Objetivo</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Estado</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Fechas</th>
                                <th class="text-end pe-4 py-3 text-secondary text-uppercase small" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($campanias as $c): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= (int)$c['id'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($c['nombre']) ?>">
                                            <?= htmlspecialchars($c['nombre']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($c['servicio'] ?? 'General') ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $c['objetivo_tipo']))) ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="/marketing/public/campañas/cambiar-estado" class="d-inline">
                                            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">

                                            <?php 
                                            $estado = $c['estado'] ?? 'planificada';
                                            $claseEstado = match($estado) {
                                                'activo', 'activa' => 'success',
                                                'pausado', 'pausada' => 'warning text-dark',
                                                'finalizado', 'finalizada' => 'secondary',
                                                default => 'primary'
                                            };
                                            ?>
                                            <select name="estado" 
                                                    class="form-select form-select-sm border-<?= $claseEstado ?> text-<?= $claseEstado === 'warning text-dark' ? 'dark' : $claseEstado ?> fw-bold" 
                                                    style="width: 130px; cursor: pointer; font-size: 0.75rem;"
                                                    onchange="this.form.submit()">
                                                <option value="planificada" <?= $estado === 'planificada' ? 'selected' : '' ?>>Planificada</option>
                                                <option value="activa" <?= ($estado === 'activa' || $estado === 'activo') ? 'selected' : '' ?>>🚀 Activa</option>
                                                <option value="pausada" <?= ($estado === 'pausada' || $estado === 'pausado') ? 'selected' : '' ?>>⏸ Pausada</option>
                                                <option value="finalizada" <?= ($estado === 'finalizada' || $estado === 'finalizado') ? 'selected' : '' ?>>🏁 Finalizada</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="text-success"><i class="bi bi-calendar-check me-1"></i><?= htmlspecialchars($c['fecha_inicio']) ?></span>
                                            <?php if($c['fecha_fin']): ?>
                                                <span class="text-muted"><i class="bi bi-calendar-x me-1"></i><?= htmlspecialchars($c['fecha_fin']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <!-- VER -->
                                            <a href="/marketing/public/campañas/estrategias?id=<?= (int)$c['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver Plan y Estrategias">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDITAR -->
                                            <a href="/marketing/public/campañas/editar?id=<?= (int)$c['id'] ?>" 
                                               class="btn btn-sm btn-outline-info border-start-0" 
                                               title="Editar Configuración">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- ELIMINAR -->
                                            <form method="POST" action="/marketing/public/campañas/eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta campaña? Se borrarán todas sus estrategias.');">
                                                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                                                <button class="btn btn-sm btn-outline-danger border-start-0" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Paginación -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="card-footer bg-white py-3">
                    <nav aria-label="Navegación de campañas">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <!-- Flecha Atrás -->
                            <li class="page-item <?= ($page ?? 1) <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= (($page ?? 1) > 1) ? '?page='.($page-1).'&'.($queryStringBase??'') : '#' ?>">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Páginas -->
                            <?php for($i=1; $i<=$totalPages; $i++): ?>
                                <li class="page-item <?= ($i == ($page ?? 1)) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&<?= $queryStringBase ?? '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Flecha Adelante -->
                            <li class="page-item <?= ($page ?? 1) >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= (($page ?? 1) < $totalPages) ? '?page='.($page+1).'&'.($queryStringBase??'') : '#' ?>">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3 text-muted" style="font-size: 3rem;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h5 class="text-muted">No se encontraron campañas</h5>
                    <p class="text-muted small mb-4">Intenta ajustar la búsqueda o crea una nueva.</p>
                    <a href="/marketing/public/campañas/nueva" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Nueva campaña
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>