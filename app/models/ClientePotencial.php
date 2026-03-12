<?php

class ClientePotencial {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function contar($busqueda = '') {
        $sql = "
            SELECT COUNT(*)
            FROM clientes_potenciales l
            LEFT JOIN servicios s ON s.id = l.tipo_servicio_id
            LEFT JOIN campañas  c ON c.id = l.campaña_id
        ";

        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(l.nombre LIKE ? OR l.email LIKE ? OR l.telefono LIKE ? OR l.canal LIKE ?)";
            $like = "%$busqueda%";
            $params = array_fill(0, 4, $like);
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn();
    }

    public function listar($busqueda = '', $limit = 6, $offset = 0) {
        $sql = "
            SELECT
                l.*,
                s.nombre AS servicio_nombre,
                c.nombre AS campaña_nombre
            FROM clientes_potenciales l
            LEFT JOIN servicios s ON s.id = l.tipo_servicio_id
            LEFT JOIN campañas  c ON c.id = l.campaña_id
        ";

        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(l.nombre LIKE ? OR l.email LIKE ? OR l.telefono LIKE ? OR l.canal LIKE ?)";
            $like = "%$busqueda%";
            $params = array_fill(0, 4, $like);
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY l.id DESC LIMIT $limit OFFSET $offset";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtenerPorId($id) {
        $st = $this->pdo->prepare("SELECT * FROM clientes_potenciales WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function obtenerServicios() {
        return $this->pdo->query("SELECT id, nombre FROM servicios WHERE activo = 1 ORDER BY nombre")->fetchAll();
    }

    public function obtenerCampañas() {
        return $this->pdo->query("SELECT id, nombre FROM campañas ORDER BY id DESC")->fetchAll();
    }

    public function crear($nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas) {
        $sql = "
            INSERT INTO clientes_potenciales (nombre, telefono, email, tipo_servicio_id, campaña_id, canal, notas)
            VALUES (?,?,?,?,?,?,?)
        ";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas]);
    }

    public function actualizar($id, $nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas) {
        $sql = "
            UPDATE clientes_potenciales
            SET nombre = ?, telefono = ?, email = ?, tipo_servicio_id = ?, campaña_id = ?, canal = ?, notas = ?
            WHERE id = ?
        ";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$nombre, $telefono, $email, $tipo_servicio_id, $campaña_id, $canal, $notas, $id]);
    }

    public function eliminar($id) {
        $st = $this->pdo->prepare("DELETE FROM clientes_potenciales WHERE id = ?");
        return $st->execute([$id]);
    }
}