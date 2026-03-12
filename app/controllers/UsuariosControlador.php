<?php
require_once __DIR__.'/../middleware/csrf.php';
require_once __DIR__.'/../models/Usuario.php';

class UsuariosControlador {
    private $modelo;

    public function __construct($pdo){
        $this->modelo = new Usuario($pdo);
    }

    /* ===== LISTADO DE USUARIOS ===== */
    public function index(){
        $q = trim($_GET['q'] ?? '');
        $perPage = 8; 
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        $total = $this->modelo->contar($q);
        $totalPages = ($total > 0) ? (int)ceil($total / $perPage) : 1;
        
        $usuarios = $this->modelo->listar($q, $perPage, $offset);

        $titulo = "Gestión de Usuarios";
        $active = "usuarios";
        
        $queryStringBase = http_build_query(['q' => $q]);

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/usuarios/lista.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== FORMULARIO NUEVO ===== */
    public function nuevo(){
        $roles = $this->modelo->obtenerRoles();

        $titulo = "Nuevo Usuario";
        $active = "usuarios";

        $u = [
            'id'      => null,
            'nombre'  => '',
            'correo'  => '',
            'rol_id'  => 2,
            'activo'  => 1
        ];

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/usuarios/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== GUARDAR NUEVO ===== */
    public function guardar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $rol_id = (int)($_POST['rol_id'] ?? 2);
        $activo = isset($_POST['activo']) ? 1 : 0;
        $pass   = trim($_POST['password'] ?? '');

        $errores = [];
        
        if ($nombre === '') $errores[] = "El nombre es obligatorio.";
        if (strlen($nombre) > 60) $errores[] = "El nombre es demasiado largo.";
        if ($correo === '') $errores[] = "El correo es obligatorio.";
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
        if ($pass === '')   $errores[] = "La contraseña es obligatoria.";
        if ($this->modelo->existeCorreo($correo)) {
            $errores[] = "Este correo ya está registrado.";
        }

        if ($errores) {
            $error = implode("<br>", $errores);
            $roles = $this->modelo->obtenerRoles();
            
            $titulo = "Nuevo Usuario";
            $active = "usuarios";
            $u = compact('nombre', 'correo', 'rol_id', 'activo');
            $u['id'] = null;

            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/usuarios/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $this->modelo->crear($nombre, $correo, $hash, $rol_id, $activo);
        header("Location: /marketing/public/usuarios");
        exit;
    }

    /* ===== FORMULARIO EDITAR ===== */
    public function editar(){
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        $u = $this->modelo->obtenerPorId($id);
        if (!$u) { http_response_code(404); exit("Usuario no encontrado"); }
        $roles = $this->modelo->obtenerRoles();
        $titulo = "Editar Usuario";
        $active = "usuarios";

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/usuarios/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== ACTUALIZAR ===== */
    public function actualizar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $id     = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $rol_id = (int)($_POST['rol_id'] ?? 2);
        $activo = isset($_POST['activo']) ? 1 : 0;
        $pass   = trim($_POST['password'] ?? '');

        $errores = [];
        if ($id <= 0) $errores[] = "ID inválido.";
        if ($nombre === '') $errores[] = "El nombre es obligatorio.";
        if ($correo === '') $errores[] = "El correo es obligatorio.";
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";

        // PROTECCIÓN SUPER ADMIN (Lógica de negocio)
        if ($id === 1 && $activo === 0) $activo = 1;
        if ($id === 1 && $rol_id !== 1) $rol_id = 1;
        if ($this->modelo->existeCorreo($correo, $id)) {
            $errores[] = "Este correo ya está en uso.";
        }

        if ($errores) {
            $error = implode("<br>", $errores);
            $roles = $this->modelo->obtenerRoles();
            $titulo = "Editar Usuario";
            $active = "usuarios";
            $u = compact('id', 'nombre', 'correo', 'rol_id', 'activo');

            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/usuarios/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }

        $hash = ($pass !== '') ? password_hash($pass, PASSWORD_DEFAULT) : null;

        $this->modelo->actualizar($id, $nombre, $correo, $rol_id, $activo, $hash);

        header("Location: /marketing/public/usuarios");
        exit;
    }

    /* ===== ACTIVAR / DESACTIVAR ===== */
    public function cambiarEstado(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $id     = (int)($_POST['id'] ?? 0);
        $activo = (int)($_POST['activo'] ?? 0);

        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        if ($id === 1) {
            header("Location: /marketing/public/usuarios");
            exit;
        }

        $this->modelo->cambiarEstado($id, $activo);

        header("Location: /marketing/public/usuarios");
        exit;
    }

    /* ===== ELIMINAR USUARIO ===== */
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        if ($id === 1) {
             header("Location: /marketing/public/usuarios");
             exit;
        }
        if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $id) {
             header("Location: /marketing/public/usuarios");
             exit;
        }

        $this->modelo->eliminar($id);

        header("Location: /marketing/public/usuarios");
        exit;
    }
}