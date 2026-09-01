<?php

namespace App\Models;

use CodeIgniter\Model;

class ContadorModel extends Model
{
    protected $table            = 'Tb_Contadores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'codigo_fisico', 'direccion_servicio', 'activo',
        'cliente_id', 'tipo_servicio_id', 'sector_id',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'codigo_fisico'      => 'required|max_length[30]|is_unique[Tb_Contadores.codigo_fisico,id,{id}]',
        'direccion_servicio' => 'required|max_length[255]',
        'cliente_id'         => 'required|integer',
        'tipo_servicio_id'   => 'required|integer',
        'sector_id'          => 'required|integer',
    ];

    /**
     * Contadores de un cliente, con el nombre de su tipo de servicio.
     */
    public function deCliente(int $clienteId): array
    {
        return $this->select('Tb_Contadores.*, Tb_Tipos_Servicios.nombre as tipo_servicio_nombre')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->where('cliente_id', $clienteId)
            ->findAll();
    }
}
