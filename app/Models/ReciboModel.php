<?php

namespace App\Models;

use CodeIgniter\Model;

class ReciboModel extends Model
{
    protected $table            = 'Tb_Recibos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // ¡Aquí activamos el borrado lógico recomendado!
    protected $useSoftDeletes   = true; 
    
    protected $allowedFields    = [
        'numero_recibo', 
        'id_cliente', 
        'nombre_cliente', 
        'direccion', 
        'numero_contador', 
        'monto_total', 
        'fecha_emision'
    ];

    // Activar timestamps para que CI4 maneje created_at, updated_at y deleted_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Reglas de validación para proteger el controlador
    protected $validationRules = [
        'numero_recibo'  => 'required|max_length[50]|is_unique[Tb_Recibos.numero_recibo,id,{id}]',
        'nombre_cliente' => 'required|max_length[150]',
        'direccion'      => 'required|max_length[255]',
        'monto_total'    => 'required|numeric'
    ];
}