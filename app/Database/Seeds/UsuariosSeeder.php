<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $roles = [];
        foreach (['administrador', 'secretaria', 'lector'] as $nombreRol) {
            $rol = $this->db->table('Tb_Roles')->where('nombre', $nombreRol)->get()->getRow();
            if (! $rol) {
                return;
            }
            $roles[$nombreRol] = $rol->id;
        }

        $usuarios = [
            ['nombre' => 'Administrador',       'email' => 'admin@oficinadelagua.local',      'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),      'activo' => 1, 'rol_id' => $roles['administrador']],
            ['nombre' => 'Ana Sofia Ramirez',    'email' => 'secretaria@oficinadelagua.local', 'password_hash' => password_hash('secretaria123', PASSWORD_DEFAULT), 'activo' => 1, 'rol_id' => $roles['secretaria']],
            ['nombre' => 'Carlos Eduardo Lopez', 'email' => 'lector@oficinadelagua.local',     'password_hash' => password_hash('lector123', PASSWORD_DEFAULT),     'activo' => 1, 'rol_id' => $roles['lector']],
        ];

        foreach ($usuarios as $usuario) {
            $existe = $this->db->table('Tb_Usuarios')->where('email', $usuario['email'])->get()->getRow();
            if (! $existe) {
                $this->db->table('Tb_Usuarios')->insert($usuario);
            }
        }
    }
}
