<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeuntunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_keuntungan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul_keuntungan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'judul_keuntungan_en' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'deskripsi_keuntungan' => [
                'type'       => 'TEXT',
            ],
            'deskripsi_keuntungan_en' => [
                'type'       => 'TEXT',
            ],
        ]);

        $this->forge->addKey('id_keuntungan', true);
        $this->forge->createTable('keuntungan');
    }

    public function down()
    {
        $this->forge->dropTable('keuntungan');
    }
}
