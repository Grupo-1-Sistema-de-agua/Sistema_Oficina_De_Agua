<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbRecibos extends Migration
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
            'numero_recibo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            // Llaves foráneas
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            // Snapshot contable (datos congelados en el tiempo)
            'nombre_cliente' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'numero_contador' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            // Detalles monetarios
            'monto_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'fecha_emision' => [
                'type'       => 'DATETIME',
            ],
            // Auditoría y Soft Deletes
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        // Relación con Tb_Clientes. Usamos 'SET NULL' para que si se borra físico un cliente, el recibo no explote.
        $this->forge->addForeignKey('id_cliente', 'Tb_Clientes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('Tb_Recibos', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Recibos', true);
    }
}
