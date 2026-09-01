<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbPagos extends Migration
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
            'monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'fecha_pago' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'lectura_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'metodo_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'usuario_registro_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('lectura_id');
        $this->forge->addForeignKey('lectura_id', 'Tb_Lecturas', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('metodo_id', 'Tb_Metodos_Pago', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('usuario_registro_id', 'Tb_Usuarios', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->createTable('Tb_Pagos', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Pagos', true);
    }
}
