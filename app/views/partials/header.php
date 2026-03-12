<?php
// header.php
// $titulo y $active vienen desde el controlador
$base = ''; 
?>
<!doctype html>
<html lang="es">
<head>
<link rel="icon" type="image/png" href="/assets/img/conecta.png">
  <meta charset="utf-8">
  <title><?= htmlspecialchars($titulo ?? 'Panel de Control') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ====== GENERAL ====== */
    body {
      margin: 0;
      background: #f0f2f5;
      color: #343a40;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      overflow-x: hidden;
      display: flex; 
      min-height: 100vh;
    }

    /* ====== SIDEBAR BASE ====== */
    .sidebar {
      flex: 0 0 260px;          /* Ancho normal */
      width: 260px;
      background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
      color: white;
      height: 100vh;            /* Altura completa */
      position: sticky;         /* Fijo al hacer scroll */
      top: 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 10px rgba(0,0,0,0.1);
      z-index: 1000;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); /* Animación suave */
      overflow-x: hidden;       /* Ocultar desbordamiento horizontal al colapsar */
      overflow-y: auto;         /* Scroll vertical si hace falta */
    }

    /* Scrollbar invisible pero funcional */
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }

    /* ====== ESTADO COLAPSADO (.collapsed) ====== */
    .sidebar.collapsed {
      flex: 0 0 80px;           /* Ancho reducido */
      width: 80px;
    }

    /* Ocultar textos cuando está colapsado */
    .sidebar.collapsed .menu-text, 
    .sidebar.collapsed .sidebar-brand span,
    .sidebar.collapsed .small-caps {
      display: none !important;
    }

    /* Ajustar logo */
    .sidebar.collapsed .sidebar-brand {
      justify-content: center;
      padding: 0;
    }
    .sidebar.collapsed .sidebar-brand img {
      margin: 0;
    }

    /* Centrar iconos */
    .sidebar.collapsed .nav-link {
      justify-content: center;
      padding: 15px 0;
    }
    .sidebar.collapsed .nav-link i {
      margin-right: 0;
      font-size: 1.4rem;
    }

    /* Botón logout colapsado */
    .sidebar.collapsed .btn-logout {
      justify-content: center;
      padding: 10px;
    }
    .sidebar.collapsed .btn-logout span {
      display: none;
    }
    .sidebar.collapsed .btn-logout i {
      margin: 0;
      font-size: 1.2rem;
    }

    /* Botón Toggle en Desktop */
    .toggle-desktop {
      background: rgba(255,255,255,0.1);
      border: none;
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: absolute;
      top: 20px;
      right: -15px; /* Flotando en el borde */
      z-index: 1001; /* Por encima del contenido */
      opacity: 0;
      transition: opacity 0.2s;
    }
    
    .sidebar:hover .toggle-desktop {
      opacity: 1; /* Aparece al pasar el mouse */
      right: 10px; /* Se mueve un poco adentro */
    }
    
    /* También podemos ponerlo fijo arriba si prefieres */
    .sidebar-header-actions {
        display: flex;
        justify-content: flex-end;
        padding: 5px 10px 0;
    }
    .btn-toggle-static {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.7);
        font-size: 1.2rem;
        cursor: pointer;
    }
    .btn-toggle-static:hover { color: white; }

    /* ====== ESTILOS INTERNOS SIDEBAR ====== */
    .sidebar-brand {
      height: 70px;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      white-space: nowrap; /* Evitar que el texto baje de línea */
    }
    
    .sidebar-menu {
      list-style: none;
      padding: 1rem 0;
      margin: 0;
      flex-grow: 1;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 12px 24px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s;
      border-left: 4px solid transparent;
      white-space: nowrap;
      overflow: hidden;
    }

    .nav-link:hover {
      color: #fff;
      background: rgba(255,255,255,0.1);
    }

    .nav-link.active {
      color: #fff;
      background: rgba(255,255,255,0.15);
      border-left-color: #fff;
    }

    .nav-link i {
      font-size: 1.1rem;
      margin-right: 12px;
      width: 24px;
      text-align: center;
      flex-shrink: 0;
    }

    .sidebar-footer {
      padding: 1rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      background: rgba(0,0,0,0.1);
      position: sticky;
      bottom: 0;
    }

    .btn-logout {
      width: 100%;
      background: rgba(255,50,50,0.8);
      color: white;
      border: none;
      padding: 10px;
      border-radius: 8px;
      transition: 0.2s;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      white-space: nowrap;
      overflow: hidden;
    }
    .btn-logout:hover { background: #d32f2f; }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 20px 30px;
      width: 100%;
      min-width: 0;
    }

    /* Mobile */
    .mobile-toggle {
      display: none;
      position: fixed;
      bottom: 20px; 
      right: 20px;
      z-index: 1050;
      background: #4e73df;
      color: white;
      width: 50px; height: 50px;
      border-radius: 50%;
      border: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      align-items: center; justify-content: center;
      font-size: 1.5rem;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        left: -260px;
        height: 100vh;
      }
      .sidebar.show { left: 0; }
      .mobile-toggle { display: flex; }
      .main-content { padding: 15px; }
      
      /* En móvil no usamos el modo colapsado mini, sino mostrar/ocultar completo */
      .sidebar.collapsed { width: 260px; flex: 0 0 260px; } 
    }
  </style>
</head>
<body>

  <!-- Incluir Sidebar -->
  <?php include __DIR__ . '/sidebar.php'; ?>

  <main class="main-content">