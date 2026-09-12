<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropTbRecibos extends Migration
{
    public function up()
    {
        // Tb_Recibos ya no existe como tabla propia: un "recibo" ahora
        // es un documento generado al vuelo desde Tb_Lecturas, no un
        // registro independiente que alguien captura a mano.
        $this->forge->dropTable('Tb_Recibos', true);
    }

    public function down()
    {
        // Recrea la tabla tal como estaba en la migracion original,
        // por si algun dia hay que revertir esta decision.
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'numero_recibo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nombre_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'numero_contador' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'monto_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'fecha_emision' => [
                'type' => 'DATETIME',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_cliente', 'Tb_Clientes', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('Tb_Recibos', true, ['ENGINE' => 'InnoDB']);
    }
}