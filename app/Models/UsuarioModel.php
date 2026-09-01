<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'Tb_Usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre', 'email', 'password_hash', 'activo', 'rol_id'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'email'  => 'required|valid_email|max_length[150]|is_unique[Tb_Usuarios.email,id,{id}]',
        'rol_id' => 'required|integer',
    ];

    /**
     * Trae el usuario junto con el nombre de su rol (join con Tb_Roles).
     */
    public function conRol(int $id): ?array
    {
        return $this->select('Tb_Usuarios.*, Tb_Roles.nombre as rol_nombre')
            ->join('Tb_Roles', 'Tb_Roles.id = Tb_Usuarios.rol_id')
            ->find($id);
    }
}
