<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MetodosPagoSeeder extends Seeder
{
    public function run()
    {
        $metodos = ['Efectivo', 'Transferencia', 'Deposito'];

        foreach ($metodos as $nombre) {
            $existe = $this->db->table('Tb_Metodos_Pago')->where('nombre', $nombre)->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Metodos_Pago')->insert(['nombre' => $nombre]);
            }
        }
    }
}
