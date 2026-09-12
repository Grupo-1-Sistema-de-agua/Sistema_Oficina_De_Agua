<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFechasAContadores extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Contadores', [
            'fecha_asignacion'    => ['type' => 'DATE', 'null' => true],
            'fecha_desactivacion' => ['type' => 'DATE', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('Tb_Contadores', ['fecha_asignacion', 'fecha_desactivacion']);
    }
}