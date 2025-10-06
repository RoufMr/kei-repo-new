<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriProduk extends Seeder
{
    public function run()
    {
        $nama_kategori_produk_en_1 = [
            'Agricultural Growing Media',
            'Agricultural Waste',
            'Agriculture & By-product Agents',
            'Agriculture Equipment',
            'Agriculture Products Processing',
            'Agriculture Projects',
            'Agrochemia',
            'Animal Feed',
            'Animal Products',
            'Beans',
            'Charcoal',
            'Cigarettes',
            'Cocoa Beans',
            'Coffee Beans',
            'Farm Machinery & Equipment',
            'Feed',
            'Flower Pots & Planters',
            'Fresh Seafood',
            'Fruits',
            'Garden Tools',
            'Grain',
            'Grain Products',
            'Herbal Cigars & Cigarettes',
            'Mushrooms & Truffles',
            'Nuts & Kernels',
            'Organic Produce',
            'Ornamental Plants',
            'Other Agriculture Products',
            'Plant & Animal Oil',
            'Plant Seeds & Bulbs',
            'Timber Raw Materials',
            'Vanilla Beans',
            'Vegetables',
        ];

        $nama_kategori_produk_id_1 = [
            'Media Tanam Pertanian',
            'Limbah Pertanian',
            'Agen Pertanian & Produk Sampingan',
            'Peralatan Pertanian',
            'Pengolahan Produk Pertanian',
            'Proyek Pertanian',
            'Kimia Pertanian',
            'Pakan Ternak',
            'Produk Hewan',
            'Kacang-kacangan',
            'Arang',
            'Rokok',
            'Biji Kakao',
            'Biji Kopi',
            'Mesin & Peralatan Pertanian',
            'Pakan',
            'Pot & Wadah Tanaman',
            'Makanan Laut Segar',
            'Buah-buahan',
            'Peralatan Taman',
            'Biji-bijian',
            'Produk Biji-bijian',
            'Cerutu & Rokok Herbal',
            'Jamur & Truffle',
            'Kacang & Biji-bijian',
            'Produk Organik',
            'Tanaman Hias',
            'Produk Pertanian Lainnya',
            'Minyak Tumbuhan & Hewan',
            'Benih & Bibit Tanaman',
            'Bahan Baku Kayu',
            'Biji Vanili',
            'Sayuran',
        ];

        $nama_kategori_produk_en_2 = [
            'Apparel Agents',
            'Apparel Projects',
            'Apparel Stock',
            'Boy\'s Clothing',
            'Children\'s Clothing',
            'Coats',
            'Costumes',
            'Dresses',
            'Ethnic Clothing',
            'Garment Accessories',
            'Girls Clothing',
            'Hoodies & Sweatshirts',
            'Hosiery',
            'Infant & Toddlers Clothing',
            'Jackets',
            'Jeans',
            'Ladies Blouses & Tops',
            'Men\'s Clothing',
            'Men\'s Shirts',
            'Organic Cotton Clothing',
            'Other Apparel',
            'Pants & Trousers',
            'Sewing Supplies',
            'Shorts',
            'Skirts',
            'Sleepwear',
            'Sportswear',
            'T-shirts',
            'Underwear',
            'Uniforms',
            'Wedding Apparel & Accessories',
            'Women\'s Clothing',
            'Workwear',
        ];

        $nama_kategori_produk_id_2 = [
            'Agen Pakaian',
            'Proyek Pakaian',
            'Stok Pakaian',
            'Pakaian Anak Laki-laki',
            'Pakaian Anak-anak',
            'Mantel',
            'Kostum',
            'Gaun',
            'Pakaian Adat',
            'Aksesori Pakaian',
            'Pakaian Anak Perempuan',
            'Hoodie & Sweatshirt',
            'Kaos Kaki',
            'Pakaian Bayi & Balita',
            'Jaket',
            'Celana Jeans',
            'Blus & Atasan Wanita',
            'Pakaian Pria',
            'Kemeja Pria',
            'Pakaian Katun Organik',
            'Pakaian Lainnya',
            'Celana Panjang & Celana',
            'Perlengkapan Menjahit',
            'Celana Pendek',
            'Rok',
            'Pakaian Tidur',
            'Pakaian Olahraga',
            'Kaos',
            'Pakaian Dalam',
            'Seragam',
            'Pakaian & Aksesori Pernikahan',
            'Pakaian Wanita',
            'Pakaian Kerja',
        ];

        $nama_kategori_produk_en_3 = [
            'Auto Electrical System',
            'Auto Engine',
            'Automotive & Motorcycle Parts Agent',
            'Brake System',
            'Car Care & Cleaning',
            'Exhaust System',
            'Exterior Accessories',
            'Fuel System',
            'Interior Accessories',
            'Lubrication System',
            'Motorcycle Accessories',
            'Motorcycle Parts',
            'Motorcycles',
            'Other Auto Parts',
            'Suspension System',
            'Tricycles',
            'Universal Parts',
            'Vehicle Equipment',
        ];

        $nama_kategori_produk_id_3 = [
            'Sistem Kelistrikan Otomotif',
            'Mesin Otomotif',
            'Agen Suku Cadang Otomotif & Sepeda Motor',
            'Sistem Rem',
            'Perawatan & Pembersih Mobil',
            'Sistem Knalpot',
            'Aksesori Eksterior',
            'Sistem Bahan Bakar',
            'Aksesori Interior',
            'Sistem Pelumasan',
            'Aksesori Sepeda Motor',
            'Suku Cadang Sepeda Motor',
            'Sepeda Motor',
            'Suku Cadang Lainnya',
            'Sistem Suspensi',
            'Triklis',
            'Suku Cadang Universal',
            'Peralatan Kendaraan',
        ];

        $nama_kategori_produk_en_4 = [
            'Baby Care',
            'Bath Supplies',
            'Beauty Equipment',
            'Breast Care',
            'Feminine Hygiene',
            'Fragrance & Deodorant',
            'Hair Care',
            'Hair Extensions & Wigs',
            'Makeup',
            'Makeup Tools',
            'Men Care',
            'Nail Supplies',
            'Oral Hygiene',
            'Other Beauty & Personal Care Products',
            'Sanitary Paper',
            'Shaving & Hair Removal',
            'Skin Care',
            'Spa Supplies',
            'Weight Loss',
        ];

        $nama_kategori_produk_id_4 = [
            'Perawatan Bayi',
            'Perlengkapan Mandi',
            'Peralatan Kecantikan',
            'Perawatan Payudara',
            'Kebersihan Wanita',
            'Parfum & Deodoran',
            'Perawatan Rambut',
            'Rambut Sambung & Wig',
            'Rias Wajah',
            'Alat Rias',
            'Perawatan Pria',
            'Perlengkapan Kuku',
            'Kesehatan Mulut',
            'Produk Kecantikan & Perawatan Pribadi Lainnya',
            'Kertas Saniter',
            'Cukur & Penghilang Rambut',
            'Perawatan Kulit',
            'Perlengkapan Spa',
            'Penurunan Berat Badan',
        ];

        $nama_kategori_produk_en_5 = [
            'Agrochemicals',
            'Basic Chemicals',
            'Fine Chemicals',
            'Lab Supplies',
            'Other Products & Related Services',
            'Polymer',
        ];

        $nama_kategori_produk_id_5 = [
            'Agrokimia',
            'Kimia Dasar',
            'Kimia Halus',
            'Perlengkapan Laboratorium',
            'Produk Lainnya & Layanan Terkait',
            'Polimer',
        ];

        $nama_kategori_produk_en_6 = [
            'All-In-One PC',
            'Keyboard Covers',
            'Laptop Accessories',
            'Network Cabinets',
            'Network Switches',
        ];

        $nama_kategori_produk_id_6 = [
            'PC All-in-One',
            'Pelindung Keyboard',
            'Aksesori Laptop',
            'Lemari Jaringan',
            'Switch Jaringan',
        ];

        $nama_kategori_produk_en_7 = [
            'Bathroom & Kitchen',
            'Construction Tools & Machinery',
            'Doors, Window & Sunrooms',
            'Finishing Building Material',
            'Real Estate',
            'Related Services & Other Products',
            'Stone',
            'Structural Building Materials',
            'Tiles & Flooring',
            'Timber',
            'Wallpapers/Wall Coating',
        ];

        $nama_kategori_produk_id_7 = [
            'Kamar Mandi & Dapur',
            'Alat & Mesin Konstruksi',
            'Pintu, Jendela & Ruang Musim Panas',
            'Material Finishing Bangunan',
            'Properti Real Estat',
            'Layanan Terkait & Produk Lainnya',
            'Batu',
            'Material Struktur Bangunan',
            'Keramik & Lantai',
            'Kayu Bahan Mentah',
            'Wallpaper & Pelindung Dinding',
        ];

        $nama_kategori_produk_en_8 = [
            'Accessories & Parts',
            'Home Audio, Video & Accessories',
            'Other Consumer Electronics',
            'Portable Audio, Video & Accessories',
        ];

        $nama_kategori_produk_id_8 = [
            'Aksesori & Suku Cadang',
            'Audio, Video & Aksesori Rumah',
            'Elektronik Konsumen Lainnya',
            'Audio, Video & Aksesori Portabel',
        ];

        $nama_kategori_produk_en_9 = [
            'Batteries',
            'Electrical Plugs & Sockets',
            'Electrical Products Agents',
            'Electronic & Instrument Enclosures',
            'Power Distribution Equipment',
            'Power Supplies',
            'Switches',
            'Wires, Cables & Cable Assemblies',
        ];

        $nama_kategori_produk_id_9 = [
            'Baterai',
            'Colokan & Stop Kontak Listrik',
            'Agen Produk Listrik',
            'Box Elektronik & Instrument',
            'Peralatan Distribusi Daya',
            'Sumber Daya Listrik',
            'Saklar',
            'Kabel, Jalur Kabel & Perakitan Kabel',
        ];

        $nama_kategori_produk_en_10 = [
            'Electronic Products & Components Processing',
            'El Products',
            'Passive Components',
        ];

        $nama_kategori_produk_id_10 = [
            'Pengolahan Produk & Komponen Elektronik',
            'Produk El',
            'Komponen Pasif',
        ];

        $db = \Config\Database::connect();

        foreach ($nama_kategori_produk_en_1 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 1 + $index,
                'id_kategori_induk'    => 1,
                'nama_kategori_produk' => $nama_kategori_produk_id_1[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_2 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 34 + $index,
                'id_kategori_induk'    => 2,
                'nama_kategori_produk' => $nama_kategori_produk_id_2[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_3 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 67 + $index,
                'id_kategori_induk'    => 3,
                'nama_kategori_produk' => $nama_kategori_produk_id_3[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_4 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 85 + $index,
                'id_kategori_induk'    => 4,
                'nama_kategori_produk' => $nama_kategori_produk_id_4[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_5 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 104 + $index,
                'id_kategori_induk'    => 5,
                'nama_kategori_produk' => $nama_kategori_produk_id_5[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_6 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 110 + $index,
                'id_kategori_induk'    => 6,
                'nama_kategori_produk' => $nama_kategori_produk_id_6[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_7 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 115 + $index,
                'id_kategori_induk'    => 7,
                'nama_kategori_produk' => $nama_kategori_produk_id_7[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_8 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 126 + $index,
                'id_kategori_induk'    => 8,
                'nama_kategori_produk' => $nama_kategori_produk_id_8[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_9 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 130 + $index,
                'id_kategori_induk'    => 9,
                'nama_kategori_produk' => $nama_kategori_produk_id_9[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }

        foreach ($nama_kategori_produk_en_10 as $index => $nama) {
            $data = [
                'id_kategori_produk'   => 138 + $index,
                'id_kategori_induk'    => 10,
                'nama_kategori_produk' => $nama_kategori_produk_id_10[$index],
                'nama_kategori_produk_en' => $nama,
            ];

            $db->table('kategori_produk')->insert($data);
        }
    }
}
