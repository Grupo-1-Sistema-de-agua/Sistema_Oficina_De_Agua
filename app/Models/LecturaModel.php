<?php

namespace App\Models;

use CodeIgniter\Model;

class LecturaModel extends Model
{
    /**
     * Modelo de lecturas de contadores.
     *
     * CAMBIOS RECIENTES:
     * - Se agrego 'monto_base' y 'monto_exceso' a $allowedFields. Sin esto,
     *   CodeIgniter descartaba silenciosamente esos campos al guardar, por lo
     *   que el monto calculado nunca se almacenaba (quedaba en 0.00).
     * - Se agregaron metodos para controlar la lectura por mes de calendario
     *   y la edicion de la lectura vigente (existeEnMes, lecturaDelMesActual,
     *   esUltimaDeContador, tienePago).
     */
    protected $table            = 'Tb_Lecturas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'numero_recibo', 'lectura_anterior', 'lectura_actual', 'consumo_litros',
        'fecha', 'contador_id', 'tarifa_base_id', 'tarifa_exceso_id', 'usuario_lector_id',
        'monto_base', 'monto_exceso',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'numero_recibo'      => 'required|max_length[20]|is_unique[Tb_Lecturas.numero_recibo,id,{id}]',
        'lectura_actual'     => 'required|integer',
        'fecha'              => 'required|valid_date',
        'contador_id'        => 'required|integer|is_not_unique[Tb_Contadores.id]',
        'tarifa_base_id'     => 'required|integer|is_not_unique[Tb_Tarifas.id]',
        'tarifa_exceso_id'   => 'permit_empty|integer|is_not_unique[Tb_Tarifas.id]',
        'usuario_lector_id'  => 'required|integer|is_not_unique[Tb_Usuarios.id]',
    ];

    /**
     * Lecturas que todavia no tienen un pago asociado (para el dashboard
     * de estado de cuenta y para el modulo de pagos).
     */
    public function pendientesDePago(): array
    {
        return $this->select('Tb_Lecturas.*')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->where('Tb_Pagos.id', null)
            ->findAll();
    }

    public function ultimaDeContador(int $contadorId): ?array
    {
    return $this->where('contador_id', $contadorId)
        ->orderBy('fecha', 'DESC')
        ->orderBy('id', 'DESC')
        ->first();
    }

    /**
     * Verifica si ya existe una lectura para un contador en el mismo mes
     * calendario de la fecha dada. Permite excluir una lectura especifica
     * (util para no bloquear la edicion de la lectura vigente).
     */
    public function existeEnMes(int $contadorId, string $fecha, ?int $exceptoId = null): bool
    {
        $inicioMes = date('Y-m-01 00:00:00', strtotime($fecha));
        $inicioProximoMes = date('Y-m-01 00:00:00', strtotime('+1 month', strtotime($fecha)));

        $this->where('contador_id', $contadorId)
            ->where('fecha >=', $inicioMes)
            ->where('fecha <', $inicioProximoMes);

        if ($exceptoId !== null) {
            $this->where('id !=', $exceptoId);
        }

        return $this->countAllResults() > 0;
    }

    /**
     * Mapa contador_id => lectura registrada en el mes actual (si existe).
     */
    public function lecturaDelMesActual(array $contadorIds): array
    {
        if (empty($contadorIds)) {
            return [];
        }

        $inicioMes = date('Y-m-01 00:00:00');
        $inicioProximoMes = date('Y-m-01 00:00:00', strtotime('+1 month'));

        $rows = $this->select('contador_id, id')
            ->whereIn('contador_id', $contadorIds)
            ->where('fecha >=', $inicioMes)
            ->where('fecha <', $inicioProximoMes)
            ->findAll();

        $mapa = [];
        foreach ($rows as $fila) {
            $mapa[(int) $fila['contador_id']] = (int) $fila['id'];
        }
        return $mapa;
    }

    /**
     * True si la lectura es la mas reciente de su contador.
     */
    public function esUltimaDeContador(int $lecturaId, int $contadorId): bool
    {
        $ultima = $this->ultimaDeContador($contadorId);

        return $ultima !== null && (int) $ultima['id'] === $lecturaId;
    }

    /**
     * True si una lectura ya tiene un pago registrado.
     */
    public function tienePago(int $lecturaId): bool
    {
        return $this->db->table('Tb_Pagos')
            ->where('lectura_id', $lecturaId)
            ->countAllResults() > 0;
    }

     /**
     * Historial completo de lecturas de un contador (para el detalle).
     */
    public function historialDeContador(int $contadorId): array
    {
        return $this->where('contador_id', $contadorId)
            ->orderBy('fecha', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Mapa contador_id => cantidad de lecturas sin pago (para el badge
     * "Al dia / Pendiente" en el listado de contadores).
     */
    public function pendientesPorContador(): array
    {
        $rows = $this->select('Tb_Lecturas.contador_id, COUNT(*) as total')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->where('Tb_Pagos.id', null)
            ->groupBy('Tb_Lecturas.contador_id')
            ->findAll();

        $map = [];
        foreach ($rows as $r) {
            $map[$r['contador_id']] = (int) $r['total'];
        }
        return $map;
    }

}
