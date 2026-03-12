<?php
$esEdicion = !empty($u['id']);
$action = $esEdicion ? ('/marketing/public/usuarios/actualizar') : ('/marketing/public/usuarios/guardar');

$esSuperAdmin = ($esEdicion && (int)$u['id'] === 1);
?>

<div class="container py-4" style="max-width: 700px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="/marketing/public/usuarios" class="btn btn-outline-secondary me-3 shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <?= $esEdicion ? 'Editar Usuario' : 'Nuevo Usuario' ?>
            </h3>
            <p class="text-muted small mb-0">Configura los datos de acceso y permisos.</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($esSuperAdmin): ?>
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <i class="bi bi-shield-lock-fill me-2"></i> <strong>Cuenta Principal:</strong> Este es el usuario Super Admin. No se puede desactivar ni cambiar su rol, pero puedes editar sus datos.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="<?= $action ?>" method="post" autocomplete="off">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre Completo *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" class="form-control" 
                                   placeholder="Ej: Moises" 
                                   maxlength="60"
                                   value="<?= htmlspecialchars($u['nombre'] ?? '') ?>" required>
                        </div>
                        <div class="form-text small">Máximo 60 caracteres.</div>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="correo" class="form-control" 
                                   placeholder="admin@conecta.local" 
                                   maxlength="100"
                                   value="<?= htmlspecialchars($u['correo'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Rol de Usuario</label>
                        <?php if ($esSuperAdmin): ?>
                            <!-- Si es super admin, mostramos el rol pero deshabilitamos el cambio -->
                            <input type="text" class="form-control bg-light" value="Admin (Principal)" disabled>
                            <input type="hidden" name="rol_id" value="1">
                        <?php else: ?>
                            <select name="rol_id" class="form-select">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= (int)$r['id'] ?>" 
                                        <?= ((int)($u['rol_id'] ?? 0) === (int)$r['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($r['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                        <div class="form-text small">Define los permisos dentro del sistema.</div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold d-block">Estado de la cuenta</label>
                        <?php if ($esSuperAdmin): ?>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" checked disabled>
                                <label class="form-check-label ms-2 mt-1 fw-bold text-success">
                                    <i class="bi bi-check-circle-fill"></i> Acceso permanente
                                </label>
                                <input type="hidden" name="activo" value="1">
                            </div>
                        <?php else: ?>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="activo" id="chkActivo" value="1" 
                                       style="width: 3em; height: 1.5em; cursor: pointer;"
                                       <?= !empty($u['activo']) ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2 mt-1 cursor-pointer" for="chkActivo">
                                    Acceso habilitado
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <hr class="my-3">
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-danger">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="••••••••" autocomplete="new-password"
                               <?= $esEdicion ? '' : 'required' ?>>
                        
                        <?php if($esEdicion): ?>
                            <div class="alert alert-light border small mt-2 mb-0 text-muted">
                                <i class="bi bi-info-circle me-1"></i> Deja este campo en blanco si no deseas cambiar la contraseña actual.
                            </div>
                        <?php else: ?>
                            <div class="form-text">Crea una contraseña segura para el nuevo usuario.</div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/marketing/public/usuarios" class="btn btn-light border px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= $esEdicion ? 'Guardar Cambios' : 'Crear Usuario' ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>