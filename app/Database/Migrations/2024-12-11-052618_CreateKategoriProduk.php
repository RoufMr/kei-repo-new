<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriProduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kategori_produk' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_kategori_induk' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'nama_kategori_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'nama_kategori_produk_en' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
        ]);

        $this->forge->addKey('id_kategori_produk', true);
        $this->forge->addForeignKey('id_kategori_induk', 'kategori_induk', 'id_kategori_induk', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kategori_produk');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_produk');
    }
}
