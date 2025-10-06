<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriInduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kategori_induk' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kategori_induk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'nama_kategori_induk_en' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
        ]);

        $this->forge->addKey('id_kategori_induk', true);
        $this->forge->createTable('kategori_induk');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_induk');
    }
}
