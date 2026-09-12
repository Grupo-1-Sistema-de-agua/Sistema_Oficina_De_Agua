<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnuladaToTbTarifas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Tarifas', [
            'anulada' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'after'      => 'vigente_hasta',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Tarifas', 'anulada');
    }
}