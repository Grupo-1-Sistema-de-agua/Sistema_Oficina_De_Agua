<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectorPruebaSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("INSERT INTO Tb_Sectores (nombre) VALUES ('El Centro'), ('Casco Urbano')");
    }
}