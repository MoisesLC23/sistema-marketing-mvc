<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary fw-bold mb-0">Clientes Potenciales</h3>
            <p class="text-muted small mb-0">Gestiona y contacta a tus leads capturados.</p>
        </div>
        <a class="btn btn-primary shadow-sm" href="/marketing/public/clientes/nuevo">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Lead
        </a>
    </div>

    <!-- TARJETA FILTROS (Sin selector de página) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 bg-light rounded">
            <form class="row g-2 align-items-center" method="GET" action="/marketing/public/clientes">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nombre, email, teléfono..." 
                               value="<?= htmlspecialchars($q ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100 fw-bold" type="submit">Filtrar</button>
                </div>
                <?php if(!empty($q)): ?>
                <div class="col-md-2">
                    <a href="/marketing/public/clientes" class="btn btn-link text-decoration-none text-muted w-100">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TARJETA TABLA -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="table-layout: fixed;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small text-uppercase" style="width: 28%;">Contacto</th>
                            <th class="py-3 text-secondary small text-uppercase" style="width: 22%;">Interés / Campaña</th>
                            <th class="py-3 text-secondary small text-uppercase" style="width: 15%;">Canal</th>
                            <th class="py-3 text-secondary small text-uppercase" style="width: 25%;">Notas</th>
                            <th class="text-end pe-4 py-3 text-secondary small text-uppercase" style="width: 10%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leads)): ?>
                            <?php foreach($leads as $l): ?>
                                <tr>
                                    <!-- COLUMNA CONTACTO (Con Truncate) -->
                                    <td class="ps-4 text-truncate">
                                        <div class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($l['nombre']) ?>">
                                            <?= htmlspecialchars($l['nombre']) ?>
                                        </div>
                                        <?php if(!empty($l['email'])): ?>
                                            <div class="small text-muted text-truncate" title="<?= htmlspecialchars($l['email']) ?>">
                                                <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($l['email']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($l['telefono'])): ?>
                                            <div class="small text-muted text-truncate">
                                                <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($l['telefono']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- COLUMNA INTERÉS -->
                                    <td class="text-truncate">
                                        <?php if(!empty($l['servicio_nombre'])): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mb-1 text-truncate" style="max-width: 100%;">
                                                <?= htmlspecialchars($l['servicio_nombre']) ?>
                                            </span><br>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($l['campaña_nombre'])): ?>
                                            <small class="text-muted text-truncate d-block" title="<?= htmlspecialchars($l['campaña_nombre']) ?>">
                                                <i class="bi bi-megaphone me-1"></i><?= htmlspecialchars($l['campaña_nombre']) ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted small">–</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- COLUMNA CANAL -->
                                    <td>
                                        <?php 
                                            $canal = strtolower($l['canal'] ?? '');
                                            $icon = 'bi-chat-left-text';
                                            $color = 'secondary';
                                            
                                            if(str_contains($canal, 'whats')) { $icon = 'bi-whatsapp'; $color = 'success'; }
                                            elseif(str_contains($canal, 'face')) { $icon = 'bi-facebook'; $color = 'primary'; }
                                            elseif(str_contains($canal, 'insta')) { $icon = 'bi-instagram'; $color = 'danger'; }
                                            elseif(str_contains($canal, 'tiktok')) { $icon = 'bi-tiktok'; $color = 'dark'; }
                                            elseif(str_contains($canal, 'mail') || str_contains($canal, 'correo')) { $icon = 'bi-envelope'; $color = 'warning text-dark'; }
                                            elseif(str_contains($canal, 'llamada') || str_contains($canal, 'tel')) { $icon = 'bi-telephone'; $color = 'info text-dark'; }
                                        ?>
                                        <span class="badge rounded-pill bg-<?= $color ?> bg-opacity-10 text-<?= str_contains($color, 'text-dark') ? 'dark' : $color ?> text-truncate" style="max-width: 100%;">
                                            <i class="bi <?= $icon ?> me-1"></i> <?= htmlspecialchars($l['canal'] ?: 'N/A') ?>
                                        </span>
                                    </td>

                                    <!-- COLUMNA NOTAS -->
                                    <td class="text-truncate">
                                        <small class="text-muted d-block text-truncate" title="<?= htmlspecialchars($l['notas'] ?: '') ?>">
                                            <?= htmlspecialchars($l['notas'] ?: '—') ?>
                                        </small>
                                    </td>

                                    <!-- ACCIONES -->
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="/marketing/public/clientes/editar?id=<?= (int)$l['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="/marketing/public/clientes/eliminar" class="d-inline" onsubmit="return confirm('¿Eliminar este lead permanentemente?');">
                                                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                                                <input type="hidden" name="id" value="<?= (int)$l['id'] ?>">
                                                <button class="btn btn-sm btn-outline-danger border-start-0" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3 text-muted" style="font-size: 2.5rem;"><i class="bi bi-inbox"></i></div>
                                    <h5 class="text-muted">No se encontraron clientes</h5>
                                    <p class="text-muted small">Intenta ajustar los filtros o agrega un nuevo lead.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER CON PAGINACIÓN -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="card-footer bg-white py-3">
                <nav aria-label="Navegación de clientes">
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
        </div>
    </div>
</div>