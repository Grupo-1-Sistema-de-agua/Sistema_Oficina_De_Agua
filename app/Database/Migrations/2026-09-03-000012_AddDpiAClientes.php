<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDpiAClientes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('Tb_Clientes', [
            'dpi' => [
                'type'       => 'VARCHAR',
                'constraint' => 13,
                'null'       => true,
                'after'      => 'nombre',
            ],
        ]);
        $this->db->query('ALTER TABLE Tb_Clientes ADD UNIQUE KEY dpi_unico (dpi)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE Tb_Clientes DROP KEY dpi_unico');
        $this->forge->dropColumn('Tb_Clientes', 'dpi');
    }
}