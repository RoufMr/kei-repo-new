<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>
<style>
    /* Custom style for the result */
    .result-harga-exwork,
    .result-harga-fob,
    .result-harga-cfr,
    .result-harga-cif {
        color: red;
        /* Set text color to red */
        font-size: 1.5em;
        /* Increase font size */
    }

    /* Initially hide the submit button and komponen container */
    #komponenExworkContainer,
    #komponenFOBContainer,
    #komponenCFRContainer,
    #komponenCIFContainer,
    #submitKomponenExworkButton,
    #submitKomponenFOBButton,
    #submitKomponenCFRButton,
    #submitKomponenCIFButton {
        display: none;
    }

    /* Membuat tabel bisa digeser ke kanan jika kolomnya terlalu panjang */
    .table-responsive {
        overflow-y: auto;
        width: 100%;
    }

    .table {
        min-height: 500px;
        /* Menjaga agar tabel tetap panjang */
    }

    .nav-link {
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
        /* Menambah jarak antar field */
    }

    .btn-custom {
        text-align: center;
        color: #ffffff;
    }

    .btn-custom:hover {
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0px 0px 10px #F2BF02;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #F2BF02 !important;
        /* Mengubah warna saat hover menjadi #F2BF02 */
    }

    .table td,
    .table th {
        white-space: normal !important;
        /* biar teks panjang bisa turun ke bawah */
        word-wrap: break-word;
        /* potong kata panjang */
        vertical-align: top !important;
        /* isi cell mulai dari atas */
        height: auto !important;
        /* tinggi mengikuti isi */
    }

    /* Calendar Styles */
    .calendar-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 20px 0;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 0 10px;
    }

    .month-navigation {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .nav-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .current-month {
        font-size: 28px;
        font-weight: bold;
        color: #2d3748;
        text-align: center;
        min-width: 250px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        background: #e2e8f0;
        border-radius: 10px;
        padding: 2px;
    }

    .calendar-day-header {
        background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
        color: white;
        padding: 15px 5px;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
    }

    .calendar-day {
        background: white;
        border-radius: 8px;
        min-height: 100px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        border: 2px solid transparent;
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: #4a90e2;
    }

    .calendar-day.other-month {
        opacity: 0.3;
        background: #f8f9fa;
    }

    .calendar-day.today {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
        font-weight: bold;
    }

    .calendar-day.has-content {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        color: white;
    }

    .day-number {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .content-indicator {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        margin-top: 5px;
    }

    .content-badge {
        font-size: 8px;
        padding: 2px 4px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.9);
        color: #2d3748;
        font-weight: 500;
    }

    .content-count {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ff6b6b;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
    }

    /* Modal Styles */
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .content-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #4a90e2;
        transition: all 0.3s ease;
    }

    .content-item:hover {
        background: #e3f2fd;
        transform: translateX(5px);
    }

    .content-type-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
        margin-right: 8px;
    }

    .type-reels {
        background: #ff6b6b;
        color: white;
    }

    .type-singlepost {
        background: #4ecdc4;
        color: white;
    }

    .type-carousel {
        background: #45b7d1;
        color: white;
    }

    .type-story {
        background: #96ceb4;
        color: white;
    }

    .platform-badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        margin-right: 4px;
    }

    .platform-instagram {
        background: #e1306c;
        color: white;
    }

    .platform-tiktok {
        background: #000000;
        color: white;
    }

    .platform-youtube {
        background: #ff0000;
        color: white;
    }

    .platform-facebook {
        background: #1877f2;
        color: white;
    }

    .no-content {
        text-align: center;
        color: #718096;
        font-style: italic;
        padding: 40px 20px;
    }

    .add-content-btn {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .add-content-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(64, 192, 87, 0.4);
        color: white;
    }

    /* Legend */
    .legend {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #4a5568;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 3px;
    }

    .legend-today {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    }

    .legend-content {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
    }

    .legend-empty {
        background: white;
        border: 1px solid #e2e8f0;
    }
    

    @media (max-width: 768px) {
        .calendar-grid {
            font-size: 12px;
        }

        .calendar-day {
            min-height: 80px;
            padding: 4px;
        }

        .current-month {
            font-size: 20px;
            min-width: 200px;
        }
    }
