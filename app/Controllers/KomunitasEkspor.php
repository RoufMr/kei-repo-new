<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BelajarEksporModel;
use App\Models\KategoriBelajarEksporModel;
use App\Models\Member;
use App\Models\Sertifikat;
use App\Models\Produk;
use App\Models\Buyers;
use App\Models\KategoriVideoModel;
use App\Models\VideoTutorialModel;
use App\Models\Exwork;
use App\Models\FOB;
use App\Models\CFR;
use App\Models\CIF;
use App\Models\Satuan;
use App\Models\MPM;
use App\Models\Planner;
use App\Models\WebsiteAudit;
use App\Models\Slider;
use App\Models\WebProfile;
use App\Models\ManfaatJoin;
use App\Models\Pengumuman;
use App\Models\TentangKami;
use App\Models\KategoriInduk;
use App\Models\KategoriProduk;
use App\Models\Meta;
use App\Models\Fitur;
use App\Models\Keuntungan;
use App\Models\KontenPilar;
use App\Models\KontenPlanner;
use App\Models\KontenPlatform;
use App\Models\KontenType;
use App\Models\KalkulatorState;
use App\Models\UkuranKontainer;
use CodeIgniter\I18n\Time;
use DateTime;
use Exception;
use CodeIgniter\HTTP\ResponseInterface;

class KomunitasEkspor extends BaseController
{
    // public function tonton($slug = null) // Atau berdasarkan ID
    // {
    //     // 1. Ambil data video dari database berdasarkan slug atau ID
    //     $videoModel = new VideoModel();
    //     // Ganti 'slug' dengan kolom yang Anda gunakan
    //     $videoData = $videoModel->where('slug', $slug)->first();

    //     if (!$videoData) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Video tidak ditemukan.');
    //     }

    //     // 2. Ambil UID Cloudflare dari database
    //     $video_uid = $videoData['cloudflare_uid']; // Pastikan nama kolom ini sesuai

    //     // 3. Generate Signed URL (Logic yang sama persis)
    //     $keyId = getenv('CLOUDFLARE_STREAM_KEY_ID');
    //     $secret = getenv('CLOUDFLARE_STREAM_SECRET');
    //     $expiry = time() + 3600; // Berlaku 1 jam

    //     $payload = ['sub' => $video_uid, 'kid' => $keyId, 'exp' => $expiry, 'downloadable' => false];
    //     $token = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');

    //     // 4. Siapkan data untuk dikirim ke view
    //     $data['video'] = $videoData; // Data video asli untuk judul, deskripsi, dll.
    //     // $data['kategori'] = ... ; // Data lain yang Anda butuhkan
    //     // $data['related_videos'] = ... ;
    //     $data['lang'] = $this->lang;

    //     // INI YANG PALING PENTING:
    //     // Kirim URL yang sudah ditandatangani sebagai variabel terpisah
    //     $data['signed_video_url'] = "https://stream.terama.site/{$token}/manifest/video.m3u8";

    //     return view('path/to/your/view_file', $data);
    // }
    public function planner()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile    = new WebProfile();
        $model_kontenpilar   = new KontenPilar();
        $model_kontentype    = new KontenType();
        $model_kontenplatform = new KontenPlatform();
        $model_kontenplanner = new KontenPlanner();

        $data['webprofile']     = $model_webprofile->findAll();
        $data['kontenpilar']    = $model_kontenpilar->findAll();
        $data['kontentype']     = $model_kontentype->findAll();
        $data['kontenplatform'] = $model_kontenplatform->findAll();
        $data['validation'] = \Config\Services::validation();

