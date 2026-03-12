<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary fw-bold mb-0">Gestión de Usuarios</h3>
            <p class="text-muted small mb-0">Administra el acceso y roles de tu equipo.</p>
        </div>
        <a href="/marketing/public/usuarios/nuevo" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Buscador -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form class="row g-2 align-items-center" method="GET" action="/marketing/public/usuarios">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre o correo..." 
                               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 fw-bold" type="submit">Buscar</button>
                </div>
                <?php if(!empty($_GET['q'])): ?>
                <div class="col-md-2">
                    <a href="/marketing/public/usuarios" class="btn btn-link text-decoration-none text-muted w-100">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (!empty($usuarios)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small" style="width: 5%;">ID</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 30%;">Usuario</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Rol</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Estado</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 15%;">Creado</th>
                                <th class="text-end pe-4 py-3 text-secondary text-uppercase small" style="width: 20%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= (int)$u['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- Avatar -->
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold flex-shrink-0" style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                                            </div>
                                            <div style="min-width: 0;"> 
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($u['nombre']) ?>">
                                                    <?= htmlspecialchars($u['nombre']) ?>
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                    <?= htmlspecialchars($u['correo']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                            $rolId = (int)$u['rol_id'];
                                            $rolNombre = $u['rol'] ?? 'Rol #' . $rolId;
                                            
                                            $badgeClass = match($rolId) {
                                                1 => 'bg-danger bg-opacity-10 text-danger border-danger',
                                                2 => 'bg-info bg-opacity-10 text-info border-info',
                                                default => 'bg-secondary bg-opacity-10 text-secondary'
                                            };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> border px-2 py-1">
                                            <?= htmlspecialchars($rolNombre) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ((int)$u['activo'] === 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2">
                                                <i class="bi bi-dash-circle-fill me-1"></i> Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($u['creado_en'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <!-- Editar: Siempre visible -->
                                            <a href="/marketing/public/usuarios/editar?id=<?= (int)$u['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Editar datos">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <?php if ((int)$u['id'] !== 1): ?>
                                                
                                                <form action="/marketing/public/usuarios/cambiar-estado" method="post" class="d-inline">
                                                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                                    <input type="hidden" name="activo" value="<?= (int)$u['activo'] === 1 ? 0 : 1 ?>">
                                                    
                                                    <?php if((int)$u['activo'] === 1): ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-warning border-start-0" 
                                                                title="Desactivar usuario"
                                                                onclick="return confirm('¿Desactivar acceso a este usuario?');">
                                                            <i class="bi bi-person-slash"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success border-start-0" 
                                                                title="Activar usuario">
                                                            <i class="bi bi-person-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>

                                                <form action="/marketing/public/usuarios/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario permanentemente? Esta acción no se puede deshacer.');">
                                                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-start-0" title="Eliminar usuario">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary border-start-0 disabled" title="Usuario Protegido">
                                                    <i class="bi bi-shield-lock-fill"></i>
                                                </button>
                                            <?php endif; ?>
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
                    <nav aria-label="Navegación de usuarios">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <!-- Flecha Atrás -->
                            <li class="page-item <?= ($page ?? 1) <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= (($page ?? 1) > 1) ? '?page='.($page-1).'&'.$queryStringBase : '#' ?>">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Páginas -->
                            <?php for($i=1; $i<=$totalPages; $i++): ?>
                                <li class="page-item <?= ($i == ($page ?? 1)) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&<?= $queryStringBase ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Flecha Adelante -->
                            <li class="page-item <?= ($page ?? 1) >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= (($page ?? 1) < $totalPages) ? '?page='.($page+1).'&'.$queryStringBase : '#' ?>">
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
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="text-muted">No se encontraron usuarios</h5>
                    <p class="text-muted small">Intenta con otra búsqueda o crea un nuevo usuario.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>