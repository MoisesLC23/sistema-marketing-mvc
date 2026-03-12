<?php
class Usuario {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function contar($busqueda = '') {
        $sql = "SELECT COUNT(*) FROM usuarios u";
        $where = [];
        $params = [];
        if ($busqueda !== '') {
            $where[] = "(u.nombre LIKE ? OR u.correo LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn();
    }

    public function listar($busqueda = '', $limit = 8, $offset = 0) {
        $sql = "
            SELECT u.id, u.nombre, u.correo, u.rol_id, r.nombre AS rol, u.activo, u.creado_en
            FROM usuarios u
            LEFT JOIN roles r ON r.id = u.rol_id
        ";
        $where = [];
        $params = [];
        if ($busqueda !== '') {
            $where[] = "(u.nombre LIKE ? OR u.correo LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY u.id ASC LIMIT $limit OFFSET $offset";
        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtenerRoles() {
        return $this->pdo->query("SELECT id, nombre FROM roles ORDER BY id")->fetchAll();
    }

    public function obtenerPorId($id) {
        $st = $this->pdo->prepare("SELECT id, nombre, correo, rol_id, activo FROM usuarios WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function existeCorreo($correo, $excluirId = null) {
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $params = [$correo];

        if ($excluirId) {
            $sql .= " AND id != ?";
            $params[] = $excluirId;
        }

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (bool)$st->fetchColumn();
    }

    public function crear($nombre, $correo, $hash, $rol_id, $activo) {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña_hash, rol_id, activo) VALUES (?,?,?,?,?)";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$nombre, $correo, $hash, $rol_id, $activo]);
    }
    public function actualizar($id, $nombre, $correo, $rol_id, $activo, $hash = null) {
        if ($hash) {
            $sql = "UPDATE usuarios SET nombre=?, correo=?, rol_id=?, activo=?, contraseña_hash=? WHERE id=?";
            $params = [$nombre, $correo, $rol_id, $activo, $hash, $id];
        } else {
            $sql = "UPDATE usuarios SET nombre=?, correo=?, rol_id=?, activo=? WHERE id=?";
            $params = [$nombre, $correo, $rol_id, $activo, $id];
        }
        
        $st = $this->pdo->prepare($sql);
        return $st->execute($params);
    }
    public function cambiarEstado($id, $activo) {
        $st = $this->pdo->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
        return $st->execute([$activo, $id]);
    }
    public function eliminar($id) {
        $st = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $st->execute([$id]);
    }
    public function buscarPorCorreoLogin($correo) {
        $sql = "SELECT * FROM usuarios WHERE correo = ? AND activo = 1 LIMIT 1";
        $st = $this->pdo->prepare($sql);
        $st->execute([$correo]);
        return $st->fetch();
    }
}