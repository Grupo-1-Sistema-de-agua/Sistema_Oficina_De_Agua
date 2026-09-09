<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMontoALecturas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Lecturas', [
            'monto_base' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => 0,
            ],
            'monto_exceso' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Lecturas', ['monto_base', 'monto_exceso']);
    }
}