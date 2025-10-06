<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FiturSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_fitur' => 1,
                'role' => 'visitor',
                'nama_fitur' => 'Beranda',
                'nama_fitur_en' => 'Home',
            ],
            [
                'id_fitur' => 2,
                'role' => 'visitor',
                'nama_fitur' => 'Tentang Kami',
                'nama_fitur_en' => 'About Us',
            ],
            [
                'id_fitur' => 3,
                'role' => 'visitor',
                'nama_fitur' => 'Materi Ekspor',
                'nama_fitur_en' => 'Export Lesson',
            ],
            [
                'id_fitur' => 4,
                'role' => 'visitor',
                'nama_fitur' => 'Video Tutorial',
                'nama_fitur_en' => 'Video Tutorials',
            ],
            [
                'id_fitur' => 5,
                'role' => 'visitor',
                'nama_fitur' => 'Data Member',
                'nama_fitur_en' => 'Members',
            ],
            [
                'id_fitur' => 6,
                'role' => 'visitor',
                'nama_fitur' => 'Pendaftaran',
                'nama_fitur_en' => 'Registration',
            ],
            [
                'id_fitur' => 7,
                'role' => 'member',
                'nama_fitur' => 'Beranda',
                'nama_fitur_en' => 'Home',
            ],
            [
                'id_fitur' => 8,
                'role' => 'member',
                'nama_fitur' => 'Tentang Kami',
                'nama_fitur_en' => 'About Us',
            ],
            [
                'id_fitur' => 9,
                'role' => 'member',
                'nama_fitur' => 'Materi Ekspor',
                'nama_fitur_en' => 'Export Lesson',
            ],
            [
                'id_fitur' => 10,
                'role' => 'member',
                'nama_fitur' => 'Video Tutorial',
                'nama_fitur_en' => 'Video Tutorials',
            ],
            [
                'id_fitur' => 11,
                'role' => 'member',
                'nama_fitur' => 'Data Member',
                'nama_fitur_en' => 'Members',
            ],
            [
                'id_fitur' => 12,
                'role' => 'member',
                'nama_fitur' => 'Data Buyers',
                'nama_fitur_en' => 'Buyers Data',
            ],
            [
                'id_fitur' => 13,
                'role' => 'member',
                'nama_fitur' => 'Aplikasi Kalkulator Harga Ekspor',
                'nama_fitur_en' => 'Application Export Price Calculator',
            ],
            [
                'id_fitur' => 14,
                'role' => 'member',
                'nama_fitur' => 'Aplikasi Marketing Progress Monitoring',
                'nama_fitur_en' => 'Application Marketing Progress Monitoring',
            ],
            [
                'id_fitur' => 15,
                'role' => 'member',
                'nama_fitur' => 'Pengumuman',
                'nama_fitur_en' => 'Announcement',
            ],
            [
                'id_fitur' => 16,
                'role' => 'member',
                'nama_fitur' => 'Edit Profile',
                'nama_fitur_en' => 'Edit Profile',
            ],
        ];
        $this->db->table('fitur')->insertBatch($data);
    }
}
