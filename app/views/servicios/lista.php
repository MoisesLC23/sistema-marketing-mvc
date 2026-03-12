<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary fw-bold mb-0">Catálogo de Servicios</h3>
            <p class="text-muted small mb-0">Gestiona la oferta comercial de la agencia.</p>
        </div>
        <a class="btn btn-primary shadow-sm" href="/marketing/public/servicios/nuevo">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Servicio
        </a>
    </div>

    <!-- Buscador -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form class="row g-2 align-items-center" method="GET" action="/marketing/public/servicios">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar servicio por nombre, slug o descripción..." 
                               value="<?= htmlspecialchars($q ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 fw-bold" type="submit">Buscar</button>
                </div>
                <?php if(!empty($q)): ?>
                <div class="col-md-2">
                    <a href="/marketing/public/servicios" class="btn btn-link text-decoration-none text-muted w-100">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (!empty($servicios)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small" style="width: 5%;">ID</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 25%;">Servicio</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 20%;">Slug (URL)</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 35%;">Descripción</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 10%;">Estado</th>
                                <th class="text-end pe-4 py-3 text-secondary text-uppercase small" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($servicios as $s): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= (int)$s['id'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($s['nombre']) ?></div>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-muted small bg-light px-2 py-1 rounded border">
                                            <?= htmlspecialchars($s['slug']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($s['descripcion']) ?>">
                                            <?= htmlspecialchars($s['descripcion'] ?: 'Sin descripción') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if($s['activo']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                                <i class="bi bi-check-circle-fill me-1"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">
                                                <i class="bi bi-pause-circle-fill me-1"></i> Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="/marketing/public/servicios/editar?id=<?= (int)$s['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="/marketing/public/servicios/eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                                                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
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
                    <nav aria-label="Navegación de servicios">
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
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5 class="text-muted">No se encontraron servicios</h5>
                    <p class="text-muted small mb-4">Intenta ajustar la búsqueda o agrega un nuevo servicio.</p>
                    <a href="/marketing/public/servicios/nuevo" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Agregar servicio
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>