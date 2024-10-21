<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T6Usuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'apellido' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'correoElectronico' => [
                'type' => 'VARCHAR',
                'constraint' => '75',
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'contrasena' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
