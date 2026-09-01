<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbLecturas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'numero_recibo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'lectura_anterior' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 0,
            ],
            'lectura_actual' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'consumo_litros' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'fecha' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'contador_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tarifa_base_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tarifa_exceso_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'usuario_lector_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('numero_recibo');
        $this->forge->addForeignKey('contador_id', 'Tb_Contadores', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('tarifa_base_id', 'Tb_Tarifas', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('tarifa_exceso_id', 'Tb_Tarifas', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('usuario_lector_id', 'Tb_Usuarios', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->createTable('Tb_Lecturas', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Lecturas', true);
    }
}
