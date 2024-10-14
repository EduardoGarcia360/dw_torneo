<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T4Goles extends Migration
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
            'jugador_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'jornada_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'cantidad_goles' => [
                'type' => 'INT',
                'constraint' => 5
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign keys
        $this->forge->addForeignKey('jugador_id', 'jugadores', 'id');
        $this->forge->addForeignKey('jornada_id', 'jornadas', 'id');

        // Create table
        $this->forge->createTable('goles');
    }

    public function down()
    {
        $this->forge->dropTable('goles');
    }
}
