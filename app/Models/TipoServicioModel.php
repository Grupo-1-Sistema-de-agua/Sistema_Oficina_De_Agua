<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoServicioModel extends Model
{
    protected $table            = 'Tb_Tipos_Servicios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['codigo', 'nombre', 'volumen_incluido_litros', 'es_servicio'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',
        'codigo' => 'required|max_length[20]|is_unique[Tb_Tipos_Servicios.codigo,id,{id}]',
        'nombre' => 'required|max_length[50]',
    ];

    /**
     * Solo los tipos que representan un servicio contratable
     * (excluye el tipo "exceso", que es solo para tarifas de excedente).
     */
    public function contratables(): array
    {
        return $this->where('es_servicio', 1)->findAll();
    }
}
