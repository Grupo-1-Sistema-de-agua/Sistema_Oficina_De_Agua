<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbContadores extends Migration
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
            'codigo_fisico' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'direccion_servicio' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'cliente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tipo_servicio_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'sector_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('codigo_fisico');
        $this->forge->addForeignKey('cliente_id', 'Tb_Clientes', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('tipo_servicio_id', 'Tb_Tipos_Servicios', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('sector_id', 'Tb_Sectores', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->createTable('Tb_Contadores', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Contadores', true);
    }
}
