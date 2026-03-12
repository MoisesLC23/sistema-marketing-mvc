<?php
class Campaña {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function contar($busqueda = '') {
        $sql = "SELECT COUNT(*) FROM campañas c LEFT JOIN servicios s ON s.id = c.servicio_id";
        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(c.nombre LIKE ? OR s.nombre LIKE ? OR c.objetivo_tipo LIKE ?)";
            $like = "%$busqueda%";
            $params[] = $like; $params[] = $like; $params[] = $like;
        }

        if ($where) $sql .= " WHERE " . implode(" AND ", $where);

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn();
    }

    public function listar($busqueda = '', $limit = 7, $offset = 0) {
        $sql = "SELECT c.*, s.nombre AS servicio FROM campañas c LEFT JOIN servicios s ON s.id = c.servicio_id";
        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(c.nombre LIKE ? OR s.nombre LIKE ? OR c.objetivo_tipo LIKE ?)";
            $like = "%$busqueda%";
            $params[] = $like; $params[] = $like; $params[] = $like;
        }

        if ($where) $sql .= " WHERE " . implode(" AND ", $where);

        $sql .= " ORDER BY c.id DESC LIMIT $limit OFFSET $offset";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtenerPorId($id) {
        $st = $this->pdo->prepare("SELECT * FROM campañas WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }
    
    public function obtenerDetalle($id) {
        $st = $this->pdo->prepare("SELECT c.*, s.nombre AS servicio FROM campañas c LEFT JOIN servicios s ON s.id = c.servicio_id WHERE c.id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function crear($datos) {
        $sql = "INSERT INTO campañas (nombre, objetivo_tipo, objetivo_detalle, publico_objetivo, presupuesto, fecha_inicio, fecha_fin, servicio_id, estado, notas, creado_por, redes_sociales) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $datos['nombre'], $datos['objetivo_tipo'], $datos['objetivo_detalle'], 
            $datos['publico_objetivo'], $datos['presupuesto'], $datos['fecha_inicio'], 
            $datos['fecha_fin'], $datos['servicio_id'], $datos['estado'], 
            $datos['notas'], $datos['creado_por'], $datos['redes_sociales']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE campañas SET nombre=?, objetivo_tipo=?, objetivo_detalle=?, publico_objetivo=?, presupuesto=?, fecha_inicio=?, fecha_fin=?, servicio_id=?, notas=?, redes_sociales=? WHERE id=?";
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            $datos['nombre'], $datos['objetivo_tipo'], $datos['objetivo_detalle'], 
            $datos['publico_objetivo'], $datos['presupuesto'], $datos['fecha_inicio'], 
            $datos['fecha_fin'], $datos['servicio_id'], $datos['notas'], 
            $datos['redes_sociales'], $id
        ]);
    }

    public function cambiarEstado($id, $estado) {
        $st = $this->pdo->prepare("UPDATE campañas SET estado = ? WHERE id = ?");
        return $st->execute([$estado, $id]);
    }

    public function eliminar($id) {
        $st = $this->pdo->prepare("DELETE FROM campañas WHERE id = ?");
        return $st->execute([$id]);
    }


    public function obtenerEstrategias($campañaId) {
        $st = $this->pdo->prepare("SELECT * FROM campaña_estrategias WHERE campaña_id = ? ORDER BY orden, id");
        $st->execute([$campañaId]);
        return $st->fetchAll();
    }

    public function obtenerPlanReciente($campañaId) {
        $st = $this->pdo->prepare("SELECT plan_json FROM campaña_planes WHERE campaña_id = ? ORDER BY id DESC LIMIT 1");
        $st->execute([$campañaId]);
        return $st->fetch();
    }

    public function guardarPlan($campañaId, $planJson) {
        $this->pdo->prepare("DELETE FROM campaña_planes WHERE campaña_id = ?")->execute([$campañaId]);
        
        $st = $this->pdo->prepare("INSERT INTO campaña_planes (campaña_id, plan_json) VALUES (?, ?)");
        return $st->execute([$campañaId, $planJson]);
    }

    public function limpiarEstrategias($campañaId) {
        $st = $this->pdo->prepare("DELETE FROM campaña_estrategias WHERE campaña_id = ?");
        return $st->execute([$campañaId]);
    }

    public function insertarEstrategia($campañaId, $titulo, $propuesta, $canal, $cta, $orden) {
        $sql = "INSERT INTO campaña_estrategias (campaña_id, titulo, propuesta, canal, cta, orden, completada) VALUES (?,?,?,?,?,?,0)";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$campañaId, $titulo, $propuesta, $canal, $cta, $orden]);
    }

    public function toggleCompletada($estrategiaId, $estado) {
        $st = $this->pdo->prepare("UPDATE campaña_estrategias SET completada=? WHERE id=?");
        return $st->execute([$estado, $estrategiaId]);
    }

    public function aprobarEstrategia($estrategiaId, $aprobado) {
        $st = $this->pdo->prepare("UPDATE campaña_estrategias SET aprobado=? WHERE id=?");
        return $st->execute([$aprobado, $estrategiaId]);
    }
    
    public function obtenerServiciosActivos() {
        return $this->pdo->query("SELECT id, nombre FROM servicios WHERE activo = 1 ORDER BY nombre")->fetchAll();
    }

    public function obtenerNombreServicio($servicioId) {
        if (!$servicioId) return null;
        $st = $this->pdo->prepare("SELECT nombre FROM servicios WHERE id=?"); 
        $st->execute([$servicioId]);
        return $st->fetchColumn();
    }

    public function obtenerConfiguracionEmpresa() {
        return $this->pdo->query("SELECT * FROM configuracion_empresa LIMIT 1")->fetch() ?: [];
    }
}