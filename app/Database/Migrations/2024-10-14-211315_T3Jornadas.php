<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T3Jornadas extends Migration
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
            'numero_jornada' => [
                'type' => 'INT',
                'constraint' => 5
            ],
            'fecha_juego' => [
                'type' => 'DATE'
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Create table
        $this->forge->createTable('jornadas');
    }

    public function down()
    {
        $this->forge->dropTable('jornadas');
    }
}
