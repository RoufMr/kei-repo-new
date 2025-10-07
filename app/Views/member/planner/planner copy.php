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
    #submitKomponenCIFButton,
    #submitKomponenContentTypeButton,
    #submitKomponenPlatformButton {
        display: none;
    }

    /* Membuat tabel bisa digeser ke kanan jika kolomnya terlalu panjang */
    .table-responsive {
        overflow-y: auto;
        width: 100%;
    }

    .table {
        min-height: 300px;
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

    /* Table styling improvements */
    .table {
        min-height: 300px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #03AADE;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 12px;
        padding: 15px 10px;
        border: none;
    }

    .table tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
        white-space: normal !important;
        word-wrap: break-word;
        height: auto !important;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Enhanced form styling */
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .form-check {
        margin-bottom: 8px;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-label {
        font-weight: 500;
        color: #4a5568;
    }

    /* Enhanced section headers */
    .card h3 {
        color: #2d3748;
        font-weight: 700;
        position: relative;
        padding-bottom: 10px;
    }

    .card h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    /* Enhanced preview image styling */
    .border {
        border-radius: 15px !important;
        border: 2px dashed #cbd5e0 !important;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .border:hover {
        border-color: #667eea !important;
        background: #f7fafc;
    }

    #previewFoto {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    /* Enhanced text styling */
    .text-custom-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        font-size: 2.5rem;
    }

    .text-custom-paragraph {
        color: #4a5568;
        font-size: 1.1rem;
        font-weight: 500;
    }

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
        background-color: #03AADE;
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
        box-shadow: 0 5px 15px rgba(3, 170, 222, 0.4);
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
        background-color: #03AADE;
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
        border-color: #03AADE;
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
        background-color: #03AADE;
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .content-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #03AADE;
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

<body style="background-color: #f8f9fa; min-height: 100vh;">

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
                                            <a href="<?= base_url('sosmed-planner/konten-pilar/hapus/' . $kp['id']) ?>"
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
                                                <form action="<?= base_url('sosmed-planner/konten-pilar/update/' . $kp['id']) ?>" method="post">
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
                            <form action="<?= base_url('sosmed-planner/konten-pilar/tambah') ?>" method="post" id="formTambahKonten">
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
                                            <a href="<?= base_url('sosmed-planner/konten-type/hapus/' . $kt['id']) ?>"
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
                                                <form action="<?= base_url('sosmed-planner/konten-type/update/' . $kt['id']) ?>" method="post">
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
                            <form action="<?= base_url('sosmed-planner/konten-type/tambah') ?>" method="post" id="formTambahKontenType">
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
                                            <a href="<?= base_url('sosmed-planner/konten-platform/hapus/' . $kp['id']) ?>"
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
                                                <form action="<?= base_url('sosmed-planner/konten-platform/update/' . $kp['id']) ?>" method="post">
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
                            <form action="<?= base_url('sosmed-planner/konten-platform/tambah') ?>" method="post" id="formTambahKontenPlatform">
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
            <div class="card shadow p-3 m-2 col-12">
                <h3 class="text-center mb-4">Tambah Konten Baru</h3>

                <div class="row">
                    <div class="col-6 text-center">
                        <label for="uploadMedia" class="form-label fw-bold">Upload Media</label>
                        <input type="file" class="form-control mb-3" id="uploadMedia" name="media_file"
                            accept="image/*,video/*" form="addContentForm">

                        <!-- Error untuk media upload -->
                        <?php if ($validation->getError('media_file')): ?>
                            <div class="text-danger small mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <?= $validation->getError('media_file') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Preview Container -->
                        <div class="border p-3 position-relative" style="min-height:250px;" id="previewContainer">
                            <div id="previewPlaceholder" class="d-flex flex-column justify-content-center align-items-center h-100 text-muted">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                <p class="mb-1">Drag & drop file di sini</p>
                                <small>atau klik untuk memilih file</small>
                                <small class="mt-2 text-muted">Mendukung: JPG, PNG, GIF, MP4, MOV, AVI (Max 10MB)</small>
                            </div>

                            <!-- Preview Image -->
                            <img id="previewImage" src="" alt="Preview" style="max-width:100%; max-height:230px; display:none;" class="rounded">

                            <!-- Preview Video -->
                            <video id="previewVideo" controls style="max-width:100%; max-height:230px; display:none;" class="rounded">
                                <source src="" type="">
                                Browser Anda tidak mendukung tag video.
                            </video>

                            <!-- Remove Preview Button -->
                            <button type="button" id="removePreview" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- File Info -->
                        <div id="fileInfo" class="mt-2 text-start small text-muted" style="display:none;">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="fileName"></span> (<span id="fileSize"></span>)
                        </div>
                    </div>

                    <!-- Kolom Kanan: Form Input -->
                    <div class="col-6">
                        <form id="addContentForm" action="<?= base_url('sosmed-planner/konten-planner/tambah') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Input Judul Konten -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Konten</label>
                                <input type="text"
                                    class="form-control <?= $validation->hasError('title') ? 'is-invalid' : '' ?>"
                                    name="title"
                                    placeholder="Masukkan judul konten"
                                    value="<?= old('title') ?>"
                                    required>
                                <?php if ($validation->getError('title')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('title') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Dropdown Konten Tipe -->
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
                                <?php if ($validation->getError('content_type_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('content_type_id') ?>
                                    </div>
                                <?php endif; ?>
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
                                <?php if ($validation->getError('content_pillar_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('content_pillar_id') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Input Caption -->
                            <div class="mb-3">
                                <label class="form-label">Caption</label>
                                <textarea class="form-control <?= $validation->hasError('caption') ? 'is-invalid' : '' ?>"
                                    name="caption"
                                    rows="3"
                                    placeholder="Tulis caption di sini..."
                                    maxlength="2200"><?= old('caption') ?></textarea>
                                <div class="d-flex justify-content-between">
                                    <?php if ($validation->getError('caption')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= $validation->getError('caption') ?>
                                        </div>
                                    <?php else: ?>
                                        <div></div>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <span id="captionCount">0</span>/2200 karakter
                                    </small>
                                </div>
                            </div>

                            <!-- Dropdown Platform -->
                            <div class="mb-3">
                                <label class="form-label">Platform</label>
                                <?php if ($validation->getError('platforms')): ?>
                                    <div class="text-danger small mb-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <?= $validation->getError('platforms') ?>
                                    </div>
                                <?php endif; ?>

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
                                <?php if ($validation->getError('status')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('status') ?>
                                    </div>
                                <?php endif; ?>
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
                                    <?php if ($validation->getError('posting_date')): ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('posting_date') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Waktu Upload</label>
                                    <input type="time"
                                        class="form-control <?= $validation->hasError('posting_time') ? 'is-invalid' : '' ?>"
                                        name="posting_time"
                                        value="<?= old('posting_time') ?: '09:00' ?>"
                                        required>
                                    <?php if ($validation->getError('posting_time')): ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('posting_time') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>

                                <div>
                                    <button type="button" class="btn btn-outline-info me-2" onclick="previewContent()">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Simpan Konten
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Modal -->
            <div class="modal fade" id="contentPreviewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-eye me-2"></i>Preview Konten
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="previewModalContent">
                            <!-- Preview content will be loaded here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-success" onclick="submitFormFromPreview()">
                                <i class="fas fa-save me-2"></i>Simpan Konten
                            </button>
                        </div>
                    </div>
                </div>
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

    <!-- CSS Styles -->
    <style>
        .month-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .calendar-stats .stat-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #03AADE;
            transition: all 0.3s ease;
        }

        .calendar-stats .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #03AADE;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 600;
        }

        .content-item-enhanced {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .content-item-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .content-media-preview {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .platform-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            margin-right: 4px;
        }

        .performance-metrics {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .metric {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #6c757d;
        }

        .calendar-day-mini-stats {
            position: absolute;
            bottom: 2px;
            right: 2px;
            font-size: 8px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 1px 4px;
            border-radius: 8px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-draft {
            background-color: #6c757d;
        }

        .status-ready {
            background-color: #ffc107;
        }

        .status-scheduled {
            background-color: #17a2b8;
        }

        .status-published {
            background-color: #28a745;
        }

        .quick-actions {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }

        .btn-quick {
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 4px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-day,
        .calendar-day-header {
            border: 1px solid #dee2e6;
            min-height: 80px;
            padding: 5px;
            position: relative;
            background: #fff;
            border-radius: 6px;
            font-size: 14px;
        }

        .calendar-day-header {
            font-weight: bold;
            text-align: center;
            background: #f1f3f5;
        }

        @media (max-width: 768px) {
            .month-controls {
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
            }

            .current-month {
                font-size: 20px !important;
            }

            .calendar-stats .row>div {
                margin-bottom: 10px;
            }
        }
    </style>


    <!-- Script Preview Foto -->
    <script>
        const uploadFoto = document.getElementById('uploadFoto');
        const previewFoto = document.getElementById('previewFoto');

        uploadFoto.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                previewFoto.style.display = "block";
                reader.addEventListener("load", function() {
                    previewFoto.setAttribute("src", this.result);
                });
                reader.readAsDataURL(file);
            } else {
                previewFoto.style.display = "none";
            }
        });
        // Enhanced Calendar JavaScript with Database Connection
        let currentDate = new Date();
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        function formatDateKey(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update month display
            document.getElementById('currentMonth').textContent =
                `${monthNames[month]} ${year}`;

            // Clear calendar grid
            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';

            // Add day headers
            dayNames.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });

            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();

            // Add empty cells for previous month
            const prevMonth = new Date(year, month, 0);
            const daysInPrevMonth = prevMonth.getDate();

            for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                const dayElement = createDayElement(
                    daysInPrevMonth - i,
                    new Date(year, month - 1, daysInPrevMonth - i),
                    true
                );
                calendarGrid.appendChild(dayElement);
            }

            // Add days of current month
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day);
                const dayElement = createDayElement(day, date, false);
                calendarGrid.appendChild(dayElement);
            }

            // Add days of next month to fill the grid
            const totalCells = calendarGrid.children.length;
            const remainingCells = 42 - totalCells + 7; // 6 weeks * 7 days + headers

            for (let day = 1; day <= remainingCells; day++) {
                const dayElement = createDayElement(
                    day,
                    new Date(year, month + 1, day),
                    true
                );
                calendarGrid.appendChild(dayElement);
            }
        }

        function createDayElement(dayNumber, date, isOtherMonth) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';

            if (isOtherMonth) {
                dayElement.classList.add('other-month');
            }

            // Check if it's today
            const today = new Date();
            if (date.toDateString() === today.toDateString()) {
                dayElement.classList.add('today');
            }

            // Check if there's content for this day
            const dateKey = formatDateKey(date);
            const dayContent = contentData[dateKey] || [];

            if (dayContent.length > 0 && !isOtherMonth) {
                dayElement.classList.add('has-content');
            }

            // Create day structure
            dayElement.innerHTML = `
            <div class="day-number">${dayNumber}</div>
            ${dayContent.length > 0 ? `<div class="content-count">${dayContent.length}</div>` : ''}
            <div class="content-indicator">
                ${dayContent.slice(0, 3).map(content => 
                    `<span class="content-badge">${content.type}</span>`
                ).join('')}
                ${dayContent.length > 3 ? `<span class="content-badge">+${dayContent.length - 3}</span>` : ''}
            </div>
        `;

            // Add click event
            if (!isOtherMonth) {
                dayElement.addEventListener('click', () => showDayContent(date, dayContent));
            }

            return dayElement;
        }

        function showDayContent(date, content) {
            const modal = new bootstrap.Modal(document.getElementById('contentModal'));
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            // Format date for title
            const dateStr = date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            modalTitle.innerHTML = `
            <i class="fas fa-calendar-day me-2"></i>
            ${dateStr}
        `;

            if (content.length === 0) {
                modalContent.innerHTML = `
                <div class="no-content">
                    <i class="fas fa-calendar-times fa-3x mb-3" style="color: #cbd5e0;"></i>
                    <h5>Belum Ada Konten</h5>
                    <p>Tidak ada konten yang dijadwalkan untuk hari ini.</p>
                </div>
            `;
            } else {
                modalContent.innerHTML = content.map(item => `
                <div class="content-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-1">${item.title}</h6>
                        <small class="text-muted">${item.time}</small>
                    </div>
                    
                    <div class="mb-2">
                        <span class="content-type-badge type-${item.type.toLowerCase().replace(' ', '')}">${item.type}</span>
                        ${item.platform.map(platform => 
                            `<span class="platform-badge platform-${platform.toLowerCase()}">${platform}</span>`
                        ).join('')}
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">
                            <strong>Pilar:</strong> ${item.pillar} | 
                            <strong>Status:</strong> <span class="badge bg-${getStatusColor(item.status)}">${item.status}</span>
                        </small>
                    </div>
                    
                    <p class="mb-0 text-muted" style="font-size: 13px;">
                        ${item.caption}
                    </p>
                    
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="editContent(${item.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-success me-2" onclick="previewContent(${item.id})">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteContent(${item.id})">
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
                'Draft': 'secondary',
                'Ready': 'warning',
                'Scheduled': 'info',
                'Published': 'success'
            };
            return colors[status] || 'secondary';
        }

        function scrollToAddContent() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('contentModal'));
            modal.hide();

            // Scroll to add content form
            document.querySelector('.card:has(h3:contains("Tambah Konten Baru"))').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function editContent(contentId) {
            // Implement edit functionality
            alert(`Edit konten dengan ID: ${contentId}`);
            // In real app: window.location.href = `<?= base_url('konten/edit/') ?>${contentId}`;
        }

        function previewContent(contentId) {
            // Implement preview functionality
            alert(`Preview konten dengan ID: ${contentId}`);
            // In real app: window.open(`<?= base_url('konten/preview/') ?>${contentId}`, '_blank');
        }

        function deleteContent(contentId) {
            if (confirm('Apakah Anda yakin ingin menghapus konten ini?')) {
                // Implement delete functionality
                alert(`Menghapus konten dengan ID: ${contentId}`);
                // In real app: 
                // fetch(`<?= base_url('konten/delete/') ?>${contentId}`, {method: 'DELETE'})
                //     .then(() => location.reload());
            }
        }

        // Event listeners for calendar navigation
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        });

        // Initialize calendar when page loads
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar();

            // Auto-update calendar every minute to keep "today" accurate
            setInterval(() => {
                const now = new Date();
                if (now.getDate() !== currentDate.getDate()) {
                    generateCalendar();
                }
            }, 60000);
        });

        // Dynamic form functionality for adding components
        let komponenCount = {
            exwork: 0,
            contentType: 0,
            platform: 0
        };

        // Content Pillar dynamic form
        document.getElementById('tambahKolomExwork').addEventListener('click', function() {
            komponenCount.exwork++;
            const container = document.getElementById('komponenExworkContainer');
            const submitButton = document.getElementById('submitKomponenExworkButton');

            const newField = document.createElement('div');
            newField.className = 'mb-3';
            newField.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_komponen[]" placeholder="Nama Komponen" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="deskripsi_komponen[]" placeholder="Deskripsi Komponen" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeKomponen(this, 'exwork')">Hapus</button>
                </div>
            </div>
        `;

            container.appendChild(newField);
            container.style.display = 'block';
            submitButton.style.display = 'block';
            submitButton.textContent = `Simpan Komponen (${komponenCount.exwork})`;
        });

        function removeKomponen(button, type) {
            button.closest('.mb-3').remove();
            komponenCount[type]--;

            const container = document.getElementById(`komponen${type === 'exwork' ? 'Exwork' : type === 'contentType' ? 'ContentType' : 'Platform'}Container`);
            const submitButton = document.getElementById(`submitKomponen${type === 'exwork' ? 'Exwork' : type === 'contentType' ? 'ContentType' : 'Platform'}Button`);

            if (komponenCount[type] === 0) {
                container.style.display = 'none';
                submitButton.style.display = 'none';
            } else {
                submitButton.textContent = `Simpan Komponen (${komponenCount[type]})`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const uploadMedia = document.getElementById('uploadMedia');
            const previewContainer = document.getElementById('previewContainer');
            const previewPlaceholder = document.getElementById('previewPlaceholder');
            const previewImage = document.getElementById('previewImage');
            const previewVideo = document.getElementById('previewVideo');
            const removePreview = document.getElementById('removePreview');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const captionTextarea = document.querySelector('textarea[name="caption"]');
            const captionCount = document.getElementById('captionCount');

            // File upload handling
            uploadMedia.addEventListener('change', handleFileUpload);

            // Drag and drop handling
            previewContainer.addEventListener('click', () => uploadMedia.click());
            previewContainer.addEventListener('dragover', handleDragOver);
            previewContainer.addEventListener('dragleave', handleDragLeave);
            previewContainer.addEventListener('drop', handleDrop);

            // Remove preview
            removePreview.addEventListener('click', clearPreview);

            // Caption counter
            captionTextarea.addEventListener('input', updateCaptionCount);

            // Initialize caption count
            updateCaptionCount();

            function handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    processFile(file);
                }
            }

            function handleDragOver(event) {
                event.preventDefault();
                previewContainer.classList.add('dragover');
            }

            function handleDragLeave(event) {
                event.preventDefault();
                previewContainer.classList.remove('dragover');
            }

            function handleDrop(event) {
                event.preventDefault();
                previewContainer.classList.remove('dragover');

                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    processFile(files[0]);
                }
            }

            function processFile(file) {
                // Validate file
                if (!validateFile(file)) {
                    return;
                }

                // Show file info
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.style.display = 'block';

                // Preview file
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewPlaceholder.style.display = 'none';
                    removePreview.style.display = 'block';

                    if (file.type.startsWith('image/')) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        previewVideo.style.display = 'none';
                    } else if (file.type.startsWith('video/')) {
                        previewVideo.querySelector('source').src = e.target.result;
                        previewVideo.querySelector('source').type = file.type;
                        previewVideo.load();
                        previewVideo.style.display = 'block';
                        previewImage.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }

            function validateFile(file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/mov', 'video/avi'];
                const maxSize = 10 * 1024 * 1024; // 10MB

                if (!allowedTypes.includes(file.type)) {
                    showAlert('Format file tidak didukung. Gunakan: JPG, PNG, GIF, MP4, MOV, AVI', 'danger');
                    return false;
                }

                if (file.size > maxSize) {
                    showAlert('Ukuran file terlalu besar. Maksimal 10MB', 'danger');
                    return false;
                }

                return true;
            }

            function clearPreview() {
                uploadMedia.value = '';
                previewImage.style.display = 'none';
                previewVideo.style.display = 'none';
                removePreview.style.display = 'none';
                fileInfo.style.display = 'none';
                previewPlaceholder.style.display = 'flex';
            }

            function updateCaptionCount() {
                const count = captionTextarea.value.length;
                captionCount.textContent = count;

                if (count > 2000) {
                    captionCount.parentElement.classList.add('text-danger');
                } else if (count > 1500) {
                    captionCount.parentElement.classList.add('text-warning');
                    captionCount.parentElement.classList.remove('text-danger');
                } else {
                    captionCount.parentElement.classList.remove('text-danger', 'text-warning');
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
                document.getElementById('addContentForm').reset();
                document.getElementById('removePreview').click();
                updateCaptionCount();
            }
        }

        function previewContent() {
            const form = document.getElementById('addContentForm');
            const formData = new FormData(form);

            // Validate required fields
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Build preview content
            let previewHTML = `
        <div class="preview-content">
            <h5><i class="fas fa-info-circle me-2"></i>Informasi Konten</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Judul:</strong> ${formData.get('title') || 'Belum diisi'}</p>
                    <p><strong>Tipe:</strong> ${getSelectedText('content_type_id')}</p>
                    <p><strong>Pilar:</strong> ${getSelectedText('content_pillar_id')}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(formData.get('status'))}">${formData.get('status')}</span></p>
                    <p><strong>Tanggal:</strong> ${formatDate(formData.get('posting_date'))}</p>
                    <p><strong>Waktu:</strong> ${formData.get('posting_time')}</p>
                </div>
            </div>
            <div class="mb-3">
                <strong>Platform:</strong> ${getSelectedPlatforms()}
            </div>
            <div class="mb-3">
                <strong>Caption:</strong><br>
                <div class="bg-light p-3 rounded">${formData.get('caption') || '<em>Belum ada caption</em>'}</div>
            </div>
        </div>
    `;

            document.getElementById('previewModalContent').innerHTML = previewHTML;
            new bootstrap.Modal(document.getElementById('contentPreviewModal')).show();
        }

        function getSelectedText(selectId) {
            const select = document.querySelector(`select[name="${selectId}"]`);
            return select.selectedOptions[0]?.text || 'Belum dipilih';
        }

        function getStatusColor(status) {
            const colors = {
                'Draft': 'secondary',
                'Ready': 'warning',
                'Scheduled': 'info',
                'Published': 'success'
            };
            return colors[status] || 'secondary';
        }

        function getSelectedPlatforms() {
            const checkboxes = document.querySelectorAll('input[name="platforms[]"]:checked');
            const platforms = Array.from(checkboxes).map(cb => cb.nextElementSibling.textContent.trim());
            return platforms.length > 0 ? platforms.join(', ') : 'Belum dipilih';
        }

        function formatDate(dateString) {
            if (!dateString) return 'Belum dipilih';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function submitFormFromPreview() {
            bootstrap.Modal.getInstance(document.getElementById('contentPreviewModal')).hide();
            document.getElementById('addContentForm').submit();
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        <i class="fas fa-${type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            const container = document.querySelector('.card');
            container.insertBefore(alertDiv, container.firstChild.nextSibling);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>

    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?= $this->endSection(); ?>