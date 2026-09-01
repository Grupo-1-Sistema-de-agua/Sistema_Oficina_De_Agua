<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nombre' => 'administrador'],
            ['nombre' => 'secretaria'],
            ['nombre' => 'lector'],
        ];

        foreach ($roles as $rol) {
            $existe = $this->db->table('Tb_Roles')->where('nombre', $rol['nombre'])->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Roles')->insert($rol);
            }
        }
    }
}
