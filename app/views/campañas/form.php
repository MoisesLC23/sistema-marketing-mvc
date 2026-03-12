<?php
$esEdicion = !empty($c['id']);
$redesMarcadas = [];
if (!empty($c['redes_sociales'])) {
  $tmp = json_decode($c['redes_sociales'], true);
  if (is_array($tmp)) $redesMarcadas = $tmp;
}
?>

<div class="container py-4" style="max-width: 900px;">
    
    <div class="d-flex align-items-center mb-4">
        <a href="/marketing/public/campañas" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="fw-bold mb-0 text-primary">
            <?= $esEdicion ? 'Editar Campaña' : 'Nueva Campaña' ?>
        </h3>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger shadow-sm border-0 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/marketing/public/campañas/<?= $esEdicion ? 'actualizar' : 'guardar' ?>">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
      <?php if ($esEdicion): ?>
        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
      <?php endif; ?>

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Información Básica</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">Nombre de la campaña *</label>
                    <input type="text" name="nombre" class="form-control form-control-lg"
                           placeholder="Ej: Campaña Verano 2025"
                           value="<?= htmlspecialchars($c['nombre'] ?? '') ?>" required>
                </div>
                
                <!-- Servicio -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Servicio vinculado</label>
                    <select name="servicio_id" class="form-select form-select-lg">
                      <option value="">(General / Institucional)</option>
                      <?php foreach($servicios as $s): ?>
                        <option value="<?= (int)$s['id'] ?>"
                          <?= ((int)($c['servicio_id'] ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($s['nombre']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                </div>

                <!-- Objetivo Tipo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Objetivo principal *</label>
                    <?php $obj = $c['objetivo_tipo'] ?? 'aumentar_ventas'; ?>
                    <select name="objetivo_tipo" class="form-select">
                      <option value="aumentar_ventas" <?= $obj==='aumentar_ventas' ? 'selected' : '' ?>>📈 Aumentar ventas</option>
                      <option value="lanzar_nuevo_producto" <?= $obj==='lanzar_nuevo_producto' ? 'selected' : '' ?>>🚀 Lanzar nuevo producto</option>
                      <option value="mejorar_reconocimiento" <?= $obj==='mejorar_reconocimiento' ? 'selected' : '' ?>>📢 Mejorar reconocimiento de marca</option>
                      <option value="fidelizar_clientes" <?= $obj==='fidelizar_clientes' ? 'selected' : '' ?>>🤝 Fidelizar clientes</option>
                      <option value="otro" <?= $obj==='otro' ? 'selected' : '' ?>>✨ Otro</option>
                    </select>
                </div>

                <!-- Detalle Objetivo -->
                <div class="col-md-6">
                    <label class="form-label">Detalle específico</label>
                    <input type="text" name="objetivo_detalle" class="form-control" 
                           placeholder="Ej: Lograr 50 leads cualificados"
                           value="<?= htmlspecialchars($c['objetivo_detalle'] ?? '') ?>">
                </div>
            </div>
        </div>
      </div>

      <!-- TARJETA PÚBLICO Y PRESUPUESTO -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-success"><i class="bi bi-people me-2"></i>Estrategia y Recursos</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Público Objetivo -->
                <div class="col-12">
                    <label class="form-label fw-bold">Público objetivo *</label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <?php
                              $pubGuardado = $c['publico_objetivo'] ?? '';
                              $opcionesPub = ['Jóvenes (18-29)', 'Adultos (30-50)', 'Adultos mayores (50+)', 'Familias', 'Empresas B2B'];
                              $pubSel = in_array($pubGuardado, $opcionesPub, true) ? $pubGuardado : 'otro';
                              $pubOtroVal = ($pubSel === 'otro') ? $pubGuardado : '';
                            ?>
                            <select name="publico_objetivo_sel" class="form-select" id="selectPublico">
                              <?php foreach ($opcionesPub as $op): ?>
                                <option value="<?= htmlspecialchars($op) ?>" <?= $pubSel === $op ? 'selected' : '' ?>><?= htmlspecialchars($op) ?></option>
                              <?php endforeach; ?>
                              <option value="otro" <?= $pubSel==='otro' ? 'selected' : '' ?>>Otro (Escribir)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                             <input type="text" name="publico_objetivo_otro" id="inputPublicoOtro"
                                   class="form-control <?= $pubSel !== 'otro' ? 'bg-light' : '' ?>"
                                   placeholder="Describe tu público ideal..."
                                   value="<?= htmlspecialchars($pubOtroVal ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Presupuesto -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-success">Presupuesto (USD) *</label>
                    <?php
                      $presActual = (float)($c['presupuesto'] ?? 0);
                      $presSel = 'otro';
                      if ($presActual == 750) $presSel = '500-1000';
                      elseif ($presActual == 1500) $presSel = '1000-2000';
                      elseif ($presActual == 3000) $presSel = '2000-4000';
                      elseif ($presActual == 6000) $presSel = '4000-8000';
                      $presOtroVal = ($presSel === 'otro') ? $presActual : '';
                    ?>
                    <div class="input-group">
                        <select name="presupuesto_sel" class="form-select">
                          <option value="500-1000" <?= $presSel==='500-1000' ? 'selected' : '' ?>>Bajo (500-1k)</option>
                          <option value="1000-2000" <?= $presSel==='1000-2000' ? 'selected' : '' ?>>Medio (1k-2k)</option>
                          <option value="2000-4000" <?= $presSel==='2000-4000' ? 'selected' : '' ?>>Alto (2k-4k)</option>
                          <option value="4000-8000" <?= $presSel==='4000-8000' ? 'selected' : '' ?>>Empresarial (4k+)</option>
                          <option value="otro" <?= $presSel==='otro' ? 'selected' : '' ?>>Otro monto</option>
                        </select>
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" name="presupuesto_otro" class="form-control" 
                               placeholder="0.00" value="<?= htmlspecialchars($presOtroVal) ?>">
                    </div>
                    <div class="form-text">Usado por la IA para dimensionar acciones.</div>
                </div>

                <!-- Fechas -->
                <div class="col-md-3">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($c['fecha_inicio'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($c['fecha_fin'] ?? '') ?>">
                </div>
            </div>
        </div>
      </div>

      <!-- TARJETA REDES Y NOTAS -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-info"><i class="bi bi-share me-2"></i>Canales y Notas</h6>
        </div>
        <div class="card-body">
            <label class="form-label fw-bold mb-3">Redes Sociales Objetivo</label>
            <div class="row g-3 mb-4">
                <?php
                  $opRedes = [
                    'Facebook' => 'bi-facebook',
                    'Instagram' => 'bi-instagram',
                    'TikTok' => 'bi-tiktok',
                    'YouTube' => 'bi-youtube',
                    'Twitter' => 'bi-twitter-x',
                    'WhatsApp' => 'bi-whatsapp',
                    'LinkedIn' => 'bi-linkedin'
                  ];
                ?>
                <?php foreach ($opRedes as $red => $icon): ?>
                <div class="col-6 col-md-3">
                    <div class="form-check p-3 border rounded bg-light h-100">
                        <input class="form-check-input" type="checkbox" name="redes_sociales[]" 
                               value="<?= htmlspecialchars($red) ?>" id="red_<?= md5($red) ?>"
                               <?= in_array($red, $redesMarcadas, true) ? 'checked' : '' ?>>
                        <label class="form-check-label w-100 cursor-pointer" for="red_<?= md5($red) ?>">
                            <i class="bi <?= $icon ?> me-1"></i> <?= htmlspecialchars($red) ?>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-0">
                <label class="form-label fw-bold">Notas Internas</label>
                <textarea name="notas" class="form-control" rows="2" placeholder="Notas privadas para el equipo..."><?= htmlspecialchars($c['notas'] ?? '') ?></textarea>
            </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 pb-5">
          <a href="/marketing/public/campañas" class="btn btn-light border px-4">Cancelar</a>
          <button class="btn btn-primary px-4 fw-bold">
            <i class="bi bi-save me-1"></i> <?= $esEdicion ? 'Guardar Cambios' : 'Crear Campaña' ?>
          </button>
      </div>

    </form>
</div>