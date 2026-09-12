<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TarifaModel;

class TarifasSeeder extends Seeder
{
    public function run()
    {
        $existe = $this->db->table('Tb_Tarifas')->where('precio', 35.00)->get()->getRow();
        if ($existe) {
            return;
        }

        $tipos = [];
        foreach (['CUARTO_PAJA', 'MEDIA_PAJA', 'EXCESO'] as $codigo) {
            $tipo = $this->db->table('Tb_Tipos_Servicios')->where('codigo', $codigo)->get()->getRow();
            if (! $tipo) {
                return;
            }
            $tipos[$codigo] = $tipo->id;
        }

        $tarifas = [
            ['precio' => 35.00,  'vigente_desde' => '2025-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['CUARTO_PAJA'], 'anulada' => 0],
            ['precio' => 40.00,  'vigente_desde' => '2026-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['CUARTO_PAJA'], 'anulada' => 0],
            ['precio' => 70.00,  'vigente_desde' => '2025-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['MEDIA_PAJA'],  'anulada' => 0],
            ['precio' => 80.00,  'vigente_desde' => '2026-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['MEDIA_PAJA'],  'anulada' => 0],
            ['precio' => 3.50,   'vigente_desde' => '2025-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['EXCESO'],      'anulada' => 0],
            ['precio' => 4.00,   'vigente_desde' => '2026-06-01 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['EXCESO'],      'anulada' => 0],
            ['precio' => 999.00, 'vigente_desde' => '2026-06-15 00:00:00', 'vigente_hasta' => null, 'tipo_servicio_id' => $tipos['CUARTO_PAJA'], 'anulada' => 1],
        ];

        $this->db->table('Tb_Tarifas')->insertBatch($tarifas);

        $tarifaModel = new TarifaModel();
        $tarifaModel->recalcularVigenciaHasta($tipos['CUARTO_PAJA']);
        $tarifaModel->recalcularVigenciaHasta($tipos['MEDIA_PAJA']);
        $tarifaModel->recalcularVigenciaHasta($tipos['EXCESO']);
    }
}
