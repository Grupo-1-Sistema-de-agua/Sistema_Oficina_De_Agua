<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MetodosPagoSeeder extends Seeder
{
    public function run()
    {
        $metodos = [
            ['nombre' => 'Efectivo'],
            ['nombre' => 'Transferencia'],
            ['nombre' => 'Deposito'],
        ];

        foreach ($metodos as $metodo) {
            $existe = $this->db->table('Tb_Metodos_Pago')->where('nombre', $metodo['nombre'])->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Metodos_Pago')->insert($metodo);
            }
        }
    }
}
