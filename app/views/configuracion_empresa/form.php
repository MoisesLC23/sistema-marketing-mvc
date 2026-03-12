<div class="container py-4" style="max-width: 1000px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-building-gear me-2"></i>Configuración de Empresa
            </h3>
            <p class="text-muted mb-0 small">
                Estos datos alimentarán a la IA para generar estrategias personalizadas y precisas.
            </p>
        </div>
        <button type="submit" form="formConfig" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-save me-2"></i>Guardar Todo
        </button>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/marketing/public/configuracion-empresa/guardar" id="formConfig">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= $cfg['id'] ?? '' ?>">

        <div class="row g-4">
            
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-fingerprint me-2"></i>Identidad Corporativa</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre de la Empresa *</label>
                                <input type="text" name="nombre_empresa" class="form-control form-control-lg bg-light"
                                       placeholder="Ej: Conecta Telecomunicaciones"
                                       value="<?= htmlspecialchars($cfg['nombre_empresa'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rubro / Sector</label>
                                <input type="text" name="rubro_sector" class="form-control"
                                       placeholder="Ej: Telecomunicaciones"
                                       value="<?= htmlspecialchars($cfg['rubro_sector'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Años en Mercado</label>
                                <input type="number" name="anios_mercado" class="form-control"
                                       placeholder="Ej: 5"
                                       value="<?= htmlspecialchars($cfg['anios_mercado'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Sobre Nosotros (Historia breve)</label>
                                <textarea name="acerca_nosotros" class="form-control" rows="2" 
                                          placeholder="Breve reseña de la empresa para dar contexto a la IA...">
                                          <?= htmlspecialchars($cfg['acerca_nosotros'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-success"><i class="bi bi-compass me-2"></i>Plataforma Estratégica</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Misión</label>
                            <textarea name="mision" class="form-control" rows="3" placeholder="¿Cuál es nuestro propósito?"><?= htmlspecialchars($cfg['mision'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Visión</label>
                            <textarea name="vision" class="form-control" rows="3" placeholder="¿A dónde queremos llegar?"><?= htmlspecialchars($cfg['vision'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase text-muted">Valores</label>
                            <textarea name="valores" class="form-control" rows="2" placeholder="Ej: Innovación, Integridad..."><?= htmlspecialchars($cfg['valores'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-info"><i class="bi bi-shop me-2"></i>Mercado y Competencia</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Público Objetivo General</label>
                            <textarea name="publico_objetivo" class="form-control" rows="3" placeholder="Describe a tu cliente ideal global..."><?= htmlspecialchars($cfg['publico_objetivo'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Competidores Directos</label>
                            <div class="alert alert-light border small mb-1 py-1 px-2">
                                <i class="bi bi-info-circle me-1"></i> Formato: <strong>Nombre | Descripción breve | Web</strong> (uno por línea)
                            </div>
                            <textarea name="competidores_crudos" class="form-control font-monospace small" rows="5" placeholder="Ej: Competidor A | Precios bajos | www.compa.com"><?= htmlspecialchars($competidores_crudos ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-warning"><i class="bi bi-grid-1x2 me-2"></i>Análisis FODA (SWOT)</h6>
                    </div>
                    <div class="card-body bg-light">
                        <p class="text-muted small mb-3">Escribe cada punto en una línea nueva. Esto ayuda a la IA a entender tu situación actual.</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border border-start-0 border-end-0 border-top-0 border-bottom-4 border-success">
                                    <label class="form-label fw-bold text-success"><i class="bi bi-arrow-up-circle me-1"></i>Fortalezas</label>
                                    <textarea name="fortalezas_crudas" class="form-control border-0 bg-light" rows="4" placeholder="- Equipo experto&#10;- Tecnología propia"><?= htmlspecialchars($fortalezas_crudas ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border border-start-0 border-end-0 border-top-0 border-bottom-4 border-danger">
                                    <label class="form-label fw-bold text-danger"><i class="bi bi-arrow-down-circle me-1"></i>Debilidades</label>
                                    <textarea name="debilidades_crudas" class="form-control border-0 bg-light" rows="4" placeholder="- Presupuesto limitado&#10;- Poca presencia online"><?= htmlspecialchars($debilidades_crudas ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border border-start-0 border-end-0 border-top-0 border-bottom-4 border-primary">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-lightbulb me-1"></i>Oportunidades</label>
                                    <textarea name="oportunidades_crudas" class="form-control border-0 bg-light" rows="4" placeholder="- Crecimiento del sector&#10;- Nuevas tendencias"><?= htmlspecialchars($oportunidades_crudas ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border border-start-0 border-end-0 border-top-0 border-bottom-4 border-secondary">
                                    <label class="form-label fw-bold text-secondary"><i class="bi bi-shield-exclamation me-1"></i>Amenazas</label>
                                    <textarea name="amenazas_crudas" class="form-control border-0 bg-light" rows="4" placeholder="- Nuevos competidores&#10;- Cambios regulatorios"><?= htmlspecialchars($amenazas_crudas ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt me-2"></i>Ubicación y Contacto</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Dirección / Ubicación</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="ubicacion" class="form-control"
                                           value="<?= htmlspecialchars($cfg['ubicacion'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sitio Web</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-globe"></i></span>
                                    <input type="url" name="url_web" class="form-control" placeholder="https://"
                                           value="<?= htmlspecialchars($cfg['url_web'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WhatsApp Corporativo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="whatsapp" class="form-control"
                                           value="<?= htmlspecialchars($cfg['whatsapp'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email de Contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email_contacto" class="form-control"
                                           value="<?= htmlspecialchars($cfg['email_contacto'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> 

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pb-5">
            <a href="/marketing/public/dashboard" class="btn btn-light border px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5 fw-bold">
                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
            </button>
        </div>

    </form>
</div>