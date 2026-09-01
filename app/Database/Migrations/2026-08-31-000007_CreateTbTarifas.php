<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbTarifas extends Migration
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
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'vigente_desde' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'vigente_hasta' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'tipo_servicio_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('tipo_servicio_id', 'Tb_Tipos_Servicios', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->createTable('Tb_Tarifas', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Tarifas', true);
    }
}
