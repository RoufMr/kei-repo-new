<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMetaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_meta' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'meta_title_beranda' => [
                'type' => 'TEXT',
            ],
            'meta_title_beranda_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_beranda' => [
                'type' => 'TEXT',
            ],
            'meta_description_beranda_en' => [
                'type' => 'TEXT',
            ],
            'meta_title_tentang' => [
                'type' => 'TEXT',
            ],
            'meta_title_tentang_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_tentang' => [
                'type' => 'TEXT',
            ],
            'meta_description_tentang_en' => [
                'type' => 'TEXT',
            ],
            'meta_title_materi' => [
                'type' => 'TEXT',
            ],
            'meta_title_materi_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_materi' => [
                'type' => 'TEXT',
            ],
            'meta_description_materi_en' => [
                'type' => 'TEXT',
            ],
            'meta_title_tutorial' => [
                'type' => 'TEXT',
            ],
            'meta_title_tutorial_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_tutorial' => [
                'type' => 'TEXT',
            ],
            'meta_description_tutorial_en' => [
                'type' => 'TEXT',
            ],
            'meta_title_member' => [
                'type' => 'TEXT',
            ],
            'meta_title_member_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_member' => [
                'type' => 'TEXT',
            ],
            'meta_description_member_en' => [
                'type' => 'TEXT',
            ],
            'meta_title_daftar' => [
                'type' => 'TEXT',
            ],
            'meta_title_daftar_en' => [
                'type' => 'TEXT',
            ],
            'meta_description_daftar' => [
                'type' => 'TEXT',
            ],
            'meta_description_daftar_en' => [
                'type' => 'TEXT',
            ],
        ]);

        $this->forge->addKey('id_meta', true);
        $this->forge->createTable('meta');
    }

    public function down()
    {
        $this->forge->dropTable('meta');
    }
}
