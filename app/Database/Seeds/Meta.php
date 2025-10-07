<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Meta extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_meta' => 1,
                'meta_title_beranda' => 'Komunitas Ekspor Indonesia - Jembatan Menuju Pasar Global',
                'meta_title_beranda_en' => 'Komunitas Ekspor Indonesia - Bridging the Global Market',
                'meta_description_beranda' => 'Komunitas Ekspor Indonesia (KEI) adalah platform bagi para eksportir Indonesia untuk terhubung, berbagi informasi, dan memperluas jaringan bisnis mereka ke pasar internasional. Bergabunglah dan wujudkan potensi ekspor Anda!',
                'meta_description_beranda_en' => 'The Komunitas Ekspor Indonesia (KEI) is a platform for Indonesian exporters to connect, share insights, and expand their business networks globally. Join us and unlock your export potential!',

                'meta_title_tentang' => 'Tentang Kami - Komunitas Ekspor Indonesia',
                'meta_title_tentang_en' => 'About Us - Komunitas Ekspor Indonesia',
                'meta_description_tentang' => 'Pelajari lebih lanjut tentang Komunitas Ekspor Indonesia (KEI), misi kami, dan bagaimana kami membantu eksportir Indonesia mencapai pasar internasional.',
                'meta_description_tentang_en' => 'Learn more about the Komunitas Ekspor Indonesia (KEI), our mission, and how we support Indonesian exporters in reaching international markets.',

                'meta_title_materi' => 'Materi Ekspor - Komunitas Ekspor Indonesia',
                'meta_title_materi_en' => 'Export Lessons - Komunitas Ekspor Indonesia',
                'meta_description_materi' => 'Temukan berbagai materi edukasi terkait ekspor untuk membantu bisnis Anda menembus pasar global.',
                'meta_description_materi_en' => 'Discover various educational lessons related to export to help your business reach global markets.',

                'meta_title_tutorial' => 'Video Tutorial Ekspor - Komunitas Ekspor Indonesia',
                'meta_title_tutorial_en' => 'Export Tutorial Videos - Komunitas Ekspor Indonesia',
                'meta_description_tutorial' => 'Tonton video tutorial langkah-langkah ekspor dan pelajari strategi sukses dari para ahli.',
                'meta_description_tutorial_en' => 'Watch step-by-step export tutorial videos and learn success strategies from experts.',

                'meta_title_member' => 'Data Member - Komunitas Ekspor Indonesia',
                'meta_title_member_en' => 'Members - Komunitas Ekspor Indonesia',
                'meta_description_member' => 'Akses data member Komunitas Ekspor Indonesia dan temukan peluang untuk berkolaborasi.',
                'meta_description_member_en' => 'Access the Komunitas Ekspor Indonesia member data and find opportunities to collaborate.',

                'meta_title_daftar' => 'Daftar Member - Komunitas Ekspor Indonesia',
                'meta_title_daftar_en' => 'Member Registration - Komunitas Ekspor Indonesia',
                'meta_description_daftar' => 'Bergabunglah dengan Komunitas Ekspor Indonesia dan perluas jaringan bisnis Anda ke pasar global.',
                'meta_description_daftar_en' => 'Join the Komunitas Ekspor Indonesia and expand your business network to global markets.'
            ],
        ];
        $this->db->table('meta')->insertBatch($data);
    }
}
