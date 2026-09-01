<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table            = 'Tb_Roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[Tb_Roles.nombre,id,{id}]',
    ];
}
