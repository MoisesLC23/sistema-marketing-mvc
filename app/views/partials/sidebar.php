<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$base = ''; 

$rolIdPlano   = isset($_SESSION['usuario_rol_id']) ? (int)$_SESSION['usuario_rol_id'] : null;
$rolTexto     = $_SESSION['usuario_rol'] ?? null;
$rolIdAnidado = isset($_SESSION['usuario']['rol_id']) ? (int)$_SESSION['usuario']['rol_id'] : null;
$isAdmin      = ($rolIdPlano === 1 || $rolIdAnidado === 1 || $rolTexto === 'admin');

$items = [
  ['texto' => 'Dashboard',           'ruta' => '/dashboard',            'key' => 'dashboard',             'icon' => 'bi-speedometer2'],
  ['texto' => 'Planes de Marketing', 'ruta' => '/campañas',             'key' => 'campañas',              'icon' => 'bi-megaphone'],
  ['texto' => 'Config. Empresa',     'ruta' => '/configuracion-empresa','key' => 'configuracion-empresa', 'icon' => 'bi-building-gear'],
  ['texto' => 'Servicios',           'ruta' => '/servicios',            'key' => 'servicios',             'icon' => 'bi-box-seam'],
  ['texto' => 'Clientes Potenciales','ruta' => '/clientes',             'key' => 'clientes',              'icon' => 'bi-people'],
];
?>

<nav id="sidebar" class="sidebar">
    
    <div class="d-flex flex-column align-items-center w-100 pt-3 pb-2">
        
        <div class="sidebar-brand d-flex justify-content-center w-100 mb-2" style="height: auto; border: none; padding: 0;">
            <a href="<?= $base ?>/dashboard" class="text-decoration-none text-white d-flex align-items-center justify-content-center gap-2 p-2 rounded hover-bg-light-10" title="Ir al Dashboard">
                <img src="<?= $base ?>/assets/img/conecta.png" alt="Conecta" 
                     style="max-height: 35px; width: auto; object-fit: contain;"
                     onerror="this.style.display='none';"> 
                <span class="fw-bold menu-text" style="font-size: 1.1rem; letter-spacing: 0.5px;">MARKETING</span>
            </a>
        </div>
        
        <button id="btnToggleSidebar" 
                class="btn-toggle-static d-none d-md-flex align-items-center justify-content-center rounded-circle mt-1" 
                title="Contraer/Expandir menú"
                style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); border: none; cursor: pointer; transition: background 0.2s;">
            <i class="bi bi-list text-white fs-5"></i>
        </button>

    </div>

    <div class="w-100 border-top border-white-50 opacity-25 mb-2"></div>

    <ul class="sidebar-menu w-100 mt-1">
        
        <li class="px-3 pt-2 pb-1 text-uppercase small text-white-50 fw-bold small-caps text-center text-md-start">
            <span class="menu-text">Menú</span>
            <i class="bi bi-three-dots d-md-none d-block text-center"></i>
        </li>

        <?php foreach ($items as $it): ?>
            <?php 
                if (!$isAdmin && $it['key'] === 'configuracion-empresa') continue;
                $isActive = (isset($active) && $active === $it['key']);
            ?>
            <li class="nav-item">
                <a href="<?= $base . $it['ruta'] ?>" class="nav-link <?= $isActive ? 'active' : '' ?>" title="<?= htmlspecialchars($it['texto']) ?>">
                    <i class="bi <?= $it['icon'] ?>"></i>
                    <span class="menu-text"><?= htmlspecialchars($it['texto']) ?></span>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ($isAdmin): ?>
            <li class="px-3 pt-4 pb-1 text-uppercase small text-white-50 fw-bold small-caps text-center text-md-start">
                <span class="menu-text">Admin</span>
            </li>
            <li class="nav-item">
                <a href="<?= $base ?>/usuarios" class="nav-link <?= ($active ?? '') === 'usuarios' ? 'active' : '' ?>" title="Usuarios">
                    <i class="bi bi-person-badge-fill"></i>
                    <span class="menu-text">Usuarios</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>

    <div class="sidebar-footer">
        <button type="button" id="btnLogout" class="btn-logout shadow-sm" title="Cerrar Sesión">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Cerrar Sesión</span>
        </button>
    </div>
</nav>

<button class="mobile-toggle d-md-none" id="mobileToggle">
    <i class="bi bi-list"></i>
</button>

<style>
    #btnToggleSidebar:hover {
        background: rgba(255,255,255,0.3) !important;
    }
</style>