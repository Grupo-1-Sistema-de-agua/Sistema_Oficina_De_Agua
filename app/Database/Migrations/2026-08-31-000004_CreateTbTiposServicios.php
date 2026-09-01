<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbTiposServicios extends Migration
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
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'volumen_incluido_litros' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'es_servicio' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('codigo');
        $this->forge->createTable('Tb_Tipos_Servicios', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Tipos_Servicios', true);
    }
}
