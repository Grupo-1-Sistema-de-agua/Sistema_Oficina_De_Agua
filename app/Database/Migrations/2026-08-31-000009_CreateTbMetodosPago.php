<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbMetodosPago extends Migration
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
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nombre');
        $this->forge->createTable('Tb_Metodos_Pago', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Metodos_Pago', true);
    }
}
