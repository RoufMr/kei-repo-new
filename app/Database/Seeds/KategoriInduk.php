<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriInduk extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_kategori_induk' => 1,
                'nama_kategori_induk' => 'Pertanian',
                'nama_kategori_induk_en' => 'Agriculture',
            ],
            [
                'id_kategori_induk' => 2,
                'nama_kategori_induk' => 'Pakaian',
                'nama_kategori_induk_en' => 'Apparel',
            ],
            [
                'id_kategori_induk' => 3,
                'nama_kategori_induk' => 'Mobil & Sepeda Motor',
                'nama_kategori_induk_en' => 'Automobiles & Motorcycles',
            ],
            [
                'id_kategori_induk' => 4,
                'nama_kategori_induk' => 'Kecantikan & Perawatan Pribadi',
                'nama_kategori_induk_en' => 'Beauty & Personal Care',
            ],
            [
                'id_kategori_induk' => 5,
                'nama_kategori_induk' => 'Kimia',
                'nama_kategori_induk_en' => 'Chemicals',
            ],
            [
                'id_kategori_induk' => 6,
                'nama_kategori_induk' => 'Perangkat Keras & Perangkat Lunak Komputer',
                'nama_kategori_induk_en' => 'Computer Hardware & Software',
            ],
            [
                'id_kategori_induk' => 7,
                'nama_kategori_induk' => 'Konstruksi & Real Estat',
                'nama_kategori_induk_en' => 'Construction & Real Estate',
            ],
            [
                'id_kategori_induk' => 8,
                'nama_kategori_induk' => 'Elektronik Konsumen',
                'nama_kategori_induk_en' => 'Consumer Electronics',
            ],
            [
                'id_kategori_induk' => 9,
                'nama_kategori_induk' => 'Peralatan & Perlengkapan Listrik',
                'nama_kategori_induk_en' => 'Electrical Equipment & Supplies',
            ],
            [
                'id_kategori_induk' => 10,
                'nama_kategori_induk' => 'Komponen & Perlengkapan Elektronik',
                'nama_kategori_induk_en' => 'Electronic Components & Supplies',
            ],
        ];
        $this->db->table('kategori_induk')->insertBatch($data);
    }
}
