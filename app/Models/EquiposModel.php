<?php

namespace App\Models;

use CodeIgniter\Model;

class EquiposModel extends Model
{
    protected $table            = 'equipos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre_equipo'];

    // Dates
    protected $useTimestamps = false;

}
