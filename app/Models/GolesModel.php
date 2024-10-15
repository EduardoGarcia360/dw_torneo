<?php

namespace App\Models;

use CodeIgniter\Model;

class GolesModel extends Model
{
    protected $table            = 'goles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['jugador_id', 'jornada_id', 'cantidad_goles'];

    // Dates
    protected $useTimestamps = false;

    public function golesJugadorJornada()
    {
        return $this->select('goles.*, jugadores.nombres, jugadores.apellidos, jornadas.numero_jornada')
            ->join('jugadores', 'goles.jugador_id = jugadores.id')
            ->join('jornadas', 'goles.jornada_id = jornadas.id')
            ->findAll();
    }

}
