<?php

namespace App\Models;

use CodeIgniter\Model;

class IncidenciasModel extends Model
{
    protected $table            = 'incidencias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['jugador_id', 'descripcion', 'tipo_tarjeta', 'fecha_incidencia', 'fecha_suspension'];

    // Dates
    protected $useTimestamps = false;

    public function incidenciasJugadores ()
    {
        return $this->select('incidencias.*, jugadores.nombres, jugadores.apellidos, jugadores.fotografia')
            ->join('jugadores', 'incidencias.jugador_id = jugadores.id')
            ->findAll();
    }

}
