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

   protected $validationRules = [
    'nombre'              => 'required|max_length[150]',
    'dpi'                 => 'permit_empty|exact_length[13]|is_unique[Tb_Clientes.dpi,id,{id}]',
    'direccion_principal' => 'required|max_length[255]',
            ];
}
