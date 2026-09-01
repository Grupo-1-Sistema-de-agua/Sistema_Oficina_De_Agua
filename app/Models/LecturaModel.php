<?php

namespace App\Models;

use CodeIgniter\Model;

class LecturaModel extends Model
{
    protected $table            = 'Tb_Lecturas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'numero_recibo', 'lectura_anterior', 'lectura_actual', 'consumo_litros',
        'fecha', 'contador_id', 'tarifa_base_id', 'tarifa_exceso_id', 'usuario_lector_id',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'numero_recibo'      => 'required|max_length[20]|is_unique[Tb_Lecturas.numero_recibo,id,{id}]',
        'lectura_actual'     => 'required|integer',
        'fecha'              => 'required|valid_date',
        'contador_id'        => 'required|integer',
        'tarifa_base_id'     => 'required|integer',
        'usuario_lector_id'  => 'required|integer',
    ];

    /**
     * Lecturas que todavia no tienen un pago asociado (para el dashboard
     * de estado de cuenta y para el modulo de pagos).
     */
    public function pendientesDePago(): array
    {
        return $this->select('Tb_Lecturas.*')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->where('Tb_Pagos.id', null)
            ->findAll();
    }
}
