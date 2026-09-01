<?php

namespace App\Models;

use CodeIgniter\Model;

class MetodoPagoModel extends Model
{
    protected $table            = 'Tb_Metodos_Pago';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required|max_length[30]|is_unique[Tb_Metodos_Pago.nombre,id,{id}]',
    ];
}
