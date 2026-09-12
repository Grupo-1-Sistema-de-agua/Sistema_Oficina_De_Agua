<?php

namespace App\Models;

use CodeIgniter\Model;

class SectorModel extends Model
{
    protected $table            = 'Tb_Sectores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',
        'nombre' => 'required|max_length[50]|is_unique[Tb_Sectores.nombre,id,{id}]',
    ];
}