</style>

<div class="py-5" style="text-align: center;">
    <h2 class="text-custom-title">Sosial Media Planner</h2>
    <p class="text-custom-paragraph mt-2">Berikut aplikasi Sosial Media Planner</p>
</div>

<div class="container">
    <div class="row justify-content-center">
        <!-- Content Pillar Section -->
        <div class="card shadow p-4 m-1 col-5">
            <h3 class="text-center mb-4">Content Pillar</h3>

            <!-- Tabel untuk menampilkan komponen Content Pillar -->
            <div class="table-responsive">
                <table class="table table-bordered mb-3">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Nama</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kontenpilar)) : ?>
                            <?php foreach ($kontenpilar as $index => $kp) : ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($kp['nama']) ?></td>
                                    <td><?= esc($kp['deskripsi']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal<?= $kp['id'] ?>">
                                            Edit
                                        </button>
                                        <a href="<?= base_url('/sosmed-planner/konten-pilar/hapus/' . $kp['id']) ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus konten pilar ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editModal<?= $kp['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $kp['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title" id="editModalLabel<?= $kp['id'] ?>">Edit Content Pillar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <form action="<?= base_url('/sosmed-planner/konten-pilar/update/' . $kp['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama<?= $kp['id'] ?>" class="form-label">Nama</label>
                                                        <input type="text" id="nama<?= $kp['id'] ?>" name="nama"
                                                            class="form-control" value="<?= esc($kp['nama']) ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="deskripsi<?= $kp['id'] ?>" class="form-label">Deskripsi</label>
                                                        <input type="text" id="deskripsi<?= $kp['id'] ?>" name="deskripsi"
                                                            class="form-control" value="<?= esc($kp['deskripsi']) ?>">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data Content Pillar</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Tambah Komponen di bawah tabel (rata tengah) -->
            <div class="text-center mt-3">
                <button type="button" class="btn btn-custom mb-2" style="background-color: #03AADE;"
                    data-bs-toggle="modal" data-bs-target="#tambahModal">
                    Tambah Content Pillar
                </button>
            </div>
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Content Pillar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <form action="<?= base_url('/sosmed-planner/konten-pilar/tambah') ?>" method="post" id="formTambahKonten">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div id="containerTambahKonten">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="nama_kontenpilar[]" placeholder="Nama Komponen" required>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="deskripsi_kontenpilar[]" placeholder="Deskripsi Komponen">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="mt-2" style="border: 1px solid black; background-color: black;">
        </div>

        <!-- Content Type Section -->
        <div class="card shadow p-4 m-1 col-3">
            <h3 class="text-center mb-4">Content Type</h3>

            <div class="table-responsive">
                <table class="table table-bordered mb-3">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Nama</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kontentype)) : ?>
                            <?php foreach ($kontentype as $index => $kt) : ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($kt['nama']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModalType<?= $kt['id'] ?>">
                                            Edit
                                        </button>
                                        <a href="<?= base_url('/sosmed-planner/konten-type/hapus/' . $kt['id']) ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus konten type ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModalType<?= $kt['id'] ?>" tabindex="-1" aria-labelledby="editModalTypeLabel<?= $kt['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title" id="editModalTypeLabel<?= $kt['id'] ?>">Edit Content Type</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <form action="<?= base_url('/sosmed-planner/konten-type/update/' . $kt['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama<?= $kt['id'] ?>" class="form-label">Nama</label>
                                                        <input type="text" id="nama<?= $kt['id'] ?>" name="nama"
                                                            class="form-control" value="<?= esc($kt['nama']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data Content Type</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Tambah Komponen di bawah tabel (rata tengah) -->
            <div class="text-center mt-3">
                <button type="button" class="btn btn-custom mb-2" style="background-color: #03AADE;"
                    data-bs-toggle="modal" data-bs-target="#tambahModalType">
                    Tambah Content Type
                </button>
            </div>

            <!-- Modal Tambah Content Type -->
            <div class="modal fade" id="tambahModalType" tabindex="-1" aria-labelledby="tambahModalTypeLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="tambahModalTypeLabel">Tambah Content Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <form action="<?= base_url('/sosmed-planner/konten-type/tambah') ?>" method="post" id="formTambahKontenType">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="nama_kontentype" placeholder="Nama Content Type" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="mt-2" style="border: 1px solid black; background-color: black;">
        </div>

        <!-- Platform Section -->
        <div class="card shadow p-4 m-1 col-3">
            <h3 class="text-center mb-4">Content Platform</h3>

            <div class="table-responsive">
                <table class="table table-bordered mb-3">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Nama</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kontenplatform)) : ?>
                            <?php foreach ($kontenplatform as $index => $kp) : ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($kp['nama']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModalPlatform<?= $kp['id'] ?>">
                                            Edit
                                        </button>
                                        <a href="<?= base_url('/sosmed-planner/konten-platform/hapus/' . $kp['id']) ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus konten platform ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModalPlatform<?= $kp['id'] ?>" tabindex="-1"
                                    aria-labelledby="editModalLabelPlatform<?= $kp['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title" id="editModalLabelPlatform<?= $kp['id'] ?>">Edit Content Platform</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <form action="<?= base_url('/sosmed-planner/konten-platform/update/' . $kp['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama<?= $kp['id'] ?>" class="form-label">Nama</label>
                                                        <input type="text" id="nama<?= $kp['id'] ?>" name="nama"
                                                            class="form-control" value="<?= esc($kp['nama']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data Content Platform</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Tambah Komponen di bawah tabel (rata tengah) -->
            <div class="text-center mt-3">
                <button type="button" class="btn btn-custom mb-2" style="background-color: #03AADE;"
                    data-bs-toggle="modal" data-bs-target="#tambahModalplatform">
                    Tambah Content Platform
                </button>
            </div>

            <!-- Modal Tambah Content Platform -->
            <div class="modal fade" id="tambahModalplatform" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Content Platform</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <form action="<?= base_url('/sosmed-planner/konten-platform/tambah') ?>" method="post" id="formTambahKontenPlatform">
                            <?= csrf_field() ?>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <!-- Disesuaikan dengan controller -->
                                        <input type="text" class="form-control" name="nama_kontenplatform" placeholder="Nama Content Platform" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="mt-2" style="border: 1px solid black; background-color: black;">

        </div>

        <!-- Add New Content Section -->
        <div class="card shadow p-3 m-2 col-12" id="add-content-card">
            <h3 class="text-center mb-4">Tambah Konten Baru</h3>

            <form action="<?= base_url('/sosmed-planner/konten-planner/tambah') ?>" method="post" enctype="multipart/form-data">
                <div class="row d-flex">
                    <!-- Kolom Kiri: Preview Foto -->
                    <div class="col-6 text-center">
                        <label for="uploadFoto" class="form-label fw-bold">Upload Foto</label>
                        <input type="file" class="form-control mb-3" id="uploadFoto" name="uploadFoto" accept="image/*">

                        <!-- Preview Gambar -->
                        <div class="border p-3" style="min-height:250px;">
                            <img id="previewFoto" src="" alt="Preview Foto" style="max-width:100%; max-height:230px; display:none;">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Form Input -->
                    <div class="col-6">
                        <!-- Dropdown Konten Tipe -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Konten</label>
                            <input type="text"
                                class="form-control <?= $validation->hasError('title') ? 'is-invalid' : '' ?>"
                                name="title"
                                placeholder="Masukkan judul konten"
                                value="<?= old('title') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konten Tipe</label>
                            <select class="form-select <?= $validation->hasError('content_type_id') ? 'is-invalid' : '' ?>"
                                name="content_type_id"
                                required>
                                <option value="">-- Pilih Konten Tipe --</option>
                                <?php foreach ($kontentype as $type): ?>
                                    <option value="<?= $type['id'] ?>"
                                        <?= old('content_type_id') == $type['id'] ? 'selected' : '' ?>>
                                        <?= esc($type['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dropdown Konten Pilar -->
                        <div class="mb-3">
                            <label class="form-label">Konten Pilar</label>
                            <select class="form-select <?= $validation->hasError('content_pillar_id') ? 'is-invalid' : '' ?>"
                                name="content_pillar_id"
                                required>
                                <option value="">-- Pilih Konten Pilar --</option>
                                <?php foreach ($kontenpilar as $pillar): ?>
                                    <option value="<?= $pillar['id'] ?>"
                                        <?= old('content_pillar_id') == $pillar['id'] ? 'selected' : '' ?>>
                                        <?= esc($pillar['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Caption</label>
                            <textarea class="form-control <?= $validation->hasError('caption') ? 'is-invalid' : '' ?>"
                                name="caption"
                                rows="3"
                                placeholder="Tulis caption di sini..."
                                maxlength="2200"><?= old('caption') ?></textarea>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <span id="captionCount">0</span>/2200 karakter
                                </small>
                            </div>
                        </div>

                        <!-- Dropdown Platform -->
                        <div class="mb-3">
                            <label class="form-label">Platform</label>
                            <div class="form-check-group">
                                <?php
                                $oldPlatforms = old('platforms') ?: [];
                                foreach ($kontenplatform as $platform):
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="platforms[]"
                                            value="<?= $platform['id'] ?>"
                                            id="platform_<?= $platform['id'] ?>"
                                            <?= in_array($platform['id'], $oldPlatforms) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="platform_<?= $platform['id'] ?>">
                                            <?php if (isset($platform['icon'])): ?>
                                                <i class="<?= $platform['icon'] ?> me-2"></i>
                                            <?php endif; ?>
                                            <?= esc($platform['nama']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Dropdown Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select <?= $validation->hasError('status') ? 'is-invalid' : '' ?>"
                                name="status"
                                required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Draft" <?= old('status') == 'Draft' ? 'selected' : 'selected' ?>>Draft</option>
                                <option value="Ready" <?= old('status') == 'Ready' ? 'selected' : '' ?>>Ready</option>
                                <option value="Scheduled" <?= old('status') == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                <option value="Published" <?= old('status') == 'Published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>

                        <!-- Tanggal dan Waktu Upload -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Upload</label>
                                <input type="date"
                                    class="form-control <?= $validation->hasError('posting_date') ? 'is-invalid' : '' ?>"
                                    name="posting_date"
                                    value="<?= old('posting_date') ?: date('Y-m-d') ?>"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Waktu Upload</label>
                                <input type="time"
                                    class="form-control <?= $validation->hasError('posting_time') ? 'is-invalid' : '' ?>"
                                    name="posting_time"
                                    value="<?= old('posting_time') ?: '09:00' ?>"
                                    required>
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Simpan Konten</button>
                        </div>
            </form>
        </div>
    </div>
</div>

<!-- Calendar Section -->
<div class="card shadow p-3 m-2 col-12">
    <div class="calendar-container">
        <h3 class="text-center mb-4">
            <i class="fas fa-calendar-alt me-2"></i>
            Kalender Konten
        </h3>

        <div class="calendar-header">
            <button class="nav-btn" id="prevMonth">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="current-month" id="currentMonth"></div>
            <button class="nav-btn" id="nextMonth">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="calendar-grid" id="calendarGrid">
            <!-- Calendar will be generated here -->
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color legend-today"></div>
                <span>Hari Ini</span>
            </div>
            <div class="legend-item">
                <div class="legend-color legend-content"></div>
                <span>Ada Konten</span>
            </div>
            <div class="legend-item">
                <div class="legend-color legend-empty"></div>
                <span>Kosong</span>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- Modal untuk menampilkan detail konten -->
<div class="modal fade" id="contentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-calendar-day me-2"></i>
                    Konten Tanggal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="add-content-btn" onclick="scrollToAddContent()">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Konten Baru
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script Preview Foto -->
<script>
    // === Preview Foto ===
    const uploadFoto = document.getElementById('uploadFoto');
    const previewFoto = document.getElementById('previewFoto');

    if (uploadFoto) {
        uploadFoto.addEventListener('change', function() {
            const file = this.files?.[0];
            if (file) {
                const reader = new FileReader();
                previewFoto.style.display = "block";
                reader.addEventListener("load", function() {
                    previewFoto.setAttribute("src", this.result);
                });
                reader.readAsDataURL(file);
            } else {
                previewFoto.style.display = "none";
                previewFoto.removeAttribute('src');
            }
        });
    }

    // === Calendar ===
    let currentDate = new Date();
    let contentData = {};

    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    function formatDateKey(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    async function loadCalendarData(year, monthIndex) {
        const y = year;
        const m = String(monthIndex + 1).padStart(2, '0');
        const url = '<?= base_url("/sosmed-planner/calendar-data") ?>?year=' + y + '&month=' + m;

        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!res.ok) {
            console.error('Gagal load calendar-data:', res.status, url);
            return {};
        }
        return await res.json();
    }

    async function generateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // 1) fetch data bulan aktif
        contentData = await loadCalendarData(year, month);

        // 2) render header
        const currentMonthEl = document.getElementById('currentMonth');
        if (currentMonthEl) currentMonthEl.textContent = `${monthNames[month]} ${year}`;

        const calendarGrid = document.getElementById('calendarGrid');
        if (!calendarGrid) return;
        calendarGrid.innerHTML = '';

        // headers (7)
        dayNames.forEach(day => {
            const h = document.createElement('div');
            h.className = 'calendar-day-header';
            h.textContent = day;
            calendarGrid.appendChild(h);
        });

        // tanggal
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // cell kosong untuk akhir bulan sebelumnya
        const prevMonth = new Date(year, month, 0);
        const daysInPrevMonth = prevMonth.getDate();
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
            const el = createDayElement(daysInPrevMonth - i, new Date(year, month - 1, daysInPrevMonth - i), true);
            calendarGrid.appendChild(el);
        }

        // hari bulan ini
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const el = createDayElement(day, date, false);
            calendarGrid.appendChild(el);
        }

        // genapkan jadi 6 minggu x 7 hari (tanpa header)
        const headers = 7;
        const cellsSoFar = calendarGrid.children.length - headers;
        const totalCellsNeeded = 6 * 7;
        const remainingCells = totalCellsNeeded - cellsSoFar;
        for (let day = 1; day <= remainingCells; day++) {
            const el = createDayElement(day, new Date(year, month + 1, day), true);
            calendarGrid.appendChild(el);
        }
    }

    function createDayElement(dayNumber, date, isOtherMonth) {
        const el = document.createElement('div');
        el.className = 'calendar-day';
        if (isOtherMonth) el.classList.add('other-month');

        const today = new Date();
        if (date.toDateString() === today.toDateString()) {
            el.classList.add('today');
        }

        const dateKey = formatDateKey(date);
        const dayContent = contentData[dateKey] || []; // array

        if (dayContent.length > 0 && !isOtherMonth) {
            el.classList.add('has-content');
        }

        el.innerHTML = `
    <div class="day-number">${dayNumber}</div>
    ${dayContent.length > 0 ? `<div class="content-count">${dayContent.length}</div>` : ''}
    <div class="content-indicator">
      ${dayContent.slice(0,3).map(c => `<span class="content-badge">${c.type ?? ''}</span>`).join('')}
      ${dayContent.length > 3 ? `<span class="content-badge">+${dayContent.length - 3}</span>` : ''}
    </div>
  `;

        if (!isOtherMonth) {
            el.addEventListener('click', () => showDayContent(date, dayContent));
        }
        return el;
    }

    function showDayContent(date, content) {
        const modal = new bootstrap.Modal(document.getElementById('contentModal'));
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');

        const dateStr = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        modalTitle.innerHTML = `<i class="fas fa-calendar-day me-2"></i>${dateStr}`;

        if (!content || content.length === 0) {
            modalContent.innerHTML = `
      <div class="no-content">
        <i class="fas fa-calendar-times fa-3x mb-3" style="color:#cbd5e0;"></i>
        <h5>Belum Ada Konten</h5>
        <p>Tidak ada konten yang dijadwalkan untuk hari ini.</p>
      </div>`;
        } else {
            modalContent.innerHTML = content.map(item => `
      <div class="content-item">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="mb-1">${item.title ?? ''}</h6>
          <small class="text-muted">${item.time ?? ''}</small>
        </div>
        <div class="mb-2">
          <span class="content-type-badge type-${String(item.type ?? '').toLowerCase().replace(/\s+/g,'')}">${item.type ?? ''}</span>
          ${(item.platform ?? []).map(p =>
            `<span class="platform-badge platform-${String(p).toLowerCase().replace(/\s+/g,'')}">${p}</span>`
          ).join('')}
        </div>
        <div class="mb-2">
          <small class="text-muted">
            <strong>Pilar:</strong> ${item.pillar ?? '-'} |
            <strong>Status:</strong> <span class="badge bg-${getStatusColor(item.status)}">${item.status ?? '-'}</span>
          </small>
        </div>
        <p class="mb-0 text-muted" style="font-size:13px;">${item.caption ?? ''}</p>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editContent(${item.id})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button type="button" class="btn btn-sm btn-outline-success me-2" onclick="previewContent(${item.id})">
                <i class="fas fa-eye"></i> Preview
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteContent(${item.id})">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
      </div>
    `).join('');
        }
        modal.show();
    }

    function getStatusColor(status) {
        const colors = {
            Draft: 'secondary',
            Ready: 'warning',
            Scheduled: 'info',
            Published: 'success'
        };
        return colors[status] || 'secondary';
    }

    // === Scroll ke form tambah konten (pakai ID, bukan :contains) ===
    function scrollToAddContent() {
        const modalEl = document.getElementById('contentModal');
        const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
        if (modal) modal.hide();

        const targetElement = document.getElementById('add-content-card');
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            console.warn('Elemen form tambah konten (#add-content-card) tidak ditemukan');
        }
    }

    // === Listener navigasi ===
    const prevBtn = document.getElementById('prevMonth');
    if (prevBtn) prevBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar();
    });
    const nextBtn = document.getElementById('nextMonth');
    if (nextBtn) nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar();
    });

    // === Optional listeners (aman kalau tombolnya tidak ada) ===
    const btnEx = document.getElementById('tambahKolomExwork');
    if (btnEx) {
        btnEx.addEventListener('click', function() {
            // ... isi logika kamu di sini kalau memang dipakai ...
        });
    }

    // === Init ===
    document.addEventListener('DOMContentLoaded', () => {
        generateCalendar();
        // refresh "today" badge tiap menit
        setInterval(() => {
            const now = new Date();
            if (now.getDate() !== currentDate.getDate()) generateCalendar();
        }, 60000);
    });

    // === FUNGSI GLOBAL UNTUK TOMBOL MODAL ===
    window.editContent = function(id) {
        window.location.href = "<?= base_url('/sosmed-planner/konten-planner/edit/') ?>" + id;
    };

    window.previewContent = function(id) {
        window.location.href = "<?= base_url('/sosmed-planner/konten-planner/preview/') ?>" + id;
    };

    window.deleteContent = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus konten ini?')) {
            window.location.href = "<?= base_url('/sosmed-planner/konten-planner/delete/') ?>" + id;
        }
    };

    // optional: pastikan tombol "Tambah Konten Baru" juga bisa dipanggil global
    window.scrollToAddContent = scrollToAddContent;
</script>

<!-- Add FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?= $this->endSection(); ?>