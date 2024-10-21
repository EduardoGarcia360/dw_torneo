<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre','apellido','correoElectronico','telefono','contrasena'];

    // Dates
    protected $useTimestamps = false;

    public function verificarUsuario($correoElectronico, $contrasena)
    {
        return $this->select('usuarios.*')
                    ->where('usuarios.correoElectronico', $correoElectronico)
                    ->where('usuarios.contrasena', $contrasena)
                    ->first();
    }

}
