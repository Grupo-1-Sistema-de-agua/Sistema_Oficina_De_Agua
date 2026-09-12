<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Corre todos los seeders del proyecto en el orden correcto segun las
 * dependencias entre tablas. Uso: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(TiposServiciosSeeder::class);
        $this->call(MetodosPagoSeeder::class);
        $this->call(SectoresSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(ClientesSeeder::class);
        $this->call(ContadoresSeeder::class);
        $this->call(TarifasSeeder::class);
        $this->call(LecturasSeeder::class);
        $this->call(PagosSeeder::class);
    }
}