        return view('member/planner/planner', $data);
    }

    public function tambah_kontenplanner()
    {
        $kontenplanner = new KontenPlanner();

        // Validasi form
        $validation = \Config\Services::validation();
        $rules = [
            'title'             => 'required|min_length[3]|max_length[255]',
            'content_type_id'   => 'required|integer',
            'content_pillar_id' => 'required|integer',
            'caption'           => 'permit_empty|max_length[2200]',
            'status'            => 'required|in_list[Draft,Ready,Scheduled,Published]',
            'posting_date'      => 'required|valid_date',
            'posting_time'      => 'required',
            'platforms'         => 'required',
            'uploadFoto'        => 'if_exist|is_image[uploadFoto]|max_size[uploadFoto,2048]|mime_in[uploadFoto,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // --- Upload Foto ---
        $fotoPath = null;
        $fileFoto = $this->request->getFile('uploadFoto');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/konten', $newName);
            $fotoPath = 'uploads/konten/' . $newName;
        }

        // --- Tangani Platforms (array) ---
        $platforms = $this->request->getPost('platforms'); // array dari checkbox
        $platformCsv = is_array($platforms) ? implode(',', $platforms) : (string)$platforms;

        // --- Simpan data ke DB ---
        $kontenplanner->insert([
            'judul'               => $this->request->getPost('title'),
            'content_type_id'     => (int) $this->request->getPost('content_type_id'),
            'content_pillar_id'   => (int) $this->request->getPost('content_pillar_id'),
            'content_platform_id' => $platformCsv, // ubah ke VARCHAR di DB
            'caption'             => $this->request->getPost('caption'),
            'status'              => $this->request->getPost('status'),
            'tanggal_posting'     => $this->request->getPost('posting_date'),
            'jam_posting'         => $this->request->getPost('posting_time'),
            'media'               => $fotoPath,
            'link_canva'          => $this->request->getPost('link_canva'),
            'slug'                => url_title($this->request->getPost('title'), '-', true),
        ]);

        return redirect()->to(base_url('/sosmed-planner'))
            ->with('success', 'Konten berhasil ditambahkan!');
    }

    public function getCalendarData()
    {
        helper('url');

        $year  = $this->request->getGet('year') ?? date('Y');
        $month = $this->request->getGet('month') ?? date('m');

        $planner = new \App\Models\KontenPlanner();

        $rows = $planner
            ->select('content_planner.*,
                  konten_type.nama  AS type_name,
                  konten_pilar.nama AS pillar_name')
            ->join('konten_type',  'konten_type.id = content_planner.content_type_id', 'left')
            ->join('konten_pilar', 'konten_pilar.id = content_planner.content_pillar_id', 'left')
            ->where('YEAR(tanggal_posting)', $year)
            ->where('MONTH(tanggal_posting)', $month)
            ->orderBy('tanggal_posting', 'ASC')
            ->orderBy('jam_posting', 'ASC')
            ->findAll();

        // Kalau platform disimpan sebagai CSV id (mis. "1,2"), map ke nama:
        $platformModel = new \App\Models\KontenPlatform();
        $platformRef = [];
        foreach ($platformModel->findAll() as $p) {
            $platformRef[(string)$p['id']] = [
                'name' => $p['nama'],
                // kirim absolute URL biar aman dipakai di <img>
                'logo' => !empty($p['logo']) ? base_url($p['logo']) : null,
            ];
        }

        $calendarData = [];
        foreach ($rows as $r) {
            $dateKey = $r['tanggal_posting'];

            // content_platform_id disimpan comma-separated ID (mis. "1,2")
            $platformIds = array_filter(explode(',', (string)$r['content_platform_id']));
            $platforms = array_map(function ($id) use ($platformRef) {
                return $platformRef[(string)$id] ?? ['name' => (string)$id, 'logo' => null];
            }, $platformIds);

            if (!isset($calendarData[$dateKey])) $calendarData[$dateKey] = [];

            $calendarData[$dateKey][] = [
                'id'       => $r['id'],
                'slug'     => $r['slug'],
                'title'    => $r['judul'],
                'type'     => $r['type_name'] ?? '-',
                'platforms' => $platforms,
                'platform' => array_map(fn($x) => $x['name'], $platforms),
                'pillar'   => $r['pillar_name'] ?? '-',
                'status'   => $r['status'],
                'time'     => date('H:i', strtotime($r['jam_posting'])),
                'caption'  => $r['caption'],
                'media'    => $r['media'],
            ];
        }

        return $this->response->setJSON($calendarData);
    }

    public function tambah_kontenpilar()
    {
        $kontenpilar = new KontenPilar();

        $data = [
            'nama' => $this->request->getPost('nama_kontenpilar'),
            'deskripsi' => $this->request->getPost('deskripsi_kontenpilar'),
        ];

        $kontenpilar->insert($data);

        return redirect()->to('/sosmed-planner');
    }
    public function update_kontenpilar($id)
    {
        $kontenpilarModel = new KontenPilar();

        if (!$this->validate([
            'nama'      => 'required',
            'deskripsi' => 'permit_empty'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kontenpilarModel->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ]);

        return redirect()->to('/sosmed-planner')->with('success', 'Content pillar berhasil diperbarui');
    }

    public function hapus_kontenpilar($id)
    {
        $model = new \App\Models\KontenPilar();

        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to(base_url('sosmed-planner'))
                ->with('success', 'Konten pilar berhasil dihapus.');
        } else {
            return redirect()->to(base_url('sosmed-planner'))
                ->with('error', 'Konten pilar tidak ditemukan.');
        }
    }

    // ================= CONTENT TYPE =================
    public function tambah_kontentype()
    {
        $model = new \App\Models\KontenType();

        $data = [
            'nama' => $this->request->getPost('nama_kontentype'),
        ];

        $model->insert($data);

        return redirect()->to('/sosmed-planner')->with('success', 'Content type berhasil ditambahkan');
    }

    public function update_kontentype($id)
    {
        $model = new \App\Models\KontenType();

        if (!$this->validate([
            'nama' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'nama' => $this->request->getPost('nama')
        ]);

        return redirect()->to('/sosmed-planner')->with('success', 'Content type berhasil diperbarui');
    }

    public function hapus_kontentype($id)
    {
        $model = new \App\Models\KontenType();

        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('/sosmed-planner')->with('success', 'Content type berhasil dihapus.');
        }
        return redirect()->to('/sosmed-planner')->with('error', 'Content type tidak ditemukan.');
    }

    // ================= CONTENT PLATFORM =================
    public function tambah_kontenplatform()
    {
        $model = new \App\Models\KontenPlatform();

        // Ambil data input
        $nama = $this->request->getPost('nama_kontenplatform');
        $logoFile = $this->request->getFile('logo');

        // Validasi input
        if (!$nama || !$logoFile->isValid()) {
            return redirect()->back()->with('error', 'Nama dan logo wajib diisi!');
        }

        // Pastikan folder upload ada
        $uploadPath = FCPATH . 'uploads/logo_platform/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Simpan file logo
        $newName = $logoFile->getRandomName();
        $logoFile->move($uploadPath, $newName);

        // Simpan ke database
        $data = [
            'nama' => $nama,
            'logo' => 'uploads/logo_platform/' . $newName
        ];

        $model->insert($data);

        return redirect()->to('/sosmed-planner')->with('success', 'Content platform berhasil ditambahkan');
    }


    public function update_kontenplatform($id)
    {
        $model = new \App\Models\KontenPlatform();

        // Validasi minimal: nama wajib
        if (!$this->validate([
            'nama' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data lama (perlu untuk hapus file lama jika diganti / dihapus)
        $existing = $model->find($id);
        if (!$existing) {
            return redirect()->to('/sosmed-planner')->with('error', 'Data tidak ditemukan.');
        }

        $nama        = $this->request->getPost('nama');
        $removeLogo  = $this->request->getPost('remove_logo'); // '1' jika dicentang
        $logoFile    = $this->request->getFile('logo');

        // Siapkan payload update
        $updateData = ['nama' => $nama];

        // 1) Jika user minta hapus logo
        if ($removeLogo === '1') {
            // Hapus file lama (jika ada dan ada di disk)
            if (!empty($existing['logo'])) {
                $oldPath = FCPATH . $existing['logo'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $updateData['logo'] = null;
        }

        // 2) Jika user upload file logo baru (opsional)
        if ($logoFile && $logoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            // Validasi file gambar
            $valid = $this->validate([
                'logo' => [
                    'rules'  => 'uploaded[logo]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/webp]|max_size[logo,2048]',
                    'errors' => [
                        'uploaded' => 'Pilih file logo.',
                        'is_image' => 'File logo harus berupa gambar.',
                        'mime_in'  => 'Format logo harus jpg, jpeg, png, atau webp.',
                        'max_size' => 'Ukuran logo maksimal 2MB.'
                    ]
                ]
            ]);

            if (!$valid) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Pastikan folder upload tersedia
            $uploadDir = FCPATH . 'uploads/logo_platform/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Pindahkan file baru
            $newName = $logoFile->getRandomName();
            $logoFile->move($uploadDir, $newName);
            $relativePath = 'uploads/logo_platform/' . $newName;

            // Hapus file lama (jika ada), untuk menghindari sampah file
            if (!empty($existing['logo'])) {
                $oldPath = FCPATH . $existing['logo'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Simpan path baru
            $updateData['logo'] = $relativePath;
        }

        // Lakukan update
        $model->update($id, $updateData);

        return redirect()->to('/sosmed-planner')->with('success', 'Content platform berhasil diperbarui');
    }


    public function hapus_kontenplatform($id)
    {
        $model = new \App\Models\KontenPlatform();

        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('/sosmed-planner')->with('success', 'Content platform berhasil dihapus.');
        }
        return redirect()->to('/sosmed-planner')->with('error', 'Content platform tidak ditemukan.');
    }

    public function edit_kontenplanner($slug)
    {
        session()->set('lang', 'id');

        $m = new \App\Models\KontenPlanner();
        $row = $m->where('slug', $slug)->first();  // lookup by slug
        if (!$row) {
            return redirect()->to(base_url('sosmed-planner'))
                ->with('error', 'Konten tidak ditemukan.');
        }

        // Ambil master data untuk dropdown
        $kontenTypeModel   = new \App\Models\KontenType();
        $kontenPilarModel  = new \App\Models\KontenPilar();
        $kontenPlatModel   = new \App\Models\KontenPlatform();

        $data = [];
        $data['konten']          = $row;
        $data['webprofile']      = (new \App\Models\WebProfile())->findAll() ?? [];

        // === KIRIM VARIABEL YANG DIPAKAI DI VIEW ===
        $data['kontentype']      = $kontenTypeModel->findAll();     // <-- untuk select Konten Tipe
        $data['kontenpilar']     = $kontenPilarModel->findAll();    // <-- untuk select Konten Pilar
        $data['kontenplatform']  = $kontenPlatModel->findAll();     // <-- untuk checkbox/option platform

        // Platform terpilih (CSV → array)
        $data['selectedPlatforms'] = array_filter(array_map('trim', explode(',', (string)($row['content_platform_id'] ?? ''))));

        // (opsional) nama ringkas untuk header/subtitle
        $type   = $kontenTypeModel->find($row['content_type_id'] ?? 0);
        $pillar = $kontenPilarModel->find($row['content_pillar_id'] ?? 0);
        $data['kontenTypeName']   = $type['nama'] ?? '-';
        $data['kontenPillarName'] = $pillar['nama'] ?? '-';

        return view('member/planner/edit_konten', $data);
    }



    public function preview_kontenplanner($slug)
    {
        $m = new \App\Models\KontenPlanner();
        $data['konten'] = $m->where('slug', $slug)->first();
        if (!$data['konten']) {
            return redirect()->to(base_url('sosmed-planner'))->with('error', 'Konten tidak ditemukan.');
        }

        $data['hideLangSwitcher'] = true;
        $data['webprofile']       = (new \App\Models\WebProfile())->findAll() ?? [];

        $type                     = (new \App\Models\KontenType())->find($data['konten']['content_type_id'] ?? 0);
        $pillar                   = (new \App\Models\KontenPilar())->find($data['konten']['content_pillar_id'] ?? 0);
        $data['kontenplatform']   = (new \App\Models\KontenPlatform())->findAll();
        $data['kontenTypeName']   = $type['nama'] ?? '-';
        $data['kontenPillarName'] = $pillar['nama'] ?? '-';

        return view('member/planner/preview_konten', $data);
    }

    public function update_kontenplanner($slug)
    {
        $rules = [
            'title'             => 'required|min_length[3]|max_length[255]',
            'content_type_id'   => 'required|integer',
            'content_pillar_id' => 'required|integer',
            'caption'           => 'permit_empty|max_length[2200]',
            'status'            => 'required|in_list[Draft,Ready,Scheduled,Published]',
            'posting_date'      => 'required|valid_date',
            'posting_time'      => 'required',
            'platforms'         => 'permit_empty',
            'uploadFoto'        => 'if_exist|is_image[uploadFoto]|max_size[uploadFoto,2048]|mime_in[uploadFoto,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.')
                ->with('errors', $this->validator->getErrors());
        }

        $m   = new \App\Models\KontenPlanner();
        $row = $m->where('slug', $slug)->first();
        if (!$row) {
            return redirect()->to(base_url('/sosmed-planner'))->with('error', 'Konten tidak ditemukan.');
        }

        // HANDLE FILE (opsional)
        $fotoPath = $row['media'] ?? null;
        $fileFoto = $this->request->getFile('uploadFoto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newName  = $fileFoto->getRandomName();
            $fileFoto->move('uploads/konten', $newName);
            $fotoPath = 'uploads/konten/' . $newName;
        }

        // Platforms
        $platforms  = $this->request->getPost('platforms');
        if (!is_array($platforms)) $platforms = [];
        $platformCsv = implode(',', $platforms);

        $title = $this->request->getPost('title');
        $newSlug = $this->makeUniqueSlug($title, $row['id']); // pastikan unik, lihat helper di bawah

        $payload = [
            'judul'               => $title,
            'content_type_id'     => (int)$this->request->getPost('content_type_id'),
            'content_pillar_id'   => (int)$this->request->getPost('content_pillar_id'),
            'content_platform_id' => $platformCsv,
            'caption'             => $this->request->getPost('caption'),
            'status'              => $this->request->getPost('status'),
            'tanggal_posting'     => $this->request->getPost('posting_date'),
            'jam_posting'         => $this->request->getPost('posting_time'),
            'media'               => $fotoPath,
            'link_canva'          => $this->request->getPost('link_canva'),
            'slug'                => $newSlug,
        ];

        $ok = $m->update($row['id'], $payload);
        if (!$ok) {
            return redirect()->back()->withInput()->with('error', 'Update gagal.')
                ->with('errors', $m->errors() ?: ['DB error']);
        }

        // redirect pakai slug baru (jika judul diganti)
        return redirect()->to(base_url('/sosmed-planner'))->with('success', 'Konten diperbarui.');
    }

    public function hapus_kontenplanner($slug)
    {
        $m   = new \App\Models\KontenPlanner();
        $row = $m->where('slug', $slug)->first();

        if ($row) {
            $m->delete($row['id']);
            return redirect()->to(base_url('/sosmed-planner'))->with('success', 'Konten berhasil dihapus.');
        }
        return redirect()->to(base_url('/sosmed-planner'))->with('error', 'Konten tidak ditemukan.');
    }

    /**
     * Pastikan slug unik. Jika bentrok, tambahkan -2, -3, dst.
     */
    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = url_title($title, '-', true);
        $slug = $base;
        $m = new \App\Models\KontenPlanner();

        $i = 2;
        while (true) {
            $builder = $m->where('slug', $slug);
            if ($ignoreId) $builder->where('id !=', $ignoreId);
            $exists = $builder->first();

            if (!$exists) return $slug;
            $slug = $base . '-' . $i;
            $i++;
        }
    }

    public function index()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();
        $data['webprofile'] = $webprofile;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_beranda, title_beranda_en, meta_description_beranda, meta_description_beranda_en')
            ->first();
        $data['meta'] = $meta;

        $model_slider = new Slider();
        $model_member = new Member();
        $model_manfaatjoin = new ManfaatJoin();
        $model_fitur = new Fitur();

        $slider = $model_slider->findAll();
        $member = $model_member->where('role', 'member')->findAll();
        $top4_member = $model_member
            ->where('role', 'member')
            ->orderBy('popular_point', 'DESC')
            ->limit(4)
            ->findAll();
        $manfaatjoin = $model_manfaatjoin->findAll();
        $fitur_visitor = $model_fitur->where('role', 'visitor')->findAll();
        $fitur_member = $model_fitur->where('role', 'member')->findAll();

        foreach ($member as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['slider'] = $slider;
        $data['member'] = $member;
        $data['manfaatjoin'] = $manfaatjoin;
        $data['fitur_visitor'] = $fitur_visitor;
        $data['fitur_member'] = $fitur_member;
        $data['top4_member'] = $top4_member;

        return view('beranda/index', $data);
    }

    public function freeindex()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;
        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_beranda, title_beranda_en, meta_description_beranda, meta_description_beranda_en')
            ->first();
        $data['meta'] = $meta;

        $model_slider = new Slider();
        $model_member = new Member();
        $model_manfaatjoin = new ManfaatJoin();
        $model_fitur = new Fitur();

        $slider = $model_slider->findAll();
        $member = $model_member->where('role', 'member')->findAll();
        $top4_member = $model_member
            ->where('role', 'member')
            ->where('status', 1)
            ->orderBy('popular_point', 'DESC')
            ->limit(4)
            ->findAll();
        $manfaatjoin = $model_manfaatjoin->findAll();
        $fitur_visitor = $model_fitur->where('role', 'visitor')->findAll();
        $fitur_member = $model_fitur->where('role', 'member')->findAll();

        foreach ($member as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['slider'] = $slider;
        $data['member'] = $member;
        $data['manfaatjoin'] = $manfaatjoin;
        $data['fitur_visitor'] = $fitur_visitor;
        $data['fitur_member'] = $fitur_member;
        $data['top4_member'] = $top4_member;

        return view('member/beranda/index', $data);
    }

    public function tentang_kami()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_tentang, title_tentang_en, meta_description_tentang, meta_description_tentang_en')
            ->first();
        $data['meta'] = $meta;

        $model_webprofile = new WebProfile();
        $model_tentang = new TentangKami();
        $model_manfaatjoin = new ManfaatJoin();

        $webprofile = $model_webprofile->findAll();
        $tentang = $model_tentang->first();
        $manfaat = $model_manfaatjoin->findAll();

        $data['webprofile'] = $webprofile;
        $data['tentang_kami'] = $tentang;
        $data['manfaatjoin'] = $manfaat;

        return view('tentang/index', $data);
    }

    public function member_tentang_kami()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;
        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_tentang,meta_description_tentang ')
            ->first();
        $data['meta'] = $meta;

        $model_webprofile = new WebProfile();
        $model_tentang = new TentangKami();
        $model_manfaatjoin = new ManfaatJoin();

        $webprofile = $model_webprofile->findAll();
        $tentang = $model_tentang->first();
        $manfaat = $model_manfaatjoin->findAll();

        $data['webprofile'] = $webprofile;
        $data['tentang_kami'] = $tentang;
        $data['manfaatjoin'] = $manfaat;

        return view('member/tentang/index', $data);
    }

    public function premium_tentang_kami()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $model_tentang = new TentangKami();
        $model_manfaatjoin = new ManfaatJoin();

        $webprofile = $model_webprofile->findAll();
        $tentang = $model_tentang->first();
        $manfaat = $model_manfaatjoin->findAll();

        $data['webprofile'] = $webprofile;
        $data['tentang_kami'] = $tentang;
        $data['manfaatjoin'] = $manfaat;

        return view('premium/tentang/index', $data);
    }

    public function premiumindex()
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_slider = new Slider();
        $model_member = new Member();
        $model_manfaatjoin = new ManfaatJoin();

        $slider = $model_slider->findAll();
        $member = $model_member->where('role', 'member')->findAll();
        $top4_member = $model_member
            ->where('role', 'member')
            ->orderBy('popular_point', 'DESC')
            ->limit(4)
            ->findAll();
        $manfaatjoin = $model_manfaatjoin->findAll();

        foreach ($member as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['slider'] = $slider;
        $data['member'] = $member;
        $data['manfaatjoin'] = $manfaatjoin;
        $data['top4_member'] = $top4_member;

        return view('premium/beranda/index', $data);
    }

    public function belajar_ekspor($slug = null)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_materi, title_materi_en, meta_description_materi, meta_description_materi_en')
            ->first();

        // ✅ Meta global untuk halaman utama
        $data['title'] = ($lang === 'id')
            ? ($meta['title_materi'] ?? 'Belajar Ekspor')
            : ($meta['title_materi_en'] ?? 'Export Learning');

        $data['meta_description'] = ($lang === 'id')
            ? ($meta['meta_description_materi'] ?? 'Materi dan kategori belajar ekspor.')
            : ($meta['meta_description_materi_en'] ?? 'Learning materials and export categories.');

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Kategori dropdown
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        $perPage = 9;
        $page = $this->request->getVar('page') ?? 1;

        $data['belajar_ekspor'] = $belajarEksporModel->getAllWithCategoryAndPagination($perPage, $page);
        $data['active_category'] = null;

        $data['pager'] = $belajarEksporModel->pager;

        return view('belajar-ekspor/belajar_ekspor', $data);
    }

    public function search_belajar_ekspor(?string $keywordSegmen = null)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $data['meta'] = $model_meta
            ->select('title_materi, title_materi_en, meta_description_materi, meta_description_materi_en')
            ->first();

        helper(['text', 'url']);

        // Ambil dari segmen atau fallback ?keyword=
        $keyword = $keywordSegmen ?? $this->request->getGet('keyword') ?? '';
        if ($keyword !== '') {
            $keyword = str_replace('+', ' ', $keyword);
            $keyword = urldecode($keyword);
            if (strpos($keyword, ' ') === false && strpos($keyword, '-') !== false) {
                $keyword = str_replace('-', ' ', $keyword);
            }
            // Normalisasi spasi beruntun -> satu spasi
            $keyword = preg_replace('/\s+/', ' ', trim($keyword));
        }

        // (Opsional) jika datang dari query-string, redirect 301 ke URL cantik
        if ($keywordSegmen === null && $this->request->getGet('keyword')) {
            $encoded = str_replace('%20', '+', rawurlencode($keyword));
            $pretty  = base_url(($lang === 'en' ? 'en/lessons/keyword=' : 'id/materi/keyword=') . $encoded);
            return redirect()->to($pretty, 301);
        }

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        $perPage = 9;
        $page = (int)($this->request->getVar('page') ?? 1);

        $hasilPencarian = $belajarEksporModel->getSearchAllWithCategoryAndPagination($keyword, $perPage, $page);
        $data['hasilPencarian'] = $hasilPencarian ?? [];
        $data['keyword'] = $keyword;
        $data['active_category'] = null;

        $data['pager'] = $belajarEksporModel->pager;

        // Pastikan pagination tetap di path "keyword="
        if ($keyword !== '' && $data['pager']) {
            $encodedForPath = str_replace('%20', '+', rawurlencode($keyword));
            $prettyBase = base_url(($lang === 'en' ? 'en/lessons/keyword=' : 'id/materi/keyword=') . $encodedForPath);
            $data['pager']->setPath($prettyBase);
        }

        $data['title'] = ($lang === 'id') ? 'Hasil Pencarian Materi Ekspor' : 'Search Results - Export Lessons';
        $data['meta_description'] = ($lang === 'id')
            ? 'Hasil pencarian materi dan artikel belajar ekspor.'
            : 'Search results for export learning materials.';

        return view('belajar-ekspor/belajar_ekspor_search', $data);
    }

    public function kategori_belajar_ekspor(string $slug)
    {
        // Bahasa aktif
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        // (Opsional) Webprofile untuk header/footer/layout
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        // Model
        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel      = new KategoriBelajarEksporModel();

        // ============================
        // 1) Cek sebagai KATEGORI
        // ============================
        $kategori = $kategoriBelajarEksporModel
            ->groupStart()
            ->where('slug', $slug)
            ->orWhere('slug_en', $slug)
            ->groupEnd()
            ->first();

        if ($kategori) {
            // ---- Meta (fallback ke nama kategori jika title/meta kosong) ----
            $data['title'] = ($lang === 'id')
                ? (!empty($kategori['title_kategori_belajar_ekspor'])
                    ? $kategori['title_kategori_belajar_ekspor']
                    : ($kategori['nama_kategori'] ?? ''))
                : (!empty($kategori['title_kategori_belajar_ekspor_en'])
                    ? $kategori['title_kategori_belajar_ekspor_en']
                    : ($kategori['nama_kategori_en'] ?? ''));

            $data['meta_description'] = ($lang === 'id')
                ? (!empty($kategori['meta_description_kategori_belajar_ekspor'])
                    ? $kategori['meta_description_kategori_belajar_ekspor']
                    : ($kategori['nama_kategori'] ?? ''))
                : (!empty($kategori['meta_description_kategori_belajar_ekspor_en'])
                    ? $kategori['meta_description_kategori_belajar_ekspor_en']
                    : ($kategori['nama_kategori_en'] ?? ''));

            $data['current_category_name'] = ($lang === 'id')
                ? ($kategori['title_kategori_belajar_ekspor'] ?? '')
                : ($kategori['title_kategori_belajar_ekspor_en'] ?? '');

            // ---- Pagination & listing artikel dalam kategori ----
            $perPage = 9;
            $page    = (int)($this->request->getVar('page') ?? 1);

            $data['belajar_ekspor'] = $belajarEksporModel->getSpecificByCategoryWithPagination(
                $kategori['id_kategori_belajar_ekspor'],
                $perPage,
                $page
            );

            // Dropdown kategori & state aktif
            $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();
            $data['active_category']         = $kategori['id_kategori_belajar_ekspor'];

            // Pager (pastikan path tetap /id/materi/{slug} atau /en/lessons/{slug})
            $data['pager'] = $belajarEksporModel->pager;
            if (!empty($data['pager'])) {
                $base = base_url(($lang === 'en' ? 'en/lessons/' : 'id/materi/') . $slug);
                $data['pager']->setPath($base);
            }

            // ---- Render view listing kategori (pakai view daftar yang sama) ----
            return view('belajar-ekspor/belajar_ekspor', $data);
        }

        // ============================
        // 2) Bukan kategori → cek ARTIKEL
        // ============================
        $artikel = $belajarEksporModel
            ->groupStart()
            ->where('slug', $slug)
            ->orWhere('slug_en', $slug)
            ->groupEnd()
            ->first();

        if ($artikel) {
            // Delegasikan ke method detail agar tidak duplikasi logic
            return $this->belajar_ekspor_detail($slug);
        }

        // ============================
        // 3) Tidak ditemukan → fallback
        // ============================
        return redirect()
            ->to(base_url($lang === 'en' ? 'en/lessons' : 'id/materi'))
            ->with('error', 'Konten tidak ditemukan.');
    }

    // public function kategori_belajar_ekspor($slug)
    // {
    //     $lang = session()->get('lang') ?? 'id';
    //     $data['lang'] = $lang;

    //     $model_webprofile = new WebProfile();
    //     $data['webprofile'] = $model_webprofile->findAll();

    //     $belajarEksporModel = new BelajarEksporModel();
    //     $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

    //     // 🔹 Ambil kategori berdasarkan slug
    //     $kategori = $kategoriBelajarEksporModel
    //         ->where('slug', $slug)
    //         ->orWhere('slug_en', $slug)
    //         ->first();

    //     if (!$kategori) {
    //         return redirect()->to('/')->with('error', 'Kategori tidak ditemukan');
    //     }

    //     // ✅ Meta dari kategori (langsung dari tabel kategori_belajar_ekspor)
    //     $data['title'] = ($lang === 'id')
    //         ? ($kategori['title_kategori_belajar_ekspor'] ?: $kategori['nama_kategori'])
    //         : ($kategori['title_kategori_belajar_ekspor_en'] ?: $kategori['nama_kategori_en']);

    //     $data['meta_description'] = ($lang === 'id')
    //         ? ($kategori['meta_description_kategori_belajar_ekspor'] ?: $kategori['nama_kategori'])
    //         : ($kategori['meta_description_kategori_belajar_ekspor_en'] ?: $kategori['nama_kategori_en']);

    //     $perPage = 9;
    //     $page = $this->request->getVar('page') ?? 1;

    //     // 🔹 Data belajar ekspor berdasarkan kategori
    //     $data['belajar_ekspor'] = $belajarEksporModel->getSpecificByCategoryWithPagination(
    //         $kategori['id_kategori_belajar_ekspor'],
    //         $perPage,
    //         $page
    //     );

    //     // 🔹 Semua kategori (dropdown)
    //     $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

    //     $data['active_category'] = $kategori['id_kategori_belajar_ekspor'];
    //     $data['pager'] = $belajarEksporModel->pager;

    //     return view('belajar-ekspor/belajar_ekspor', $data);
    // }


    public function belajar_ekspor_detail($slug)
    {
        $lang = session()->get('lang') ?? 'id';
        $pendaftaranLink = ($lang === 'en') ? 'registration' : 'pendaftaran';
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Mengambil artikel berdasarkan slug
        $artikel = $belajarEksporModel->where('slug', $slug)->orWhere('slug_en', $slug)->first();

        if (!$artikel) {
            // Jika artikel tidak ditemukan, redirect atau tampilkan pesan error
            return redirect()->to('/')->with('error', 'Artikel tidak ditemukan');
        }

        // Cek apakah slug sesuai dengan bahasa yang sedang aktif
        if (($lang === 'id' && $slug !== $artikel['slug']) || ($lang === 'en' && $slug !== $artikel['slug_en'])) {
            // Redirect ke URL slug yang benar sesuai bahasa
            $correctSlug = $lang === 'id' ? $artikel['slug'] : $artikel['slug_en'];
            $correctulr = $lang === 'id' ? 'materi' : 'lessons';
            return redirect()->to("$lang/$correctulr/$correctSlug");
        }

        // Mengambil kategori artikel berdasarkan id_kategori
        $kategori = $kategoriBelajarEksporModel->find($artikel['id_kategori_belajar_ekspor']);

        // Mengambil artikel terkait
        $related_artikel = $belajarEksporModel->where('slug !=', $slug)->orderBy('created_at', 'DESC')->limit(3)->findAll();

        foreach ($related_artikel as &$item) {
            $item['kategori'] = $kategoriBelajarEksporModel->find($item['id_kategori_belajar_ekspor']);
        }
        // Mengirim data artikel, kategori, dan artikel terkait ke view
        $data = [
            'artikel' => $artikel,
            'kategori' => $kategori,
            'belajar_ekspor' => $related_artikel,
            'webprofile' => $webprofile,
            'lang' => $lang,
            'pendaftaranLink' => $pendaftaranLink,
            'isGuest' => ! session()->has('user_id'), // Flag guest
        ];

        return view('belajar-ekspor/belajar_ekspor_detail', $data);
    }

    public function pendaftaran()
    {
        if (session()->get('user_id') || session()->get('logged_in')) {
            return redirect()->to('/beranda')->with('info', 'Kamu sudah login.');
        }

        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();
        $data['webprofile'] = $webprofile;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_daftar, title_daftar_en, meta_description_daftar, meta_description_daftar_en')
            ->first();
        $data['meta'] = $meta;

        $model_keuntungan = new Keuntungan();
        $keuntungan = $model_keuntungan->findAll();
        $data['keuntungan'] = $keuntungan;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_kategori_induk = new KategoriInduk();
        $kategori_induk = $model_kategori_induk->findAll();
        $data['kategori_induk'] = $kategori_induk;

        $model_kategori_produk = new KategoriProduk();
        $kategori_produk = $model_kategori_produk->findAll();

        $kategori_produk_terkelompok = [];
        foreach ($kategori_produk as $produk) {
            $kategori_produk_terkelompok[$produk['id_kategori_induk']][] = $produk;
        }
        $data['kategori_produk_terkelompok'] = $kategori_produk_terkelompok;

        return view('pendaftaran/pendaftaran', $data);
    }

    public function video_tutorial($slug = null)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $data['pendaftaranLink'] = ($lang === 'en') ? 'register' : 'pendaftaran';

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_tutorial, title_tutorial_en, meta_description_tutorial, meta_description_tutorial_en')
            ->first();
        // $data['meta'] = $meta;
        // ✅ Meta global untuk halaman utama
        $data['title'] = ($lang === 'id')
            ? ($meta['title_tutorial'] ?? 'Video Tutorial')
            : ($meta['title_tutorial_en'] ?? 'Videos Tutorial');

        $data['meta_description'] = ($lang === 'id')
            ? ($meta['meta_description_tutorial'] ?? 'Video dan kategori Video Tutorial.')
            : ($meta['meta_description_tutorial_en'] ?? 'Videos and export categories Videos Tutorial.');

        $videoTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();

        // $kategori = $kategoriModel->findAll();
        $data['kategori_video'] = $kategoriVideoModel->orderBy('nama_kategori_video', 'ASC')->findAll();

        $perPage = 9;
        $page = $this->request->getVar('page') ?? 1;

        // $videos = [];


        $data['video_tutorial'] = $videoTutorialModel->getAllWithCategoryAndPagination($perPage, $page);
        $data['active_category'] = null;

        $data['pager'] = $videoTutorialModel->pager;

        // $data['video_tutorial'] = $videos;
        // $data['selected_category'] = $slug;

        return view('video-tutorial/video_tutorial', $data);
    }

    public function search_video_tutorial(?string $keywordSegmen = null)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $data['meta'] = $model_meta
            ->select('title_tutorial, title_tutorial_en, meta_description_tutorial, meta_description_tutorial_en')
            ->first();

        helper(['text', 'url']);

        // // Ambil keyword dari query string
        // $keyword = $this->request->getGet('keyword');
        // Ambil dari segmen atau fallback ?keyword=
        $keyword = $keywordSegmen ?? $this->request->getGet('keyword') ?? '';
        if ($keyword !== '') {
            $keyword = str_replace('+', ' ', $keyword);
            $keyword = urldecode($keyword);
            if (strpos($keyword, ' ') === false && strpos($keyword, '-') !== false) {
                $keyword = str_replace('-', ' ', $keyword);
            }
            // Normalisasi spasi beruntun -> satu spasi
            $keyword = preg_replace('/\s+/', ' ', trim($keyword));
        }

        // (Opsional) jika datang dari query-string, redirect 301 ke URL cantik
        if ($keywordSegmen === null && $this->request->getGet('keyword')) {
            $encoded = str_replace('%20', '+', rawurlencode($keyword));
            $pretty  = base_url(($lang === 'en' ? 'en/videos/keyword=' : 'id/video/keyword=') . $encoded);
            return redirect()->to($pretty, 301);
        }

        // Instansiasi model yang diperlukan
        $videoTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();
        $data['kategori_video'] = $kategoriVideoModel->findAll();

        $perPage = 9; // Number of items per page
        $page = (int)($this->request->getVar('page') ?? 1);

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $videoTutorialModel->getSearchAllWithCategoryAndPagination($keyword, $perPage, $page);
        $data['hasilPencarian'] = $hasilPencarian ?? [];
        $data['keyword'] = $keyword;
        $data['active_category'] = null;

        // Render view hasil pencarian
        $data['pager'] = $videoTutorialModel->pager; // Get the pager instance

        // Pastikan pagination tetap di path "keyword="
        if ($keyword !== '' && $data['pager']) {
            $encodedForPath = str_replace('%20', '+', rawurlencode($keyword));
            $prettyBase = base_url(($lang === 'en' ? 'en/videos/keyword=' : 'id/video/keyword=') . $encodedForPath);
            $data['pager']->setPath($prettyBase);
        }

        $data['title'] = ($lang === 'id') ? 'Hasil Pencarian video' : 'Search Results - videos';
        $data['meta_description'] = ($lang === 'id')
            ? 'Hasil pencarian video.'
            : 'Search results for videos.';


        return view('video-tutorial/video_tutorial_search', $data);
    }

    public function video_selengkapnya($slug)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $vidioTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();

        // ============================
        // 1) Cek sebagai KATEGORI
        // ============================
        $kategori = $kategoriVideoModel
            ->groupStart()
            ->where('slug', $slug)
            ->orWhere('slug_en', $slug)
            ->groupEnd()
            ->first();

        if ($kategori) {
            // ---- Meta (fallback ke nama kategori jika title/meta kosong) ----
            $data['title'] = ($lang === 'id')
                ? (!empty($kategori['title_kategori_video'])
                    ? $kategori['title_kategori_video']
                    : ($kategori['nama_kategori_video'] ?? ''))
                : (!empty($kategori['title_kategori_video_en'])
                    ? $kategori['title_kategori_video_en']
                    : ($kategori['nama_kategori_video_en'] ?? ''));

            $data['meta_description'] = ($lang === 'id')
                ? (!empty($kategori['meta_description_kategori_video'])
                    ? $kategori['meta_description_kategori_video']
                    : ($kategori['nama_kategori_video'] ?? ''))
                : (!empty($kategori['meta_description_kategori_video_en'])
                    ? $kategori['meta_description_kategori_video_en']
                    : ($kategori['nama_kategori_video_en'] ?? ''));

            $data['current_category_name'] = ($lang === 'id')
                ? ($kategori['title_kategori_video'] ?? '')
                : ($kategori['title_kategori_video_en'] ?? '');

            // ---- Pagination & listing video dalam kategori ----
            $perPage = 9;
            $page    = (int)($this->request->getVar('page') ?? 1);

            $data['video_tutorial'] = $vidioTutorialModel->getSpecificByCategoryWithPagination(
                $kategori['id_kategori_video'],
                $perPage,
                $page
            );

            // Dropdown kategori & state aktif
            $data['kategori_video'] = $kategoriVideoModel->findAll();
            $data['active_category']         = $kategori['id_kategori_video'];

            // Pager (pastikan path tetap /id/materi/{slug} atau /en/lessons/{slug})
            $data['pager'] = $vidioTutorialModel->pager;
            if (!empty($data['pager'])) {
                $base = base_url(($lang === 'en' ? 'en/videos/' : 'id/video/') . $slug);
                $data['pager']->setPath($base);
            }

            // ---- Render view listing kategori (pakai view daftar yang sama) ----
            return view('video-tutorial/video_tutorial', $data);
        }

        // ============================
        // 2) Bukan kategori → cek ARTIKEL
        // ============================
        $video = $vidioTutorialModel
            ->groupStart()
            ->where('slug', $slug)
            ->orWhere('slug_en', $slug)
            ->groupEnd()
            ->first();

        if ($video) {
            // Delegasikan ke method detail agar tidak duplikasi logic
            return $this->video_tutorial_detail($slug);
        }

        // ============================
        // 3) Tidak ditemukan → fallback
        // ============================
        return redirect()
            ->to(base_url($lang === 'en' ? 'en/videos' : 'id/video'))
            ->with('error', 'Konten tidak ditemukan.');
    }

    public function video_tutorial_detail($slug)
    {
        $lang = session()->get('lang') ?? 'id';
        $pendaftaranLink = ($lang === 'en') ? 'registration' : 'pendaftaran';
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();

        // Inisialisasi model untuk video dan kategori
        $vidioTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();
        // Mengambil data video berdasarkan slug
        $video = $vidioTutorialModel->where('slug', $slug)->orWhere('slug_en', $slug)->first();

        if (!$video) {
            // Jika video tidak ditemukan, redirect atau tampilkan pesan error
            return redirect()->to('/')->with('error', 'video tidak ditemukan');
        }
        // Cek apakah slug sesuai dengan bahasa yang sedang aktif
        if (($lang === 'id' && $slug !== $video['slug']) || ($lang === 'en' && $slug !== $video['slug_en'])) {
            // Redirect ke URL slug yang benar sesuai bahasa
            $correctSlug = $lang === 'id' ? $video['slug'] : $video['slug_en'];
            $correctulr = $lang === 'id' ? 'video' : 'videos';
            return redirect()->to("$lang/$correctulr/$correctSlug");
        }

        // Mengambil informasi kategori video
        $kategori = $kategoriVideoModel->find($video['id_kategori_video']);

        // Mengambil video terkait berdasarkan kategori video saat ini, dan pastikan tidak mengambil video yang sedang dilihat
        $related_video = $vidioTutorialModel->where('slug !=', $slug)->orderBy('created_at', 'DESC')->limit(3)->findAll();

        foreach ($related_video as &$item) {
            $item['kategori'] = $kategoriVideoModel->find($item['id_kategori_video']);
        }

        // Menyiapkan data untuk dikirimkan ke view
        $data = [
            'video' => $video,
            'related_video' => $related_video,
            'kategori' => $kategori,
            'webprofile' => $webprofile,
            'lang' => $lang,
            'pendaftaranLink' => $pendaftaranLink,
            'isGuest' => ! session()->has('user_id'), // Flag guest
        ];

        // Mengembalikan view dengan data yang telah disiapkan
        return view('video-tutorial/video_tutorial_detail', $data);
    }
    // public function videoPage($videoId)
    // {
    //     $token = bin2hex(random_bytes(16)); // token acak
    //     // simpan token + status unused di session/db
    //     $_SESSION['video_token'] = [
    //         'token' => $token,
    //         'used' => false,
    //         'expire' => time() + 300,
    //     ];

    //     // Kirim token ke view
    //     return view('video_page', [
    //         'video_url' => base_url("stream/$videoId.m3u8"),
    //         'video_token' => $token,
    //         // data lain
    //     ]);
    // }
    public function registrasiMember()
    {
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        $secretKey = "6LfFqdsrAAAAANncDSHh52IX8-blFSlTsKrfVnAR"; // ambil dari Google reCAPTCHA admin
        $verifyResponse = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}"
        );
        $responseData = json_decode($verifyResponse);

        if (! $responseData->success) {
            return redirect()->back()->with('error', 'Captcha tidak valid, coba lagi.')->withInput();
        }

        $memberModel = new Member();
        $kategoriProdukModel = new KategoriProduk();

        // Ambil input dari form
        $data = [
            'role' => 'member',
            'status_premium' => null,
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'kode_referral' => $this->request->getPost('referral'),
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_usaha_en'),
            'tahun_dibentuk' => $this->request->getPost('tahun_berdiri'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            'produk_utama' => $this->request->getPost('produk_utama'),
            'produk_utama_en' => $this->request->getPost('produk_utama_en'),
            'email' => $this->request->getPost('email'),
            'pic' => $this->request->getPost('pic'),
            'pic_phone' => $this->request->getPost('nomor_pic'),
            'kategori_produk' => $this->request->getPost('kategori_produk'),
            'alamat_website' => $this->request->getPost('alamat_website'),
            'nama_rekening' => $this->request->getPost('nama_rekening'),
            'nomor_rekening' => $this->request->getPost('nomor_rekening'),
            'jenis_bank' => $this->request->getPost('jenis_bank'),
            'status' => '0', // default
            'popular_point' => 0
        ];

        // === Kategori Produk Baru ===
        if ($data['kategori_produk'] === 'other') {
            $id_induk = $this->request->getPost('id_induk');
            $kategori_baru = $this->request->getPost('kategori_baru');
            if ($id_induk && $kategori_baru) {
                $kategoriProdukModel->insert([
                    'id_kategori_induk' => $id_induk,
                    'nama_kategori_produk' => $kategori_baru,
                    'nama_kategori_produk_en' => $kategori_baru
                ]);
                $data['kategori_produk'] = $kategori_baru;
            }
        }

        // === Upload Foto Usaha ===
        $foto_usaha = $this->request->getFile('foto_usaha');
        if ($foto_usaha && $foto_usaha->isValid() && !$foto_usaha->hasMoved()) {
            $newName = $foto_usaha->getRandomName();
            $foto_usaha->move('uploads/foto_usaha', $newName);
            $data['foto_profil'] = $newName;
        }

        // === Upload Bukti Transfer ===
        $bukti_transfer = $this->request->getFile('bukti_transfer');
        if ($bukti_transfer && $bukti_transfer->isValid() && !$bukti_transfer->hasMoved()) {
            $newBukti = $bukti_transfer->getRandomName();
            $bukti_transfer->move('uploads/bukti_transfer', $newBukti);
            $data['bukti_transfer'] = $newBukti;
        }

        // === Validasi unik ===
        if ($memberModel->where('username', $data['username'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        if ($memberModel->where('email', $data['email'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
        }

        if ($data['kode_referral'] && $data['kode_referral'] == $data['username']) {
            return redirect()->back()->withInput()->with('error', 'Kode referral tidak boleh sama dengan username.');
        }

        // === Simpan ke database ===
        $inserted = $memberModel->insert($data);

        if ($inserted) {
            return redirect()->back()->with('success', 'Pendaftaran berhasil! Data Anda sudah disimpan, mohon tunggu verifikasi admin.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }


    public function daftarMemberPremium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_member = new Member();

        $member = $model_member->where('id_member', $user_id)->first();

        $pesan = "Saya " . $member['username'] . ", Ingin Mendaftar Member Premium Komunitas Ekspor Indonesia!";

        $nomor_wa = '6283153270334';

        $whatsapp = "https://wa.me/$nomor_wa?text=" . urlencode($pesan);

        return redirect()->to($whatsapp);
    }

    public function checkAvailability()
    {
        // Mengambil data dari request
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $referral = $this->request->getPost('referral');
        $userModel = new Member();

        // Cek apakah username ada
        if ($username) {
            $userExists = $userModel->where('username', $username)->first();
            if ($userExists) {
                return $this->response->setJSON(['status' => 'exists', 'field' => 'username']);
            } else {
                return $this->response->setJSON(['status' => 'available', 'field' => 'username']);
            }
        }

        // Cek apakah email ada
        if ($email) {
            $emailExists = $userModel->where('email', $email)->first();
            if ($emailExists) {
                return $this->response->setJSON(['status' => 'exists', 'field' => 'email']);
            } else {
                return $this->response->setJSON(['status' => 'available', 'field' => 'email']);
            }
        }

        // Cek apakah referral valid (harus ada di database)
        if ($referral) {
            $referralExists = $userModel->where('username', $referral)->first();  // Cek referral sebagai username di database
            if (!$referralExists) {
                return $this->response->setJSON(['status' => 'invalid', 'field' => 'referral', 'message' => 'Kode referral tidak valid']);
            } else {
                return $this->response->setJSON(['status' => 'valid', 'field' => 'referral']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    public function visitor_landing_page($slug)
    {
        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        // Cari member berdasarkan username, karena slug dibuat dari username
        $member = $model_member->where('role', 'premium')->where('username', url_title($slug, '-', true))->first();

        $member_id = $member['id_member'];

        // Mengambil data sertifikat dan produk berdasarkan id_member
        $sertifikat = $model_sertifikat->where('id_member', $member_id)->findAll();
        $produk = $model_produk->where('id_member', $member_id)->findAll();

        // Kirimkan data ke view
        $data['member'] = $member;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;

        return view('landing-page/index', $data);
    }

    public function premium_data_member()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_member = new Member();

        // Set pagination
        $perPage = 12; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Fetch members with pagination
        $members = $model_member
            ->where('role', 'premium')
            ->where('status_premium', 'verified')
            ->orderBy('popular_point', 'DESC')
            ->paginate($perPage);

        // Modify members to add slug
        foreach ($members as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['member'] = $members;
        $data['pager'] = $model_member->pager; // Get the pager instance

        return view('premium/data-member/index', $data);
    }

    public function premium_detail_member($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        // Cari member berdasarkan username, karena slug dibuat dari username
        $member = $model_member
            ->where('role', 'premium')
            ->where('status_premium', 'verified')
            ->where('username', url_title($slug, '-', true))
            ->first();

        // Jika member ditemukan
        if ($member) {
            // Iterasi setiap field dalam array member
            foreach ($member as $key => $value) {
                if (empty($value)) {
                    // Tetapkan nilai default khusus
                    $member[$key] = ($key === 'foto_profil') ? null : '-';
                }
            }
        } else {
            // Jika member tidak ditemukan, lemparkan 404
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Member tidak ditemukan: ' . $slug);
        }

        // Top 3 popular members
        $members = $model_member
            ->where('role', 'premium')
            ->where('status_premium', 'verified')
            ->orderBy('popular_point', 'DESC')
            ->findAll(); // Tambahkan findAll() untuk mengambil data

        $top3_members = [];
        foreach ($members as $key => $item) {
            if ($item['id_member'] != $member['id_member']) {
                // Buat slug dari judul_artikel dan tanggal
                $item['slug'] = url_title($item['username'], '-', true);
                $top3_members[] = $item;
            }
        }

        $top3_members = array_slice($top3_members, 0, 3);

        $member_id = $member['id_member'];

        // Mengambil data sertifikat dan produk berdasarkan id_member
        $sertifikat = $model_sertifikat->where('id_member', $member_id)->findAll();
        $produk = $model_produk->where('id_member', $member_id)->findAll();

        // Kirimkan data ke view
        $data['member'] = $member;
        $data['members'] = $top3_members;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;

        return view('premium/data-member/detail', $data);
    }

    public function visitor_data_member()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();
        $data['webprofile'] = $webprofile;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_member, title_member_en, meta_description_member, meta_description_member_en')
            ->first();
        $data['meta'] = $meta;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_member = new Member();

        // Set pagination
        $perPage = 12; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Fetch members with pagination
        $members = $model_member
            ->where('role', 'member')
            ->orderBy('popular_point', 'DESC')
            ->paginate($perPage);

        // Modify members to add slug
        foreach ($members as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['member'] = $members;
        $data['pager'] = $model_member->pager; // Get the pager instance

        return view('data-member/index', $data);
    }

    // public function data_member_visitor()
    // {
    //     $model_member = new Member();

    //     $top4_member = $model_member
    //         ->orderBy('popular_point', 'DESC')
    //         ->limit(4)
    //         ->findAll();

    //     $blur_member = $model_member
    //         ->orderBy('popular_point', 'DESC')
    //         ->limit(4, 4)
    //         ->findAll();

    //     $data['top4_member'] = $top4_member;
    //     $data['blur_member'] = $blur_member;

    //     return view('data-member/index', $data);
    // }

    public function visitor_detail_member($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();
        $data['webprofile'] = $webprofile;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_member, title_member_en, meta_description_member, meta_description_member_en')
            ->first();
        $data['meta'] = $meta;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        // Cari member berdasarkan username, karena slug dibuat dari username
        $member = $model_member
            ->where('role', 'member')
            ->where('username', url_title($slug, '-', true))
            ->first();

        // Jika member ditemukan
        if ($member) {
            // Iterasi setiap field dalam array member
            foreach ($member as $key => $value) {
                if (empty($value)) {
                    // Tetapkan nilai default khusus
                    $member[$key] = ($key === 'foto_profil') ? null : '-';
                }
            }
        } else {
            // Jika member tidak ditemukan, lemparkan 404
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Member tidak ditemukan: ' . $slug);
        }

        // Top 3 popular members
        $members = $model_member
            ->where('role', 'member')
            ->orderBy('popular_point', 'DESC')
            ->findAll(); // Tambahkan findAll() untuk mengambil data

        $top3_members = [];
        foreach ($members as $key => $item) {
            if ($item['id_member'] != $member['id_member']) {
                // Buat slug dari judul_artikel dan tanggal
                $item['slug'] = url_title($item['username'], '-', true);
                $top3_members[] = $item;
            }
        }

        $top3_members = array_slice($top3_members, 0, 3);

        $member_id = $member['id_member'];

        // Mengambil data sertifikat dan produk berdasarkan id_member
        $sertifikat = $model_sertifikat->where('id_member', $member_id)->findAll();
        $produk = $model_produk->where('id_member', $member_id)->findAll();

        // Kirimkan data ke view
        $data['member'] = $member;
        $data['members'] = $top3_members;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;

        // return view('data-member/detail', $data);
        return view('beranda/index', $data);
    }

    public function member_data_member()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_member = new Member();

        // Set pagination
        $perPage = 12; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Fetch members with pagination
        $members = $model_member
            ->where(['role' => 'member', 'status' => '1'])
            ->orderBy('popular_point', 'DESC')
            ->paginate($perPage);

        // Modify members to add slug
        foreach ($members as &$item) {
            $item['slug'] = url_title($item['username'], '-', true);
        }

        $data['member'] = $members;
        $data['pager'] = $model_member->pager; // Get the pager instance

        return view('member/data-member/index', $data);
        // return view('beranda/index', $data);
    }

    public function member_detail_member($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        // Cari member berdasarkan username, karena slug dibuat dari username
        $member = $model_member
            ->where('role', 'member')
            ->where('username', url_title($slug, '-', true))
            ->first();

        // Jika member ditemukan
        if ($member) {
            // Iterasi setiap field dalam array member
            foreach ($member as $key => $value) {
                if (empty($value)) {
                    // Tetapkan nilai default khusus
                    $member[$key] = ($key === 'foto_profil') ? null : '-';
                }
            }
        } else {
            // Jika member tidak ditemukan, lemparkan 404
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Member tidak ditemukan: ' . $slug);
        }

        // Top 3 popular members
        $members = $model_member
            ->where('role', 'member')
            ->orderBy('popular_point', 'DESC')
            ->findAll(); // Tambahkan findAll() untuk mengambil data

        $top3_members = [];
        foreach ($members as $key => $item) {
            if ($item['id_member'] != $member['id_member']) {
                // Buat slug dari judul_artikel dan tanggal
                $item['slug'] = url_title($item['username'], '-', true);
                $top3_members[] = $item;
            }
        }

        $top3_members = array_slice($top3_members, 0, 3);

        $member_id = $member['id_member'];

        // Mengambil data sertifikat dan produk berdasarkan id_member
        $sertifikat = $model_sertifikat->where('id_member', $member_id)->findAll();
        $produk = $model_produk->where('id_member', $member_id)->findAll();

        // Kirimkan data ke view
        $data['member'] = $member;
        $data['members'] = $top3_members;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;

        return view('member/data-member/detail', $data);
        // return view('beranda/index', $data);
    }

    // public function data_buyers()
    // {
    //     $model_webprofile = new WebProfile();

    //     $webprofile = $model_webprofile->findAll();

    //     $data['webprofile'] = $webprofile;

    //     $lang = session()->get('lang') ?? 'id';
    //     $data['lang'] = $lang;

    //     $model_buyers = new Buyers();

    //     // Set pagination
    //     $perPage = 10; // Number of members per page
    //     $page = $this->request->getVar('page') ?? 1; // Get the current page number

    //     $buyers = $model_buyers
    //         ->orderBy('verif_date', 'DESC')
    //         ->paginate($perPage);

    //     $data['buyers'] = $buyers;
    //     $data['pager'] = $model_buyers->pager;
    //     $data['page'] = $page;
    //     $data['perPage'] = $perPage;

    //     return view('data-buyers/index', $data);
    // }

    public function search_buyers()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_buyers = new Buyers();

        // Set pagination
        $perPage = 10; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $model_buyers->like('nama_perusahaan', $keyword)
            ->orLike('negara_perusahaan', $keyword)
            ->orLike('hs_code', $keyword)
            ->orLike('deskripsi_hs_code', $keyword)
            ->paginate($perPage); // Pastikan method ini mengembalikan data dengan kategori

        // Jika ada hasil pencarian
        if (count($hasilPencarian) > 0) {
            $data['hasilPencarian'] = $hasilPencarian;
        } else {
            $data['hasilPencarian'] = [];
        }

        // Kirimkan keyword pencarian untuk ditampilkan di view
        $data['keyword'] = $keyword;
        $data['pager'] = $model_buyers->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('member/data-buyers/search', $data);
    }

    public function data_buyers()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_produk = new Produk();
        $model_buyers = new Buyers();

        $produk = $model_produk->where('id_member', $user_id)->findColumn('hs_code');

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // If there are hs_codes, find buyers with matching hs_codes
        $buyers = [];
        if ($produk) {
            $buyers = $model_buyers->whereIn('hs_code', $produk)->paginate($perPage);
        }

        // Prepare data to pass to the view
        $data['buyers'] = $buyers;
        $data['pager'] = $model_buyers->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('premium/data-buyers/index', $data);
    }

    public function edit_profile()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        $member = $model_member->where('id_member', $user_id)->first();
        $sertifikat = $model_sertifikat->where('id_member', $user_id)->findAll();
        $produk = $model_produk->where('id_member', $user_id)->findAll();

        $model_kategori_induk = new KategoriInduk();
        $kategori_induk = $model_kategori_induk->findAll();
        $data['kategori_induk'] = $kategori_induk;

        $model_kategori_produk = new KategoriProduk();
        $kategori_produk = $model_kategori_produk->findAll();
        $kategori_produk_terkelompok = [];
        foreach ($kategori_produk as $produk) {
            $kategori_produk_terkelompok[$produk['id_kategori_induk']][] = $produk;
        }
        $data['kategori_produk_terkelompok'] = $kategori_produk_terkelompok;

        $data['member'] = $member;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;

        return view('member/edit-profile', $data);
    }

    public function updateFotoProfil()
    {
        // Ambil ID user dari session
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $memberModel = new Member();

        // Ambil data pengguna dari database
        $member = $memberModel->find($userId);

        if (!$member) {
            return redirect()->back()->with('error', 'Data member tidak ditemukan.');
        }

        // Validasi file yang diupload
        if (!$this->validate([
            'foto_profil' => [
                'rules' => 'uploaded[foto_profil]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,8048]',
                'errors' => [
                    'uploaded' => 'Silakan pilih file untuk diupload.',
                    'is_image' => 'File yang diupload harus berupa gambar.',
                    'mime_in' => 'Gambar harus berformat jpg, jpeg, atau png.',
                    'max_size' => 'Ukuran gambar maksimal 2MB.',
                ],
            ],
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Ambil file yang diupload
        $file = $this->request->getFile('foto_profil');
        $newFileName = $file->getRandomName(); // Nama file baru

        if ($file->isValid() && !$file->hasMoved()) {
            $file->move('uploads/foto_usaha/', $newFileName); // Simpan file baru
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload file.');
        }

        // Siapkan data untuk diupdate
        $data = ['foto_profil' => $newFileName];

        // Perbarui data di database
        $updateStatus = $memberModel->update($userId, $data);

        if ($updateStatus) {
            // Hapus file lama jika ada
            $oldFileName = $member['foto_profil'];
            if ($oldFileName && file_exists('uploads/foto_usaha/' . $oldFileName)) {
                unlink('uploads/foto_usaha/' . $oldFileName);
            }

            return redirect()->to('edit-profile')->with('success', 'Foto profil berhasil diperbarui.');
        } else {
            // Jika update gagal, hapus file baru
            if (file_exists('uploads/foto_usaha/' . $newFileName)) {
                unlink('uploads/foto_usaha/' . $newFileName);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function ubah_informasi_akun()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_member = new Member();

        $password = $this->request->getPost('password');

        if ($password !== null) {
            $data = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            $model_member->update($user_id, $data);
        }

        return redirect()->to('edit-profile');
    }

    public function ubah_profil_perusahaan()
    {
        $session = session();
        $user_id = $session->get('user_id');
        $model_member = new Member();

        $fields = [
            'nama_perusahaan',
            'deskripsi_perusahaan',
            'deskripsi_perusahaan_en',
            'alamat_perusahaan',
            'alamat_website',
            'tahun_dibentuk',
            'kategori_produk',
            'kategori_produk_en',
            'produk_utama',
            'produk_utama_en',
            'pic',
            'email',
            'pic_phone',
        ];

        /// Initialize validation rules without individual error messages
        $validationRules = array_fill_keys($fields, [
            'rules' => 'required'
        ]);

        // Perform validation
        if (!$this->validate($validationRules)) {
            // Get all validation errors
            $errors = $this->validator->getErrors();

            // Count the number of missing fields
            $missingCount = count($errors);

            // Set the custom error message with the missing count
            $generalErrorMessage = "Ada $missingCount Input Yang Masih Belum Diisi!";

            // Redirect back with the input and only the general error message
            return redirect()->back()->withInput()->with('errors', ['general' => $generalErrorMessage]);
        }

        // Prepare data for update
        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_perusahaan_en'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            'alamat_website' => $this->request->getPost('alamat_website'),
            'tahun_dibentuk' => $this->request->getPost('tahun_dibentuk'),
            'kategori_produk' => $this->request->getPost('kategori_produk'),
            'kategori_produk_en' => $this->request->getPost('kategori_produk_en'),
            'produk_utama' => $this->request->getPost('produk_utama'),
            'produk_utama_en' => $this->request->getPost('produk_utama_en'),
            'pic' => $this->request->getPost('pic'),
            'email' => $this->request->getPost('email'),
            'pic_phone' => $this->request->getPost('pic_phone'),
        ];

        // Update member's profile
        $model_member->update($user_id, $data);

        return redirect()->to('edit-profile');
    }


    // public function add_sertifikat()
    // {
    //     $session = session();
    //     $user_id = $session->get('user_id');

    //     $model_sertifikat = new Sertifikat();

    //     $fileSertifikat = $this->request->getFile('sertifikat');
    //     $namaFile = null;
    //     if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
    //         $namaFile = uniqid() . '.' . $fileSertifikat->getClientExtension();
    //         $fileSertifikat->move(ROOTPATH . 'public/certificate', $namaFile);
    //     }

    //     $data = [
    //         'id_member' => $user_id,
    //         'sertifikat' => $namaFile,
    //     ];

    //     // Insert data into the database
    //     $model_sertifikat->insert($data);

    //     // Redirect after successful insert
    //     return redirect()->to('/edit-profile');
    // }

    // public function delete_sertifikat($id)
    // {
    //     $session = session();
    //     $user_id = $session->get('user_id'); // Ambil user_id dari sesi

    //     $model_sertifikat = new Sertifikat();
    //     $sertifikat = $model_sertifikat->find($id);

    //     // Cek apakah sertifikat ada dan apakah sertifikat milik user yang sedang login
    //     if ($sertifikat && $sertifikat['id_member'] == $user_id) {
    //         // Hapus file foto sertifikat jika ada
    //         if ($sertifikat['sertifikat'] && file_exists(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat'])) {
    //             unlink(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat']);
    //         }

    //         // Hapus sertifikat dari database
    //         $model_sertifikat->delete($id);

    //         return redirect()->to('/edit-profile')->with('success', 'Sertifikat berhasil dihapus');
    //     } else {
    //         // Redirect dengan pesan error jika sertifikat tidak ditemukan atau tidak dimiliki user yang sedang login
    //         return redirect()->to('/edit-profile')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus sertifikat ini']);
    //     }
    // }

    // public function add_produk()
    // {
    //     $session = session();
    //     $user_id = $session->get('user_id');

    //     $model_produk = new Produk();

    //     $fields = [
    //         'nama_produk',
    //         'deskripsi_produk',
    //         'hs_code',
    //         'minimum_order_qty',
    //         'kapasitas_produksi_bln',
    //     ];

    //     // Set validation rules without `foto_produk` and apply only required rules
    //     $validationRules = array_fill_keys($fields, [
    //         'rules' => 'required'
    //     ]);

    //     // Validate other fields
    //     if (!$this->validate($validationRules)) {
    //         $errors = $this->validator->getErrors();
    //     } else {
    //         $errors = [];
    //     }

    //     // Separate check for `foto_produk`
    //     $fotoProduk = $this->request->getFile('foto_produk');
    //     if (!$fotoProduk || !$fotoProduk->isValid()) {
    //         $errors['foto_produk'] = "Foto produk harus diunggah!";
    //     }

    //     // Count errors and handle response if there are any missing inputs
    //     if (!empty($errors)) {
    //         $missingCount = count($errors);
    //         $generalErrorMessage = "Ada $missingCount Input Yang Masih Belum Diisi!";
    //         return redirect()->back()->withInput()->with('errors', ['general' => $generalErrorMessage]);
    //     }

    //     // Process and move `foto_produk` if uploaded
    //     $namaFile = null;
    //     if ($fotoProduk && $fotoProduk->isValid() && !$fotoProduk->hasMoved()) {
    //         $namaFile = uniqid() . '.' . $fotoProduk->getClientExtension();
    //         $fotoProduk->move(ROOTPATH . 'public/img', $namaFile);
    //     }

    //     // Prepare data for insertion
    //     $data = [
    //         'id_member' => $user_id,
    //         'foto_produk' => $namaFile,
    //         'nama_produk' => $this->request->getPost('nama_produk'),
    //         'nama_produk_en' => $this->request->getPost('nama_produk'),
    //         'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
    //         'deskripsi_produk_en' => $this->request->getPost('deskripsi_produk'),
    //         'hs_code' => $this->request->getPost('hs_code'),
    //         'minimum_order_qty' => $this->request->getPost('minimum_order_qty'),
    //         'kapasitas_produksi_bln' => $this->request->getPost('kapasitas_produksi_bln'),
    //     ];

    //     // Insert data into the database
    //     $model_produk->insert($data);

    //     // Redirect after successful insert
    //     return redirect()->to('/edit-profile');
    // }

    // public function delete_produk($id)
    // {
    //     $session = session();
    //     $user_id = $session->get('user_id'); // Ambil user_id dari sesi

    //     $model_produk = new Produk();
    //     $produk = $model_produk->find($id);

    //     // Cek apakah produk ada dan apakah produk milik user yang sedang login
    //     if ($produk && $produk['id_member'] == $user_id) {
    //         // Hapus file foto produk jika ada
    //         if ($produk['foto_produk'] && file_exists(ROOTPATH . 'public/img/' . $produk['foto_produk'])) {
    //             unlink(ROOTPATH . 'public/img/' . $produk['foto_produk']);
    //         }

    //         // Hapus produk dari database
    //         $model_produk->delete($id);

    //         return redirect()->to('/edit-profile')->with('success', 'Produk berhasil dihapus');
    //     } else {
    //         // Redirect dengan pesan error jika produk tidak ditemukan atau tidak dimiliki user yang sedang login
    //         return redirect()->to('/edit-profile')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
    //     }
    // }

    public function edit_profile_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_member = new Member();
        $model_sertifikat = new Sertifikat();
        $model_produk = new Produk();

        $member = $model_member->where('id_member', $user_id)->first();
        $sertifikat = $model_sertifikat->where('id_member', $user_id)->findAll();
        $produk = $model_produk->where('id_member', $user_id)->findAll();
        $jumlahProduk = $model_produk->where('id_member', $user_id)->countAllResults();

        $data['member'] = $member;
        $data['sertifikat'] = $sertifikat;
        $data['produk'] = $produk;
        $data['jumlahProduk'] = $jumlahProduk;

        return view('premium/edit-profile', $data);
    }

    public function updateFotoProfil_premium()
    {
        // Ambil ID user dari session
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $memberModel = new Member();

        // Ambil data pengguna dari database
        $member = $memberModel->find($userId);

        if (!$member) {
            return redirect()->back()->with('error', 'Data member tidak ditemukan.');
        }

        // Validasi file yang diupload
        if (!$this->validate([
            'foto_profil' => [
                'rules' => 'uploaded[foto_profil]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,8048]',
                'errors' => [
                    'uploaded' => 'Silakan pilih file untuk diupload.',
                    'is_image' => 'File yang diupload harus berupa gambar.',
                    'mime_in' => 'Gambar harus berformat jpg, jpeg, atau png.',
                    'max_size' => 'Ukuran gambar maksimal 2MB.',
                ],
            ],
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // Ambil file yang diupload
        $file = $this->request->getFile('foto_profil');
        $newFileName = $file->getRandomName(); // Nama file baru

        if ($file->isValid() && !$file->hasMoved()) {
            $file->move('img', $newFileName); // Simpan file baru
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload file.');
        }

        // Siapkan data untuk diupdate
        $data = ['foto_profil' => $newFileName];

        // Perbarui data di database
        $updateStatus = $memberModel->update($userId, $data);

        if ($updateStatus) {
            // Hapus file lama jika ada
            $oldFileName = $member['foto_profil'];
            if ($oldFileName && file_exists('img/' . $oldFileName)) {
                unlink('img/' . $oldFileName);
            }

            return redirect()->to('/edit-profile-premium')->with('success', 'Foto profil berhasil diperbarui.');
        } else {
            // Jika update gagal, hapus file baru
            if (file_exists('img/' . $newFileName)) {
                unlink('img/' . $newFileName);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function ubah_informasi_akun_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_member = new Member();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($email != null && $password == null) {
            $data = [
                'email' => $email,
            ];

            $model_member->update($user_id, $data);
        } elseif ($email == null && $password != null) {
            $data = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            $model_member->update($user_id, $data);
        } elseif ($email != null && $password != null) {
            $data = [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            $model_member->update($user_id, $data);
        }

        return redirect()->to('/edit-profile-premium');
    }

    public function ubah_profil_perusahaan_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_member = new Member();

        $fields = [
            'nama_perusahaan',
            'tipe_bisnis',
            'tipe_bisnis_en',
            'deskripsi_perusahaan',
            'deskripsi_perusahaan_en',
            'produk_utama',
            'produk_utama_en',
            'tahun_dibentuk',
            'skala_bisnis',
            'skala_bisnis_en',
            'kategori_produk',
            'kategori_produk_en',
            'pic',
            'pic_phone',
            'latitude',
            'longitude'
        ];

        // Initialize validation rules without individual error messages
        $validationRules = array_fill_keys($fields, [
            'rules' => 'required'
        ]);

        // Perform validation
        if (!$this->validate($validationRules)) {
            // Get all validation errors
            $errors = $this->validator->getErrors();

            // Count the number of missing fields
            $missingCount = count($errors);

            // Set the custom error message with the missing count
            $generalErrorMessage = "Ada $missingCount Input Yang Masih Belum Diisi!";

            // Redirect back with the input and only the general error message
            return redirect()->back()->withInput()->with('errors', ['general' => $generalErrorMessage]);
        }

        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'tipe_bisnis' => $this->request->getPost('tipe_bisnis'),
            'tipe_bisnis_en' => $this->request->getPost('tipe_bisnis_en'),
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_perusahaan_en'),
            'produk_utama' => $this->request->getPost('produk_utama'),
            'produk_utama_en' => $this->request->getPost('produk_utama_en'),
            'tahun_dibentuk' => $this->request->getPost('tahun_dibentuk'),
            'skala_bisnis' => $this->request->getPost('skala_bisnis'),
            'skala_bisnis_en' => $this->request->getPost('skala_bisnis_en'),
            'kategori_produk' => $this->request->getPost('kategori_produk'),
            'kategori_produk_en' => $this->request->getPost('kategori_produk_en'),
            'pic' => $this->request->getPost('pic'),
            'pic_phone' => $this->request->getPost('pic_phone'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];

        $model_member->update($user_id, $data);

        return redirect()->to('/edit-profile-premium');
    }

    public function add_sertifikat_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_sertifikat = new Sertifikat();

        $fileSertifikat = $this->request->getFile('sertifikat');
        $namaFile = null;
        if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            $namaFile = uniqid() . '.' . $fileSertifikat->getClientExtension();
            $fileSertifikat->move(ROOTPATH . 'public/certificate', $namaFile);
        }

        $data = [
            'id_member' => $user_id,
            'sertifikat' => $namaFile,
            'nama_sertifikat' => $this->request->getPost('nama_sertifikat'),
            'nama_sertifikat_en' => $this->request->getPost('nama_sertifikat_en'),
        ];

        // Insert data into the database
        $model_sertifikat->insert($data);

        // Redirect after successful insert
        return redirect()->to('/edit-profile-premium');
    }

    public function delete_sertifikat_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id'); // Ambil user_id dari sesi

        $model_sertifikat = new Sertifikat();
        $sertifikat = $model_sertifikat->find($id);

        // Cek apakah sertifikat ada dan apakah sertifikat milik user yang sedang login
        if ($sertifikat && $sertifikat['id_member'] == $user_id) {
            // Hapus file foto sertifikat jika ada
            if ($sertifikat['sertifikat'] && file_exists(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat'])) {
                unlink(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat']);
            }

            // Hapus sertifikat dari database
            $model_sertifikat->delete($id);

            return redirect()->to('/edit-profile-premium')->with('success', 'Sertifikat berhasil dihapus');
        } else {
            // Redirect dengan pesan error jika sertifikat tidak ditemukan atau tidak dimiliki user yang sedang login
            return redirect()->to('/edit-profile-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus sertifikat ini']);
        }
    }

    public function add_produk_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_produk = new Produk();

        $fields = [
            'nama_produk',
            'nama_produk_en',
            'deskripsi_produk',
            'deskripsi_produk_en',
            'hs_code',
            'minimum_order_qty',
            'kapasitas_produksi_bln',
        ];

        // Set validation rules without `foto_produk` and apply only required rules
        $validationRules = array_fill_keys($fields, [
            'rules' => 'required'
        ]);

        // Validate other fields
        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
        } else {
            $errors = [];
        }

        // Separate check for `foto_produk`
        $fotoProduk = $this->request->getFile('foto_produk');
        if (!$fotoProduk || !$fotoProduk->isValid()) {
            $errors['foto_produk'] = "Foto produk harus diunggah!";
        }

        // Count errors and handle response if there are any missing inputs
        if (!empty($errors)) {
            $missingCount = count($errors);
            $generalErrorMessage = "Ada $missingCount Input Yang Masih Belum Diisi!";
            return redirect()->back()->withInput()->with('errors', ['general' => $generalErrorMessage]);
        }

        // Process and move `foto_produk` if uploaded
        $namaFile = null;
        if ($fotoProduk && $fotoProduk->isValid() && !$fotoProduk->hasMoved()) {
            $namaFile = uniqid() . '.' . $fotoProduk->getClientExtension();
            $fotoProduk->move(ROOTPATH . 'public/img', $namaFile);
        }

        // Prepare data for insertion
        $data = [
            'id_member' => $user_id,
            'foto_produk' => $namaFile,
            'nama_produk' => $this->request->getPost('nama_produk'),
            'nama_produk_en' => $this->request->getPost('nama_produk_en'),
            'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
            'deskripsi_produk_en' => $this->request->getPost('deskripsi_produk_en'),
            'hs_code' => $this->request->getPost('hs_code'),
            'minimum_order_qty' => $this->request->getPost('minimum_order_qty'),
            'kapasitas_produksi_bln' => $this->request->getPost('kapasitas_produksi_bln'),
        ];

        // Insert data into the database
        $model_produk->insert($data);

        // Redirect after successful insert
        return redirect()->to('/edit-profile-premium');
    }

    public function delete_produk_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id'); // Ambil user_id dari sesi

        $model_produk = new Produk();
        $produk = $model_produk->find($id);

        // Cek apakah produk ada dan apakah produk milik user yang sedang login
        if ($produk && $produk['id_member'] == $user_id) {
            // Hapus file foto produk jika ada
            if ($produk['foto_produk'] && file_exists(ROOTPATH . 'public/img/' . $produk['foto_produk'])) {
                unlink(ROOTPATH . 'public/img/' . $produk['foto_produk']);
            }

            // Hapus produk dari database
            $model_produk->delete($id);

            return redirect()->to('/edit-profile-premium')->with('success', 'Produk berhasil dihapus');
        } else {
            // Redirect dengan pesan error jika produk tidak ditemukan atau tidak dimiliki user yang sedang login
            return redirect()->to('/edit-profile-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function update_warna_landing_premium()
    {
        $session = session();
        $user_id = $session->get('user_id'); // Ambil user_id dari sesi

        if (!$user_id) {
            return redirect()->to('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        $member_model = new Member();

        // Ambil input warna
        $primaryColor = $this->request->getPost('primaryColor');
        $secondaryColor = $this->request->getPost('secondaryColor');

        // Validasi warna
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $primaryColor) || !preg_match('/^#[0-9A-Fa-f]{6}$/', $secondaryColor)) {
            return redirect()->back()->with('error', 'Format warna tidak valid.');
        }

        // Data awal untuk disimpan
        $data = [
            'warna_utama' => $primaryColor,
            'warna_sekunder' => $secondaryColor,
        ];

        // Cek keberadaan user sebelum update
        $existingUser = $member_model->find($user_id);

        if (!$existingUser) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        // Proses upload file (gambar utama dan perusahaan)
        $uploadFields = [
            'gambar_utama' => 'gambar_utama',
            'gambar_perusahaan' => 'gambar_perusahaan'
        ];

        foreach ($uploadFields as $inputName => $dbField) {
            $file = $this->request->getFile($inputName);

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move('img/', $fileName);

                // Hapus gambar lama jika ada
                if (!empty($existingUser[$dbField]) && file_exists(FCPATH . 'img/' . $existingUser[$dbField])) {
                    unlink(FCPATH . 'img/' . $existingUser[$dbField]);
                }

                // Tambahkan nama file baru ke data
                $data[$dbField] = $fileName;
            }
        }

        // Simpan data ke database
        if ($member_model->update($user_id, $data)) {
            return redirect()->back()->with('success', 'Landing page berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui landing page.');
        }
    }

    public function index_kalkulator()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();
        $model_exwork     = new Exwork();
        $model_fob        = new FOB();
        $model_cfr        = new CFR();
        $model_cif        = new CIF();
        $model_satuan     = new Satuan();
        $model_state      = new KalkulatorState();
        $model_ukuran     = new UkuranKontainer();

        $data['webprofile'] = $model_webprofile->findAll();
        $data['exwork'] = $model_exwork->where('id_member', $user_id)->findAll();
        $data['fob']    = $model_fob->where('id_member', $user_id)->findAll();
        $data['cfr']    = $model_cfr->where('id_member', $user_id)->findAll();
        $data['cif']    = $model_cif->where('id_member', $user_id)->findAll();

        // Pastikan satuan selalu ada 1 baris per user
        $rowSatuan = $model_satuan->where('id_member', $user_id)->first();

        $data['satuanRow']   = $rowSatuan;
        $data['labelSatuan'] = $rowSatuan['satuan'] ?? '';
        $data['idSatuan']    = $rowSatuan['id_satuan'] ?? 0;

        // Ukuran kontainer (aktif saja)
        $data['ukuranKontainer'] = $model_ukuran
            ->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();

        // Server state
        $state = $model_state->where('id_member', $user_id)->first();
        $data['state'] = $state ?: [
            'nama_produk'   => '',
            'jumlah_barang' => 0,
            'hpp'           => 0,
            'keuntungan'    => 0,
        ];

        $data['user_id'] = $user_id;
        return view('member/kalkulator-ekspor/kalkulator_ekspor', $data);
    }

    public function save_kalkulator_state()
    {
        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON(['ok' => false, 'msg' => 'Method Not Allowed']);
        }

        $user_id = session()->get('user_id');
        if (!$user_id) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'msg' => 'Unauthorized']);
        }

        $payload = [
            'nama_produk'       => trim((string)$this->request->getPost('nama_produk')),
            'jumlah_barang'     => (int)($this->request->getPost('jumlah_barang') ?? 0),
            'hpp'               => (int)($this->request->getPost('hpp') ?? 0),
            'keuntungan'        => (int)($this->request->getPost('keuntungan') ?? 0),
            'ukuran_kontainer'  => trim((string)$this->request->getPost('ukuran_kontainer') ?? ''),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $model = new KalkulatorState();
        $exists = $model->where('id_member', $user_id)->first();

        if ($exists) {
            $model->update($exists['id_state'], $payload);
        } else {
            $payload['id_member'] = $user_id;
            $model->insert($payload);
        }

        return $this->response->setJSON(['ok' => true]);
    }

    public function load_kalkulator_state()
    {
        $user_id = session()->get('user_id');
        if (!$user_id) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'msg' => 'Unauthorized']);
        }

        $model = new KalkulatorState();
        $row = $model->where('id_member', $user_id)->first();

        return $this->response->setJSON([
            'ok' => true,
            'data' => $row ?: [
                'nama_produk'       => '',
                'jumlah_barang'     => 0,
                'hpp'               => 0,
                'keuntungan'        => 0,
                'ukuran_kontainer'  => '',
            ],
        ]);
    }

    // public function ganti_satuan($id)
    // {
    //     $session = session();
    //     $user_id = $session->get('user_id');

    //     $model_satuan = new Satuan();

    //     $satuan = $model_satuan->find($id);

    //     if ($satuan) {
    //         $data = [
    //             'id_member' => $user_id,
    //             'satuan' => $this->request->getPost('satuan'),
    //         ];

    //         $model_satuan->update($id, $data);

    //         return redirect()->to('/kalkulator-ekspor')->with('success', 'Produk berhasil disimpan!');
    //     } else {
    //         return redirect()->to('/kalkulator-ekspor')->with('error', 'Produk gagal disimpan!');
    //     }
    // }

    // public function save_satuan()
    // {
    //     if (!$this->request->is('post')) {
    //         return redirect()->back()->with('error', 'Method tidak valid.');
    //     }

    //     $user_id = session()->get('user_id');
    //     if (!$user_id) {
    //         return redirect()->to('/kalkulator-ekspor')->with('error', 'Unauthorized');
    //     }

    //     $satuan = trim((string)$this->request->getPost('satuan'));

    //     $model = new Satuan();
    //     $row   = $model->where('id_member', $user_id)->first();

    //     if ($satuan === '') {
    //         if ($row) {
    //             $model->update($row['id_satuan'], ['satuan' => '']);
    //         } else {
    //             $model->insert(['id_member' => $user_id, 'satuan' => '']);
    //         }
    //         return redirect()->to('/kalkulator-ekspor')->with('success', 'Satuan berhasil diperbarui.');
    //     }

    //     // Kalau ada nilai → simpan/perbarui seperti biasa
    //     if ($row) {
    //         $model->update($row['id_satuan'], ['satuan' => $satuan]);
    //         $msg = 'Satuan berhasil diperbarui ✅';
    //     } else {
    //         $model->insert(['id_member' => $user_id, 'satuan' => $satuan]);
    //         $msg = 'Satuan baru berhasil disimpan ✅';
    //     }

    //     return redirect()->to('/kalkulator-ekspor')->with('success', $msg);
    // }

    public function kalkulator_state_json()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Jika belum login / tak ada user id, balas kosong
        if (!$user_id) {
            return $this->response->setJSON([
                'jumlah_barang' => null,
                'hpp'           => null,
                'keuntungan'    => null,
                'csrf_token'    => csrf_hash(),
            ]);
        }

        $model = new \App\Models\KalkulatorState();
        $row   = $model->where('id_member', $user_id)->first();

        return $this->response->setJSON([
            'jumlah_barang' => $row['jumlah_barang'] ?? null,
            'hpp'           => $row['hpp'] ?? null,
            'keuntungan'    => $row['keuntungan'] ?? null,
            'csrf_token'    => csrf_hash(),
        ]);
    }

    // POST: simpan/update state (upsert)
    public function kalkulator_state_upsert()
    {
        $session = session();
        $user_id = $session->get('user_id');

        if (!$user_id) {
            return $this->response->setStatusCode(401)
                ->setJSON(['message' => 'Unauthorized', 'csrf_token' => csrf_hash()]);
        }

        // ambil raw nilai; biarkan kosong jadi null
        $jumlah = $this->request->getPost('jumlah_barang');
        $hpp    = $this->request->getPost('hpp');
        $untung = $this->request->getPost('keuntungan');

        // sanit angka: buang non-digit; kosong -> null
        $clean = function ($v) {
            if ($v === null || $v === '') return null;
            $v = preg_replace('/[^\d]/', '', (string)$v);
            return $v === '' ? null : (int)$v;
        };

        $jumlah = $clean($jumlah);
        $hpp    = $clean($hpp);
        $untung = $clean($untung);

        $model = new \App\Models\KalkulatorState();
        $row   = $model->where('id_member', $user_id)->first();

        $data = [
            'id_member'     => $user_id,
            'jumlah_barang' => $jumlah,
            'hpp'           => $hpp,
            'keuntungan'    => $untung,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        if ($row) {
            // pk kamu: id_state
            $model->update($row['id_state'], $data);
        } else {
            $model->insert($data);
        }

        return $this->response->setJSON(['ok' => true, 'csrf_token' => csrf_hash()]);
    }

    public function upsert_satuan_json()
    {
        if (!$this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON(['ok' => false, 'msg' => 'Method Not Allowed']);
        }

        $user_id = session()->get('user_id');
        if (!$user_id) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'msg' => 'Unauthorized']);
        }

        $satuan = trim((string)$this->request->getPost('satuan'));
        $model  = new \App\Models\Satuan();
        $row    = $model->where('id_member', $user_id)->first();

        if ($row) {
            $model->update($row['id_satuan'], ['satuan' => $satuan]);
        } else {
            $model->insert(['id_member' => $user_id, 'satuan' => $satuan]);
        }

        return $this->response->setJSON(['ok' => true, 'satuan' => $satuan]);
    }

    public function add_exwork()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenExwork.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenExwork
        $komponenExworkArray = $this->request->getPost('komponenExwork');

        $model_exwork = new Exwork();

        // Loop through the array and insert each komponenExwork into the database
        foreach ($komponenExworkArray as $komponenExwork) {
            $data = [
                'id_member' => $user_id,
                'komponen_exwork' => esc($komponenExwork),  // Sanitize the input
            ];
            $model_exwork->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor#exwork')->with('success', 'Komponen Exwork berhasil ditambahkan!');
    }

    public function save_all_exwork()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $request = $this->request;

        $model = new \App\Models\Exwork();

        // --- 1) UPDATE biaya EXISTING ---
        // Cari semua field yang namanya exwork_{id}
        $post = $request->getPost();
        $updated = 0;

        foreach ($post as $key => $val) {
            if (preg_match('/^exwork_(\d+)$/', $key, $m)) {
                $id = (int) $m[1];
                $biaya = (int) preg_replace('/[^\d]/', '', (string) $val);

                // Validasi kepemilikan
                $row = $model->find($id);
                if ($row && (int)$row['id_member'] === (int)$user_id) {
                    // Update kolom biaya
                    $model->update($id, ['biaya' => $biaya]);
                    $updated++;
                }
            }
        }

        // --- 2) INSERT komponen BARU (jika ada) ---
        $kompBaru = (array) $request->getPost('komponenExwork');
        $biayaBaru = (array) $request->getPost('biayaExwork');

        $inserted = 0;
        if (!empty($kompBaru) && !empty($biayaBaru)) {
            $len = min(count($kompBaru), count($biayaBaru));
            for ($i = 0; $i < $len; $i++) {
                $nama  = trim((string) ($kompBaru[$i] ?? ''));
                $biaya = (int) preg_replace('/[^\d]/', '', (string) ($biayaBaru[$i] ?? ''));

                if ($nama !== '' && $biaya >= 0) {
                    $model->insert([
                        'id_member'       => $user_id,
                        'komponen_exwork' => esc($nama),
                        'biaya'           => $biaya,
                    ]);
                    $inserted++;
                }
            }
        }

        return redirect()->to('/kalkulator-ekspor#exwork')
            ->with('success', "Komponen Exwork Berhasil Disimpan.");
    }

    public function delete_exwork($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_exwork = new Exwork();

        $exwork = $model_exwork->find($id);

        if ($exwork && $exwork['id_member'] == $user_id) {
            $model_exwork->delete($id);
            return redirect()->to('/kalkulator-ekspor#exwork')->with('success', 'Komponen Exwork berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor#exwork')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_fob()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenFOB.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenFOB
        $komponenFOBArray = $this->request->getPost('komponenFOB');

        $model_fob = new FOB();

        // Loop through the array and insert each komponenFOB into the database
        foreach ($komponenFOBArray as $komponenFOB) {
            $data = [
                'id_member' => $user_id,
                'komponen_fob' => esc($komponenFOB),  // Sanitize the input
            ];
            $model_fob->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor#fob')->with('success', 'Komponen FOB berhasil ditambahkan!');
    }

    public function save_all_fob()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $request = $this->request;
        $model   = new FOB();
        $post    = $request->getPost();

        $updated = 0;

        // Update biaya existing (tanpa cast int)
        foreach ($post as $key => $val) {
            if (preg_match('/^fob_(\d+)$/', $key, $m)) {
                $id    = (int) $m[1];
                $biaya = preg_replace('/[^\d]/', '', (string) $val);

                $row = $model->find($id);
                if ($row && (int)$row['id_member'] === (int)$user_id) {
                    $model->update($id, ['biaya' => $biaya]);
                    $updated++;
                }
            }
        }

        // Insert komponen baru (tanpa cast int)
        $kompBaru  = (array) $request->getPost('komponenFOB');
        $biayaBaru = (array) $request->getPost('biayaFOB');

        $inserted = 0;
        if (!empty($kompBaru) && !empty($biayaBaru)) {
            $len = min(count($kompBaru), count($biayaBaru));
            for ($i = 0; $i < $len; $i++) {
                $nama  = trim((string) ($kompBaru[$i] ?? ''));
                $biaya = preg_replace('/[^\d]/', '', (string) ($biayaBaru[$i] ?? ''));

                if ($nama !== '' && $biaya !== '') {
                    $model->insert([
                        'id_member'    => $user_id,
                        'komponen_fob' => esc($nama),
                        'biaya'        => $biaya,
                    ]);
                    $inserted++;
                }
            }
        }

        return redirect()->to('/kalkulator-ekspor#fob')
            ->with('success', "Komponen FOB Berhasil Disimpan.");
    }

    public function delete_fob($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_fob = new FOB();

        $fob = $model_fob->find($id);

        if ($fob && $fob['id_member'] == $user_id) {
            $model_fob->delete($id);
            return redirect()->to('/kalkulator-ekspor#fob')->with('success', 'Komponen FOB berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor#fob')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_cfr()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenCFR.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenCFR
        $komponenCFRArray = $this->request->getPost('komponenCFR');

        $model_cfr = new CFR();

        // Loop through the array and insert each komponenCFR into the database
        foreach ($komponenCFRArray as $komponenCFR) {
            $data = [
                'id_member' => $user_id,
                'komponen_cfr' => esc($komponenCFR),  // Sanitize the input
            ];
            $model_cfr->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor#cfr')->with('success', 'Komponen CFR berhasil ditambahkan!');
    }

    public function save_all_cfr()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $request = $this->request;
        $model   = new CFR();
        $post    = $request->getPost();

        $updated = 0;

        // Update biaya existing (tanpa cast int)
        foreach ($post as $key => $val) {
            if (preg_match('/^cfr_(\d+)$/', $key, $m)) {
                $id    = (int) $m[1];
                $biaya = preg_replace('/[^\d]/', '', (string) $val);

                $row = $model->find($id);
                if ($row && (int)$row['id_member'] === (int)$user_id) {
                    $model->update($id, ['biaya' => $biaya]);
                    $updated++;
                }
            }
        }

        // Insert komponen baru (tanpa cast int)
        $kompBaru  = (array) $request->getPost('komponenCFR');
        $biayaBaru = (array) $request->getPost('biayaCFR');

        $inserted = 0;
        if (!empty($kompBaru) && !empty($biayaBaru)) {
            $len = min(count($kompBaru), count($biayaBaru));
            for ($i = 0; $i < $len; $i++) {
                $nama  = trim((string) ($kompBaru[$i] ?? ''));
                $biaya = preg_replace('/[^\d]/', '', (string) ($biayaBaru[$i] ?? ''));

                if ($nama !== '' && $biaya !== '') {
                    $model->insert([
                        'id_member'    => $user_id,
                        'komponen_cfr' => esc($nama),
                        'biaya'        => $biaya,
                    ]);
                    $inserted++;
                }
            }
        }

        return redirect()->to('/kalkulator-ekspor#cfr')
            ->with('success', "Komponen CFR Berhasil Disimpan.");
    }

    public function delete_cfr($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_cfr = new CFR();

        $cfr = $model_cfr->find($id);

        if ($cfr && $cfr['id_member'] == $user_id) {
            $model_cfr->delete($id);
            return redirect()->to('/kalkulator-ekspor#cfr')->with('success', 'Komponen CFR berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor#cfr')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_cif()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenCIF.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenCIF
        $komponenCIFArray = $this->request->getPost('komponenCIF');

        $model_cif = new CIF();

        // Loop through the array and insert each komponenCIF into the database
        foreach ($komponenCIFArray as $komponenCIF) {
            $data = [
                'id_member' => $user_id,
                'komponen_cif' => esc($komponenCIF),  // Sanitize the input
            ];
            $model_cif->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor#cif')->with('success', 'Komponen CIF berhasil ditambahkan!');
    }

    public function save_all_cif()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $request = $this->request;
        $model   = new CIF();
        $post    = $request->getPost();

        $updated = 0;

        // Update biaya existing (tanpa cast int)
        foreach ($post as $key => $val) {
            if (preg_match('/^cif_(\d+)$/', $key, $m)) {
                $id    = (int) $m[1];
                $biaya = preg_replace('/[^\d]/', '', (string) $val);

                $row = $model->find($id);
                if ($row && (int)$row['id_member'] === (int)$user_id) {
                    $model->update($id, ['biaya' => $biaya]);
                    $updated++;
                }
            }
        }

        // Insert komponen baru (tanpa cast int)
        $kompBaru  = (array) $request->getPost('komponenCIF');
        $biayaBaru = (array) $request->getPost('biayaCIF');

        $inserted = 0;
        if (!empty($kompBaru) && !empty($biayaBaru)) {
            $len = min(count($kompBaru), count($biayaBaru));
            for ($i = 0; $i < $len; $i++) {
                $nama  = trim((string) ($kompBaru[$i] ?? ''));
                $biaya = preg_replace('/[^\d]/', '', (string) ($biayaBaru[$i] ?? ''));

                if ($nama !== '' && $biaya !== '') {
                    $model->insert([
                        'id_member'    => $user_id,
                        'komponen_cif' => esc($nama),
                        'biaya'        => $biaya,
                    ]);
                    $inserted++;
                }
            }
        }

        return redirect()->to('/kalkulator-ekspor#cif')
            ->with('success', "Komponen CIF Berhasil Disimpan.");
    }

    public function delete_cif($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_cif = new CIF();

        $cif = $model_cif->find($id);

        if ($cif && $cif['id_member'] == $user_id) {
            $model_cif->delete($id);
            return redirect()->to('/kalkulator-ekspor#cif')->with('success', 'Komponen CIF berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor#cif')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }


    public function index_kalkulator_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_exwork = new Exwork();
        $model_fob = new FOB();
        $model_cfr = new CFR();
        $model_cif = new CIF();
        $model_satuan = new Satuan();

        $exwork = $model_exwork->where('id_member', $user_id)->findAll();
        $fob = $model_fob->where('id_member', $user_id)->findAll();
        $cfr = $model_cfr->where('id_member', $user_id)->findAll();
        $cif = $model_cif->where('id_member', $user_id)->findAll();
        $satuan = $model_satuan->where('id_member', $user_id)->findAll();

        $data['exwork'] = $exwork;
        $data['fob'] = $fob;
        $data['cfr'] = $cfr;
        $data['cif'] = $cif;
        $data['satuan'] = $satuan;

        return view('premium/kalkulator-ekspor/kalkulator_ekspor', $data);
    }

    public function ganti_satuan_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_satuan = new Satuan();

        // Mencari satuan berdasarkan ID
        $satuan = $model_satuan->find($id);

        // Jika satuan ditemukan, lakukan update
        if ($satuan) {
            // Mengambil input dari form
            $data = [
                'id_member' => $user_id,
                'satuan' => $this->request->getPost('satuan'),
            ];

            // Melakukan update data pada model
            $model_satuan->update($id, $data);

            // Redirect setelah update berhasil
            return redirect()->to('/kalkulator-ekspor-premium');
        } else {
            // Jika data tidak ditemukan, bisa diarahkan ke halaman error
            return redirect()->to('/kalkulator-ekspor-premium')->with('error', 'Data satuan tidak ditemukan.');
        }
    }

    public function add_exwork_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenExwork.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenExwork
        $komponenExworkArray = $this->request->getPost('komponenExwork');

        $model_exwork = new Exwork();

        // Loop through the array and insert each komponenExwork into the database
        foreach ($komponenExworkArray as $komponenExwork) {
            $data = [
                'id_member' => $user_id,
                'komponen_exwork' => esc($komponenExwork),  // Sanitize the input
            ];
            $model_exwork->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Komponen Exwork berhasil ditambahkan!');
    }

    public function delete_exwork_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_exwork = new Exwork();

        $exwork = $model_exwork->find($id);

        if ($exwork && $exwork['id_member'] == $user_id) {
            $model_exwork->delete($id);
            return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_fob_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenFOB.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenFOB
        $komponenFOBArray = $this->request->getPost('komponenFOB');

        $model_fob = new FOB();

        // Loop through the array and insert each komponenFOB into the database
        foreach ($komponenFOBArray as $komponenFOB) {
            $data = [
                'id_member' => $user_id,
                'komponen_fob' => esc($komponenFOB),  // Sanitize the input
            ];
            $model_fob->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Komponen FOB berhasil ditambahkan!');
    }

    public function delete_fob_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_fob = new FOB();

        $fob = $model_fob->find($id);

        if ($fob && $fob['id_member'] == $user_id) {
            $model_fob->delete($id);
            return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_cfr_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenCFR.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenCFR
        $komponenCFRArray = $this->request->getPost('komponenCFR');

        $model_cfr = new CFR();

        // Loop through the array and insert each komponenCFR into the database
        foreach ($komponenCFRArray as $komponenCFR) {
            $data = [
                'id_member' => $user_id,
                'komponen_cfr' => esc($komponenCFR),  // Sanitize the input
            ];
            $model_cfr->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Komponen CFR berhasil ditambahkan!');
    }

    public function delete_cfr_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_cfr = new CFR();

        $cfr = $model_cfr->find($id);

        if ($cfr && $cfr['id_member'] == $user_id) {
            $model_cfr->delete($id);
            return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function add_cif_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'komponenCIF.*' => 'required',  // Ensure each component is required
        ]);

        if (!$this->validate($validation->getRules())) {
            // If validation fails, redirect back with errors
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Get the array of komponenCIF
        $komponenCIFArray = $this->request->getPost('komponenCIF');

        $model_cif = new CIF();

        // Loop through the array and insert each komponenCIF into the database
        foreach ($komponenCIFArray as $komponenCIF) {
            $data = [
                'id_member' => $user_id,
                'komponen_cif' => esc($komponenCIF),  // Sanitize the input
            ];
            $model_cif->insert($data);
        }

        return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Komponen CIF berhasil ditambahkan!');
    }

    public function delete_cif_premium($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_cif = new CIF();

        $cif = $model_cif->find($id);

        if ($cif && $cif['id_member'] == $user_id) {
            $model_cif->delete($id);
            return redirect()->to('/kalkulator-ekspor-premium')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->to('/kalkulator-ekspor-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus produk ini']);
        }
    }

    public function pengumuman()
    {
        $model_webprofile = new WebProfile();
        $model_pengumuman = new Pengumuman();

        $pengumuman = $model_pengumuman->get_active_pengumuman();
        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;
        $data['pengumuman'] = $pengumuman;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        return view('member/pengumuman/pengumuman', $data);
    }

    public function pengumuman_premium()
    {
        $model_webprofile = new WebProfile();
        $model_pengumuman = new Pengumuman();

        $pengumuman = $model_pengumuman->get_active_pengumuman();
        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;
        $data['pengumuman'] = $pengumuman;

        return view('premium/pengumuman/pengumuman', $data);
    }

    public function detail_pengumuman($slug = null)
    {
        $model_webprofile = new WebProfile();
        $model_pengumuman = new Pengumuman();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;
        $data['pengumuman'] = $model_pengumuman->where('slug', $slug)->first();
        // Mendapatkan pengumuman lainnya, selain yang sedang dibuka
        $data['pengumuman_lainnya'] = $model_pengumuman->where('slug !=', $slug)->get_active_pengumuman(3); // Limit untuk 3 pengumuman lainnya

        return view('member/pengumuman/detail-pengumuman', $data);
    }

    public function detail_pengumuman_premium($slug = null)
    {
        $model_webprofile = new WebProfile();
        $model_pengumuman = new Pengumuman();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;
        $data['pengumuman'] = $model_pengumuman->where('slug', $slug)->first();
        // Mendapatkan pengumuman lainnya, selain yang sedang dibuka
        $data['pengumuman_lainnya'] = $model_pengumuman->where('slug !=', $slug)->get_active_pengumuman(3); // Limit untuk 3 pengumuman lainnya

        return view('premium/pengumuman/detail-pengumuman', $data);
    }

    public function mpm()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_mpm = new MPM();

        // Cari tahun paling lama yang ada di database
        $oldest_year = $model_mpm
            ->select('YEAR(tgl_kirim_email) as tahun_kirim')
            ->orderBy('tahun_kirim', 'ASC') // Ambil yang terlama
            ->first(); // Hanya ambil satu record (tahun terlama)

        // Jika data ada, set tahun terlama dari database, jika tidak, default ke tahun sekarang
        $start_year = isset($oldest_year['tahun_kirim']) ? $oldest_year['tahun_kirim'] : date('Y');

        $current_year = date('Y'); // Tahun sekarang

        // Buat array tahun dari tahun terlama hingga tahun saat ini
        $years = [];
        for ($year = $start_year; $year <= $current_year; $year++) {
            $years[] = $year;
        }

        // Balik array agar tahun terbaru ada di atas
        $years = array_reverse($years);

        // // Ambil data dari database hanya untuk tahun-tahun yang ada
        // $mpm_data = $model_mpm
        //     ->select('YEAR(tgl_kirim_email) as tahun_kirim, COUNT(*) as jumlah') // Hitung jumlah data per tahun
        //     ->groupBy('tahun_kirim')
        //     ->orderBy('tahun_kirim', 'DESC')
        //     ->findAll();

        // // Buat array untuk memetakan data dari database berdasarkan tahun
        // $mpm_year = [];
        // foreach ($mpm_data as $data) {
        //     $mpm_year[$data['tahun_kirim']] = $data['jumlah']; // Simpan jumlah data per tahun
        // }

        // Set pagination
        $perPage = 10; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        $mpmtable = $model_mpm->where('id_member', $user_id)->paginate($perPage);

        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        foreach ($mpmtable as &$item) {
            // Mengubah format tgl_kirim_email
            $tgl_kirim = date('d F Y', strtotime($item['tgl_kirim_email']));
            $bulanInggris_kirim = date('F', strtotime($item['tgl_kirim_email']));
            $item['tgl_kirim_email'] = str_replace($bulanInggris_kirim, $bulanIndonesia[$bulanInggris_kirim], $tgl_kirim);

            // Memeriksa jika update_terakhir bernilai null
            if (is_null($item['update_terakhir'])) {
                $item['update_terakhir'] = '';
            } else {
                // Mengubah format update_terakhir jika tidak null
                $tgl_update = date('d F Y', strtotime($item['update_terakhir']));
                $bulanInggris_update = date('F', strtotime($item['update_terakhir']));
                $item['update_terakhir'] = str_replace($bulanInggris_update, $bulanIndonesia[$bulanInggris_update], $tgl_update);
            }
        }

        $data['mpmtable'] = $mpmtable;
        $data['pager'] = $model_mpm->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;
        $data['years'] = $years; // Semua tahun dari yang terlama sampai sekarang, dengan urutan terbaru di atas
        // $data['mpm_year'] = $mpm_year; // Data dari database

        return view('member/mpm/mpm', $data);
    }


    public function add_mpm()
    {
        $session = session();
        $user_id = $session->get('user_id');


        $tgl_kirim_email = $this->request->getPost('tgl_kirim_email');

        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tgl = date('d F Y', strtotime($tgl_kirim_email));
        $bulanInggris = date('F', strtotime($tgl_kirim_email));
        $tgl_kirim_email = str_replace($bulanInggris, $bulanIndonesia[$bulanInggris], $tgl);

        $data = [
            'id_member' => $user_id,
            'tgl_kirim_email' => $this->request->getPost('tgl_kirim_email'),
            'update_terakhir' => NULL,
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'negara_perusahaan' => $this->request->getPost('negara_perusahaan'),
            'status_progres' => $this->request->getPost('status_progres'),
            'progres' => '1. Mengirim email pada tanggal ' . $tgl_kirim_email,
        ];

        $model_mpm = new MPM();
        $model_mpm->insert($data);

        session()->setFlashdata('success', 'Data berhasil disimpan!');

        return redirect()->to('/mpm');
    }

    public function edit_mpm()
    {
        $model_mpm = new MPM();

        // Ambil ID MPM yang akan diedit
        $id_mpm = $this->request->getPost('id_mpm');

        // Ambil ID user yang sedang login
        $session = session();
        $user_id = $session->get('user_id');  // Sesuaikan dengan session yang kamu pakai

        // Cari data MPM berdasarkan ID MPM
        $mpm = $model_mpm->find($id_mpm);

        // Pastikan data ditemukan
        if (!$mpm) {
            return redirect()->to('/mpm')->withInput()->with('errors', ['MPM tidak ditemukan.']);
        }

        // Cek apakah user yang sedang login adalah pemilik MPM tersebut
        if ($mpm['id_member'] != $user_id) {
            // Jika ID user yang sedang login tidak sama dengan id_member MPM, larang akses
            return redirect()->to('/mpm')->withInput()->with('errors', ['Anda tidak memiliki izin untuk mengedit MPM ini.']);
        }

        // Jika lolos pengecekan, lanjutkan untuk mengupdate MPM
        $now = Time::now();
        $data = [
            'update_terakhir' => $now,
            'progres' => $this->request->getPost('progres'),
        ];

        $model_mpm->update($id_mpm, $data);

        return redirect()->to('/mpms')->with('success', 'MPM telah berhasil diperbarui.');
    }

    public function getEmailsByDate($month, $year)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_mpm = new MPM();

        // Ambil jumlah email yang dikirim per tanggal dalam bulan dan tahun tertentu
        $result = $model_mpm
            ->select('DAY(tgl_kirim_email) as hari, COUNT(*) as jumlah_email')
            ->where('id_member', $user_id)
            ->where('MONTH(tgl_kirim_email)', $month)
            ->where('YEAR(tgl_kirim_email)', $year)
            ->groupBy('hari')
            ->findAll();

        // Buat array dengan format [hari => jumlah_email]
        $emailData = [];
        foreach ($result as $row) {
            $emailData[$row['hari']] = $row['jumlah_email'];
        }

        return $this->response->setJSON($emailData);
    }

    public function mpm_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_mpm = new MPM();

        // Cari tahun paling lama yang ada di database
        $oldest_year = $model_mpm
            ->select('YEAR(tgl_kirim_email) as tahun_kirim')
            ->orderBy('tahun_kirim', 'ASC') // Ambil yang terlama
            ->first(); // Hanya ambil satu record (tahun terlama)

        // Jika data ada, set tahun terlama dari database, jika tidak, default ke tahun sekarang
        $start_year = isset($oldest_year['tahun_kirim']) ? $oldest_year['tahun_kirim'] : date('Y');

        $current_year = date('Y'); // Tahun sekarang

        // Buat array tahun dari tahun terlama hingga tahun saat ini
        $years = [];
        for ($year = $start_year; $year <= $current_year; $year++) {
            $years[] = $year;
        }

        // Balik array agar tahun terbaru ada di atas
        $years = array_reverse($years);

        // // Ambil data dari database hanya untuk tahun-tahun yang ada
        // $mpm_data = $model_mpm
        //     ->select('YEAR(tgl_kirim_email) as tahun_kirim, COUNT(*) as jumlah') // Hitung jumlah data per tahun
        //     ->groupBy('tahun_kirim')
        //     ->orderBy('tahun_kirim', 'DESC')
        //     ->findAll();

        // // Buat array untuk memetakan data dari database berdasarkan tahun
        // $mpm_year = [];
        // foreach ($mpm_data as $data) {
        //     $mpm_year[$data['tahun_kirim']] = $data['jumlah']; // Simpan jumlah data per tahun
        // }

        // Set pagination
        $perPage = 10; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        $mpmtable = $model_mpm->where('id_member', $user_id)->paginate($perPage);

        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        foreach ($mpmtable as &$item) {
            // Mengubah format tgl_kirim_email
            $tgl_kirim = date('d F Y', strtotime($item['tgl_kirim_email']));
            $bulanInggris_kirim = date('F', strtotime($item['tgl_kirim_email']));
            $item['tgl_kirim_email'] = str_replace($bulanInggris_kirim, $bulanIndonesia[$bulanInggris_kirim], $tgl_kirim);

            // Memeriksa jika update_terakhir bernilai null
            if (is_null($item['update_terakhir'])) {
                $item['update_terakhir'] = '';
            } else {
                // Mengubah format update_terakhir jika tidak null
                $tgl_update = date('d F Y', strtotime($item['update_terakhir']));
                $bulanInggris_update = date('F', strtotime($item['update_terakhir']));
                $item['update_terakhir'] = str_replace($bulanInggris_update, $bulanIndonesia[$bulanInggris_update], $tgl_update);
            }
        }

        $data['mpmtable'] = $mpmtable;
        $data['pager'] = $model_mpm->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;
        $data['years'] = $years; // Semua tahun dari yang terlama sampai sekarang, dengan urutan terbaru di atas
        // $data['mpm_year'] = $mpm_year; // Data dari database

        return view('premium/mpm/mpm', $data);
    }

    public function add_mpm_premium()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $tgl_kirim_email = $this->request->getPost('tgl_kirim_email');

        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tgl = date('d F Y', strtotime($tgl_kirim_email));
        $bulanInggris = date('F', strtotime($tgl_kirim_email));
        $tgl_kirim_email = str_replace($bulanInggris, $bulanIndonesia[$bulanInggris], $tgl);

        $data = [
            'id_member' => $user_id,
            'tgl_kirim_email' => $this->request->getPost('tgl_kirim_email'),
            'update_terakhir' => NULL,
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'negara_perusahaan' => $this->request->getPost('negara_perusahaan'),
            'status_progres' => $this->request->getPost('status_progres'),
            'progres' => '1. Mengirim email pada tanggal ' . $tgl_kirim_email,
        ];

        $model_mpm = new MPM();
        $model_mpm->insert($data);

        return redirect()->to('/mpm-premium');
    }

    public function edit_mpm_premium()
    {
        $model_mpm = new MPM();

        // Ambil ID MPM yang akan diedit
        $id_mpm = $this->request->getPost('id_mpm');

        // Ambil ID user yang sedang login
        $session = session();
        $user_id = $session->get('user_id');  // Sesuaikan dengan session yang kamu pakai

        // Cari data MPM berdasarkan ID MPM
        $mpm = $model_mpm->find($id_mpm);

        // Pastikan data ditemukan
        if (!$mpm) {
            return redirect()->to('/mpm-premium')->withInput()->with('errors', ['MPM tidak ditemukan.']);
        }

        // Cek apakah user yang sedang login adalah pemilik MPM tersebut
        if ($mpm['id_member'] != $user_id) {
            // Jika ID user yang sedang login tidak sama dengan id_member MPM, larang akses
            return redirect()->to('/mpm-premium')->withInput()->with('errors', ['Anda tidak memiliki izin untuk mengedit MPM ini.']);
        }

        // Jika lolos pengecekan, lanjutkan untuk mengupdate MPM
        $now = Time::now();
        $data = [
            'update_terakhir' => $now,
            'progres' => $this->request->getPost('progres'),
        ];

        $model_mpm->update($id_mpm, $data);

        return redirect()->to('/mpm-premium')->with('success', 'MPM telah berhasil diperbarui.');
    }

    public function getEmailsByDate_premium($month, $year)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_mpm = new MPM();

        // Ambil jumlah email yang dikirim per tanggal dalam bulan dan tahun tertentu
        $result = $model_mpm
            ->select('DAY(tgl_kirim_email) as hari, COUNT(*) as jumlah_email')
            ->where('id_member', $user_id)
            ->where('MONTH(tgl_kirim_email)', $month)
            ->where('YEAR(tgl_kirim_email)', $year)
            ->groupBy('hari')
            ->findAll();

        // Buat array dengan format [hari => jumlah_email]
        $emailData = [];
        foreach ($result as $row) {
            $emailData[$row['hari']] = $row['jumlah_email'];
        }

        return $this->response->setJSON($emailData);
    }

    public function login()
    {
        // Guard: jika sudah login, pantulkan ke beranda
        if (session()->get('user_id') || session()->get('logged_in')) {
            return redirect()->to('/beranda')->with('info', 'Kamu sudah login.');
        }

        return view('login/login');
    }


    public function authenticate()
    {
        $session     = session();
        $memberModel = new Member();

        // Jika sudah login, jangan autentikasi ulang
        if ($session->get('user_id') || $session->get('logged_in')) {
            return redirect()->to('/beranda')->with('info', 'Kamu sudah login.');
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Cari user berdasarkan username
        $user = $memberModel->where('username', $username)->first();

        if (!$user) {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->back();
        }

        // Pastikan status aktif
        if ((int)$user['status'] !== 1) {
            $session->setFlashdata('error', 'Akun Anda belum dikonfirmasi oleh admin.');
            return redirect()->back();
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Password salah.');
            return redirect()->back();
        }

        // Set session + regenerate (anti session fixation)
        $sessionData = [
            'user_id'        => $user['id_member'],
            'username'       => $user['username'],
            'role'           => $user['role'],
            'status_premium' => $user['status_premium'],
            'logged_in'      => true,
            'status'         => $user['status'],
        ];

        $session->regenerate(true);
        $session->set($sessionData);

        // Arahkan sesuai role (tetap pakai logika milikmu)
        if ($user['role'] === 'admin') {
            return redirect()->to('/beranda');
        } elseif ($user['role'] === 'member' || ($user['role'] === 'premium' && $user['status_premium'] !== 'verified')) {
            return redirect()->to('/beranda');
        } elseif ($user['role'] === 'premium') {
            return redirect()->to('/beranda-premium');
        }

        // fallback aman
        return redirect()->to('/beranda');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }

    public function member_data_buyers()
    {
        $lang = session()->get('lang') ?? 'id';

        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $model_buyers = new Buyers();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $buyers = $model_buyers
            ->orderBy('verif_date', 'DESC')
            ->paginate($perPage);

        // Prepare data to pass to the view
        $data['buyers'] = $buyers;
        $data['pager'] = $model_buyers->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;
        $data['lang'] = $lang;

        return view('member/data-buyers/index', $data);
    }

    public function member_belajar_ekspor($slug = null)
    {
        // Hapus sistem multi-bahasa, cukup 1 versi
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_materi, meta_description_materi')
            ->first();

        // Meta global untuk halaman utama
        $data['title'] = $meta['title_materi'] ?? 'Belajar Ekspor';
        $data['meta_description'] = $meta['meta_description_materi'] ?? 'Materi dan kategori belajar ekspor.';

        // Model untuk materi & kategori
        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Kategori dropdown (untuk filter atau navigasi)
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        // Pagination
        $perPage = 9;
        $page = $this->request->getVar('page') ?? 1;

        // Ambil semua materi dengan kategori & pagination
        $data['belajar_ekspor'] = $belajarEksporModel->getAllWithCategoryAndPagination($perPage, $page);

        $data['active_category'] = null;
        $data['pager'] = $belajarEksporModel->pager;

        // Tampilkan view member
        return view('member/belajar-ekspor/belajar_ekspor', $data);
    }

    public function member_search_belajar_ekspor(?string $keywordSegmen = null)
    {
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $data['meta'] = $model_meta
            ->select('title_materi, title_materi_en, meta_description_materi, meta_description_materi_en')
            ->first();

        helper(['text', 'url']);

        // Ambil dari segmen atau fallback ?keyword=
        $keyword = $keywordSegmen ?? $this->request->getGet('keyword') ?? '';
        if ($keyword !== '') {
            $keyword = str_replace('+', ' ', $keyword);
            $keyword = urldecode($keyword);
            if (strpos($keyword, ' ') === false && strpos($keyword, '-') !== false) {
                $keyword = str_replace('-', ' ', $keyword);
            }
            // Normalisasi spasi beruntun -> satu spasi
            $keyword = preg_replace('/\s+/', ' ', trim($keyword));
        }

        // (Opsional) jika datang dari query-string, redirect 301 ke URL cantik
        if ($keywordSegmen === null && $this->request->getGet('keyword')) {
            $encoded = str_replace('%20', '+', rawurlencode($keyword));
            $pretty  = base_url(('materi/keyword=') . $encoded);
            return redirect()->to($pretty, 301);
        }

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        $perPage = 9;
        $page = (int)($this->request->getVar('page') ?? 1);

        $hasilPencarian = $belajarEksporModel->getSearchAllWithCategoryAndPagination($keyword, $perPage, $page);
        $data['hasilPencarian'] = $hasilPencarian ?? [];
        $data['keyword'] = $keyword;
        $data['active_category'] = null;

        $data['pager'] = $belajarEksporModel->pager;

        // Pastikan pagination tetap di path "keyword="
        if ($keyword !== '' && $data['pager']) {
            $encodedForPath = str_replace('%20', '+', rawurlencode($keyword));
            $prettyBase = base_url(('materi/keyword=') . $encodedForPath);
            $data['pager']->setPath($prettyBase);
        }

        $data['title'] = 'Hasil Pencarian Materi Ekspor';
        $data['meta_description'] = 'Hasil pencarian materi dan artikel belajar ekspor.';

        return view('member/belajar-ekspor/belajar_ekspor_search', $data);
    }

    // public function member_belajar_ekspor($slug = null)
    // {

    //     $model_webprofile = new WebProfile();
    //     $model_kategori = new KategoriBelajarEksporModel();

    //     $webprofile = $model_webprofile->findAll();
    //     $kategori = $model_kategori->findAll();

    //     $data['webprofile'] = $webprofile;
    //     $data['kategori_belajar_ekspor'] = $kategori;

    //     $belajarEksporModel = new BelajarEksporModel();

    //     $perPage = 9; // Number of items per page
    //     $page = $this->request->getVar('page') ?? 1; // Get the current page number

    //     // Query untuk mendapatkan data
    //     $belajarEkspor = $belajarEksporModel->getAllWithCategoryAndPagination($perPage, $page);

    //     $data['belajar_ekspor'] = $belajarEkspor;

    //     if ($slug) {
    //         // Jika slug kategori dipilih, ambil data sesuai kategori
    //         $kategori = $model_kategori->where('slug', $slug)->first();
    //         if (!$kategori) {
    //             return redirect()->to('/')->with('error', 'Kategori tidak ditemukan');
    //         }
    //         // Mengambil data berdasarkan kategori
    //         $data['belajar_ekspor'] = $belajarEksporModel->getByCategory($kategori['id_kategori_belajar_ekspor']);

    //         // Mengirimkan data kategori yang dipilih ke view
    //         $data['active_category'] = $kategori['id_kategori_belajar_ekspor'];
    //     } else {
    //         // Jika tidak ada slug, tampilkan semua data
    //         $data['belajar_ekspor'] = $belajarEksporModel->getAllWithCategory();

    //         // Tidak ada kategori yang aktif
    //         $data['active_category'] = null;
    //     }

    //     $data['pager'] = $belajarEksporModel->pager; // Get the pager instance

    //     return view('member/belajar-ekspor/belajar_ekspor', $data);
    // }

    public function member_kategori_belajar_ekspor($slug)
    {
        // Webprofile (untuk header/footer layout)
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        // Model
        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // ============================
        // 1) Cek apakah slug adalah kategori
        // ============================
        $kategori = $kategoriBelajarEksporModel
            ->where('slug', $slug)
            ->first();

        if ($kategori) {
            // ---- Meta (fallback ke nama kategori jika kosong) ----
            $data['title'] = !empty($kategori['title_kategori_belajar_ekspor'])
                ? $kategori['title_kategori_belajar_ekspor']
                : ($kategori['nama_kategori'] ?? '');

            $data['meta_description'] = !empty($kategori['meta_description_kategori_belajar_ekspor'])
                ? $kategori['meta_description_kategori_belajar_ekspor']
                : ($kategori['nama_kategori'] ?? '');

            // ✅ Nama kategori aktif untuk judul dinamis di view
            $data['current_category_name'] = $kategori['title_kategori_belajar_ekspor'] ?? '';

            // ---- Pagination & daftar artikel per kategori ----
            $perPage = 9;
            $page = (int)($this->request->getVar('page') ?? 1);

            $data['belajar_ekspor'] = $belajarEksporModel->getSpecificByCategoryWithPagination(
                $kategori['id_kategori_belajar_ekspor'],
                $perPage,
                $page
            );

            // Dropdown kategori & kategori aktif
            $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();
            $data['active_category'] = $kategori['id_kategori_belajar_ekspor'];

            // Pager (pastikan path-nya tetap rapi)
            $data['pager'] = $belajarEksporModel->pager;
            if (!empty($data['pager'])) {
                $base = base_url('materi/' . $slug);
                $data['pager']->setPath($base);
            }

            // ---- Render view daftar kategori (pakai view yang sama dengan listing umum)
            return view('member/belajar-ekspor/belajar_ekspor', $data);
        }

        // ============================
        // 2) Jika bukan kategori → cek apakah slug adalah artikel
        // ============================
        $artikel = $belajarEksporModel
            ->where('slug', $slug)
            ->first();

        if ($artikel) {
            return $this->member_belajar_ekspor_detail($slug);
        }

        // ============================
        // 3) Jika tidak ditemukan → redirect ke halaman utama
        // ============================
        return redirect()
            ->to(base_url('materi/'))
            ->with('error', 'Konten tidak ditemukan.');
    }

    // public function member_kategori_belajar_ekspor($slug)
    // {

    //      // Bahasa aktif
    //     $lang = session()->get('lang') ?? 'id';
    //     $data['lang'] = $lang;

    //     // (Opsional) Webprofile untuk header/footer/layout
    //     $model_webprofile = new WebProfile();
    //     $data['webprofile'] = $model_webprofile->findAll();

    //     // Model
    //     $belajarEksporModel = new BelajarEksporModel();
    //     $kategoriBelajarEksporModel      = new KategoriBelajarEksporModel();

    //     // ============================
    //     // 1) Cek sebagai KATEGORI
    //     // ============================
    //     $kategori = $kategoriBelajarEksporModel
    //         ->groupStart()
    //         ->where('slug', $slug)
    //         ->orWhere('slug_en', $slug)
    //         ->groupEnd()
    //         ->first();

    //     if ($kategori) {
    //         // ---- Meta (fallback ke nama kategori jika title/meta kosong) ----
    //         $data['title'] = ($lang === 'id')
    //             ? (!empty($kategori['title_kategori_belajar_ekspor'])
    //                 ? $kategori['title_kategori_belajar_ekspor']
    //                 : ($kategori['nama_kategori'] ?? ''))
    //             : (!empty($kategori['title_kategori_belajar_ekspor_en'])
    //                 ? $kategori['title_kategori_belajar_ekspor_en']
    //                 : ($kategori['nama_kategori_en'] ?? ''));

    //         $data['meta_description'] = ($lang === 'id')
    //             ? (!empty($kategori['meta_description_kategori_belajar_ekspor'])
    //                 ? $kategori['meta_description_kategori_belajar_ekspor']
    //                 : ($kategori['nama_kategori'] ?? ''))
    //             : (!empty($kategori['meta_description_kategori_belajar_ekspor_en'])
    //                 ? $kategori['meta_description_kategori_belajar_ekspor_en']
    //                 : ($kategori['nama_kategori_en'] ?? ''));

    //         $data['current_category_name'] = ($lang === 'id')
    //             ? ($kategori['title_kategori_belajar_ekspor'] ?? '')
    //             : ($kategori['title_kategori_belajar_ekspor_en'] ?? '');

    //         // ---- Pagination & listing artikel dalam kategori ----
    //         $perPage = 9;
    //         $page    = (int)($this->request->getVar('page') ?? 1);

    //         $data['belajar_ekspor'] = $belajarEksporModel->getSpecificByCategoryWithPagination(
    //             $kategori['id_kategori_belajar_ekspor'],
    //             $perPage,
    //             $page
    //         );

    //         // Dropdown kategori & state aktif
    //         $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();
    //         $data['active_category']         = $kategori['id_kategori_belajar_ekspor'];

    //         // Pager (pastikan path tetap /id/materi/{slug} atau /en/lessons/{slug})
    //         $data['pager'] = $belajarEksporModel->pager;
    //         if (!empty($data['pager'])) {
    //             $base = base_url(($lang === 'en' ? 'en/lessons/' : 'id/materi/') . $slug);
    //             $data['pager']->setPath($base);
    //         }

    //         // ---- Render view listing kategori (pakai view daftar yang sama) ----
    //         return view('member/belajar-ekspor/belajar_ekspor', $data);
    //     }

    //     // ============================
    //     // 2) Bukan kategori → cek ARTIKEL
    //     // ============================
    //     $artikel = $belajarEksporModel
    //         ->groupStart()
    //         ->where('slug', $slug)
    //         ->orWhere('slug_en', $slug)
    //         ->groupEnd()
    //         ->first();

    //     if ($artikel) {
    //         // Delegasikan ke method detail agar tidak duplikasi logic
    //         return $this->member_belajar_ekspor_detail($slug);
    //     }

    //     // ============================
    //     // 3) Tidak ditemukan → fallback
    //     // ============================
    //     return redirect()
    //         ->to(base_url($lang === 'en' ? 'en/lessons' : 'id/materi'))
    //         ->with('error', 'Konten tidak ditemukan.');
    // }


    public function premium_belajar_ekspor($slug = null)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Mengambil semua kategori
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        if ($slug) {
            // Jika slug kategori dipilih, ambil data sesuai kategori
            $kategori = $kategoriBelajarEksporModel->where('slug', $slug)->first();
            if (!$kategori) {
                return redirect()->to('/')->with('error', 'Kategori tidak ditemukan');
            }
            // Mengambil data berdasarkan kategori
            $data['belajar_ekspor'] = $belajarEksporModel->getByCategory($kategori['id_kategori_belajar_ekspor']);

            // Mengirimkan data kategori yang dipilih ke view
            $data['active_category'] = $kategori['id_kategori_belajar_ekspor'];
        } else {
            // Jika tidak ada slug, tampilkan semua data
            $data['belajar_ekspor'] = $belajarEksporModel->getAllWithCategory();

            // Tidak ada kategori yang aktif
            $data['active_category'] = null;
        }

        return view('premium/belajar-ekspor/belajar_ekspor', $data);
    }

    public function premium_search_belajar_ekspor()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        // Instansiasi model yang diperlukan
        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Mengambil semua kategori untuk ditampilkan di sidebar/filter
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $belajarEksporModel->like('judul_belajar_ekspor', $keyword)
            ->orLike('deskripsi_belajar_ekspor', $keyword)
            ->getAllWithCategory(); // Pastikan method ini mengembalikan data dengan kategori

        // Jika ada hasil pencarian
        if (count($hasilPencarian) > 0) {
            $data['hasilPencarian'] = $hasilPencarian;
        } else {
            $data['hasilPencarian'] = [];
        }

        // Kirimkan keyword pencarian untuk ditampilkan di view
        $data['keyword'] = $keyword;

        // Tidak ada kategori yang aktif di pencarian
        $data['active_category'] = null;

        // Render view hasil pencarian
        return view('premium/belajar-ekspor/belajar_ekspor_search', $data);
    }

    public function premium_kategori_belajar_ekspor($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Mengambil kategori berdasarkan slug
        $kategori = $kategoriBelajarEksporModel->where('slug', $slug)->first();
        if (!$kategori) {
            // Jika kategori tidak ditemukan, redirect atau tampilkan error
            return redirect()->to('/')->with('error', 'Kategori tidak ditemukan');
        }

        // Mengambil data belajar ekspor yang terkait dengan kategori yang dipilih
        $data['belajar_ekspor'] = $belajarEksporModel->getByCategory($kategori['id_kategori_belajar_ekspor']);

        // Mengambil semua kategori untuk menu dropdown
        $data['kategori_belajar_ekspor'] = $kategoriBelajarEksporModel->findAll();

        // Mengirim data kategori yang dipilih untuk ditampilkan di view
        $data['active_category'] = $kategori['id_kategori_belajar_ekspor'];

        return view('premium/belajar-ekspor/belajar_ekspor', $data);
    }

    public function member_belajar_ekspor_detail($slug)
    {
        // Webprofile untuk layout
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();

        // Model
        $belajarEksporModel = new BelajarEksporModel();
        $kategoriBelajarEksporModel = new KategoriBelajarEksporModel();

        // Ambil artikel berdasar slug (satu bahasa)
        $artikel = $belajarEksporModel
            ->where('slug', $slug)
            ->first();

        if (!$artikel) {
            // Jika artikel tidak ditemukan, kembali ke daftar member
            return redirect()->to('/')->with('error', 'Artikel tidak ditemukan');
        }

        // Ambil kategori artikel
        $kategori = $kategoriBelajarEksporModel->find($artikel['id_kategori_belajar_ekspor']);

        // Ambil artikel terkait: satu kategori yang sama, bukan artikel saat ini
        // (Gunakan kolom id primer milik tabel kamu; sesuaikan nama kolom id bila berbeda)
        $related_artikel = $belajarEksporModel
            ->where('id_kategori_belajar_ekspor', $artikel['id_kategori_belajar_ekspor'])
            ->where('id_belajar_ekspor !=', $artikel['id_belajar_ekspor'])
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->find();

        // (Opsional) lengkapi info kategori di setiap related
        foreach ($related_artikel as &$item) {
            $item['kategori'] = $kategoriBelajarEksporModel->find($item['id_kategori_belajar_ekspor']);
        }

        // Siapkan data untuk view
        $data = [
            'artikel'        => $artikel,
            'kategori'       => $kategori,
            'belajar_ekspor' => $related_artikel, // dipakai sebagai "related"
            'webprofile'     => $webprofile,
            // 'isGuest'        => ! session()->has('user_id'),

            // (Opsional) Title & meta untuk <h1>/<title> jika view memakainya
            // 'title'            => $artikel['judul_belajar_ekspor'] ?? 'Detail Materi',
            // 'meta_description' => $artikel['deskripsi_belajar_ekspor'] ?? '',
        ];

        return view('member/belajar-ekspor/belajar_ekspor_detail', $data);
    }


    public function premium_belajar_ekspor_detail($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $belajarEksporModel = new BelajarEksporModel();
        $kategoriModel = new KategoriBelajarEksporModel();

        // Mengambil artikel berdasarkan slug
        $artikel = $belajarEksporModel->where('slug', $slug)->first();

        if (!$artikel) {
            // Jika artikel tidak ditemukan, redirect atau tampilkan pesan error
            return redirect()->to('/')->with('error', 'Artikel tidak ditemukan');
        }

        // Mengambil kategori artikel berdasarkan id_kategori
        $kategori = $kategoriModel->find($artikel['id_kategori_belajar_ekspor']);

        // Mengambil artikel terkait
        $related_artikel = $belajarEksporModel->where('slug !=', $slug)->orderBy('created_at', 'DESC')->limit(3)->findAll();

        // Mengirim data artikel, kategori, dan artikel terkait ke view
        $data = [
            'artikel' => $artikel,
            'kategori' => $kategori,
            'belajar_ekspor' => $related_artikel,
            'webprofile' => $webprofile,
        ];

        return view('premium/belajar-ekspor/belajar_ekspor_detail', $data);
    }

    public function premium_video_tutorial($slug = null)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $vidioModel = new VideoTutorialModel();
        $kategoriModel = new KategoriVideoModel();

        // Mengambil semua kategori
        $kategori = $kategoriModel->findAll();

        $vidio = [];

        if ($slug) {
            // Jika ada slug kategori, ambil video berdasarkan kategori dan batasi hanya 3
            $vidio = $vidioModel->getLimitedVideosByKategori($slug, 3);
        } else {
            // Jika tidak ada kategori, ambil 3 video dari setiap kategori
            foreach ($kategori as $kat) {
                $vidio[$kat['nama_kategori_video']] = $vidioModel->getLimitedVideosByKategori($kat['slug'], 3);
            }
        }

        // Mengirimkan data ke view
        $data['video_tutorial'] = $vidio;
        $data['kategori_vidio'] = $kategori;
        $data['selected_category'] = $slug;

        return view('premium/video-tutorial/video_tutorial', $data);
    }

    public function member_video_tutorial($slug = null)
    {

        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_tutorial, title_tutorial_en, meta_description_tutorial, meta_description_tutorial_en')
            ->first();
        // $data['meta'] = $meta;
        // ✅ Meta global untuk halaman utama
        $data['title'] = $meta['title_tutorial'] ?? 'Video Tutorial';
        $data['meta_description'] = $meta['meta_description_tutorial'] ?? 'Video dan kategori Video Tutorial.';

        $videoTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();

        // $kategori = $kategoriModel->findAll();
        $data['kategori_video'] = $kategoriVideoModel->orderBy('nama_kategori_video', 'ASC')->findAll();

        $perPage = 9;
        $page = $this->request->getVar('page') ?? 1;

        // $videos = [];


        $data['video_tutorial'] = $videoTutorialModel->getAllWithCategoryAndPagination($perPage, $page);
        $data['active_category'] = null;

        $data['pager'] = $videoTutorialModel->pager;

        // $data['video_tutorial'] = $videos;
        // $data['selected_category'] = $slug;


        return view('member/video-tutorial/video_tutorial', $data);
    }

    public function member_video_selengkapnya($slug)
    {
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $vidioTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();

        // ============================
        // 1) Cek sebagai KATEGORI
        // ============================
        $kategori = $kategoriVideoModel
            ->where('slug', $slug)
            ->first();

        if ($kategori) {
            // ---- Meta (fallback ke nama kategori jika title/meta kosong) ----
            $data['title'] = !empty($kategori['title_kategori_video'])
                ? $kategori['title_kategori_video']
                : ($kategori['nama_kategori_video'] ?? '');

            $data['meta_description'] = !empty($kategori['meta_description_kategori_video'])
                ? $kategori['meta_description_kategori_video']
                : ($kategori['nama_kategori_video'] ?? '');

            $data['current_category_name'] = ($kategori['title_kategori_video'] ?? '');

            // ---- Pagination & listing video dalam kategori ----
            $perPage = 9;
            $page    = (int)($this->request->getVar('page') ?? 1);

            $data['video_tutorial'] = $vidioTutorialModel->getSpecificByCategoryWithPagination(
                $kategori['id_kategori_video'],
                $perPage,
                $page
            );

            // Dropdown kategori & state aktif
            $data['kategori_video'] = $kategoriVideoModel->findAll();
            $data['active_category']         = $kategori['id_kategori_video'];

            // Pager (pastikan path tetap /id/materi/{slug} atau /en/lessons/{slug})
            $data['pager'] = $vidioTutorialModel->pager;
            if (!empty($data['pager'])) {
                $base = base_url(('video/') . $slug);
                $data['pager']->setPath($base);
            }

            // ---- Render view listing kategori (pakai view daftar yang sama) ----
            return view('member/video-tutorial/video_tutorial', $data);
        }

        // ============================
        // 2) Bukan kategori → cek ARTIKEL
        // ============================
        $video = $vidioTutorialModel
            ->where('slug', $slug)
            ->first();

        if ($video) {
            // Delegasikan ke method detail agar tidak duplikasi logic
            return $this->member_video_tutorial_detail($slug);
        }

        // ============================
        // 3) Tidak ditemukan → fallback
        // ============================
        return redirect()
            ->to(base_url('video/'))
            ->with('error', 'Konten tidak ditemukan.');
    }

    public function member_video_tutorial_detail($slug)
    {
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();

        // Inisialisasi model untuk video dan kategori
        $vidioTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();
        // Mengambil data video berdasarkan slug
        $video = $vidioTutorialModel->where('slug', $slug)->orWhere('slug_en', $slug)->first();

        if (!$video) {
            // Jika video tidak ditemukan, redirect atau tampilkan pesan error
            return redirect()->to('/')->with('error', 'video tidak ditemukan');
        }

        $kategori = $kategoriVideoModel->find($video['id_kategori_video']);

        // Mengambil video terkait berdasarkan kategori video saat ini, dan pastikan tidak mengambil video yang sedang dilihat
        $related_video = $vidioTutorialModel
            ->where('id_kategori_video', $video['id_kategori_video'])
            ->where('id_video !=', $video['id_video'])
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->find();

        foreach ($related_video as &$item) {
            $item['kategori'] = $kategoriVideoModel->find($item['id_kategori_video']);
        }

        // Menyiapkan data untuk dikirimkan ke view
        $data = [
            'video' => $video,
            'related_video' => $related_video,
            'kategori' => $kategori,
            'webprofile' => $webprofile,
            // 'isGuest' => ! session()->has('user_id'), // Flag guest
        ];
        // Mengembalikan view dengan data yang telah disiapkan
        return view('member/video-tutorial/video_tutorial_detail', $data);
    }

    public function member_search_video_tutorial(?string $keywordSegmen = null)
    {
        $model_webprofile = new WebProfile();
        $data['webprofile'] = $model_webprofile->findAll();

        $model_meta = new Meta();
        $data['meta'] = $model_meta
            ->select('title_tutorial, title_tutorial_en, meta_description_tutorial, meta_description_tutorial_en')
            ->first();

        helper(['text', 'url']);

        // // Ambil keyword dari query string
        // $keyword = $this->request->getGet('keyword');
        // Ambil dari segmen atau fallback ?keyword=
        $keyword = $keywordSegmen ?? $this->request->getGet('keyword') ?? '';
        if ($keyword !== '') {
            $keyword = str_replace('+', ' ', $keyword);
            $keyword = urldecode($keyword);
            if (strpos($keyword, ' ') === false && strpos($keyword, '-') !== false) {
                $keyword = str_replace('-', ' ', $keyword);
            }
            // Normalisasi spasi beruntun -> satu spasi
            $keyword = preg_replace('/\s+/', ' ', trim($keyword));
        }

        // (Opsional) jika datang dari query-string, redirect 301 ke URL cantik
        if ($keywordSegmen === null && $this->request->getGet('keyword')) {
            $encoded = str_replace('%20', '+', rawurlencode($keyword));
            $pretty  = base_url(('video/keyword=') . $encoded);
            return redirect()->to($pretty, 301);
        }

        // Instansiasi model yang diperlukan
        $videoTutorialModel = new VideoTutorialModel();
        $kategoriVideoModel = new KategoriVideoModel();
        $data['kategori_video'] = $kategoriVideoModel->findAll();

        $perPage = 9; // Number of items per page
        $page = (int)($this->request->getVar('page') ?? 1);

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $videoTutorialModel->getSearchAllWithCategoryAndPagination($keyword, $perPage, $page);
        $data['hasilPencarian'] = $hasilPencarian ?? [];
        $data['keyword'] = $keyword;
        $data['active_category'] = null;

        // Render view hasil pencarian
        $data['pager'] = $videoTutorialModel->pager; // Get the pager instance

        // Pastikan pagination tetap di path "keyword="
        if ($keyword !== '' && $data['pager']) {
            $encodedForPath = str_replace('%20', '+', rawurlencode($keyword));
            $prettyBase = base_url(('video/keyword=') . $encodedForPath);
            $data['pager']->setPath($prettyBase);
        }

        $data['title'] = 'Hasil Pencarian video';
        $data['meta_description'] =  'Hasil pencarian video.';


        return view('member/video-tutorial/video_tutorial_search', $data);
    }

    public function premium_video_tutorial_detail($slug)
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        // Inisialisasi model untuk video dan kategori
        $vidioModel = new VideoTutorialModel();
        $kategoriModel = new KategoriVideoModel();

        // Mengambil data video berdasarkan slug
        $video = $vidioModel->getVideoBySlug($slug);

        // Memastikan bahwa video ditemukan, jika tidak redirect atau tampilkan error
        if (!$video) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Video tidak ditemukan");
        }

        // Mengambil video terkait berdasarkan kategori video saat ini
        $related_videos = $vidioModel->getRelatedVideos($video['id_kategori_video'], $video['id_video']);

        // Mengambil informasi kategori video
        $kategori = $kategoriModel->find($video['id_kategori_video']);

        // Menyiapkan data untuk dikirimkan ke view
        $data = [
            'video' => $video,
            'related_videos' => $related_videos,
            'kategori' => $kategori,
            'webprofile' => $webprofile,
        ];

        // Mengembalikan view dengan data yang telah disiapkan
        return view('premium/video-tutorial/video_tutorial_detail', $data);
    }

    public function website_audit()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        $session = session();
        $user_id = $session->get('user_id');

        $model_webaudit = new WebsiteAudit();

        $webaudit = $model_webaudit->where('id_member', $user_id)->first();

        $data['webaudit'] = $webaudit;

        return view('premium/website-audit/website-audit', $data);
    }

    public function add_website_audit()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $model_webaudit = new WebsiteAudit();

        $data = [
            'id_member' => $user_id,
            'link_website' => $this->request->getPost('link_website'),
        ];

        $model_webaudit->insert($data);

        return redirect()->to('/website-audit');
    }

    public function delete_website_audit($id)
    {
        $session = session();
        $user_id = $session->get('user_id'); // Ambil user_id dari sesi

        $model_webaudit = new WebsiteAudit();
        $webaudit = $model_webaudit->find($id);

        if ($webaudit && $webaudit['id_member'] == $user_id) {
            $model_webaudit->delete($id);
            return redirect()->to('/website-audit')->with('success', 'Website Audit berhasil dihapus');
        } else {
            return redirect()->to('/website-audit')->withInput()->with('errors', ['Anda tidak memiliki izin untuk menghapus website audit ini']);
        }
    }

    public function admin_dashboard()
    {
        $model_belajarekspor = new BelajarEksporModel();
        $model_buyers = new Buyers();
        $model_cfr = new CFR();
        $model_cif = new CIF();
        $model_exwork = new Exwork();
        $model_fob = new FOB();
        $model_kategoribelajarekspor = new KategoriBelajarEksporModel;
        $model_kategorivideo = new KategoriVideoModel();
        $model_manfaatjoin = new ManfaatJoin();
        $model_member = new Member();
        $model_mpm = new MPM();
        $model_pengumuman = new Pengumuman();
        $model_produk = new Produk();
        $model_satuan = new Satuan();
        $model_sertifikat = new Sertifikat();
        $model_slider = new Slider();
        $model_videotutorial = new VideoTutorialModel();
        $model_webprofile = new WebProfile();
        $model_websiteaudit = new WebsiteAudit();
        $model_tentang = new TentangKami();

        $belajarekspor = $model_belajarekspor->countAll();
        $buyers = $model_buyers->countAll();
        $cfr = $model_cfr->countAll();
        $cif = $model_cif->countAll();
        $exwork = $model_exwork->countAll();
        $fob = $model_fob->countAll();
        $kategoribelajarekspor = $model_kategoribelajarekspor->countAll();
        $kategorivideo = $model_kategorivideo->countAll();
        $manfaatjoin = $model_manfaatjoin->countAll();
        $member = $model_member->where(['role' => 'member', 'status' => '1'])->countAllResults();
        $mpm = $model_mpm->countAll();
        $pengumuman = $model_pengumuman->countAll();
        $produk = $model_produk->countAll();
        $satuan = $model_satuan->countAll();
        $sertifikat = $model_sertifikat->countAll();
        $slider = $model_slider->countAll();
        $videotutorial = $model_videotutorial->countAll();
        $webprofile = $model_webprofile->countAll();
        $websiteaudit = $model_websiteaudit->countAll();
        $tentang = $model_tentang->countAll();

        $data['belajarekspor'] = $belajarekspor;
        $data['buyers'] = $buyers;
        $data['cfr'] = $cfr;
        $data['cif'] = $cif;
        $data['exwork'] = $exwork;
        $data['fob'] = $fob;
        $data['kategoribelajarekspor'] = $kategoribelajarekspor;
        $data['kategorivideo'] = $kategorivideo;
        $data['manfaatjoin'] = $manfaatjoin;
        $data['member'] = $member;
        $data['mpm'] = $mpm;
        $data['pengumuman'] = $pengumuman;
        $data['produk'] = $produk;
        $data['satuan'] = $satuan;
        $data['sertifikat'] = $sertifikat;
        $data['slider'] = $slider;
        $data['videotutorial'] = $videotutorial;
        $data['webprofile'] = $webprofile;
        $data['websiteaudit'] = $websiteaudit;
        $data['tentang_kami'] = $tentang;

        return view('admin/dashboard/index', $data);
    }

    public function admin_member()
    {
        $model_member = new Member();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $members = $model_member
            ->where(['role' => 'member', 'status' => '1'])
            ->orderBy('tanggal_verifikasi', 'DESC')
            ->paginate($perPage);

        // 🔹 Ubah status jadi teks
        foreach ($members as &$m) {
            $m['status_text'] = ($m['status'] == 1) ? 'Aktif' : 'Pending';
        }

        $data['member'] = $members;
        $data['pager'] = $model_member->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/member/index', $data);
    }


    public function admin_search_member()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_member = new Member();

        // Set pagination
        $perPage = 10; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $model_member
            ->where('role', 'member')
            ->groupStart()
            ->like('role', $keyword)
            ->orLike('status_premium', $keyword)
            ->orLike('username', $keyword)
            ->orLike('kode_referral', $keyword)
            ->orLike('popular_point', $keyword)
            ->orLike('nama_perusahaan', $keyword)
            ->orLike('deskripsi_perusahaan', $keyword)
            ->orLike('deskripsi_perusahaan_en', $keyword)
            ->orLike('tipe_bisnis', $keyword)
            ->orLike('tipe_bisnis_en', $keyword)
            ->orLike('produk_utama', $keyword)
            ->orLike('produk_utama_en', $keyword)
            ->orLike('tahun_dibentuk', $keyword)
            ->orLike('skala_bisnis', $keyword)
            ->orLike('skala_bisnis_en', $keyword)
            ->orLike('email', $keyword)
            ->orLike('pic', $keyword)
            ->orLike('pic_phone', $keyword)
            ->orLike('kategori_produk', $keyword)
            ->orLike('kategori_produk_en', $keyword)
            ->orLike('latitude', $keyword)
            ->orLike('longitude', $keyword)
            ->groupEnd()
            ->orderBy('tanggal_verifikasi', 'DESC')
            ->paginate($perPage); // Pastikan method ini mengembalikan data dengan kategori

        // Jika ada hasil pencarian
        if (count($hasilPencarian) > 0) {
            $data['hasilPencarian'] = $hasilPencarian;
        } else {
            $data['hasilPencarian'] = [];
        }

        // Kirimkan keyword pencarian untuk ditampilkan di view
        $data['keyword'] = $keyword;
        $data['pager'] = $model_member->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/member/search', $data);
    }

    // public function admin_calon_member()
    // {
    //     $model_member = new Member();

    //     $perPage = 10;
    //     $page = $this->request->getVar('page') ?? 1;

    //     // ambil data member dengan role 'member' dan status 0 (belum aktif)
    //     $member = $model_member
    //         ->where('role', 'member')
    //         ->where('status', '0')
    //         ->orderBy('tanggal_verifikasi', 'DESC')
    //         ->paginate($perPage);

    //     $data['member'] = $member;
    //     $data['pager'] = $model_member->pager;
    //     $data['page'] = $page;
    //     $data['perPage'] = $perPage;

    //     return view('admin/member/pendaftaran', $data);
    // }

    public function admin_calon_member()
    {
        $model_member = new Member();

        $data['member'] = $model_member
            ->where('role', 'member')
            ->where('status', 0) // hanya calon member
            ->findAll();

        return view('admin/member/pendaftaran', $data);
    }

    public function admin_detail_member($id_member)
    {
        $model_member = new Member();

        // Tentukan hanya kolom yang ingin diambil
        $fields = [
            'id_member',
            'username',
            'foto_profil',
            'kode_referral',
            'popular_point',
            'nama_perusahaan',
            'deskripsi_perusahaan',
            'status',
            'tipe_bisnis',
            'produk_utama',
            'tahun_dibentuk',
            'skala_bisnis',
            'email',
            'pic',
            'pic_phone',
            'kategori_produk',
            'alamat_perusahaan',
            'alamat_website',
            'bukti_transfer'
        ];

        $data['member'] = $model_member->select($fields)->find($id_member);

        if (!$data['member']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Member tidak ditemukan');
        }

        $data['member']['status_text'] = $data['member']['status'] == 1 ? 'Aktif' : 'Pending';

        return view('admin/member/detail_member', $data);
    }

    public function admin_konfirmasi_member($id_member)
    {
        $model_member = new Member();
        $member = $model_member->find($id_member);

        if ($member) {
            $model_member->update($id_member, ['status' => 1]);
            return redirect()->to(base_url('admin-member'))->with('success', 'Member berhasil dikonfirmasi!');
        } else {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }
    }


    public function admin_add_member()
    {
        $model_kategori_induk = new KategoriInduk();
        $kategori_induk = $model_kategori_induk->findAll();
        $data['kategori_induk'] = $kategori_induk;

        $model_kategori_produk = new KategoriProduk();
        $kategori_produk = $model_kategori_produk->findAll();
        $kategori_produk_terkelompok = [];
        foreach ($kategori_produk as $produk) {
            $kategori_produk_terkelompok[$produk['id_kategori_induk']][] = $produk;
        }
        $data['kategori_produk_terkelompok'] = $kategori_produk_terkelompok;

        return view('admin/member/add', $data);
    }

    public function admin_create_member()
    {
        $model_member = new Member();
        $model_satuan = new Satuan();

        $now = Time::now();

        $password = $this->request->getPost('password');

        $fotoProfil = $this->request->getFile('foto_profil');

        $namaFile = null;
        if ($fotoProfil && $fotoProfil->isValid() && !$fotoProfil->hasMoved()) {
            $namaFile = uniqid() . '.' . $fotoProfil->getClientExtension();
            $fotoProfil->move(ROOTPATH . 'public/img', $namaFile);
        }

        // $role = $this->request->getPost('role');

        // if ($role == 'premium') {
        //     $status_premium = 'pending';
        // } else {
        //     $status_premium = null;
        // }

        $tahun_dibentuk = $this->request->getPost('tahun_dibentuk');

        if ($tahun_dibentuk == null) {
            $tahun_dibentuk = 1950;
        }

        $data = [
            'role' => 'member',
            // 'status_premium' => $status_premium,
            'username' => $this->request->getPost('username_referral'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'foto_profil' => $namaFile,
            'kode_referral' => $this->request->getPost('username_referral'),
            'popular_point' => 0,
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_perusahaan_en'),
            // 'tipe_bisnis' => $this->request->getPost('tipe_bisnis'),
            // 'tipe_bisnis_en' => $this->request->getPost('tipe_bisnis_en'),
            'produk_utama' => $this->request->getPost('produk_utama'),
            'produk_utama_en' => $this->request->getPost('produk_utama_en'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            'alamat_website' => $this->request->getPost('alamat_website'),
            'tahun_dibentuk' => $tahun_dibentuk,
            // 'skala_bisnis' => $this->request->getPost('skala_bisnis'),
            // 'skala_bisnis_en' => $this->request->getPost('skala_bisnis_en'),
            'email' => $this->request->getPost('email'),
            'pic' => $this->request->getPost('pic'),
            'pic_phone' => $this->request->getPost('pic_phone'),
            'tanggal_verifikasi' => $now,
            'kategori_produk' => $this->request->getPost('kategori_produk'),
            'kategori_produk_en' => $this->request->getPost('kategori_produk_en'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];

        $model_member->insert($data);
        $id_member = $model_member->insertID();

        $data1 = [
            'id_member' => $id_member,
            'satuan' => 'pcs',
        ];

        $model_satuan->insert($data1);

        return redirect()->to('/admin-member');
    }

    public function admin_edit_member($id)
    {
        $model_member = new Member();
        $model_kategori_induk = new KategoriInduk();
        $kategori_induk = $model_kategori_induk->findAll();
        $data['kategori_induk'] = $kategori_induk;

        $model_kategori_produk = new KategoriProduk();
        $kategori_produk = $model_kategori_produk->findAll();
        $kategori_produk_terkelompok = [];
        foreach ($kategori_produk as $produk) {
            $kategori_produk_terkelompok[$produk['id_kategori_induk']][] = $produk;
        }
        $data['kategori_produk_terkelompok'] = $kategori_produk_terkelompok;

        $member = $model_member->where('role', 'member')->find($id);

        $data['member'] = $member;

        return view('admin/member/edit', $data);
    }

    public function admin_update_member($id)
    {
        $model_member = new Member();
        $member = $model_member->find($id);
        $password = $this->request->getPost('password');
        $fotoProfil = $this->request->getFile('foto_profil');
        $data = []; // Initialize data array

        // Check if the password is null or provided
        if ($password == null) {
            if ($fotoProfil->isValid() && !$fotoProfil->hasMoved()) {
                // Set new file name
                $namaFile = uniqid() . '.' . $fotoProfil->getClientExtension();

                // Remove old file if exists
                if ($member['foto_profil'] && file_exists(ROOTPATH . 'public/uploads/foto_usaha' . $member['foto_profil'])) {
                    unlink(ROOTPATH . 'public/uploads/foto_usaha' . $member['foto_profil']);
                }

                // Move new file and update data array
                $fotoProfil->move(ROOTPATH . 'public/uploads/foto_usaha', $namaFile);
                $data['foto_profil'] = $namaFile;
            } else {
                // Keep existing file if new file is invalid
                $data['foto_profil'] = $member['foto_profil'];
            }
        } else {
            // Update password if provided
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Populate the remaining fields for data array
        $data = array_merge($data, [
            'role' => 'member',
            // 'status_premium' => $this->request->getPost('status_premium'),
            'username' => $this->request->getPost('username_referral'),
            'kode_referral' => $this->request->getPost('username_referral'),
            'popular_point' => $this->request->getPost('popular_point'),
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_perusahaan_en'),
            // 'tipe_bisnis' => $this->request->getPost('tipe_bisnis'),
            // 'tipe_bisnis_en' => $this->request->getPost('tipe_bisnis_en'),
            'produk_utama' => $this->request->getPost('produk_utama'),
            'produk_utama_en' => $this->request->getPost('produk_utama_en'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            'alamat_website' => $this->request->getPost('alamat_website'),
            'tahun_dibentuk' => $this->request->getPost('tahun_dibentuk'),
            // 'skala_bisnis' => $this->request->getPost('skala_bisnis'),
            // 'skala_bisnis_en' => $this->request->getPost('skala_bisnis_en'),
            'email' => $this->request->getPost('email'),
            'pic' => $this->request->getPost('pic'),
            'pic_phone' => $this->request->getPost('pic_phone'),
            'kategori_produk' => $this->request->getPost('kategori_produk'),
            'kategori_produk_en' => $this->request->getPost('kategori_produk_en'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ]);

        // Perform update operation
        $model_member->update($id, $data);

        return redirect()->to('/admin-member');
    }

    public function admin_delete_member($id)
    {
        $model_member = new \App\Models\Member();

        $member = $model_member->where('role', 'member')->find($id);

        if (!$member) {
            return redirect()->back()->with('error', 'Data member tidak ditemukan.');
        }

        // === Hapus foto usaha (lokasi: public/uploads/foto_usaha/) ===
        if (!empty($member['foto_profil'])) {
            $fotoPath = FCPATH . 'uploads/foto_usaha/' . $member['foto_profil'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        // === (Opsional) Hapus bukti transfer juga ===
        if (!empty($member['bukti_transfer'])) {
            $buktiPath = FCPATH . 'uploads/bukti_transfer/' . $member['bukti_transfer'];
            if (file_exists($buktiPath)) {
                unlink($buktiPath);
            }
        }

        // === Hapus data dari database ===
        $model_member->delete($id);

        return redirect()->to('/admin-member')->with('success', 'Data member dan file terkait berhasil dihapus.');
    }

    public function admin_buyers()
    {
        $model_buyers = new Buyers();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $buyers = $model_buyers
            ->orderBy('verif_date', 'DESC')
            ->paginate($perPage);

        $data['buyers'] = $buyers;
        $data['pager'] = $model_buyers->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/buyers/index', $data);
    }

    public function admin_search_buyers()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_buyers = new Buyers();

        // Set pagination
        $perPage = 10; // Number of members per page
        $page = $this->request->getVar('page') ?? 1; // Get the current page number

        // Query pencarian: mencari berdasarkan judul, tags, atau deskripsi
        $hasilPencarian = $model_buyers->like('nama_perusahaan', $keyword)
            ->orLike('email_perusahaan', $keyword)
            ->orLike('website_perusahaan', $keyword)
            ->orLike('hs_code', $keyword)
            ->orLike('negara_perusahaan', $keyword)
            ->paginate($perPage); // Pastikan method ini mengembalikan data dengan kategori

        // Jika ada hasil pencarian
        if (count($hasilPencarian) > 0) {
            $data['hasilPencarian'] = $hasilPencarian;
        } else {
            $data['hasilPencarian'] = [];
        }

        // Kirimkan keyword pencarian untuk ditampilkan di view
        $data['keyword'] = $keyword;
        $data['pager'] = $model_buyers->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/buyers/search', $data);
    }

    public function admin_add_buyers()
    {
        return view('admin/buyers/add');
    }

    public function admin_create_buyers()
    {
        $model_buyers = new Buyers();

        $now = Time::now();

        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'email_perusahaan' => $this->request->getPost('email_perusahaan'),
            'website_perusahaan' => $this->request->getPost('website_perusahaan'),
            'hs_code' => $this->request->getPost('hs_code'),
            'deskripsi_hs_code' => $this->request->getPost('deskripsi_hs_code'),
            'negara_perusahaan' => $this->request->getPost('negara_perusahaan'),
            'verif_date' => $now,
        ];

        $model_buyers->insert($data);

        return redirect()->to('/admin-buyers');
    }

    public function admin_edit_buyers($id)
    {
        $model_buyers = new Buyers();

        $buyers = $model_buyers->find($id);

        $data['buyers'] = $buyers;

        return view('admin/buyers/edit', $data);
    }

    public function admin_update_buyers($id)
    {
        $model_buyers = new Buyers();

        $data = [
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'email_perusahaan' => $this->request->getPost('email_perusahaan'),
            'website_perusahaan' => $this->request->getPost('website_perusahaan'),
            'hs_code' => $this->request->getPost('hs_code'),
            'deskripsi_hs_code' => $this->request->getPost('deskripsi_hs_code'),
            'negara_perusahaan' => $this->request->getPost('negara_perusahaan'),
        ];

        $model_buyers->update($id, $data);

        return redirect()->to('/admin-buyers');
    }

    public function admin_delete_buyers($id)
    {
        $model_buyers = new Buyers();

        $model_buyers->delete($id);

        return redirect()->to('/admin-buyers');
    }

    // Admin Produk
    public function admin_produk()
    {
        $model_produk = new Produk();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $produk = $model_produk
            ->select('produk.*, member.username AS username_member')
            ->join('member', 'member.id_member = produk.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['produk'] = $produk;
        $data['pager'] = $model_produk->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/produk/index', $data);
    }

    public function admin_search_produk()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_produk = new Produk();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_produk
            ->select('produk.*, member.username AS username_member')
            ->join('member', 'member.id_member = produk.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('produk.nama_produk', $keyword)
            ->orLike('produk.nama_produk_en', $keyword)
            ->orLike('produk.deskripsi_produk', $keyword)
            ->orLike('produk.deskripsi_produk_en', $keyword)
            ->orLike('produk.hs_code', $keyword)
            ->orLike('produk.minimum_order_qty', $keyword)
            ->orLike('produk.kapasitas_produksi_bln', $keyword)
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_produk->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/produk/search', $data);
    }

    public function admin_add_produk()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/produk/add', $data);
    }

    public function admin_create_produk()
    {
        $model_produk = new Produk();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-produk')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $fotoProduk = $this->request->getFile('foto_produk');

        $namaFile = null;
        if ($fotoProduk && $fotoProduk->isValid() && !$fotoProduk->hasMoved()) {
            $namaFile = uniqid() . '.' . $fotoProduk->getClientExtension();
            $fotoProduk->move(ROOTPATH . 'public/img', $namaFile);
        }

        $data = [
            'id_member' => $id_member,
            'foto_produk' => $namaFile,
            'nama_produk' => $this->request->getPost('nama_produk'),
            'nama_produk_en' => $this->request->getPost('nama_produk_en'),
            'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
            'deskripsi_produk_en' => $this->request->getPost('deskripsi_produk_en'),
            'hs_code' => $this->request->getPost('hs_code'),
            'minimum_order_qty' => $this->request->getPost('minimum_order_qty'),
            'kapasitas_produksi_bln' => $this->request->getPost('kapasitas_produksi_bln'),
        ];

        $model_produk->insert($data);

        return redirect()->to('/admin-produk');
    }

    public function admin_edit_produk($id)
    {
        $model_produk = new Produk();
        $model_member = new Member();

        $produk = $model_produk->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['produk'] = $produk;
        $data['member'] = $member;

        return view('admin/produk/edit', $data);
    }

    public function admin_update_produk($id)
    {
        $model_produk = new Produk();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-produk')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $produk = $model_produk->find($id);

        $fotoProduk = $this->request->getFile('foto_produk');

        if ($fotoProduk->isValid() && !$fotoProduk->hasMoved()) {
            // Set new file name
            $namaFile = uniqid() . '.' . $fotoProduk->getClientExtension();

            // Remove old file if exists
            if ($produk['foto_produk'] && file_exists(ROOTPATH . 'public/img/' . $produk['foto_produk'])) {
                unlink(ROOTPATH . 'public/img/' . $produk['foto_produk']);
            }

            // Move new file and update data array
            $fotoProduk->move(ROOTPATH . 'public/img/', $namaFile);
            $data['foto_produk'] = $namaFile;
        } else {
            // Keep existing file if new file is invalid
            $data['foto_produk'] = $produk['foto_produk'];
        }

        $data = array_merge($data, [
            'id_member' => $id_member,
            'nama_produk' => $this->request->getPost('nama_produk'),
            'nama_produk_en' => $this->request->getPost('nama_produk_en'),
            'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
            'deskripsi_produk_en' => $this->request->getPost('deskripsi_produk_en'),
            'hs_code' => $this->request->getPost('hs_code'),
            'minimum_order_qty' => $this->request->getPost('minimum_order_qty'),
            'kapasitas_produksi_bln' => $this->request->getPost('kapasitas_produksi_bln'),
        ]);

        $model_produk->update($id, $data);

        return redirect()->to('/admin-produk');
    }

    public function admin_delete_produk($id)
    {
        $model_produk = new Produk();

        $produk = $model_produk->find($id);

        if ($produk['foto_produk'] && file_exists(ROOTPATH . 'public/img/' . $produk['foto_produk'])) {
            unlink(ROOTPATH . 'public/img/' . $produk['foto_produk']);
        }

        $model_produk->delete($id);

        return redirect()->to('/admin-produk');
    }

    // Admin Sertifikat
    public function admin_sertifikat()
    {
        $model_sertifikat = new Sertifikat();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $sertifikat = $model_sertifikat
            ->select('sertifikat.*, member.username AS username_member')
            ->join('member', 'member.id_member = sertifikat.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['sertifikat'] = $sertifikat;
        $data['pager'] = $model_sertifikat->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/sertifikat/index', $data);
    }

    public function admin_search_sertifikat()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_sertifikat = new Sertifikat();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_sertifikat
            ->select('sertifikat.*, member.username AS username_member')
            ->join('member', 'member.id_member = sertifikat.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('sertifikat.nama_sertifikat', $keyword)
            ->orLike('sertifikat.nama_sertifikat_en', $keyword)
            ->orLike('sertifikat.sertifikat', $keyword)
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_sertifikat->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/sertifikat/search', $data);
    }

    public function admin_add_sertifikat()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/sertifikat/add', $data);
    }

    public function admin_create_sertifikat()
    {
        $model_sertifikat = new Sertifikat();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-sertifikat')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $fileSertifikat = $this->request->getFile('sertifikat');

        $namaFile = null;

        if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            $namaFile = uniqid() . '.' . $fileSertifikat->getClientExtension();
            $fileSertifikat->move(ROOTPATH . 'public/certificate', $namaFile);
        }

        $data = [
            'id_member' => $id_member,
            'nama_sertifikat' => $this->request->getPost('nama_sertifikat'),
            'nama_sertifikat_en' => $this->request->getPost('nama_sertifikat_en'),
            'sertifikat' => $namaFile,
        ];

        $model_sertifikat->insert($data);

        return redirect()->to('/admin-sertifikat');
    }

    public function admin_edit_sertifikat($id)
    {
        $model_sertifikat = new Sertifikat();
        $model_member = new Member();

        $sertifikat = $model_sertifikat->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['sertifikat'] = $sertifikat;
        $data['member'] = $member;

        return view('admin/sertifikat/edit', $data);
    }

    public function admin_update_sertifikat($id)
    {
        $model_sertifikat = new Sertifikat();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-sertifikat')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $sertifikat = $model_sertifikat->find($id);

        $fileSertifikat = $this->request->getFile('sertifikat');

        if ($fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            // Set new file name
            $namaFile = uniqid() . '.' . $fileSertifikat->getClientExtension();

            // Remove old file if exists
            if ($sertifikat['sertifikat'] && file_exists(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat'])) {
                unlink(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat']);
            }

            // Move new file and update data array
            $fileSertifikat->move(ROOTPATH . 'public/certificate', $namaFile);
            $data['sertifikat'] = $namaFile;
        } else {
            // Keep existing file if new file is invalid
            $data['sertifikat'] = $sertifikat['sertifikat'];
        }

        $data = array_merge($data, [
            'id_member' => $id_member,
            'nama_sertifikat' => $this->request->getPost('nama_sertifikat'),
            'nama_sertifikat_en' => $this->request->getPost('nama_sertifikat_en'),
        ]);

        $model_sertifikat->update($id, $data);

        return redirect()->to('/admin-sertifikat');
    }

    public function admin_delete_sertifikat($id)
    {
        $model_sertifikat = new Sertifikat();

        $sertifikat = $model_sertifikat->find($id);

        if ($sertifikat['sertifikat'] && file_exists(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat'])) {
            unlink(ROOTPATH . 'public/certificate/' . $sertifikat['sertifikat']);
        }

        $model_sertifikat->delete($id);

        return redirect()->to('/admin-sertifikat');
    }

    public function admin_belajar_ekspor()
    {
        $model_belajarekspor = new BelajarEksporModel();
        $model_kategori = new KategoriBelajarEksporModel();

        $belajar_ekspor = $model_belajarekspor->getAllWithCategory();

        $data['belajar_ekspor'] = $belajar_ekspor;

        return view('admin/belajar-ekspor/index', $data);
    }

    public function admin_search_belajar()
    {
        helper('text');

        $keyword = $this->request->getGet('keyword');
        $model_belajarekspor = new BelajarEksporModel();

        // Set pagination
        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query pencarian dengan join
        $hasilPencarian = $model_belajarekspor
            ->select('belajar_ekspor.*, kategori_belajar_ekspor.nama_kategori')
            ->join('kategori_belajar_ekspor', 'kategori_belajar_ekspor.id_kategori_belajar_ekspor = belajar_ekspor.id_kategori_belajar_ekspor')
            ->groupStart()
            ->like('judul_belajar_ekspor', $keyword)
            ->orLike('kategori_belajar_ekspor.nama_kategori', $keyword)
            ->orLike('deskripsi_belajar_ekspor', $keyword)
            ->orLike('belajar_ekspor.slug', $keyword) // Menyebutkan tabel untuk slug
            ->groupEnd()
            ->paginate($perPage, 'group1');

        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;

        return view('admin/belajar-ekspor/search', $data);
    }


    public function admin_belajar_ekspor_tambah()
    {
        $model_kategori = new KategoriBelajarEksporModel();

        $kategori_ekspor = $model_kategori->findAll();

        $data['nama_kategori'] = $kategori_ekspor;

        return view('admin/belajar-ekspor/tambah', $data);
    }

    public function admin_belajar_ekspor_store()
    {
        $model_belajarekspor = new BelajarEksporModel();

        $data = [
            'judul_belajar_ekspor' => $this->request->getPost('judul_belajar_ekspor'),
            'judul_belajar_ekspor_en' => $this->request->getPost('judul_belajar_ekspor_en'),
            'id_kategori_belajar_ekspor' => $this->request->getPost('id_kategori'),
            'id_kategori_en' => $this->request->getPost('id_kategori'),
            'deskripsi_belajar_ekspor' => $this->request->getPost('deskripsi_belajar_ekspor'),
            'deskripsi_belajar_ekspor_en' => $this->request->getPost('deskripsi_belajar_ekspor_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_title_en' => $this->request->getPost('meta_title_en'),
            'meta_deskripsi' => $this->request->getPost('meta_deskripsi'),
            'meta_deskripsi_en' => $this->request->getPost('meta_deskripsi_en'),
        ];

        // Mengambil file gambar
        $file = $this->request->getFile('foto_belajar_ekspor');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Beri nama acak pada file gambar untuk menghindari konflik nama
            $newName = $file->getRandomName();

            // Pindahkan file ke folder 'img' dengan nama baru
            $file->move('img/', $newName);

            // Simpan nama file gambar ke dalam array data untuk disimpan ke database
            $data['foto_belajar_ekspor'] = $newName;
        }

        $model_belajarekspor->insert($data);

        return redirect()->to('/admin-belajar-ekspor');
    }

    public function admin_belajar_ekspor_ubah($id)
    {
        $model_belajarekspor = new BelajarEksporModel();
        $model_kategori = new KategoriBelajarEksporModel();

        $belajar_ekspor = $model_belajarekspor->find($id);
        $kategori_ekspor = $model_kategori->findAll();

        $data['belajar_ekspor'] = $belajar_ekspor;
        $data['kategori_belajar_ekspor'] = $kategori_ekspor;

        return view('admin/belajar-ekspor/edit', $data);
    }

    public function admin_belajar_ekspor_update($id)
    {
        $model_belajarekspor = new BelajarEksporModel();
        $model_kategori = new KategoriBelajarEksporModel();

        // Ambil data lama dari database
        $existingData = $model_belajarekspor->find($id);
        if (!$existingData) {
            return redirect()->to('/admin-belajar-ekspor')->with('error', 'Data tidak ditemukan.');
        }

        // Menyiapkan data yang akan diperbarui
        $data = [
            'judul_belajar_ekspor' => $this->request->getPost('judul_belajar_ekspor'),
            'judul_belajar_ekspor_en' => $this->request->getPost('judul_belajar_ekspor_en'),
            'id_kategori_belajar_ekspor' => $this->request->getPost('id_kategori'),
            'id_kategori_en' => $this->request->getPost('id_kategori'),
            'deskripsi_belajar_ekspor' => $this->request->getPost('deskripsi_belajar_ekspor'),
            'deskripsi_belajar_ekspor_en' => $this->request->getPost('deskripsi_belajar_ekspor_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_title_en' => $this->request->getPost('meta_title_en'),
            'meta_deskripsi' => $this->request->getPost('meta_deskripsi'),
            'meta_deskripsi_en' => $this->request->getPost('meta_deskripsi_en'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('foto_belajar_ekspor');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $existingData['foto_belajar_ekspor'])) {
                unlink(FCPATH . 'img/' . $existingData['foto_belajar_ekspor']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['foto_belajar_ekspor'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['foto_belajar_ekspor'] = $existingData['foto_belajar_ekspor'];
        }

        // Update data di database
        $model_belajarekspor->update($id, $data);

        // Redirect ke halaman dengan pesan sukses
        return redirect()->to('/admin-belajar-ekspor')->with('message', 'Data berhasil diperbarui.');
    }


    public function admin_belajar_ekspor_delete($id)
    {
        $model_belajarekspor = new BelajarEksporModel();

        $model_belajarekspor->delete($id);

        return redirect()->to('/admin-belajar-ekspor');
    }

    public function admin_kategori_belajar_ekspor()
    {
        $kategori_model = new KategoriBelajarEksporModel();

        $kategori = $kategori_model->findAll();

        $data['kategori_belajar_ekspor'] = $kategori;

        return view('admin/kategori-belajar/index', $data);
    }

    public function admin_kategori_belajar_ekspor_tambah()
    {
        return view('admin/kategori-belajar/tambah');
    }

    public function admin_kategori_belajar_ekspor_store()
    {

        $kategori_model = new KategoriBelajarEksporModel();

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'nama_kategori_en' => $this->request->getPost('nama_kategori_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        $kategori_model->insert($data);

        return redirect()->to('/admin-kategori-belajar-ekspor');
    }

    public function admin_kategori_belajar_ekspor_ubah($id)
    {
        $kategori_model = new KategoriBelajarEksporModel();

        $kategori = $kategori_model->find($id);

        $data['kategori_belajar_ekspor'] = $kategori;

        return view('admin/kategori-belajar/edit', $data);
    }

    public function admin_kategori_belajar_ekspor_update($id)
    {
        $kategori_model = new KategoriBelajarEksporModel();

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'nama_kategori_en' => $this->request->getPost('nama_kategori_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        $kategori_model->update($id, $data);

        return redirect()->to('/admin-kategori-belajar-ekspor');
    }

    public function admin_kategori_belajar_ekspor_delete($id)
    {
        $kategori_model = new KategoriBelajarEksporModel();

        $kategori_model->delete($id);

        return redirect()->to('/admin-kategori-belajar-ekspor');
    }

    public function admin_video_tutorial()
    {
        $model_video = new VideoTutorialModel();

        $video = $model_video->getAllWithCategory();

        $data['video_tutorial'] = $video;

        return view('admin/video-tutorial/index', $data);
    }

    public function admin_video_tutorial_tambah()
    {
        $model_kategori = new KategoriVideoModel();

        $kategori = $model_kategori->findAll();

        $data['nama_kategori_video'] = $kategori;

        return view('admin/video-tutorial/tambah', $data);
    }

    public function admin_video_tutorial_store()
    {
        $model_video = new VideoTutorialModel();

        $data = [
            'judul_video' => $this->request->getPost('judul_video'),
            'judul_video_en' => $this->request->getPost('judul_video_en'),
            'id_kategori_video' => $this->request->getPost('id_kategori'), // Menggunakan nilai asli, bukan hasil terjemahan
            'video_url' => $this->request->getPost('video_url'),
            'deskripsi_video' => $this->request->getPost('deskripsi_video'),
            'deskripsi_video_en' => $this->request->getPost('deskripsi_video_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        // Mengambil file gambar
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Beri nama acak pada file gambar untuk menghindari konflik nama
            $newName = $file->getRandomName();

            // Pindahkan file ke folder 'img' dengan nama baru
            $file->move('img/', $newName);

            // Simpan nama file gambar ke dalam array data untuk disimpan ke database
            $data['thumbnail'] = $newName;
        }

        $model_video->insert($data);

        return redirect()->to('/admin-video-tutorial');
    }

    public function admin_video_tutorial_ubah($id)
    {
        $model_video = new VideoTutorialModel();
        $model_kategori = new KategoriVideoModel();

        $video = $model_video->find($id);
        $kategori = $model_kategori->findAll();

        $data['video_tutorial'] = $video;
        $data['kategori_video'] = $kategori;

        return view('admin/video-tutorial/edit', $data);
    }

    public function admin_video_tutorial_update($id)
    {
        $model_video = new VideoTutorialModel();

        $existingData = $model_video->find($id);
        if (!$existingData) {
            return redirect()->to('/admin-belajar-ekspor')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'judul_video' => $this->request->getPost('judul_video'),
            'judul_video_en' => $this->request->getPost('judul_video_en'),
            'id_kategori_video' => $this->request->getPost('id_kategori'), // Menggunakan nilai asli, bukan hasil terjemahan
            'video_url' => $this->request->getPost('video_url'),
            'deskripsi_video' => $this->request->getPost('deskripsi_video'),
            'deskripsi_video_en' => $this->request->getPost('deskripsi_video_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $existingData['thumbnail'])) {
                unlink(FCPATH . 'img/' . $existingData['thumbnail']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['thumbnail'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['thumbnail'] = $existingData['thumbnail'];
        }

        $model_video->update($id, $data);

        return redirect()->to('/admin-video-tutorial');
    }

    public function admin_video_tutorial_delete($id)
    {
        $model_video = new VideoTutorialModel();

        $model_video->delete($id);

        return redirect()->to('/admin-video-tutorial');
    }

    public function admin_kategori_video_tutorial()
    {
        $model_video = new KategoriVideoModel();

        $video = $model_video->findAll();

        $data['kategori_video'] = $video;

        return view('admin/Kategori-video/index', $data);
    }

    public function admin_kategori_video_tutorial_tambah()
    {
        return view('admin/Kategori-video/tambah');
    }

    public function admin_kategori_vidio_tutorial_store()
    {
        $kategori_video = new KategoriVideoModel();

        $data = [
            'nama_kategori_video' => $this->request->getPost('kategori_vidio'),
            'nama_kategori_video_en' => $this->request->getPost('kategori_vidio_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        $kategori_video->insert($data);

        return redirect()->to('/admin-kategori-video-tutorial');
    }

    public function admin_kategori_video_tutorial_ubah($id)
    {
        $model_video = new KategoriVideoModel();

        $video = $model_video->find($id);

        $data['kategori_video'] = $video;

        return view('admin/Kategori-video/edit', $data);
    }

    public function admin_kategori_video_tutorial_update($id)
    {
        $kategori_video = new KategoriVideoModel();

        $data = [
            'nama_kategori_video' => $this->request->getPost('kategori_video'),
            'nama_kategori_video_en' => $this->request->getPost('kategori_video_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        $kategori_video->update($id, $data);

        return redirect()->to('/admin-kategori-video-tutorial');
    }

    public function admin_kategori_video_tutorial_delete($id)
    {
        $kategori_video_model = new KategoriVideoModel();

        $kategori_video_model->delete($id);

        return redirect()->to('/admin-kategori-video-tutorial');
    }

    // Admin Exwork
    public function admin_exwork()
    {
        $model_exwork = new Exwork();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $exwork = $model_exwork
            ->select('exwork.*, member.username AS username_member')
            ->join('member', 'member.id_member = exwork.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['exwork'] = $exwork;
        $data['pager'] = $model_exwork->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/exwork/index', $data);
    }

    public function admin_search_exwork()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_exwork = new Exwork();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_exwork
            ->select('exwork.*, member.username AS username_member')
            ->join('member', 'member.id_member = exwork.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('exwork.komponen_exwork', $keyword) // Pencarian di `komponen_exwork`
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_exwork->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/exwork/search', $data);
    }

    public function admin_add_exwork()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/exwork/add', $data);
    }

    public function admin_create_exwork()
    {
        $model_exwork = new Exwork();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-exwork')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_exwork' => $this->request->getPost('komponen_exwork'),
        ];

        $model_exwork->insert($data);

        return redirect()->to('/admin-exwork');
    }

    public function admin_edit_exwork($id)
    {
        $model_exwork = new Exwork();
        $model_member = new Member();

        $exwork = $model_exwork->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['exwork'] = $exwork;
        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/exwork/edit', $data);
    }

    public function admin_update_exwork($id)
    {
        $model_exwork = new Exwork();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-exwork')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_exwork' => $this->request->getPost('komponen_exwork'),
        ];

        $model_exwork->update($id, $data);

        return redirect()->to('/admin-exwork');
    }

    public function admin_delete_exwork($id)
    {
        $model_exwork = new Exwork();

        $model_exwork->delete($id);

        return redirect()->to('/admin-exwork');
    }

    // Admin FOB
    public function admin_fob()
    {
        $model_fob = new FOB();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $fob = $model_fob
            ->select('fob.*, member.username AS username_member')
            ->join('member', 'member.id_member = fob.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['fob'] = $fob;
        $data['pager'] = $model_fob->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/fob/index', $data);
    }

    public function admin_search_fob()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_fob = new FOB();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_fob
            ->select('fob.*, member.username AS username_member')
            ->join('member', 'member.id_member = fob.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('fob.komponen_fob', $keyword) // Pencarian di `komponen_fob`
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_fob->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/fob/search', $data);
    }

    public function admin_add_fob()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/fob/add', $data);
    }

    public function admin_create_fob()
    {
        $model_fob = new FOB();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-fob')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_fob' => $this->request->getPost('komponen_fob'),
        ];

        $model_fob->insert($data);

        return redirect()->to('/admin-fob');
    }

    public function admin_edit_fob($id)
    {
        $model_fob = new FOB();
        $model_member = new Member();

        $fob = $model_fob->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['fob'] = $fob;
        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/fob/edit', $data);
    }

    public function admin_update_fob($id)
    {
        $model_fob = new FOB();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-fob')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_fob' => $this->request->getPost('komponen_fob'),
        ];

        $model_fob->update($id, $data);

        return redirect()->to('/admin-fob');
    }

    public function admin_delete_fob($id)
    {
        $model_fob = new FOB();

        $model_fob->delete($id);

        return redirect()->to('/admin-fob');
    }

    // Admin CFR
    public function admin_cfr()
    {
        $model_cfr = new CFR();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $cfr = $model_cfr
            ->select('cfr.*, member.username AS username_member')
            ->join('member', 'member.id_member = cfr.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['cfr'] = $cfr;
        $data['pager'] = $model_cfr->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/cfr/index', $data);
    }

    public function admin_search_cfr()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_cfr = new CFR();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_cfr
            ->select('cfr.*, member.username AS username_member')
            ->join('member', 'member.id_member = cfr.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('cfr.komponen_cfr', $keyword) // Pencarian di `komponen_cfr`
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_cfr->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/cfr/search', $data);
    }

    public function admin_add_cfr()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/cfr/add', $data);
    }

    public function admin_create_cfr()
    {
        $model_cfr = new CFR();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-cfr')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_cfr' => $this->request->getPost('komponen_cfr'),
        ];

        $model_cfr->insert($data);

        return redirect()->to('/admin-cfr');
    }

    public function admin_edit_cfr($id)
    {
        $model_cfr = new CFR();
        $model_member = new Member();

        $cfr = $model_cfr->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['cfr'] = $cfr;
        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/cfr/edit', $data);
    }

    public function admin_update_cfr($id)
    {
        $model_cfr = new CFR();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-cfr')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_cfr' => $this->request->getPost('komponen_cfr'),
        ];

        $model_cfr->update($id, $data);

        return redirect()->to('/admin-cfr');
    }

    public function admin_delete_cfr($id)
    {
        $model_cfr = new CFR();

        $model_cfr->delete($id);

        return redirect()->to('/admin-cfr');
    }

    // Admin CIF
    public function admin_cif()
    {
        $model_cif = new CIF();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $cif = $model_cif
            ->select('cif.*, member.username AS username_member')
            ->join('member', 'member.id_member = cif.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['cif'] = $cif;
        $data['pager'] = $model_cif->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/cif/index', $data);
    }

    public function admin_search_cif()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_cif = new CIF();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_cif
            ->select('cif.*, member.username AS username_member')
            ->join('member', 'member.id_member = cif.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('cif.komponen_cif', $keyword) // Pencarian di `komponen_cif`
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_cif->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/cif/search', $data);
    }

    public function admin_add_cif()
    {
        $model_member = new Member();

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/cif/add', $data);
    }

    public function admin_create_cif()
    {
        $model_cif = new CIF();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-cif')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_cif' => $this->request->getPost('komponen_cif'),
        ];

        $model_cif->insert($data);

        return redirect()->to('/admin-cif');
    }

    public function admin_edit_cif($id)
    {
        $model_cif = new CIF();
        $model_member = new Member();

        $cif = $model_cif->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['cif'] = $cif;
        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/cif/edit', $data);
    }

    public function admin_update_cif($id)
    {
        $model_cif = new CIF();
        $model_member = new Member();

        $id_member = $this->request->getPost('id_member');

        $memberData = $model_member->where('id_member', $id_member)->where('role', 'member')->first();

        if (!$memberData) {
            return redirect()->to('/admin-cif')->withInput()->with('errors', ['ID Member tidak valid atau bukan seorang member']);
        }

        $data = [
            'id_member' => $id_member,
            'komponen_cif' => $this->request->getPost('komponen_cif'),
        ];

        $model_cif->update($id, $data);

        return redirect()->to('/admin-cif');
    }

    public function admin_delete_cif($id)
    {
        $model_cif = new CIF();

        $model_cif->delete($id);

        return redirect()->to('/admin-cif');
    }

    // Admin Satuan
    public function admin_satuan()
    {
        $model_satuan = new Satuan();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $satuan = $model_satuan
            ->select('satuan.*, member.username AS username_member')
            ->join('member', 'member.id_member = satuan.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['satuan'] = $satuan;
        $data['pager'] = $model_satuan->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/satuan/index', $data);
    }

    public function admin_search_satuan()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        $model_satuan = new Satuan();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_satuan
            ->select('satuan.*, member.username AS username_member')
            ->join('member', 'member.id_member = satuan.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('satuan.satuan', $keyword) // Pencarian di `satuan`
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_satuan->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kalkulator-ekspor/satuan/search', $data);
    }

    public function admin_edit_satuan($id)
    {
        $model_satuan = new Satuan();
        $model_member = new Member();

        $satuan = $model_satuan->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['satuan'] = $satuan;
        $data['member'] = $member;

        return view('admin/kalkulator-ekspor/satuan/edit', $data);
    }

    public function admin_update_satuan($id)
    {
        $model_satuan = new Satuan();

        $data = [
            'satuan' => $this->request->getPost('satuan'),
        ];

        $model_satuan->update($id, $data);

        return redirect()->to('/admin-satuan');
    }

    // Admin MPM
    public function admin_mpm()
    {
        $model_mpm = new MPM();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $mpm = $model_mpm
            ->select('mpm.*, member.username AS username_member')
            ->join('member', 'member.id_member = mpm.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        foreach ($mpm as &$item) {
            // Mengubah format tgl_kirim_email
            $tgl_kirim = date('d F Y', strtotime($item['tgl_kirim_email']));
            $bulanInggris_kirim = date('F', strtotime($item['tgl_kirim_email']));
            $item['tgl_kirim_email'] = str_replace($bulanInggris_kirim, $bulanIndonesia[$bulanInggris_kirim], $tgl_kirim);

            // Memeriksa jika update_terakhir bernilai null
            if (is_null($item['update_terakhir'])) {
                $item['update_terakhir'] = '';
            } else {
                // Mengubah format update_terakhir jika tidak null
                $tgl_update = date('d F Y', strtotime($item['update_terakhir']));
                $bulanInggris_update = date('F', strtotime($item['update_terakhir']));
                $item['update_terakhir'] = str_replace($bulanInggris_update, $bulanIndonesia[$bulanInggris_update], $tgl_update);
            }
        }

        $data['mpm'] = $mpm;
        $data['pager'] = $model_mpm->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/mpm/index', $data);
    }

    public function admin_search_mpm()
    {
        helper('text');

        $keyword = $this->request->getGet('keyword');
        $model_mpm = new MPM();
        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Initialize variables for different date parts
        $isFullDate = false;
        $isDateAndMonth = false;
        $isMonthAndYear = false;
        $dateKeyword = null;

        try {
            // Full date format (e.g., 15 November 2024)
            $date = DateTime::createFromFormat('d F Y', $keyword);
            if ($date) {
                $isFullDate = true;
                $dateKeyword = $date->format('Y-m-d');
            }
        } catch (Exception $e) {
        }

        if (!$isFullDate) {
            // Date and month format (e.g., 15 November)
            $dateAndMonth = DateTime::createFromFormat('d F', $keyword);
            if ($dateAndMonth) {
                $isDateAndMonth = true;
                $dateKeyword = $dateAndMonth->format('-m-d'); // Matches day and month (e.g., -11-15)
            } else {
                // Month and year format (e.g., November 2024)
                $monthAndYear = DateTime::createFromFormat('F Y', $keyword);
                if ($monthAndYear) {
                    $isMonthAndYear = true;
                    $dateKeyword = $monthAndYear->format('Y-m-'); // Matches month and year (e.g., 2024-11-)
                }
            }
        }

        $hasilPencarian = $model_mpm
            ->select('mpm.*, member.username AS username_member')
            ->join('member', 'member.id_member = mpm.id_member', 'left')
            ->groupStart()
            ->like('mpm.tgl_kirim_email', $dateKeyword ?: $keyword)
            ->orLike('mpm.update_terakhir', $dateKeyword ?: $keyword)
            ->orLike('mpm.nama_perusahaan', $keyword)
            ->orLike('mpm.negara_perusahaan', $keyword)
            ->orLike('mpm.status_progres', $keyword)
            ->orLike('mpm.progres', $keyword)
            ->orLike('member.username', $keyword)
            ->groupEnd()
            ->where('role', 'member')
            ->paginate($perPage);

        // Indonesian months for formatting
        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        foreach ($hasilPencarian as &$item) {
            $tgl_kirim = date('d F Y', strtotime($item['tgl_kirim_email']));
            $bulanInggris_kirim = date('F', strtotime($item['tgl_kirim_email']));
            $item['tgl_kirim_email'] = str_replace($bulanInggris_kirim, $bulanIndonesia[$bulanInggris_kirim], $tgl_kirim);

            if (is_null($item['update_terakhir'])) {
                $item['update_terakhir'] = '';
            } else {
                $tgl_update = date('d F Y', strtotime($item['update_terakhir']));
                $bulanInggris_update = date('F', strtotime($item['update_terakhir']));
                $item['update_terakhir'] = str_replace($bulanInggris_update, $bulanIndonesia[$bulanInggris_update], $tgl_update);
            }
        }

        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_mpm->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/mpm/search', $data);
    }

    // Admin Website Audit
    public function admin_website_audit()
    {
        $model_website_audit = new WebsiteAudit();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        // Query with join to get `username` from `member` table
        $website_audit = $model_website_audit
            ->select('website_audit.*, member.username AS username_member')
            ->join('member', 'member.id_member = website_audit.id_member', 'left')
            ->where('role', 'member')
            ->paginate($perPage);

        $data['website_audit'] = $website_audit;
        $data['pager'] = $model_website_audit->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/website-audit/index', $data);
    }

    public function admin_search_website_audit()
    {
        helper('text');

        // Ambil keyword dari query string
        $keyword = $this->request->getGet('keyword');

        // Map keywords for status_verifikasi
        $mappedKeyword = null;
        switch (strtolower($keyword)) {
            case 'sesuai':
                $mappedKeyword = 'true';
                break;
            case 'tidak sesuai' || 'tidak':
                $mappedKeyword = 'false';
                break;
            case 'waiting':
                $mappedKeyword = 'waiting';
                break;
            default:
                $mappedKeyword = $keyword;
                break;
        }

        $model_website_audit = new WebsiteAudit();

        // Set pagination
        $perPage = 10; // Jumlah item per halaman
        $page = $this->request->getVar('page') ?? 1; // Mendapatkan halaman saat ini

        // Query pencarian dengan join ke tabel `member` untuk mendapatkan `username`
        $hasilPencarian = $model_website_audit
            ->select('website_audit.*, member.username AS username_member')
            ->join('member', 'member.id_member = website_audit.id_member', 'left')
            ->groupStart() // Memulai grup kondisi
            ->like('website_audit.link_website', $keyword) // Pencarian di `website_audit`
            ->orLike('website_audit.catatan_fitur', $keyword)
            ->orLike('website_audit.catatan_bahasa', $keyword)
            ->orLike('website_audit.catatan_seo', $keyword)
            ->orLike('website_audit.status_verifikasi', $mappedKeyword) // Use mappedKeyword here
            ->orLike('member.username', $keyword) // Pencarian di `username` dari `member`
            ->groupEnd() // Mengakhiri grup kondisi
            ->where('role', 'member')
            ->paginate($perPage);

        // Jika ada hasil pencarian
        $data['hasilPencarian'] = $hasilPencarian;
        $data['keyword'] = $keyword;
        $data['pager'] = $model_website_audit->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/website-audit/search', $data);
    }

    public function admin_process_website_audit($id)
    {
        $model_webaudit = new WebsiteAudit();
        $model_member = new Member();

        $webaudit = $model_webaudit->find($id);

        $member = $model_member->where('role', 'member')->select('id_member, username')->findAll();

        $data['webaudit'] = $webaudit;
        $data['member'] = $member;

        return view('admin/website-audit/process', $data);
    }

    public function admin_done_website_audit($id)
    {
        $model_webaudit = new WebsiteAudit();

        $fitur = $this->request->getPost('fitur');
        $bahasa = $this->request->getPost('bahasa');
        $seo = $this->request->getPost('seo');

        $catatan_fitur_profil_perusahaan = "Fitur Profil Perusahaan masih belum benar";
        $catatan_fitur_katalog_produk = "Fitur Katalog Produk masih belum benar";
        $catatan_fitur_kontak = "Fitur Kontak masih belum benar";
        $catatan_fitur_blog_artikel = "Fitur Blog / Artikel Edukasi masih belum benar";
        $catatan_fitur_aktivitas_berita = "Fitur Aktivitas Perusahaan / Berita masih belum benar";
        $catatan_fitur_sosmed_marketplace = "Fitur Integrasi Ke Social Media / Marketplace masih belum benar";
        $catatan_bahasa_inggris = "Bahasa Inggris masih belum benar";
        $catatan_bahasa_indonesia = "Bahasa Indonesia masih belum benar";
        $catatan_seo_meta_tags = "SEO Meta Tags Optimalisasi masih belum benar";
        $catatan_seo_struktur_data = "SEO Struktur Data masih belum benar";
        $catatan_seo_keyword_research = "SEO Keyword Research masih belum benar";

        if (!empty($fitur)) {
            if (in_array('Profil Perusahaan', $fitur)) {
                $catatan_fitur_profil_perusahaan = "";
            }
            if (in_array('Katalog Produk', $fitur)) {
                $catatan_fitur_katalog_produk = "";
            }
            if (in_array('Kontak', $fitur)) {
                $catatan_fitur_kontak = "";
            }
            if (in_array('Blog / Artikel Edukasi', $fitur)) {
                $catatan_fitur_blog_artikel = "";
            }
            if (in_array('Aktivitas Perusahaan / Berita', $fitur)) {
                $catatan_fitur_aktivitas_berita = "";
            }
            if (in_array('Integrasi Ke Social Media / Marketplace', $fitur)) {
                $catatan_fitur_sosmed_marketplace = "";
            }
        }
        if (!empty($bahasa)) {
            if (in_array('Inggris', $bahasa)) {
                $catatan_bahasa_inggris = "";
            }
            if (in_array('Indonesia', $bahasa)) {
                $catatan_bahasa_indonesia = "";
            }
        }
        if (!empty($seo)) {
            if (in_array('Meta Tags Optimalisasi', $seo)) {
                $catatan_seo_meta_tags = "";
            }
            if (in_array('Struktur Data', $seo)) {
                $catatan_seo_struktur_data = "";
            }
            if (in_array('Keyword Research', $seo)) {
                $catatan_seo_keyword_research = "";
            }
        }

        $catatan_fitur = implode(', ', array_filter([
            $catatan_fitur_profil_perusahaan,
            $catatan_fitur_katalog_produk,
            $catatan_fitur_kontak,
            $catatan_fitur_blog_artikel,
            $catatan_fitur_aktivitas_berita,
            $catatan_fitur_sosmed_marketplace
        ]));
        $catatan_bahasa = implode(', ', array_filter([
            $catatan_bahasa_inggris,
            $catatan_bahasa_indonesia
        ]));
        $catatan_seo = implode(', ', array_filter([
            $catatan_seo_meta_tags,
            $catatan_seo_struktur_data,
            $catatan_seo_keyword_research
        ]));

        $status_verifikasi = empty($catatan_fitur) && empty($catatan_bahasa) && empty($catatan_seo) ? 'true' : 'false';

        $data = [
            'status_verifikasi' => $status_verifikasi,
            'catatan_fitur' => $catatan_fitur,
            'catatan_bahasa' => $catatan_bahasa,
            'catatan_seo' => $catatan_seo,
        ];

        $model_webaudit->update($id, $data);

        return redirect()->to('/admin-website-audit');
    }

    public function admin_pengumuman()
    {
        $model_pengumuman = new Pengumuman();

        $pengumuman = $model_pengumuman->findAll();

        $data['pengumuman'] = $pengumuman;

        return view('admin/pengumuman/index', $data);
    }

    public function admin_add_pengumuman_create()
    {
        $model_pengumuman = new Pengumuman();

        $data = [
            'judul_pengumuman' => $this->request->getPost('judul_pengumuman'),
            'deskripsi_pengumuman' => $this->request->getPost('deskripsi_pengumuman'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'slug' => $this->request->getPost('slug')
        ];

        // Mengambil file gambar
        $file = $this->request->getFile('poster_pengumuman');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Beri nama acak pada file gambar untuk menghindari konflik nama
            $newName = $file->getRandomName();

            // Pindahkan file ke folder 'img' dengan nama baru
            $file->move('img/', $newName);

            // Simpan nama file gambar ke dalam array data untuk disimpan ke database
            $data['poster_pengumuman'] = $newName;
        }

        $model_pengumuman->insert($data);

        return redirect()->to('/admin-pengumuman');
    }

    public function admin_add_pengumuman()
    {
        return view('admin/pengumuman/add');
    }

    public function admin_edit_pengumuman($id)
    {
        $model_pengumuman = new Pengumuman();

        $pengumuman = $model_pengumuman->find($id);

        $data['pengumuman'] = $pengumuman;

        return view('admin/pengumuman/edit', $data);
    }

    public function admin_update_pengumuman($id)
    {
        $model_pengumuman = new Pengumuman();

        $existingData = $model_pengumuman->find($id);
        if (!$existingData) {
            return redirect()->to('/admin-pengumuman')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'judul_pengumuman' => $this->request->getPost('judul_pengumuman'),
            'deskripsi_pengumuman' => $this->request->getPost('deskripsi_pengumuman'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'slug' => $this->request->getPost('slug')
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('poster_pengumuman');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $existingData['poster_pengumuman'])) {
                unlink(FCPATH . 'img/' . $existingData['poster_pengumuman']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['poster_pengumuman'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['poster_pengumuman'] = $existingData['poster_pengumuman'];
        }

        $model_pengumuman->update($id, $data);

        return redirect()->to('/admin-pengumuman');
    }

    public function admin_delete_pengumuman($id)
    {
        $model_pengumuman = new Pengumuman();

        $model_pengumuman->delete($id);

        return redirect()->to('/admin-pengumuman');
    }

    public function admin_manfaat_join()
    {
        $model_manfaatjoin = new ManfaatJoin();

        $manfaat = $model_manfaatjoin->findAll();

        $data['manfaatjoin'] = $manfaat;

        return view('admin/manfaat-join/index', $data);
    }

    public function admin_edit_manfaat_join($id)
    {

        $model_manfaatjoin = new ManfaatJoin();

        $manfaat = $model_manfaatjoin->find($id);

        $data['manfaatjoin'] = $manfaat;

        return view('admin/manfaat-join/edit', $data);
    }

    public function admin_update_manfaat_join($id)
    {
        $model_manfaatjoin = new ManfaatJoin();

        $existingData = $model_manfaatjoin->find($id);
        if (!$existingData) {
            return redirect()->to('/admin-pengumuman')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'judul_manfaat' => $this->request->getPost('judul_manfaat'),
            'judul_manfaat_en' => $this->request->getPost('judul_manfaat_en'),
            'deskripsi_manfaat' => $this->request->getPost('deskripsi_manfaat'),
            'deskripsi_manfaat_en' => $this->request->getPost('deskripsi_manfaat_en'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $existingData['gambar'])) {
                unlink(FCPATH . 'img/' . $existingData['gambar']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['gambar'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['gambar'] = $existingData['gambar'];
        }

        $model_manfaatjoin->update($id, $data);

        return redirect()->to('/admin-manfaat-join');
    }

    public function admin_slider()
    {
        $model_slider = new Slider();

        $slider = $model_slider->findAll();

        $data['slider'] = $slider;

        return view('admin/slider/index', $data);
    }

    public function admin_edit_slider($id)
    {
        $model_slider = new Slider();

        $slider = $model_slider->find($id);

        $data['slider'] = $slider;

        return view('admin/slider/edit', $data);
    }

    public function admin_update_slider($id)
    {
        $model_slider = new Slider();

        $slider = $model_slider->find($id);

        $data = [
            'judul_slider' => $this->request->getPost('judul_slider'),
            'judul_slider_en' => $this->request->getPost('judul_slider_en'),
            'deskripsi_slider' => $this->request->getPost('deskripsi_slider'),
            'deskripsi_slider_en' => $this->request->getPost('deskripsi_slider_en'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('img_slider');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $slider['img_slider'])) {
                unlink(FCPATH . 'img/' . $slider['img_slider']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['img_slider'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['img_slider'] = $slider['img_slider'];
        }


        $model_slider->update($id, $data);

        return redirect()->to('/admin-slider');
    }


    public function admin_web_profile()
    {
        $Model_webprofile = new WebProfile();

        $webprofile = $Model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        return view('admin/web-profile/index', $data);
    }

    public function admin_edit_web_profile($id)
    {
        $Model_webprofile = new WebProfile();

        $webprofile = $Model_webprofile->find($id);

        $data['webprofile'] = $webprofile;

        return view('admin/web-profile/edit', $data);
    }

    public function admin_update_webprofile($id)
    {
        $Model_webprofile = new WebProfile();

        $webprofile = $Model_webprofile->find($id);

        $data = [
            'nama_web' => $this->request->getPost('nama_web'),
            'nama_web_en' => $this->request->getPost('nama_web_en'),
            'deskripsi_web' => $this->request->getPost('deskripsi_webprofile'),
            'deskripsi_web_en' => $this->request->getPost('deskripsi_webprofile_en'),
            'nohp_web' => $this->request->getPost('nohp_web'),
            'email_web' => $this->request->getPost('email_web'),
            'link_ig_web' => $this->request->getPost('link_ig_web'),
            'link_yt_web' => $this->request->getPost('link_yt_web'),
            'link_fb_web' => $this->request->getPost('link_fb_web'),
            'judul_ajakan' => $this->request->getPost('judul_ajakan'),
            'judul_ajakan_en' => $this->request->getPost('judul_ajakan_en'),
            'deskripsi_ajakan' => $this->request->getPost('deskripsi_ajakan'),
            'deskripsi_ajakan_en' => $this->request->getPost('deskripsi_ajakan_en'),
            'footer_text' => $this->request->getPost('footer_text'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('logo_web');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $webprofile['logo_web'])) {
                unlink(FCPATH . 'img/' . $webprofile['logo_web']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['logo_web'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['logo_web'] = $webprofile['logo_web'];
        }

        $Model_webprofile->update($id, $data);

        return redirect()->to('/admin-web-profile');
    }

    // Tentang Kami
    public function admin_tentang_kami()
    {
        $model_tentang = new TentangKami();

        $tentang = $model_tentang->findAll();

        $data['tentang_kami'] = $tentang;

        return view('admin/tentang-kami/index', $data);
    }

    public function edit_admin_tentang_kami($id)
    {
        $model_tentang = new TentangKami();

        $tentang = $model_tentang->find($id);

        $data['tentang_kami'] = $tentang;

        return view('admin/tentang-kami/edit', $data);
    }

    public function update_admin_tentang_kami($id)
    {
        $model_tentang = new TentangKami();

        $tentang = $model_tentang->find($id);

        $data = [
            'deskripsi_perusahaan' => $this->request->getPost('deskripsi_perusahaan'),
            'deskripsi_perusahaan_en' => $this->request->getPost('deskripsi_perusahaan_en'),
            'slug' => $this->request->getPost('slug'),
            'slug_en' => $this->request->getPost('slug_en'),
        ];

        // Menangani upload gambar jika ada file baru
        $file = $this->request->getFile('gambar_perusahaan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Menghapus gambar lama jika ada dan file baru berhasil diunggah
            if (file_exists(FCPATH . 'img/' . $tentang['gambar_perusahaan'])) {
                unlink(FCPATH . 'img/' . $tentang['gambar_perusahaan']);
            }

            // Simpan gambar baru dan tambahkan ke data
            $newName = $file->getRandomName();
            $file->move('img/', $newName);
            $data['gambar_perusahaan'] = $newName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['gambar_perusahaan'] = $tentang['gambar_perusahaan'];
        }

        $model_tentang->update($id, $data);

        return redirect()->to('/admin-tentang-kami');
    }

    // Invesment
    public function kelayakan_investasi()
    {
        $model_webprofile = new WebProfile();

        $webprofile = $model_webprofile->findAll();

        $data['webprofile'] = $webprofile;

        return view('premium/kelayakan-investasi/kelayakan-investasi', $data);
    }

    // Kategori Induk
    public function admin_kategori_induk()
    {
        $model_kategori_induk = new KategoriInduk();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $kategori_induk = $model_kategori_induk->paginate($perPage);

        $data['kategori_induk'] = $kategori_induk;
        $data['pager'] = $model_kategori_induk->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kategori-induk/index', $data);
    }

    public function admin_kategori_induk_create()
    {
        return view('admin/kategori-induk/tambah');
    }

    public function admin_kategori_induk_store()
    {
        $model_kategori_induk = new KategoriInduk();

        $data = [
            'nama_kategori_induk' => $this->request->getPost('nama_kategori_induk'),
            'nama_kategori_induk_en' => $this->request->getPost('nama_kategori_induk_en'),
        ];

        $model_kategori_induk->insert($data);

        return redirect()->to('admin-kategori-induk');
    }

    public function admin_kategori_induk_edit($id)
    {
        $model_kategori_induk = new KategoriInduk();

        $kategori_induk = $model_kategori_induk->find($id);

        $data['kategori_induk'] = $kategori_induk;

        return view('admin/kategori-induk/edit', $data);
    }

    public function admin_kategori_induk_update($id)
    {
        $model_kategori_induk = new KategoriInduk();

        $data = [
            'nama_kategori_induk' => $this->request->getPost('nama_kategori_induk'),
            'nama_kategori_induk_en' => $this->request->getPost('nama_kategori_induk_en'),
        ];

        $model_kategori_induk->update($id, $data);

        return redirect()->to('admin-kategori-induk');
    }

    public function admin_kategori_induk_destroy($id)
    {
        $model_kategori_induk = new KategoriInduk();

        $model_kategori_induk->delete($id);

        return redirect()->to('admin-kategori-induk');
    }

    // Kategori Produk
    public function admin_kategori_produk()
    {
        $model_kategori_produk = new KategoriProduk();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $kategori_produk = $model_kategori_produk
            ->select('kategori_produk.*, kategori_induk.nama_kategori_induk AS kategori_induk')
            ->join('kategori_induk', 'kategori_induk.id_kategori_induk = kategori_produk.id_kategori_induk', 'left')
            ->paginate($perPage);

        $data['kategori_produk'] = $kategori_produk;
        $data['pager'] = $model_kategori_produk->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/kategori-produk/index', $data);
    }

    public function admin_kategori_produk_create()
    {
        $model_kategori_induk = new KategoriInduk();
        $model_kategori_produk = new KategoriProduk();

        $kategori_induk = $model_kategori_induk->findAll();
        $kategori_produk = $model_kategori_produk->findAll();

        $data['kategori_induk'] = $kategori_induk;
        $data['kategori_produk'] = $kategori_produk;

        return view('admin/kategori-produk/tambah', $data);
    }

    public function admin_kategori_produk_store()
    {
        $model_kategori_produk = new KategoriProduk();

        $data = [
            'id_kategori_induk' => $this->request->getPost('id_kategori_induk'),
            'nama_kategori_produk' => $this->request->getPost('nama_kategori_produk'),
            'nama_kategori_produk_en' => $this->request->getPost('nama_kategori_produk_en'),
        ];

        $model_kategori_produk->insert($data);

        return redirect()->to('admin-kategori-produk');
    }

    public function admin_kategori_produk_edit($id)
    {
        $model_kategori_induk = new KategoriInduk();
        $model_kategori_produk = new KategoriProduk();

        $kategori_induk = $model_kategori_induk->findAll();
        $kategori_produk = $model_kategori_produk->find($id);

        $data['kategori_induk'] = $kategori_induk;
        $data['kategori_produk'] = $kategori_produk;

        return view('admin/kategori-produk/edit', $data);
    }

    public function admin_kategori_produk_update($id)
    {
        $model_kategori_produk = new KategoriProduk();

        $data = [
            'id_kategori_induk' => $this->request->getPost('id_kategori_induk'),
            'nama_kategori_produk' => $this->request->getPost('nama_kategori_produk'),
            'nama_kategori_produk_en' => $this->request->getPost('nama_kategori_produk_en'),
        ];

        $model_kategori_produk->update($id, $data);

        return redirect()->to('admin-kategori-produk');
    }

    public function admin_kategori_produk_destroy($id)
    {
        $model_kategori_produk = new KategoriProduk();

        $model_kategori_produk->delete($id);

        return redirect()->to('admin-kategori-produk');
    }

    // Meta
    public function admin_meta()
    {
        $model_meta = new Meta();

        $meta = $model_meta->findAll();

        $data['meta'] = $meta;

        return view('admin/meta/index', $data);
    }

    public function admin_edit_meta()
    {
        $model_meta = new Meta();

        $meta = $model_meta->first();

        $data['meta'] = $meta;

        return view('admin/meta/edit', $data);
    }

    public function admin_update_meta()
    {
        $model_meta = new Meta();

        $meta = $model_meta->first();

        $data = [
            'title_beranda' => $this->request->getPost('title_beranda'),
            'title_beranda_en' => $this->request->getPost('title_beranda_en'),
            'meta_description_beranda' => $this->request->getPost('meta_description_beranda'),
            'meta_description_beranda_en' => $this->request->getPost('meta_description_beranda_en'),
            'title_tentang' => $this->request->getPost('title_tentang'),
            'title_tentang_en' => $this->request->getPost('title_tentang_en'),
            'meta_description_tentang' => $this->request->getPost('meta_description_tentang'),
            'meta_description_tentang_en' => $this->request->getPost('meta_description_tentang_en'),
            'title_materi' => $this->request->getPost('title_materi'),
            'title_materi_en' => $this->request->getPost('title_materi_en'),
            'meta_description_materi' => $this->request->getPost('meta_description_materi'),
            'meta_description_materi_en' => $this->request->getPost('meta_description_materi_en'),
            'title_tutorial' => $this->request->getPost('title_tutorial'),
            'title_tutorial_en' => $this->request->getPost('title_tutorial_en'),
            'meta_description_tutorial' => $this->request->getPost('meta_description_tutorial'),
            'meta_description_tutorial_en' => $this->request->getPost('meta_description_tutorial_en'),
            'title_member' => $this->request->getPost('title_member'),
            'title_member_en' => $this->request->getPost('title_member_en'),
            'meta_description_member' => $this->request->getPost('meta_description_member'),
            'meta_description_member_en' => $this->request->getPost('meta_description_member_en'),
            'title_daftar' => $this->request->getPost('title_daftar'),
            'title_daftar_en' => $this->request->getPost('title_daftar_en'),
            'meta_description_daftar' => $this->request->getPost('meta_description_daftar'),
            'meta_description_daftar_en' => $this->request->getPost('meta_description_daftar_en'),
        ];

        $model_meta->update($meta['id_meta'], $data);

        return redirect()->to('admin-meta');
    }

    public function admin_fitur()
    {
        $model_fitur = new Fitur();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $fitur = $model_fitur
            ->paginate($perPage);

        $data['fitur'] = $fitur;
        $data['pager'] = $model_fitur->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/fitur/index', $data);
    }

    public function admin_fitur_add()
    {
        return view('admin/fitur/add');
    }

    public function admin_fitur_create()
    {
        $model_fitur = new Fitur();

        $data = [
            'role' => $this->request->getPost('role'),
            'nama_fitur' => $this->request->getPost('nama_fitur'),
            'nama_fitur_en' => $this->request->getPost('nama_fitur_en'),
        ];

        $model_fitur->insert($data);

        return redirect()->to('admin-fitur');
    }

    public function admin_fitur_edit($id)
    {
        $model_fitur = new Fitur();

        $fitur = $model_fitur->find($id);

        $data['fitur'] = $fitur;

        return view('admin/fitur/edit', $data);
    }

    public function admin_fitur_update($id)
    {
        $model_fitur = new Fitur();

        $data = [
            'role' => $this->request->getPost('role'),
            'nama_fitur' => $this->request->getPost('nama_fitur'),
            'nama_fitur_en' => $this->request->getPost('nama_fitur_en'),
        ];

        $model_fitur->update($id, $data);

        return redirect()->to('admin-fitur');
    }

    public function admin_fitur_delete($id)
    {
        $model_fitur = new Fitur();

        $model_fitur->delete($id);

        return redirect()->to('admin-fitur');
    }

    public function admin_keuntungan()
    {
        $model_keuntungan = new Keuntungan();

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $keuntungan = $model_keuntungan
            ->paginate($perPage);

        $data['keuntungan'] = $keuntungan;
        $data['pager'] = $model_keuntungan->pager;
        $data['page'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/keuntungan-daftar/index', $data);
    }

    public function admin_keuntungan_add()
    {
        return view('admin/keuntungan-daftar/add');
    }

    public function admin_keuntungan_create()
    {
        $model_keuntungan = new Keuntungan();

        $data = [
            'judul_keuntungan' => $this->request->getPost('judul_keuntungan'),
            'judul_keuntungan_en' => $this->request->getPost('judul_keuntungan_en'),
            'deskripsi_keuntungan' => $this->request->getPost('deskripsi_keuntungan'),
            'deskripsi_keuntungan_en' => $this->request->getPost('deskripsi_keuntungan_en'),
        ];

        $model_keuntungan->insert($data);

        return redirect()->to('admin-keuntungan');
    }

    public function admin_keuntungan_edit($id)
    {
        $model_keuntungan = new Keuntungan();

        $keuntungan = $model_keuntungan->find($id);

        $data['keuntungan'] = $keuntungan;

        return view('admin/keuntungan-daftar/edit', $data);
    }

    public function admin_keuntungan_update($id)
    {
        $model_keuntungan = new Keuntungan();

        $data = [
            'judul_keuntungan' => $this->request->getPost('judul_keuntungan'),
            'judul_keuntungan_en' => $this->request->getPost('judul_keuntungan_en'),
            'deskripsi_keuntungan' => $this->request->getPost('deskripsi_keuntungan'),
            'deskripsi_keuntungan_en' => $this->request->getPost('deskripsi_keuntungan_en'),
        ];

        $model_keuntungan->update($id, $data);

        return redirect()->to('admin-keuntungan');
    }

    public function admin_keuntungan_delete($id)
    {
        $model_keuntungan = new Keuntungan();

        $model_keuntungan->delete($id);

        return redirect()->to('admin-keuntungan');
    }

    public function syarat_ketentuan()
    {
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();
        $data['webprofile'] = $webprofile;

        $model_meta = new Meta();
        $meta = $model_meta
            ->select('title_syarat, title_syarat_en')
            ->first();
        $data['meta'] = $meta;

        $lang = session()->get('lang') ?? 'id';
        $data['lang'] = $lang;

        return view('pendaftaran/syarat_ketentuan', $data);
    }
}
