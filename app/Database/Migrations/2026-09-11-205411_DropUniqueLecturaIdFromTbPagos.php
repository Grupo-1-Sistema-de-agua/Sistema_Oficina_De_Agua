<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUniqueLecturaIdFromTbPagos extends Migration
{
    public function up()
    {
        // Buscamos el nombre real de la llave foranea sobre lectura_id,
        // porque MySQL no permite borrar el indice mientras esa llave
        // dependa de el.
        $fk = $this->db->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'Tb_Pagos'
              AND COLUMN_NAME = 'lectura_id'
              AND REFERENCED_TABLE_NAME = 'Tb_Lecturas'
        ")->getRow();

        if ($fk) {
            $this->db->query('ALTER TABLE Tb_Pagos DROP FOREIGN KEY ' . $fk->CONSTRAINT_NAME);
        }

        // Ahora si se puede quitar el UNIQUE simple sobre lectura_id.
        $this->db->query('ALTER TABLE Tb_Pagos DROP INDEX lectura_id');

        // Un indice normal (no unico) para que la FK siga siendo eficiente.
        $this->db->query('ALTER TABLE Tb_Pagos ADD INDEX idx_lectura_id (lectura_id)');

        // Columna calculada: solo tiene valor cuando el pago esta activo.
        // Con UNIQUE sobre esta columna, nunca puede haber dos pagos
        // ACTIVOS de la misma lectura, pero si pueden convivir varios
        // pagos anulados de esa lectura (todos con NULL aqui).
        $this->db->query('
            ALTER TABLE Tb_Pagos
            ADD COLUMN lectura_id_activa INT UNSIGNED
                GENERATED ALWAYS AS (CASE WHEN anulado = 0 THEN lectura_id ELSE NULL END) STORED,
            ADD UNIQUE KEY uq_lectura_id_activa (lectura_id_activa)
        ');

        // Recreamos la llave foranea, ahora apoyada en el indice normal.
        $this->db->query('
            ALTER TABLE Tb_Pagos
            ADD CONSTRAINT fk_pagos_lectura_id
            FOREIGN KEY (lectura_id) REFERENCES Tb_Lecturas(id)
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE Tb_Pagos DROP FOREIGN KEY fk_pagos_lectura_id');
        $this->db->query('ALTER TABLE Tb_Pagos DROP INDEX uq_lectura_id_activa, DROP COLUMN lectura_id_activa');
        $this->db->query('ALTER TABLE Tb_Pagos DROP INDEX idx_lectura_id');
        $this->db->query('ALTER TABLE Tb_Pagos ADD UNIQUE KEY lectura_id (lectura_id)');
        $this->db->query('
            ALTER TABLE Tb_Pagos
            ADD CONSTRAINT fk_pagos_lectura_id
            FOREIGN KEY (lectura_id) REFERENCES Tb_Lecturas(id)
        ');
    }
}