<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T1Equipos extends Migration
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
            'nombre_equipo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Create table
        $this->forge->createTable('equipos');
    }

    public function down()
    {
        $this->forge->dropTable('equipos');
    }
}
