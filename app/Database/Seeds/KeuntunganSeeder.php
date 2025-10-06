<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KeuntunganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_keuntungan' => 1,
                'judul_keuntungan' => 'Akses Pelatihan Ekspor Eksklusif',
                'judul_keuntungan_en' => 'Exclusive Export Training Access',
                'deskripsi_keuntungan' => 'Sebagai member, Anda akan mendapatkan akses ke berbagai pelatihan eksklusif mengenai ekspor dan perdagangan internasional.',
                'deskripsi_keuntungan_en' => 'As a member, you will have access to various exclusive training on export and international trade.',
            ],
            [
                'id_keuntungan' => 2,
                'judul_keuntungan' => 'Jaringan Luas dengan Pelaku Usaha',
                'judul_keuntungan_en' => 'Extensive Network with Business Actors',
                'deskripsi_keuntungan' => 'Anda akan memiliki kesempatan untuk berjejaring dengan pelaku usaha lainnya di bidang ekspor.',
                'deskripsi_keuntungan_en' => 'You will have the opportunity to network with other business actors in the export sector.',
            ],
            [
                'id_keuntungan' => 3,
                'judul_keuntungan' => 'Informasi Terbaru tentang Perdagangan Internasional',
                'judul_keuntungan_en' => 'Latest Information on International Trade',
                'deskripsi_keuntungan' => 'Tetap up-to-date dengan berita dan tren terbaru mengenai pasar internasional.',
                'deskripsi_keuntungan_en' => 'Stay up-to-date with the latest news and trends on international markets.',
            ],
            [
                'id_keuntungan' => 4,
                'judul_keuntungan' => 'Akses ke Acara dan Webinar Khusus',
                'judul_keuntungan_en' => 'Access to Exclusive Events and Webinars',
                'deskripsi_keuntungan' => 'Member komunitas akan mendapatkan undangan eksklusif ke berbagai acara, seminar, dan webinar.',
                'deskripsi_keuntungan_en' => 'Community members will receive exclusive invitations to various events, seminars, and webinars.',
            ],
            [
                'id_keuntungan' => 5,
                'judul_keuntungan' => 'Peluang Promosi dan Eksposur Bisnis',
                'judul_keuntungan_en' => 'Business Promotion and Exposure Opportunities',
                'deskripsi_keuntungan' => 'Komunitas akan memberikan kesempatan kepada member untuk mempromosikan produk dan layanan mereka.',
                'deskripsi_keuntungan_en' => 'The community provides members with opportunities to promote their products and services.',
            ],
            [
                'id_keuntungan' => 6,
                'judul_keuntungan' => 'Panduan Mendapatkan Sertifikasi Ekspor',
                'judul_keuntungan_en' => 'Export Certification Guidance',
                'deskripsi_keuntungan' => 'Anda akan mendapatkan panduan lengkap tentang bagaimana mendapatkan berbagai sertifikasi ekspor yang diperlukan.',
                'deskripsi_keuntungan_en' => 'You will receive complete guidance on how to obtain various export certifications required.',
            ],
            [
                'id_keuntungan' => 7,
                'judul_keuntungan' => 'Layanan Konsultasi Ekspor',
                'judul_keuntungan_en' => 'Export Consultation Services',
                'deskripsi_keuntungan' => 'Sebagai member, Anda akan mendapatkan akses ke layanan konsultasi dengan para pakar ekspor.',
                'deskripsi_keuntungan_en' => 'As a member, you will have access to consultation services with export experts.',
            ],
        ];

        $this->db->table('keuntungan')->insertBatch($data);
    }
}
