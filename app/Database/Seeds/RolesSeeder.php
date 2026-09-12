<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = ['administrador', 'secretaria', 'lector'];

        foreach ($roles as $nombre) {
            $existe = $this->db->table('Tb_Roles')->where('nombre', $nombre)->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Roles')->insert(['nombre' => $nombre]);
            }
        }
    }
}
