<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\LecturaModel;
use App\Models\TarifaModel;

class LecturasSeeder extends Seeder
{
    public function run()
    {
        $existe = $this->db->table('Tb_Lecturas')->where('numero_recibo', 'R-2026-0001')->get()->getRow();
        if ($existe) {
            return;
        }

        $lector = $this->db->table('Tb_Usuarios')->where('email', 'lector@oficinadelagua.local')->get()->getRow();
        if (! $lector) {
            return;
        }

        $tipoExceso = $this->db->table('Tb_Tipos_Servicios')->where('codigo', 'EXCESO')->get()->getRow();

        $lecturaModel = new LecturaModel();
        $tarifaModel  = new TarifaModel();

        // codigo_fisico => [ [fecha, lectura_actual], ... ] en orden cronologico
        $plan = [
            'CT-0001' => [['2026-08-05', 12500], ['2026-09-05', 24300]],
            'CT-0003' => [['2026-08-06', 14800], ['2026-09-06', 28300]],
            'CT-0004' => [['2026-08-07', 16200], ['2026-09-07', 28200]],
            'CT-0005' => [['2026-08-08', 9800],  ['2026-09-08', 20000]],
            'CT-0007' => [['2026-08-09', 13100], ['2026-09-09', 30100]],
            'CT-0009' => [['2026-08-10', 15600], ['2026-09-04', 25100]],
            'CT-0010' => [['2026-08-04', 11200], ['2026-09-03', 23800]],
            'CT-0011' => [['2026-08-03', 14200], ['2026-09-02', 28100]],
            'CT-0013' => [['2026-08-02', 10500], ['2026-09-01', 21500]],
            'CT-0014' => [['2026-08-01', 12800], ['2026-09-08', 27400]],
            'CT-0002' => [['2026-09-05', 48000]],
            'CT-0006' => [['2026-09-06', 55500]],
            'CT-0008' => [['2026-09-07', 63000]],
            'CT-0012' => [['2026-09-08', 41000]],
        ];

        $consecutivo = 1;

        foreach ($plan as $codigoFisico => $lecturas) {
            $contadorRow = $this->db->table('Tb_Contadores')
                ->select('Tb_Contadores.*, Tb_Tipos_Servicios.volumen_incluido_litros')
                ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
                ->where('codigo_fisico', $codigoFisico)
                ->get()->getRowArray();

            if (! $contadorRow) {
                continue;
            }

            $anterior = 0;

            foreach ($lecturas as [$fechaCorta, $actual]) {
                $fecha   = $fechaCorta . ' 09:00:00';
                $consumo = $actual - $anterior;

                $tarifaBase = $tarifaModel->vigentePara((int) $contadorRow['tipo_servicio_id'], $fecha);
                if (! $tarifaBase) {
                    $anterior = $actual;
                    continue;
                }

                $montoBase      = (float) $tarifaBase['precio'];
                $montoExceso    = 0;
                $tarifaExcesoId = null;
                $incluido       = (int) $contadorRow['volumen_incluido_litros'];

                if ($consumo > $incluido && $tipoExceso) {
                    $excedente    = $consumo - $incluido;
                    $tarifaExceso = $tarifaModel->vigentePara((int) $tipoExceso->id, $fecha);
                    if ($tarifaExceso) {
                        $montoExceso    = ceil($excedente / 1000) * $tarifaExceso['precio'];
                        $tarifaExcesoId = (int) $tarifaExceso['id'];
                    }
                }

                $numeroRecibo = 'R-2026-' . str_pad((string) $consecutivo, 4, '0', STR_PAD_LEFT);

                $lecturaModel->insert([
                    'numero_recibo'     => $numeroRecibo,
                    'lectura_anterior'  => $anterior,
                    'lectura_actual'    => $actual,
                    'consumo_litros'    => $consumo,
                    'fecha'             => $fecha,
                    'contador_id'       => (int) $contadorRow['id'],
                    'tarifa_base_id'    => (int) $tarifaBase['id'],
                    'tarifa_exceso_id'  => $tarifaExcesoId,
                    'usuario_lector_id' => $lector->id,
                    'monto_base'        => $montoBase,
                    'monto_exceso'      => $montoExceso,
                ]);

                $anterior = $actual;
                $consecutivo++;
            }
        }
    }
}
