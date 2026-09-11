<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table            = 'Tb_Pagos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['monto', 'fecha_pago', 'lectura_id', 'metodo_id', 'usuario_registro_id', 'anulado'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'monto'               => 'required|decimal',
        'fecha_pago'          => 'required|valid_date',
        'lectura_id'          => 'required|integer|is_not_unique[Tb_Lecturas.id]',
        'metodo_id'           => 'required|integer|is_not_unique[Tb_Metodos_Pago.id]',
        'usuario_registro_id' => 'required|integer|is_not_unique[Tb_Usuarios.id]',
    ];
}