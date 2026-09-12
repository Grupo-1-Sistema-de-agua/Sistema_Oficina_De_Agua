<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContadoresSeeder extends Seeder
{
    public function run()
    {
        $existe = $this->db->table('Tb_Contadores')->where('codigo_fisico', 'CT-0001')->get()->getRow();
        if ($existe) {
            return;
        }

        $clienteIds = [];
        for ($i = 1; $i <= 12; $i++) {
            $dpi = '25' . str_pad((string) $i, 11, '0', STR_PAD_LEFT);
            $cliente = $this->db->table('Tb_Clientes')->where('dpi', $dpi)->get()->getRow();
            if (! $cliente) {
                return;
            }
            $clienteIds[$i] = $cliente->id;
        }

        $cuartoPaja = $this->db->table('Tb_Tipos_Servicios')->where('codigo', 'CUARTO_PAJA')->get()->getRow();
        $mediaPaja  = $this->db->table('Tb_Tipos_Servicios')->where('codigo', 'MEDIA_PAJA')->get()->getRow();

        $sectorId = [];
        foreach (['El Centro', 'Casco Urbano', 'Aldea San Isidro'] as $nombre) {
            $sector = $this->db->table('Tb_Sectores')->where('nombre', $nombre)->get()->getRow();
            $sectorId[$nombre] = $sector->id ?? null;
        }

        $contadores = [
            ['codigo_fisico' => 'CT-0001', 'direccion_servicio' => '3a Calle 4-12 Zona 1, El Centro',               'activo' => 1, 'cliente_id' => $clienteIds[1],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['El Centro'],       'fecha_asignacion' => '2025-03-10', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0002', 'direccion_servicio' => 'Local 3, 5a Avenida 2-30 Zona 2, Casco Urbano',  'activo' => 1, 'cliente_id' => $clienteIds[1],  'tipo_servicio_id' => $mediaPaja->id,  'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-05-20', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0003', 'direccion_servicio' => '5a Avenida 2-30 Zona 2, Casco Urbano',          'activo' => 1, 'cliente_id' => $clienteIds[2],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-01-15', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0004', 'direccion_servicio' => '5a Avenida 2-31 Zona 2, Casco Urbano',          'activo' => 1, 'cliente_id' => $clienteIds[2],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-07-02', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0005', 'direccion_servicio' => 'Aldea San Isidro, Sector 2',                    'activo' => 1, 'cliente_id' => $clienteIds[3],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Aldea San Isidro'], 'fecha_asignacion' => '2025-02-11', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0006', 'direccion_servicio' => 'Aldea San Isidro, Sector 2, terreno anexo',     'activo' => 1, 'cliente_id' => $clienteIds[3],  'tipo_servicio_id' => $mediaPaja->id,  'sector_id' => $sectorId['Aldea San Isidro'], 'fecha_asignacion' => '2025-09-01', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0007', 'direccion_servicio' => '2a Calle 8-19 Zona 1, El Centro',               'activo' => 1, 'cliente_id' => $clienteIds[4],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['El Centro'],       'fecha_asignacion' => '2025-04-18', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0008', 'direccion_servicio' => '6a Avenida 1-05 Zona 3, Casco Urbano',          'activo' => 1, 'cliente_id' => $clienteIds[5],  'tipo_servicio_id' => $mediaPaja->id,  'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-06-09', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0009', 'direccion_servicio' => 'Aldea San Isidro, Sector 1',                    'activo' => 1, 'cliente_id' => $clienteIds[6],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Aldea San Isidro'], 'fecha_asignacion' => '2025-03-25', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0010', 'direccion_servicio' => '4a Calle 10-22 Zona 1, El Centro',              'activo' => 1, 'cliente_id' => $clienteIds[7],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['El Centro'],       'fecha_asignacion' => '2025-05-30', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0011', 'direccion_servicio' => '1a Avenida 3-08 Zona 2, Casco Urbano',          'activo' => 1, 'cliente_id' => $clienteIds[8],  'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-02-27', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0012', 'direccion_servicio' => 'Aldea San Isidro, Sector 3',                    'activo' => 1, 'cliente_id' => $clienteIds[9],  'tipo_servicio_id' => $mediaPaja->id,  'sector_id' => $sectorId['Aldea San Isidro'], 'fecha_asignacion' => '2025-08-14', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0013', 'direccion_servicio' => '7a Calle 5-14 Zona 1, El Centro',               'activo' => 1, 'cliente_id' => $clienteIds[10], 'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['El Centro'],       'fecha_asignacion' => '2025-01-22', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0014', 'direccion_servicio' => '2a Avenida 9-27 Zona 3, Casco Urbano',          'activo' => 1, 'cliente_id' => $clienteIds[11], 'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Casco Urbano'],    'fecha_asignacion' => '2025-04-05', 'fecha_desactivacion' => null],
            ['codigo_fisico' => 'CT-0015', 'direccion_servicio' => 'Aldea San Isidro, Sector 2',                    'activo' => 0, 'cliente_id' => $clienteIds[12], 'tipo_servicio_id' => $cuartoPaja->id, 'sector_id' => $sectorId['Aldea San Isidro'], 'fecha_asignacion' => '2025-01-05', 'fecha_desactivacion' => '2026-07-15'],
        ];

        $this->db->table('Tb_Contadores')->insertBatch($contadores);
    }
}
