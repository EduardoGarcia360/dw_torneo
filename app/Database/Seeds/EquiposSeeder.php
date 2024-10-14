<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EquiposSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre_equipo' => 'Real Madrid'
            ],
            [
                'nombre_equipo' => 'Barcelona'
            ],
            [
                'nombre_equipo' => 'Bayern'
            ]
        ];

        $this->db->table('equipos')->insertBatch($data);
    }
}
