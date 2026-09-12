<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        $existe = $this->db->table('Tb_Clientes')->where('dpi', '2500000000001')->get()->getRow();
        if ($existe) {
            return;
        }

        $clientes = [
            ['nombre' => 'María Fernanda González López',   'telefono' => '55120001', 'direccion_principal' => '3a Calle 4-12 Zona 1, El Centro'],
            ['nombre' => 'Carlos Roberto Pérez Hernández',  'telefono' => '55120002', 'direccion_principal' => '5a Avenida 2-30 Zona 2, Casco Urbano'],
            ['nombre' => 'Ana Lucía Martínez Ramírez',      'telefono' => '55120003', 'direccion_principal' => 'Aldea San Isidro, Sector 2'],
            ['nombre' => 'José Manuel Ortiz Castillo',      'telefono' => '55120004', 'direccion_principal' => '2a Calle 8-19 Zona 1, El Centro'],
            ['nombre' => 'Gabriela Alejandra Ruiz Morales', 'telefono' => '55120005', 'direccion_principal' => '6a Avenida 1-05 Zona 3, Casco Urbano'],
            ['nombre' => 'Luis Fernando Chávez Aguilar',    'telefono' => '55120006', 'direccion_principal' => 'Aldea San Isidro, Sector 1'],
            ['nombre' => 'Silvia Patricia Mendoza Flores',  'telefono' => '55120007', 'direccion_principal' => '4a Calle 10-22 Zona 1, El Centro'],
            ['nombre' => 'Édgar Alexander Reyes Gómez',     'telefono' => '55120008', 'direccion_principal' => '1a Avenida 3-08 Zona 2, Casco Urbano'],
            ['nombre' => 'Karla Ivonne Solórzano Vásquez',  'telefono' => '55120009', 'direccion_principal' => 'Aldea San Isidro, Sector 3'],
            ['nombre' => 'Miguel Ángel Cabrera Rodríguez',  'telefono' => '55120010', 'direccion_principal' => '7a Calle 5-14 Zona 1, El Centro'],
            ['nombre' => 'Diana Marisol Juárez Sandoval',   'telefono' => '55120011', 'direccion_principal' => '2a Avenida 9-27 Zona 3, Casco Urbano'],
            ['nombre' => 'Pedro Antonio Lemus Barrientos',  'telefono' => '55120012', 'direccion_principal' => 'Aldea San Isidro, Sector 2'],
        ];

        foreach ($clientes as $i => $cliente) {
            $cliente['dpi'] = '25' . str_pad((string) ($i + 1), 11, '0', STR_PAD_LEFT);
            $this->db->table('Tb_Clientes')->insert($cliente);
        }
    }
}
