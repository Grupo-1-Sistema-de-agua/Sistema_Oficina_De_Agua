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
    protected $allowedFields = [
    'codigo_fisico', 'direccion_servicio', 'activo',
    'cliente_id', 'tipo_servicio_id', 'sector_id',
    'fecha_asignacion', 'fecha_desactivacion',
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
     * Lista de contadores ACTIVOS pendientes de lectura, con el nombre del
     * cliente, sector y tipo de servicio (datos que necesita la vista).
     *
     * CAMBIO: ahora acepta un segundo parametro $q para buscar por numero de
     * contador (codigo_fisico) o por nombre del cliente, ademas del filtro
     * opcional por sector. Esto da soporte al caso de uso "Buscar por numero
     * de contador" del modulo de lecturas.
     */
   public function pendientesLectura(?int $sectorId = null, ?string $q = null): array
{
    $this->select('Tb_Contadores.*, Tb_Clientes.nombre as cliente_nombre, Tb_Sectores.nombre as sector_nombre, Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Tipos_Servicios.volumen_incluido_litros')
        ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
        ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
        ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
        ->where('Tb_Contadores.activo', 1);

    if ($sectorId) {
        $this->where('Tb_Contadores.sector_id', $sectorId);
    }

    if ($q) {
        $this->groupStart()
            ->like('Tb_Contadores.codigo_fisico', $q)
            ->orLike('Tb_Clientes.nombre', $q)
            ->groupEnd();
    }

    return $this->orderBy('Tb_Sectores.nombre', 'ASC')->findAll();
}

    /**
     * Regla: un contador ACTIVO por (cliente + direccion).
     */
    public function activoEnMismaDireccion(?int $clienteId, ?string $direccion, ?int $excluirId = null): bool
    {
        if (! $clienteId || ! $direccion) {
            return false;
        }

        $this->where('cliente_id', $clienteId)
             ->where('direccion_servicio', $direccion)
             ->where('activo', 1);

        if ($excluirId) {
            $this->where('id !=', $excluirId);
        }

        return (bool) $this->countAllResults();
    }

    /**
     * Busqueda: por codigo, direccion, nombre del cliente o DPI (mas sector).
     */
    public function buscar(?string $q = null, ?int $sectorId = null): array
    {
        $this->select('Tb_Contadores.*, Tb_Clientes.nombre as cliente_nombre, Tb_Clientes.dpi as cliente_dpi,
                       Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Sectores.nombre as sector_nombre')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id');

        if ($q) {
            $this->groupStart()
                ->like('Tb_Contadores.codigo_fisico', $q)
                ->orLike('Tb_Contadores.direccion_servicio', $q)
                ->orLike('Tb_Clientes.nombre', $q)
                ->orLike('Tb_Clientes.dpi', $q)
                ->groupEnd();
        }

        if ($sectorId) {
            $this->where('Tb_Contadores.sector_id', $sectorId);
        }

        return $this->orderBy('Tb_Contadores.id', 'DESC')->findAll();
    }

    /**
     * Todos los contadores de un cliente (para la vista de detalle).
     */
    public function deCliente(int $clienteId): array
    {
        return $this->select('Tb_Contadores.*, Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Sectores.nombre as sector_nombre')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
            ->where('Tb_Contadores.cliente_id', $clienteId)
            ->orderBy('Tb_Contadores.id', 'DESC')
            ->findAll();
    }
}
