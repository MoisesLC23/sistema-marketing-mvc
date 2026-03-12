<?php
require_once __DIR__.'/../middleware/csrf.php';
require_once __DIR__.'/../models/Servicio.php';

class ServiciosControlador {
    private $modelo;
    
    public function __construct($pdo){ 
        $this->modelo = new Servicio($pdo); 
    }

    public function index() {
        $q = trim($_GET['q'] ?? '');

        $perPage = 6;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;
        $total = $this->modelo->contar($q);
        $totalPages = ($total > 0) ? (int)ceil($total / $perPage) : 1;
        if ($page > $totalPages) {
            $page   = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $servicios = $this->modelo->listar($q, $perPage, $offset);
        $titulo = "Catálogo de Servicios";
        $active = "servicios";
        
        $queryStringBase = http_build_query(['q' => $q]);
        
        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/servicios/lista.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    // ===== FORMULARIO NUEVO =====
    public function nuevo() {
        $titulo = "Nuevo servicio";
        $active = "servicios";
        
        $servicio = [
            'id'          => null,
            'slug'        => '',
            'nombre'      => '',
            'descripcion' => '',
            'activo'      => 1
        ];
        
        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/servicios/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    // ===== GUARDAR NUEVO =====
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !csrf_check($_POST['csrf']??'')) { 
            http_response_code(400); exit('CSRF inválido'); 
        }

        $slug        = strtolower(trim($_POST['slug'] ?? ''));
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $activo      = isset($_POST['activo']) ? 1 : 0;

        $errores=[];
        if ($slug==='') $errores[]='El Slug es obligatorio.';
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) $errores[]='El Slug solo acepta letras minúsculas, números y guiones (sin espacios).';
        if ($nombre==='') $errores[]='El Nombre del servicio es obligatorio.';
        if ($this->modelo->existeSlug($slug)) {
            $errores[] = 'El Slug ya está en uso por otro servicio.';
        }

        if ($errores){
            $titulo = "Nuevo servicio";
            $active = "servicios";
            $error  = implode('<br>', array_map('htmlspecialchars',$errores));
            $servicio = compact('slug','nombre','descripcion','activo');
            
            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/servicios/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }

        $this->modelo->crear($slug, $nombre, $descripcion, $activo);

        header("Location: /marketing/public/servicios");
        exit;
    }

    // ===== FORMULARIO EDITAR =====
    public function editar() {
        $id = (int)($_GET['id'] ?? 0);
        $servicio = $this->modelo->obtenerPorId($id);
        if (!$servicio){ 
            http_response_code(404); exit('Servicio no encontrado'); 
        }

        $titulo = "Editar servicio";
        $active = "servicios";
        
        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/servicios/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    // ===== ACTUALIZAR =====
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !csrf_check($_POST['csrf']??'')) { 
            http_response_code(400); exit('CSRF inválido'); 
        }
        $id = (int)($_POST['id'] ?? 0);

        $slug        = strtolower(trim($_POST['slug'] ?? ''));
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $activo      = isset($_POST['activo']) ? 1 : 0;

        $errores=[];
        if ($id<=0) $errores[]='ID inválido.';
        if ($slug==='') $errores[]='El Slug es obligatorio.';
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) $errores[]='El Slug solo acepta letras minúsculas, números y guiones.';
        if ($nombre==='') $errores[]='El Nombre es obligatorio.';
        if ($this->modelo->existeSlug($slug, $id)) {
            $errores[] = 'El Slug ya está en uso por otro servicio.';
        }

        if ($errores){
            $titulo = "Editar servicio";
            $active = "servicios";
            $error  = implode('<br>', array_map('htmlspecialchars',$errores));
            $servicio = compact('id','slug','nombre','descripcion','activo');
            
            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/servicios/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }
        $this->modelo->actualizar($id, $slug, $nombre, $descripcion, $activo);

        header("Location: /marketing/public/servicios");
        exit;
    }

    // ===== ELIMINAR =====
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !csrf_check($_POST['csrf']??'')) { 
            http_response_code(400); exit('CSRF inválido'); 
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id<=0){ http_response_code(400); exit('ID inválido'); }
        $this->modelo->eliminar($id);
        header("Location: /marketing/public/servicios");
        exit;
    }
}