<?php

namespace App\Models;

use CodeIgniter\Model;

class JornadasModel extends Model
{
    protected $table            = 'jornadas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['numero_jornada', 'fecha_juego'];

    // Dates
    protected $useTimestamps = false;

    public function jornadaVigente()
    {
        $hoy = date('Y-m-d');
        
        return $this->select('jornadas.*')
            ->where('fecha_juego <=', $hoy)
            ->orderBy('fecha_juego', 'DESC')
            ->first();
    }

}
