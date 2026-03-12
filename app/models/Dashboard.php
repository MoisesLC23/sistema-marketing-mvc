<?php
class Dashboard {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function contarLeads() {
        return (int) ($this->pdo->query("SELECT COUNT(*) FROM clientes_potenciales")->fetchColumn() ?: 0);
    }
    public function contarServiciosActivos() {
        return (int) ($this->pdo->query("SELECT COUNT(*) FROM servicios WHERE activo = 1")->fetchColumn() ?: 0);
    }
    public function obtenerEstadisticasCampañas() {
        $total = (int) ($this->pdo->query("SELECT COUNT(*) FROM campañas")->fetchColumn() ?: 0);

        $activas = (int) ($this->pdo->query("SELECT COUNT(*) FROM campañas WHERE estado IN ('activo','activa')")->fetchColumn() ?: 0);

        $presupuesto = (float) ($this->pdo->query("SELECT SUM(presupuesto) FROM campañas WHERE estado IN ('activo','activa')")->fetchColumn() ?: 0);

        return [
            'total' => $total,
            'activas' => $activas,
            'presupuesto_activo' => $presupuesto
        ];
    }

    public function obtenerEstadisticasEstrategias() {
        try {
            $total = (int) ($this->pdo->query("SELECT COUNT(*) FROM campaña_estrategias")->fetchColumn() ?: 0);
            $aprobadas = (int) ($this->pdo->query("SELECT COUNT(*) FROM campaña_estrategias WHERE aprobado = 1")->fetchColumn() ?: 0);
            return ['total' => $total, 'aprobadas' => $aprobadas];
        } catch (\Throwable $e) {
            return ['total' => 0, 'aprobadas' => 0];
        }
    }

    public function obtenerTopCanales() {
        try {
            return $this->pdo->query("
                SELECT canal, COUNT(*) as total 
                FROM clientes_potenciales 
                WHERE canal IS NOT NULL AND canal != ''
                GROUP BY canal 
                ORDER BY total DESC 
                LIMIT 4
            ")->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function obtenerUltimasCampañas() {
        try {
            $sql = "
                SELECT c.id, c.nombre, c.estado, c.fecha_inicio, c.fecha_fin, s.nombre AS servicio, COUNT(e.id) AS total_estrategias
                FROM campañas c
                LEFT JOIN servicios s ON s.id = c.servicio_id
                LEFT JOIN campaña_estrategias e ON e.campaña_id = c.id
                GROUP BY c.id, c.nombre, c.estado, c.fecha_inicio, c.fecha_fin, s.nombre
                ORDER BY c.id DESC LIMIT 5
            ";
            return $this->pdo->query($sql)->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function obtenerNombreEmpresa() {
        try {
            $st = $this->pdo->query("SELECT nombre_empresa FROM configuracion_empresa ORDER BY id ASC LIMIT 1");
            if ($st) {
                $row = $st->fetch();
                return $row['nombre_empresa'] ?? 'Conecta Telecomunicaciones S.A.C.';
            }
        } catch (\Throwable $e) {}
        
        return 'Conecta Telecomunicaciones S.A.C.';
    }
}