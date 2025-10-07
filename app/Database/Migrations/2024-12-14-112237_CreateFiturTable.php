<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFiturTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_fitur' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'user',
            ],
            'nama_fitur' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_fitur_en' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('id_fitur', true);
        $this->forge->createTable('fitur');
    }

    public function down()
    {
        $this->forge->dropTable('fitur');
    }
}
