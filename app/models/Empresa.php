<?php
class Empresa {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function obtener() {
        $st = $this->pdo->query("SELECT * FROM configuracion_empresa ORDER BY id ASC LIMIT 1");
        return $st->fetch();
    }
    public function crear($datos) {
        $sql = "
            INSERT INTO configuracion_empresa
            (nombre_empresa, rubro_sector, publico_objetivo, anios_mercado, ubicacion,
             url_web, whatsapp, email_contacto, competidores_json,
             acerca_nosotros, mision, vision, valores,
             fortalezas_json, debilidades_json, oportunidades_json, amenazas_json)
            VALUES 
            (:nombre_empresa, :rubro_sector, :publico_objetivo, :anios_mercado, :ubicacion,
             :url_web, :whatsapp, :email_contacto, :competidores_json,
             :acerca_nosotros, :mision, :vision, :valores,
             :fortalezas_json, :debilidades_json, :oportunidades_json, :amenazas_json)
        ";
        
        $st = $this->pdo->prepare($sql);
        return $st->execute($datos);
    }
    public function actualizar($id, $datos) {
        $sql = "
            UPDATE configuracion_empresa
            SET nombre_empresa = :nombre_empresa,
                rubro_sector = :rubro_sector,
                publico_objetivo = :publico_objetivo,
                anios_mercado = :anios_mercado,
                ubicacion = :ubicacion,
                url_web = :url_web,
                whatsapp = :whatsapp,
                email_contacto = :email_contacto,
                competidores_json = :competidores_json,
                acerca_nosotros = :acerca_nosotros,
                mision = :mision,
                vision = :vision,
                valores = :valores,
                fortalezas_json = :fortalezas_json,
                debilidades_json = :debilidades_json,
                oportunidades_json = :oportunidades_json,
                amenazas_json = :amenazas_json
            WHERE id = :id
        ";
        $datos['id'] = $id;
        $st = $this->pdo->prepare($sql);
        return $st->execute($datos);
    }
}
