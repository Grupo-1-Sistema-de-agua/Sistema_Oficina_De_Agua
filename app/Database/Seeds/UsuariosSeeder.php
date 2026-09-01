<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Crea un usuario administrador inicial para poder entrar al sistema
 * la primera vez. Cambien la contrasena despues del primer login.
 */
class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $rolAdmin = $this->db->table('Tb_Roles')->where('nombre', 'administrador')->get()->getRow();

        if (! $rolAdmin) {
            // Si Tb_Roles esta vacia, corran primero RolesSeeder (DatabaseSeeder ya lo hace en orden).
            return;
        }

        $existe = $this->db->table('Tb_Usuarios')->where('email', 'admin@oficinadelagua.local')->get()->getRow();

        if (! $existe) {
            $this->db->table('Tb_Usuarios')->insert([
                'nombre'        => 'Administrador',
                'email'         => 'admin@oficinadelagua.local',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'activo'        => 1,
                'rol_id'        => $rolAdmin->id,
            ]);
        }
    }
}
