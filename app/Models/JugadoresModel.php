<?php

namespace App\Models;

use CodeIgniter\Model;

class JugadoresModel extends Model
{
    protected $table            = 'jugadores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombres', 'apellidos', 'fecha_nacimiento', 'fotografia', 'equipo_id'];

    // Dates
    protected $useTimestamps = false;

    public function jugadoresEquipos()
    {
        return $this->select('jugadores.*, equipos.nombre_equipo AS equipo_nombre')
                    ->join('equipos', 'jugadores.equipo_id = equipos.id')
                    ->findAll();
    }


}
