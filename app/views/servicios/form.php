<?php
$esEdicion = !empty($servicio['id']);
?>
<div class="container py-4" style="max-width: 700px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="/marketing/public/servicios" class="btn btn-outline-secondary me-3 shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <?= $esEdicion ? 'Editar Servicio' : 'Nuevo Servicio' ?>
            </h3>
            <p class="text-muted small mb-0">Define los detalles del servicio para asignarlo a campañas.</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="<?= $esEdicion ? '/marketing/public/servicios/actualizar' : '/marketing/public/servicios/guardar' ?>">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= (int)$servicio['id'] ?>">
                <?php endif; ?>

                <!-- Nombre -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Nombre del Servicio *</label>
                    <input type="text" name="nombre" id="nombreServicio" class="form-control form-control-lg" 
                           placeholder="Ej: Gestión de Redes Sociales" 
                           value="<?= htmlspecialchars($servicio['nombre'] ?? '') ?>" required autofocus>
                </div>

                <!-- Slug -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Identificador (Slug) *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">/servicios/</span>
                        <input type="text" name="slug" id="slugServicio" class="form-control font-monospace" 
                               placeholder="gestion-redes-sociales" 
                               value="<?= htmlspecialchars($servicio['slug'] ?? '') ?>" required>
                    </div>
                    <div class="form-text small">
                        Identificador único para URLs. Solo minúsculas, números y guiones.
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4" 
                              placeholder="Describe brevemente en qué consiste este servicio..."><?= htmlspecialchars($servicio['descripcion'] ?? '') ?></textarea>
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <label class="form-label fw-bold d-block">Estado</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" 
                               style="width: 3em; height: 1.5em; cursor: pointer;"
                               <?= !empty($servicio['activo']) ? 'checked' : '' ?>>
                        <label class="form-check-label ms-2 mt-1 cursor-pointer" for="activo">
                            Disponible para nuevas campañas
                        </label>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="/marketing/public/servicios" class="btn btn-light border px-4">Cancelar</a>
                    <button class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> <?= $esEdicion ? 'Guardar Cambios' : 'Crear Servicio' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(!$esEdicion): ?>
<script>
document.getElementById('nombreServicio').addEventListener('input', function() {
    var nombre = this.value;
    var slug = nombre.toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
    document.getElementById('slugServicio').value = slug;
});
</script>
<?php endif; ?>