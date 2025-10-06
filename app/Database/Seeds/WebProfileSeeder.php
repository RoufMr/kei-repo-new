<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebProfileSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_webprofile' => 1,
                'nama_web' => 'Komunitas Ekspor Indonesia',
                'nama_web_en' => 'Indonesian Export Community',
                'deskripsi_web' => 'Kami adalah perusahaan yang berkomitmen untuk memberikan layanan terbaik kepada pelanggan kami. Dengan pengalaman bertahun-tahun, kami selalu berupaya menciptakan inovasi baru untuk memenuhi kebutuhan Anda. Visi kami adalah menjadi yang terdepan dalam industri ini, sementara misi kami adalah memberikan solusi terbaik, menjaga kepuasan pelanggan, dan berkontribusi pada komunitas.',
                'deskripsi_web_en' => 'We are a company committed to providing the best service to our customers. With years of experience, we always strive to create new innovations to meet your needs. Our vision is to be at the forefront of the industry, while our mission is to provide the best solutions, maintain customer satisfaction, and contribute to the community.',
                'logo_web' => 'logokei.png',
                'nohp_web' => '+6281229957212',
                'email_web' => 'komunitaseksporindonesia@gmail.com',
                'link_ig_web' => 'https://www.instagram.com/komunitaseksporindonesia',
                'link_yt_web' => 'https://www.youtube.com/komunitaseksporindonesia',
                'link_fb_web' => 'https://www.facebook.com/komunitaseksporindonesia',
                'judul_ajakan' => 'Bergabunglah dengan Komunitas Eksportir!',
                'judul_ajakan_en' => 'Join the Exporter Community!',
                'deskripsi_ajakan' => 'Jadilah bagian dari jaringan eksportir yang sukses. Dapatkan peluang, pengetahuan, dan dukungan untuk mengembangkan bisnis ekspor Anda.',
                'deskripsi_ajakan_en' => 'Be part of a successful exporter network. Gain opportunities, knowledge, and support to grow your export business.',
                'footer_text' => '&copy; 2024 Komunitas Ekspor Indonesia 24. All rights reserved.'
            ]
        ];

        $this->db->table('webprofile')->insertBatch($data);
    }
}
