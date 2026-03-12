<?php
require_once __DIR__ . '/../models/Dashboard.php'; 

class DashboardControlador {
    private $modelo;

    public function __construct($pdo){
        $this->modelo = new Dashboard($pdo);
    }

    public function index(){
        $leads = $this->modelo->contarLeads();
        $serviciosActivos = $this->modelo->contarServiciosActivos();
        $statsCampanias = $this->modelo->obtenerEstadisticasCampañas();
        $campaniasTotal = $statsCampanias['total'];
        $campaniasActivas = $statsCampanias['activas'];
        $presupuestoActivo = $statsCampanias['presupuesto_activo'];
        $statsEstrategias = $this->modelo->obtenerEstadisticasEstrategias();
        $estrategiasTotal = $statsEstrategias['total'];
        $estrategiasAprobadas = $statsEstrategias['aprobadas'];
        $topCanales = $this->modelo->obtenerTopCanales();
        $ultimasCampanias = $this->modelo->obtenerUltimasCampañas();
        $empresa = $this->modelo->obtenerNombreEmpresa();
        $usuario = $_SESSION['nombre'] ?? 'Usuario';
        $rolId   = $_SESSION['usuario_rol_id'] ?? 2; 
        
        if (isset($_SESSION['usuario_rol'])) {
            $rolTexto = ucfirst($_SESSION['usuario_rol']);
        } else {
            $rolTexto = ($rolId == 1) ? 'Administrador' : 'Marketing';
        }

        $titulo = "Panel Principal";
        $active = "dashboard";

        include __DIR__ . '/../views/partials/header.php';
        include __DIR__ . '/../views/dashboard/index.php';
        include __DIR__ . '/../views/partials/footer.php';
    }
}