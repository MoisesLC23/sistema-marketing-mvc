<?php
session_start();

// Cargar configuración y librerías
require __DIR__ . '/../app/config/db.php';
require __DIR__ . '/../vendor/autoload.php';

// --- Normalización y decodificación del path ---
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$uri = preg_replace('#/+#', '/', $uri);          
$uri = rawurldecode($uri);                       

// Ajusta esto si tu carpeta pública tiene otro nombre
$basePath = '/marketing/public';                 
$route = (strpos($uri, $basePath) === 0) ? substr($uri, strlen($basePath)) : $uri;

if ($route === '') {
    $route = '/';
}
if ($route !== '/' && substr($route, -1) === '/') {
    $route = rtrim($route, '/');
}

switch ($route) {
  // ---------- Dashboard ----------
  case '/':
  case '/dashboard':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/DashboardControlador.php';
    (new DashboardControlador($pdo))->index();
    break;

  // ---------- Auth ----------
  case '/login':
    require __DIR__ . '/../app/controllers/AuthControlador.php';
    (new AuthControlador($pdo))->login();
    break;

  case '/logout':
    require __DIR__ . '/../app/controllers/AuthControlador.php';
    (new AuthControlador($pdo))->logout();
    break;

  // ---------- Configuración de la empresa ----------
  case '/configuracion-empresa':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/ConfiguracionEmpresaControlador.php';
    (new ConfiguracionEmpresaControlador($pdo))->form();
    break;

  case '/configuracion-empresa/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/ConfiguracionEmpresaControlador.php';
    (new ConfiguracionEmpresaControlador($pdo))->guardar();
    break;

  // ---------- Servicios ----------
  case '/servicios':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->index();
    break;

  case '/servicios/nuevo':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->nuevo();
    break;

  case '/servicios/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->guardar();
    break;

  case '/servicios/editar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->editar();
    break;

  case '/servicios/actualizar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->actualizar();
    break;

  case '/servicios/eliminar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ServiciosControlador.php';
    (new ServiciosControlador($pdo))->eliminar();
    break;

  // ---------- Clientes potenciales (Leads) ----------
  case '/clientes':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->index();
    break;

  case '/clientes/nuevo':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->nuevo();
    break;

  case '/clientes/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->guardar();
    break;

  case '/clientes/editar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->editar();
    break;

  case '/clientes/actualizar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->actualizar();
    break;

  case '/clientes/cambiar-estado':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->cambiarEstado();
    break;

  case '/clientes/eliminar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/ClientesControlador.php';
    (new ClientesControlador($pdo))->eliminar();
    break;

  // ---------- Campañas ----------
  case '/campañas':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->index();
    break;

  case '/campañas/nueva':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->nueva();
    break;

  case '/campañas/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->guardar();
    break;

  case '/campañas/editar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->editar();
    break;

  case '/campañas/actualizar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->actualizar();
    break;

  case '/campañas/eliminar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->eliminar();
    break;

  case '/campañas/estrategias':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->estrategias();
    break;

  case '/campañas/generar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->generar();
    break;

  case '/campañas/estrategias/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->guardarEstrategia();
    break;

  case '/campañas/estrategias/aprobar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->aprobarEstrategia();
    break;

  case '/campañas/toggle-completada':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->toggleCompletada();
    break;

  case '/campañas/cambiar-estado':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->cambiarEstado();
    break;

  case '/campañas/exportar-pdf':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirLogin();
    require __DIR__ . '/../app/controllers/CampañasControlador.php';
    (new CampañasControlador($pdo))->exportarPdf();
    break;

  // ---------- Usuarios (SOLO ADMIN) ----------
  case '/usuarios':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->index();
    break;

  case '/usuarios/nuevo':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->nuevo();
    break;

  case '/usuarios/guardar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->guardar();
    break;

  case '/usuarios/editar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->editar();
    break;

  case '/usuarios/actualizar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->actualizar();
    break;

  case '/usuarios/cambiar-estado':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->cambiarEstado();
    break;

  case '/usuarios/eliminar':
    require __DIR__ . '/../app/middleware/autenticacion.php';
    requerirAdmin();
    require __DIR__ . '/../app/controllers/UsuariosControlador.php';
    (new UsuariosControlador($pdo))->eliminar();
    break;


  // ---------- 404 ----------
  default:
    http_response_code(404);
    echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>";
    echo "<h1 style='font-size:3rem; color:#6c757d;'>404</h1>";
    echo "<p style='font-size:1.2rem; color:#333;'>Ruta no encontrada: <code>" . htmlspecialchars($route) . "</code></p>";
    echo "<a href='/marketing/public/dashboard' style='color:#0d6efd; text-decoration:none;'>Volver al inicio</a>";
    echo "</div>";
    break;
}