<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TiposServiciosSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['codigo' => 'CUARTO_PAJA', 'nombre' => '1/4 de paja', 'volumen_incluido_litros' => 15000, 'es_servicio' => 1],
            ['codigo' => 'MEDIA_PAJA',  'nombre' => '1/2 paja',    'volumen_incluido_litros' => 60000, 'es_servicio' => 1],
            ['codigo' => 'EXCESO',      'nombre' => 'Exceso de consumo', 'volumen_incluido_litros' => 1000, 'es_servicio' => 0],
        ];

        foreach ($tipos as $tipo) {
            $existe = $this->db->table('Tb_Tipos_Servicios')->where('codigo', $tipo['codigo'])->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Tipos_Servicios')->insert($tipo);
            }
        }
    }
}
