<?php
class Servicio {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function contar($busqueda = '') {
        $sql = "SELECT COUNT(*) FROM servicios";
        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(nombre LIKE ? OR slug LIKE ? OR descripcion LIKE ?)";
            $like = "%$busqueda%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (int)$st->fetchColumn();
    }

    public function listar($busqueda = '', $limit = 6, $offset = 0) {
        $sql = "SELECT * FROM servicios";
        $where = [];
        $params = [];

        if ($busqueda !== '') {
            $where[] = "(nombre LIKE ? OR slug LIKE ? OR descripcion LIKE ?)";
            $like = "%$busqueda%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function obtenerPorId($id) {
        $st = $this->pdo->prepare("SELECT * FROM servicios WHERE id = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function existeSlug($slug, $excluirId = null) {
        $sql = "SELECT id FROM servicios WHERE slug = ?";
        $params = [$slug];

        if ($excluirId) {
            $sql .= " AND id != ?";
            $params[] = $excluirId;
        }

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return (bool)$st->fetchColumn();
    }

    public function crear($slug, $nombre, $descripcion, $activo) {
        $sql = "INSERT INTO servicios (slug, nombre, descripcion, activo) VALUES (?, ?, ?, ?)";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$slug, $nombre, $descripcion, $activo]);
    }

    public function actualizar($id, $slug, $nombre, $descripcion, $activo) {
        $sql = "UPDATE servicios SET slug=?, nombre=?, descripcion=?, activo=? WHERE id=?";
        $st = $this->pdo->prepare($sql);
        return $st->execute([$slug, $nombre, $descripcion, $activo, $id]);
    }

    public function eliminar($id) {
        $st = $this->pdo->prepare("DELETE FROM servicios WHERE id = ?");
        return $st->execute([$id]);
    }
}