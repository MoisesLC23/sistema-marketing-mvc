<?php
$esEdicion = !empty($lead['id']);
?>

<div class="container py-4" style="max-width: 800px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="/marketing/public/clientes" class="btn btn-outline-secondary me-3 shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0 text-primary">
                <?= $esEdicion ? 'Editar Cliente Potencial' : 'Nuevo Cliente Potencial' ?>
            </h3>
            <p class="text-muted small mb-0">Registra los datos de contacto y el interés del prospecto.</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $esEdicion ? '/marketing/public/clientes/actualizar' : '/marketing/public/clientes/guardar' ?>">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= (int)$lead['id'] ?>">
        <?php endif; ?>

        <!-- TARJETA DE DATOS -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-lines-fill me-2"></i>Datos de Contacto</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre Completo *</label>
                        <input type="text" name="nombre" class="form-control form-control-lg" 
                               placeholder="Ej: Juan Pérez" 
                               maxlength="80"
                               value="<?= htmlspecialchars($lead['nombre'] ?? '') ?>" required>
                        <div class="form-text small">Máximo 80 caracteres.</div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6">
                        <label class="form-label">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" class="form-control" 
                                   placeholder="Ej: 987654321" 
                                   maxlength="20"
                                   value="<?= htmlspecialchars($lead['telefono'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="cliente@ejemplo.com" 
                                   maxlength="100"
                                   value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-bullseye me-2"></i>Interés y Origen</h6>
                
                <div class="row g-3">
                    <!-- Servicio -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Servicio de Interés</label>
                        <select name="tipo_servicio_id" class="form-select">
                            <option value="">(Ninguno seleccionado)</option>
                            <?php foreach($servicios as $s): ?>
                                <option value="<?= (int)$s['id'] ?>" <?= ((int)($lead['tipo_servicio_id'] ?? 0) === (int)$s['id'] ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($s['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Campaña -->
                    <div class="col-md-6">
                        <label class="form-label">Campaña Asociada</label>
                        <select name="campaña_id" class="form-select">
                            <option value="">(Orgánico / Sin campaña)</option>
                            <?php foreach($campanias as $c): ?>
                                <option value="<?= (int)$c['id'] ?>" <?= ((int)($lead['campaña_id'] ?? 0) === (int)$c['id'] ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Canal (MODIFICADO: Ahora es un SELECT estandarizado) -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Canal de Captación</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-share"></i></span>
                            <select name="canal" class="form-select">
                                <option value="">-- Seleccionar origen --</option>
                                <?php
                                    $canales = [
                                        'Facebook Ads' => 'Facebook / Meta Ads',
                                        'Instagram'    => 'Instagram',
                                        'WhatsApp'     => 'WhatsApp Directo',
                                        'Google'       => 'Google / Búsqueda Orgánica',
                                        'Llamada'      => 'Llamada Telefónica',
                                        'Email'        => 'Email / Mailing',
                                        'Referido'     => 'Recomendación / Referido',
                                        'LinkedIn'     => 'LinkedIn',
                                        'TikTok'       => 'TikTok',
                                        'Web'          => 'Sitio Web Corporativo'
                                    ];
                                    $canalActual = $lead['canal'] ?? '';
                                ?>
                                <?php foreach ($canales as $val => $label): ?>
                                    <option value="<?= htmlspecialchars($val) ?>" <?= ($canalActual === $val) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                                
                                <?php if ($canalActual && !array_key_exists($canalActual, $canales)): ?>
                                    <option value="<?= htmlspecialchars($canalActual) ?>" selected>
                                        <?= htmlspecialchars($canalActual) ?> (Otro)
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Notas / Observaciones</label>
                        <textarea name="notas" class="form-control bg-light" rows="3" 
                                  placeholder="Detalles importantes sobre el cliente..."><?= htmlspecialchars($lead['notas'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
                <a href="/marketing/public/clientes" class="btn btn-light border px-4">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Guardar Cliente
                </button>
            </div>
        </div>
    </form>
</div>