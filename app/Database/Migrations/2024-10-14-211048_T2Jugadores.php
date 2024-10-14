<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T2Jugadores extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nombres' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'apellidos' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE'
            ],
            'fotografia' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'equipo_id' => [
                'type' => 'INT',
                'unsigned' => true
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign key
        $this->forge->addForeignKey('equipo_id', 'equipos', 'id');

        // Create table
        $this->forge->createTable('jugadores');
    }

    public function down()
    {
        $this->forge->dropTable('jugadores');
    }
}
