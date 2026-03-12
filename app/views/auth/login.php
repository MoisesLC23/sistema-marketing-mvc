<?php 
if (!defined('BASE_PATH')) { 
}

include __DIR__ . '/../partials/header_auth.php'; 
?>

<div class="d-flex justify-content-center align-items-center" style="min-height:60vh">
  <div class="card shadow-lg p-4 auth-card">

    <div class="text-center mb-3">
      <img src="/marketing/public/assets/img/conecta.png"
           onerror="this.src='/assets/img/conecta.png'" 
           alt="Conecta Telecomunicaciones"
           style="max-width:150px; height:auto;">
    </div>

    <h4 class="text-center mb-3">Iniciar sesión</h4>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2 text-center" role="alert">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      
      <input type="hidden" name="csrf" value="<?= function_exists('csrf_token') ? htmlspecialchars(csrf_token()) : '' ?>">

      <div class="mb-3">
        <label for="correo" class="form-label">Correo electrónico</label>
        <input type="email"
               name="correo"
               id="correo"
               class="form-control"
               required
               autofocus
               placeholder="ejemplo@conecta.com"
               value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>"> 
      </div>

      <div class="mb-3">
        <label for="contrasena" class="form-label">Contraseña</label>
        <input type="password"
               name="contrasena"
               id="contrasena"
               class="form-control"
               required
               placeholder="••••••••">
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-2">
        Ingresar
      </button>
      
      <div class="mt-3 text-center">
          <small class="text-muted">¿Olvidaste tu contraseña? Contacta al administrador.</small>
      </div>
    </form>

  </div>
</div>

<?php 
include __DIR__ . '/../partials/footer_auth.php'; 
?>