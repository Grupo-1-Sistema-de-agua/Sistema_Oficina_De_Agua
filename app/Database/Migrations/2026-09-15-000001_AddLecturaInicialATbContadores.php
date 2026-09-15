<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLecturaInicialATbContadores extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Contadores', [
            'lectura_inicial' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Contadores', ['lectura_inicial']);
    }
}