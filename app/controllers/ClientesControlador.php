<?php
require_once __DIR__ . '/../middleware/csrf.php';
require_once __DIR__ . '/../models/ClientePotencial.php';

class ClientesControlador {
    private $modelo;

    public function __construct($pdo){
        $this->modelo = new ClientePotencial($pdo);
    }

    /* ===== LISTA DE LEADS (Paginación fija de 6) ===== */
    public function index(){
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

        $leads = $this->modelo->listar($q, $perPage, $offset);

        $titulo = "Clientes Potenciales";
        $active = "clientes";

        $queryStringBase = http_build_query(['q' => $q]);

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/leads/lista.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== NUEVO LEAD ===== */
    public function nuevo(){
        $servicios = $this->modelo->obtenerServicios();
        $campanias = $this->modelo->obtenerCampañas();

        $titulo = "Nuevo Lead";
        $active = "clientes";

        $lead = [
            'id'               => null,
            'nombre'           => '',
            'telefono'         => '',
            'email'            => '',
            'tipo_servicio_id' => '',
            'campaña_id'       => '',
            'canal'            => '',
            'notas'            => '',
        ];

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/leads/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== GUARDAR LEAD ===== */
    public function guardar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $nombre           = trim($_POST['nombre'] ?? '');
        $telefono         = trim($_POST['telefono'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $tipo_servicio_id = (int)($_POST['tipo_servicio_id'] ?? 0) ?: null;
        $campaña_id       = (int)($_POST['campaña_id'] ?? 0) ?: null;
        $canal            = trim($_POST['canal'] ?? '');
        $notas            = trim($_POST['notas'] ?? '');

        $errores = [];
        if ($nombre === '') $errores[] = "El nombre es obligatorio.";
        if (strlen($nombre) > 80) $errores[] = "El nombre es demasiado largo (máx 80 caracteres).";
        
        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El email no tiene un formato válido.";
            if (strlen($email) > 100) $errores[] = "El email es demasiado largo.";
        }
        
        if (strlen($telefono) > 20) $errores[] = "El teléfono es demasiado largo.";
        if (strlen($canal) > 50) $errores[] = "El nombre del canal es demasiado largo.";

        if ($errores) {
            $error = implode('<br>', array_map('htmlspecialchars', $errores));
            
            $servicios = $this->modelo->obtenerServicios();
            $campanias = $this->modelo->obtenerCampañas();
            
            $titulo = "Nuevo Lead";
            $active = "clientes";
            
            $lead = [
                'id' => null, 'nombre' => $nombre, 'telefono' => $telefono, 'email' => $email,
                'tipo_servicio_id' => $tipo_servicio_id, 'campaña_id' => $campaña_id,
                'canal' => $canal, 'notas' => $notas
            ];

            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/leads/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }

        $this->modelo->crear($nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas);

        header("Location: /marketing/public/clientes");
        exit;
    }

    /* ===== EDITAR LEAD ===== */
    public function editar(){
        $id = (int)($_GET['id'] ?? 0);
        $lead = $this->modelo->obtenerPorId($id);

        if (!$lead) { http_response_code(404); exit('Lead no encontrado'); }

        $servicios = $this->modelo->obtenerServicios();
        $campanias = $this->modelo->obtenerCampañas();

        $titulo = "Editar Lead";
        $active = "clientes";

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/leads/form.php';
        include __DIR__ . '/../views/partials/footer.php';
    }

    /* ===== ACTUALIZAR LEAD ===== */
    public function actualizar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

        $nombre           = trim($_POST['nombre'] ?? '');
        $telefono         = trim($_POST['telefono'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $tipo_servicio_id = (int)($_POST['tipo_servicio_id'] ?? 0) ?: null;
        $campaña_id       = (int)($_POST['campaña_id'] ?? 0) ?: null;
        $canal            = trim($_POST['canal'] ?? '');
        $notas            = trim($_POST['notas'] ?? '');

        $errores = [];
        if ($nombre === '') $errores[] = "El nombre es obligatorio.";
        if (strlen($nombre) > 80) $errores[] = "El nombre es demasiado largo.";
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email inválido.";
        if (strlen($email) > 100) $errores[] = "El email es demasiado largo.";

        if ($errores) {
            $error = implode('<br>', array_map('htmlspecialchars', $errores));
            
            $servicios = $this->modelo->obtenerServicios();
            $campanias = $this->modelo->obtenerCampañas();
            
            $titulo = "Editar Lead";
            $active = "clientes";
            
            $lead = [
                'id' => $id, 'nombre' => $nombre, 'telefono' => $telefono, 'email' => $email,
                'tipo_servicio_id' => $tipo_servicio_id, 'campaña_id' => $campaña_id,
                'canal' => $canal, 'notas' => $notas
            ];

            include __DIR__ . '/../views/partials/header.php';
            include __DIR__ . '/../views/leads/form.php';
            include __DIR__ . '/../views/partials/footer.php';
            return;
        }
        $this->modelo->actualizar($id, $nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas);

        header("Location: /marketing/public/clientes");
        exit;
    }

    /* ===== ELIMINAR LEAD ===== */
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
            http_response_code(400); exit('CSRF inválido');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

        $this->modelo->eliminar($id);

        header("Location: /marketing/public/clientes");
        exit;
    }
}