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
    protected $allowedFields    = ['precio', 'vigente_desde', 'vigente_hasta', 'tipo_servicio_id'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'precio'           => 'required|decimal',
        'vigente_desde'    => 'required|valid_date',
        'tipo_servicio_id' => 'required|integer',
    ];

    /**
     * Tarifa vigente para un tipo de servicio en una fecha dada
     * (o la fecha actual si no se especifica).
     */
    public function vigentePara(int $tipoServicioId, ?string $fecha = null): ?array
    {
        $fecha ??= date('Y-m-d H:i:s');

        return $this->where('tipo_servicio_id', $tipoServicioId)
            ->where('vigente_desde <=', $fecha)
            ->groupStart()
                ->where('vigente_hasta >=', $fecha)
                ->orWhere('vigente_hasta', null)
            ->groupEnd()
            ->orderBy('vigente_desde', 'DESC')
            ->first();
    }
}
