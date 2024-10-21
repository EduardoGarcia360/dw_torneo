<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre' => 'admin',
                'apellido' => 'admin',
                'correoElectronico' => 'admin@mail.com',
                'telefono' => '51515151',
                'contrasena' => '1234'
            ]
        ];

        $this->db->table('usuarios')->insertBatch($data);
    }
}
