<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PagosSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('Tb_Pagos')->countAllResults() > 0) {
            return;
        }

        $secretaria = $this->db->table('Tb_Usuarios')->where('email', 'secretaria@oficinadelagua.local')->get()->getRow();
        if (! $secretaria) {
            return;
        }

        $metodos = $this->db->table('Tb_Metodos_Pago')->get()->getResult();
        if (! $metodos) {
            return;
        }

        // Lecturas pagadas normalmente
        $recibosPagados = [
            'R-2026-0001', 'R-2026-0003', 'R-2026-0007', 'R-2026-0013',
            'R-2026-0015', 'R-2026-0017', 'R-2026-0019', 'R-2026-0021', 'R-2026-0022',
        ];

        // Un caso de "pagado, anulado, y vuelto a pagar" para demostrar esa logica en vivo
        $reciboConHistorial = 'R-2026-0002';

        $i = 0;
        foreach ($recibosPagados as $numeroRecibo) {
            $lectura = $this->db->table('Tb_Lecturas')->where('numero_recibo', $numeroRecibo)->get()->getRow();
            if (! $lectura) {
                continue;
            }

            $metodo    = $metodos[$i % count($metodos)];
            $fechaPago = date('Y-m-d H:i:s', strtotime($lectura->fecha . ' +3 days'));

            $this->db->table('Tb_Pagos')->insert([
                'monto'               => $lectura->monto_base + $lectura->monto_exceso,
                'fecha_pago'          => $fechaPago,
                'lectura_id'          => $lectura->id,
                'metodo_id'           => $metodo->id,
                'usuario_registro_id' => $secretaria->id,
                'anulado'             => 0,
            ]);

            $i++;
        }

        $lecturaConHistorial = $this->db->table('Tb_Lecturas')->where('numero_recibo', $reciboConHistorial)->get()->getRow();
        if ($lecturaConHistorial) {
            $metodo = $metodos[0];

            // Pago original, anulado
            $this->db->table('Tb_Pagos')->insert([
                'monto'               => $lecturaConHistorial->monto_base + $lecturaConHistorial->monto_exceso,
                'fecha_pago'          => date('Y-m-d H:i:s', strtotime($lecturaConHistorial->fecha . ' +2 days')),
                'lectura_id'          => $lecturaConHistorial->id,
                'metodo_id'           => $metodo->id,
                'usuario_registro_id' => $secretaria->id,
                'anulado'             => 1,
            ]);

            // Pago correcto, activo
            $this->db->table('Tb_Pagos')->insert([
                'monto'               => $lecturaConHistorial->monto_base + $lecturaConHistorial->monto_exceso,
                'fecha_pago'          => date('Y-m-d H:i:s', strtotime($lecturaConHistorial->fecha . ' +4 days')),
                'lectura_id'          => $lecturaConHistorial->id,
                'metodo_id'           => $metodo->id,
                'usuario_registro_id' => $secretaria->id,
                'anulado'             => 0,
            ]);
        }
    }
}
