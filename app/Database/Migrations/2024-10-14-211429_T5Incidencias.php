<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class T5Incidencias extends Migration
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
            'descripcion' => [
                'type' => 'TEXT'
            ],
            'tipo_tarjeta' => [
                'type' => 'VARCHAR',
                'constraint' => 1, // Acepta solo "R" o "A"
            ],
            'fecha_incidencia' => [
                'type' => 'DATE'
            ],
            'fecha_suspension' => [
                'type' => 'DATE',
                'null' => true // Solo se llena si hay suspensión
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Foreign key
        $this->forge->addForeignKey('jugador_id', 'jugadores', 'id');

        // Create table
        $this->forge->createTable('incidencias');
    }

    public function down()
    {
        $this->forge->dropTable('incidencias');
    }
}
