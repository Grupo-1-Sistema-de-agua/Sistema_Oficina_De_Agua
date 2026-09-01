<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Corre todos los seeders base del proyecto en el orden correcto.
 * Uso: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(TiposServiciosSeeder::class);
        $this->call(MetodosPagoSeeder::class);
        $this->call(UsuariosSeeder::class);
    }
}
