<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'Tb_Clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre', 'telefono', 'direccion_principal'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre'              => 'required|max_length[150]',
        'direccion_principal' => 'required|max_length[255]',
    ];
}
