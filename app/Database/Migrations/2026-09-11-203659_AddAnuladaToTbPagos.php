<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnuladaToTbPagos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Pagos', [
            'anulado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'after'      => 'usuario_registro_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Pagos', 'anulado');
    }
}
