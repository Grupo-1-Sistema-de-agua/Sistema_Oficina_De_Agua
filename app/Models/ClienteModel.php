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
    protected $allowedFields    = ['nombre','dpi', 'telefono', 'direccion_principal'];

    protected $useTimestamps = false;

    // AQUI AGREGAMOS EL IS_UNIQUE AL NOMBRE
    protected $validationRules = [
        'nombre'              => 'required|max_length[150]|is_unique[Tb_Clientes.nombre,id,{id}]',
        'dpi'                 => 'required|exact_length[13]|is_unique[Tb_Clientes.dpi,id,{id}]',
        'direccion_principal' => 'required|max_length[255]',
    ];

    // AQUI TRADUCIMOS AMBOS ERRORES
    protected $validationMessages = [
        'nombre' => [
            'is_unique' => 'Este nombre de cliente ya se encuentra registrado.'
        ],
        'dpi' => [
            'is_unique' => 'Este DPI ya se encuentra registrado en el sistema.'
        ]
    ];
}