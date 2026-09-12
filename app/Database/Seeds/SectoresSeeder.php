<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectoresSeeder extends Seeder
{
    public function run()
    {
        $sectores = ['El Centro', 'Casco Urbano', 'Aldea San Isidro'];

        foreach ($sectores as $nombre) {
            $existe = $this->db->table('Tb_Sectores')->where('nombre', $nombre)->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Sectores')->insert(['nombre' => $nombre]);
            }
        }
    }
}
