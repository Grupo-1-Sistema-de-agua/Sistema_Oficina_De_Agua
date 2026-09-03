<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifaModel extends Model
{
    protected $table            = 'Tb_Tarifas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['precio', 'vigente_desde', 'vigente_hasta', 'tipo_servicio_id', 'anulada'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'precio'           => 'required|decimal|greater_than[0]',
        'vigente_desde'    => 'required|valid_date',
        'tipo_servicio_id' => 'required|integer|is_not_unique[Tb_Tipos_Servicios.id]',
    ];

    /**
     * Tarifa vigente para un tipo de servicio en una fecha dada
     * (o la fecha actual si no se especifica).
     */
    public function vigentePara(int $tipoServicioId, ?string $fecha = null): ?array
    {
        $fecha ??= date('Y-m-d H:i:s');

        return $this->where('tipo_servicio_id', $tipoServicioId)
            ->where('anulada', 0)
            ->where('vigente_desde <=', $fecha)
            ->groupStart()
                ->where('vigente_hasta >=', $fecha)
                ->orWhere('vigente_hasta', null)
            ->groupEnd()
            ->orderBy('vigente_desde', 'DESC')
            ->first();
    }

    /**
    * Recalcula y guarda vigente_hasta para todas las tarifas de un
    * tipo de servicio, en orden cronologico. Cada tarifa "termina"
    * exactamente cuando comienza la siguiente; la mas reciente queda
    * con vigente_hasta = NULL (todavia abierta / vigente).
    */
    public function recalcularVigenciaHasta(int $tipoServicioId): void
    {
        $tarifas = $this->where('tipo_servicio_id', $tipoServicioId)
            ->where('anulada', 0)
            ->orderBy('vigente_desde', 'ASC')
            ->findAll();

        $total = count($tarifas);

        foreach ($tarifas as $i => $tarifa) {
            $nuevoVigenteHasta = ($i < $total - 1) ? $tarifas[$i + 1]['vigente_desde'] : null;

            if ($tarifa['vigente_hasta'] !== $nuevoVigenteHasta) {
                $this->update($tarifa['id'], ['vigente_hasta' => $nuevoVigenteHasta]);
            }
        }
    }
}