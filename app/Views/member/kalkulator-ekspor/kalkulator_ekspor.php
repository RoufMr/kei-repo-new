<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<?php
// Fallback aman jika variabel belum diset
$labelSatuan     = $labelSatuan     ?? ($satuanRow['satuan'] ?? '');
$idSatuan        = $idSatuan        ?? ($satuanRow['id_satuan'] ?? 0);
$ukuranKontainer = $ukuranKontainer ?? [];
$exwork          = $exwork ?? [];
$fob             = $fob ?? [];
$cfr             = $cfr ?? [];
$cif             = $cif ?? [];
?>

<style>
    /* ===== OUTPUT HARGA ===== */
    .result-harga-exwork,
    .result-harga-fob,
    .result-harga-cfr,
    .result-harga-cif {
        color: red;
        font-size: 1.5em;
    }

    /* ===== TABLE BASE ===== */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        table-layout: auto;
        font-size: 0.95rem;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    /* Input di kolom biaya agar tidak terlalu panjang */
    .table input.form-control {
        min-width: 120px;
    }

    /* Tombol hapus kecil */
    .btn-hapus-baris {
        font-size: .8rem;
    }

    /* Sembunyikan container komponen baru sampai tombol tambah ditekan */
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

    /* ===== FORM & CARD ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .btn-custom {
        text-align: center;
        color: #fff;
    }

    .btn-custom:hover {
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 0 10px #F2BF02;
        transition: background-color .3s ease, transform .3s ease, box-shadow .3s ease;
        background-color: #F2BF02 !important;
    }

    /* ===== SPACING CONTAINER UNTUK HP ===== */
    .calc-container {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    @media (max-width: 768px) {
        .calc-container .card {
            margin-left: 4px;
            margin-right: 4px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            font-size: .85rem;
            padding: 8px 10px;
        }

        /* Tabel jadi lebih ringkas */
        .table {
            font-size: 0.82rem;
        }

        .table th,
        .table td {
            padding: 6px 8px;
        }

        /* Tombol tambah komponen full width */
        #tambahKolomExwork,
        #tambahKolomFOB,
        #tambahKolomCFR,
        #tambahKolomCIF {
            width: 100%;
            display: block;
        }

        /* Tombol simpan juga full width */
        #submitKomponenExworkButton,
        #submitKomponenFOBButton,
        #submitKomponenCFRButton,
        #submitKomponenCIFButton {
            width: 100% !important;
            margin-top: 10px;
        }
    }

    /* ===== HP KECIL (≤425px) ===== */
    @media (max-width: 425px) {

        /* Kolom nomor diperkecil */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 35px;
        }

        /* Form komponen baru auto stack full width */
        .komponenRow .col-md-6,
        .komponenRow .col-md-5,
        .komponenRow .col-md-1 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .komponenRow .col-12 {
            margin-bottom: 8px;
        }

        .btn-hapus-baris {
            width: 100%;
            margin-top: 6px;
        }

        .form-control,
        .form-select {
            font-size: .8rem;
            padding: 7px 8px;
        }
    }

    /* ===== PERBAIKAN SUPAYA TIDAK SCROLL KANAN ===== */
    html,
    body {
        overflow-x: hidden !important;
    }
</style>


<!-- judul -->
<div class="py-5 text-center">
    <h2 class="text-custom-title">Kalkulator Ekspor</h2>
    <p class="text-custom-paragraph mt-2">Berikut aplikasi Kalkulator Ekspor Indonesia</p>
</div>

<div class="container py-2 mt-3 calc-container">
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                if (typeof notify === 'function') {
                    notify('success', '<?= esc(session()->getFlashdata('success')) ?>');
                } else {
                    alert('<?= esc(session()->getFlashdata('success')) ?>');
                }
            });
        </script>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                if (typeof notify === 'function') {
                    notify('error', '<?= esc(session()->getFlashdata('error')) ?>');
                } else {
                    alert('<?= esc(session()->getFlashdata('error')) ?>');
                }
            });
        </script>
    <?php endif; ?>

    <!-- Nama Produk -->
    <div class="form-group mb-3">
        <label for="namaProduk">Nama Produk:</label>
        <div class="input-group">
            <input required type="text" class="form-control" id="namaProduk" name="namaProduk" placeholder="Masukkan Nama Produk" autocomplete="off">
        </div>
    </div>

    <!-- Ukuran Kontainer -->
    <div class="form-group mb-3">
        <label for="ukuran_kontainer">Ukuran Kontainer:</label>
        <div class="input-group">
            <select required class="form-control" id="ukuran_kontainer" name="ukuran_kontainer">
                <option value="">Pilih Ukuran Kontainer</option>
                <?php foreach ($ukuranKontainer as $uk): ?>
                    <option value="<?= esc($uk['nama']) ?>"><?= esc($uk['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Satuan (autosave, tanpa tombol) -->
    <div id="blokSatuan" class="mb-3" autocomplete="off">
        <div class="form-group">
            <label for="satuan">Satuan:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="satuan" name="satuan"
                    placeholder="Masukkan Satuan" value="<?= esc($labelSatuan); ?>" autocomplete="off">
            </div>
        </div>
        <small class="text-muted d-block mt-1" id="satuanStatus"></small>
    </div>

    <!-- Exwork Form -->
    <div class="card shadow p-4">
        <h1 class="text-center mb-4" id="exwork">Exwork Form</h1>

        <!-- Input Jumlah Barang -->
        <div class="form-group">
            <div class="col-md-6">
                <label id="jumlahBarangLabel" for="jumlahBarang">Jumlah Barang Dalam 1 Kontainer:</label>
                <div class="input-group">
                    <input required type="text" class="form-control" id="jumlahBarang" name="jumlahBarang" placeholder="Masukkan Jumlah Barang" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"><?= esc($labelSatuan); ?></span></div>
                </div>
            </div>
        </div>

        <!-- Input HPP -->
        <div class="form-group">
            <div class="col-md-6">
                <label for="hpp">Harga Pokok Produksi (HPP):</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    <input required type="text" class="form-control" id="hpp" name="hpp" placeholder="Masukkan Biaya HPP" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"></span></div>
                </div>
            </div>
        </div>

        <!-- Input Keuntungan -->
        <div class="form-group">
            <div class="col-md-6">
                <label for="keuntungan">Keuntungan:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    <input required type="text" class="form-control" id="keuntungan" name="keuntungan" placeholder="Masukkan Biaya Keuntungan" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"></span></div>
                </div>
            </div>
        </div>

        <p class="text-danger mt-2">*<i>Komponen Exwork (Sesuaikan dengan kebutuhan)</i></p>

        <!-- === SATU FORM GABUNG EXWORK === -->
        <form action="<?= base_url('/komponen-exwork/save-all'); ?>" method="post" enctype="multipart/form-data" id="formExworkAll">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Komponen</th>
                            <th class="biaya-col-header text-center">Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($exwork)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen Exwork yang ditambahkan.</td>
                            </tr>
                            <?php else: foreach ($exwork as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_exwork']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required type="text"
                                                class="form-control exwork-existing"
                                                id="exwork_<?= $item['id_exwork'] ?>"
                                                name="exwork_<?= $item['id_exwork'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_exwork']) ?>" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-exwork/delete/' . $item['id_exwork']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>

                        <!-- Baris untuk input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomExwork">Tambah Komponen Baru</button>
                                <div id="komponenExworkContainer"></div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-custom" style="background-color:#77DD77;" id="submitKomponenExworkButton">
                                        Simpan Perubahan & Komponen (0)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <!-- === /SATU FORM GABUNG EXWORK === -->

        <div class="d-flex justify-content-between">
            <h3 class="result-harga-exwork mt-2">Rekomendasi Harga Exwork: </h3>
        </div>
        <hr class="mt-2" style="border: 1px solid black; background-color: black;">
    </div>

    <!-- FOB -->
    <div class="card shadow p-4 mt-4" id="fob">
        <h1 class="text-center mb-4">FOB Form</h1>
        <div class="form-group">
            <div class="col-md-6">
                <label for="hargaExwork">Harga Exwork:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    <input required type="text" class="form-control" id="hargaExwork" name="hargaExwork" placeholder="Masukkan Harga Exwork" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"></span></div>
                </div>
            </div>
        </div>

        <p class="text-danger">*<i>Komponen FOB (Sesuaikan dengan kebutuhan)</i></p>

        <!-- === SATU FORM GABUNG FOB === -->
        <form action="<?= base_url('/komponen-fob/save-all'); ?>" method="post" enctype="multipart/form-data" id="formFOBAll">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Komponen</th>
                            <th class="biaya-col-header">Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fob)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen FOB yang ditambahkan.</td>
                            </tr>
                            <?php else: foreach ($fob as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_fob']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required type="text"
                                                class="form-control fob-existing"
                                                id="fob_<?= $item['id_fob'] ?>"
                                                name="fob_<?= $item['id_fob'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_fob']) ?>" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-fob/delete/' . $item['id_fob']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomFOB">Tambah Komponen Baru</button>
                                <div id="komponenFOBContainer"></div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-custom" style="background-color:#77DD77;" id="submitKomponenFOBButton">
                                        Simpan Perubahan & Komponen (0)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <!-- === /SATU FORM GABUNG FOB === -->

        <div class="d-flex justify-content-between">
            <h3 class="result-harga-fob mt-2">Rekomendasi Harga FOB: </h3>
        </div>
        <hr class="mt-2" style="border: 1px solid black; background-color: black;">
    </div>

    <!-- CFR Form -->
    <div class="card shadow p-4 mb-4" id="cfr">
        <h1 class="text-center mb-4">CFR Form</h1>
        <div class="form-group">
            <div class="col-md-6">
                <label for="hargaFOB">Harga FOB:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    <input required type="text" class="form-control" id="hargaFOB" name="hargaFOB" placeholder="Masukkan Harga FOB" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"></span></div>
                </div>
            </div>
        </div>

        <p class="text-danger">*<i>Komponen CFR (Sesuaikan dengan kebutuhan)</i></p>

        <!-- === SATU FORM GABUNG CFR === -->
        <form action="<?= base_url('/komponen-cfr/save-all'); ?>" method="post" enctype="multipart/form-data" id="formCFRAll">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Komponen</th>
                            <th class="biaya-col-header">Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cfr)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen CFR yang ditambahkan.</td>
                            </tr>
                            <?php else: foreach ($cfr as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_cfr']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required type="text"
                                                class="form-control cfr-existing"
                                                id="cfr_<?= $item['id_cfr'] ?>"
                                                name="cfr_<?= $item['id_cfr'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_cfr']) ?>" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-cfr/delete/' . $item['id_cfr']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomCFR">Tambah Komponen Baru</button>
                                <div id="komponenCFRContainer"></div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-custom" style="background-color:#77DD77;" id="submitKomponenCFRButton">
                                        Simpan Perubahan & Komponen (0)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <!-- === /SATU FORM GABUNG CFR === -->

        <div class="d-flex justify-content-between">
            <h3 class="result-harga-cfr mt-2">Rekomendasi Harga CFR: </h3>
        </div>
        <hr class="mt-2" style="border: 1px solid black; background-color: black;">
    </div>

    <!-- CIF Form -->
    <div class="card shadow p-4 mb-4" id="cif">
        <h1 class="text-center mb-4">CIF Form</h1>
        <div class="form-group">
            <div class="col-md-6">
                <label for="hargaCFR">Harga CFR:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                    <input required type="text" class="form-control" id="hargaCFR" name="hargaCFR" placeholder="Masukkan Harga CFR" autocomplete="off">
                    <div class="input-group-prepend"><span class="input-group-text satuan-badge"></span></div>
                </div>
            </div>
        </div>

        <p class="text-danger">*<i>Komponen CIF (Sesuaikan dengan kebutuhan)</i></p>

        <!-- === SATU FORM GABUNG CIF === -->
        <form action="<?= base_url('/komponen-cif/save-all'); ?>" method="post" enctype="multipart/form-data" id="formCIFAll">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center w-25">Komponen</th>
                            <th class="biaya-col-header">Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cif)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen CIF yang ditambahkan.</td>
                            </tr>
                            <?php else: foreach ($cif as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_cif']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required type="text"
                                                class="form-control cif-existing"
                                                id="cif_<?= $item['id_cif'] ?>"
                                                name="cif_<?= $item['id_cif'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_cif']) ?>" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-cif/delete/' . $item['id_cif']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomCIF">Tambah Komponen Baru</button>
                                <div id="komponenCIFContainer"></div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-custom" style="background-color:#77DD77;" id="submitKomponenCIFButton">
                                        Simpan Perubahan & Komponen (0)
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="d-flex justify-content-between">
            <h3 class="result-harga-cif mt-2">Rekomendasi Harga CIF: </h3>
        </div>

        <hr class="mt-2" style="border: 1px solid black; background-color: black;">
    </div>
</div>

<script>
    // ==== USER CONTEXT (baru) ====
    const USER_ID = <?= (int)($user_id ?? 0); ?>; // dari controller
    const STORE_NS = `kei_calc_${USER_ID}_`;
    const LAST_UID_KEY = 'kei_calc_last_uid';
    const Store = window.localStorage;
    const NS = {
        get(k) {
            return Store.getItem(STORE_NS + k);
        },
        set(k, v) {
            Store.setItem(STORE_NS + k, v);
        },
        del(k) {
            Store.removeItem(STORE_NS + k);
        }
    };
    (function handleUserSwitch() {
        const last = Store.getItem(LAST_UID_KEY);
        if (last && String(last) !== String(USER_ID)) {
            // bersihkan jejak lama non-namespaced (versi sebelum patch)
            ['kalk_s_namaProduk', 'kalk_s_ukuran_kontainer', 'kalk_s_jumlahBarang', 'kalk_s_hpp', 'kalk_s_keuntungan', 'kalk_s_satuan']
            .forEach(key => Store.removeItem(key));
        }
        Store.setItem(LAST_UID_KEY, String(USER_ID));
    })();

    // ==== UTIL ====
    function formatRupiah(angka) {
        var number_string = (angka || '').toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    function bersihkanRupiah(str) {
        return (str || '').toString().replace(/\./g, '').replace(/[^\d]/g, '');
    }
    if (typeof notify !== 'function') {
        window.notify = function(type, msg) {
            alert(msg);
        };
    }

    // ==== STATE KEYS (per-user namespace via NS) ====
    const SS_KEYS = {
        nama: 'namaProduk',
        ukuran: 'ukuran_kontainer',
        jumlah: 'jumlahBarang',
        hpp: 'hpp',
        untung: 'keuntungan',
        satuan: 'satuan',
    };

    // === Update label Biaya sesuai ukuran kontainer ===
    function updateUkuranHints() {
        const size = document.getElementById('ukuran_kontainer')?.value || '';
        const suffix = size ? ' / Kontainer ' + size : '';
        document.querySelectorAll('th.biaya-col-header').forEach(th => {
            th.textContent = 'Biaya (Rp.)' + suffix;
        });
        document.querySelectorAll('label.biaya-col-header').forEach(label => {
            label.textContent = 'Biaya (Rp.)' + suffix;
        });
    }

    // === Unit helpers ===
    function getSatuanText() {
        return (document.getElementById('satuan')?.value || '').trim();
    }

    function updateSatuanBadges() {
        var txt = getSatuanText();
        document.querySelectorAll('.satuan-badge').forEach(function(el) {
            if (txt) {
                el.textContent = txt;
                el.style.display = '';
            } else {
                el.textContent = '';
                el.style.display = 'none';
            }
        });
    }

    // ==== SAVE STATE (NS + server) ====
    function saveStateOnce() {
        const nama = document.getElementById('namaProduk')?.value || '';
        const ukuran = document.getElementById('ukuran_kontainer')?.value || '';
        const jumlah = document.getElementById('jumlahBarang')?.value || '';
        const hpp = document.getElementById('hpp')?.value || '';
        const untung = document.getElementById('keuntungan')?.value || '';
        const satuan = document.getElementById('satuan')?.value || '';

        NS.set(SS_KEYS.nama, nama);
        NS.set(SS_KEYS.ukuran, ukuran);
        NS.set(SS_KEYS.jumlah, bersihkanRupiah(jumlah));
        NS.set(SS_KEYS.hpp, bersihkanRupiah(hpp));
        NS.set(SS_KEYS.untung, bersihkanRupiah(untung));
        NS.set(SS_KEYS.satuan, satuan);

        (async () => {
            try {
                const body = new URLSearchParams();
                body.set('nama_produk', nama);
                body.set('jumlah_barang', bersihkanRupiah(jumlah) || '');
                body.set('hpp', bersihkanRupiah(hpp) || '');
                body.set('keuntungan', bersihkanRupiah(untung) || '');
                await fetch('<?= base_url('kalkulator-state/save') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body
                });
            } catch (e) {}
        })();
    }

    // ==== SERVER-FIRST RESTORE lalu merge storage per-user ====
    async function restoreStateServerFirstThenStorage() {
        try {
            const res = await fetch('<?= base_url('kalkulator-state/load') ?>', {
                method: 'GET'
            });
            const json = await res.json();
            if (json?.ok && json.data) {
                const d = json.data;
                const gt0 = v => Number(v) > 0;

                const elNama = document.getElementById('namaProduk');
                if (elNama && d.nama_produk) elNama.value = d.nama_produk;

                const elJumlah = document.getElementById('jumlahBarang');
                if (elJumlah && gt0(d.jumlah_barang)) elJumlah.value = formatRupiah(String(d.jumlah_barang));

                const elHpp = document.getElementById('hpp');
                if (elHpp && gt0(d.hpp)) elHpp.value = formatRupiah(String(d.hpp));

                const elUnt = document.getElementById('keuntungan');
                if (elUnt && gt0(d.keuntungan)) elUnt.value = formatRupiah(String(d.keuntungan));
            }
        } catch (e) {
            /* silent */
        }

        const nama = NS.get(SS_KEYS.nama) || '';
        const ukuran = NS.get(SS_KEYS.ukuran) || '';
        const jumlah = NS.get(SS_KEYS.jumlah) || '';
        const hpp = NS.get(SS_KEYS.hpp) || '';
        const untung = NS.get(SS_KEYS.untung) || '';
        const satuan = NS.get(SS_KEYS.satuan) || '';

        const elNama = document.getElementById('namaProduk');
        if (elNama && !elNama.value && nama) elNama.value = nama;

        const sel = document.getElementById('ukuran_kontainer');
        if (sel && ukuran) {
            sel.value = ukuran;
            const label = document.getElementById('jumlahBarangLabel');
            if (label) label.textContent = 'Jumlah Barang Dalam 1 Kontainer ' + ukuran + ':';
        }
        updateUkuranHints();

        const elJumlah = document.getElementById('jumlahBarang');
        if (elJumlah && !elJumlah.value && jumlah) elJumlah.value = formatRupiah(jumlah);

        const elHpp = document.getElementById('hpp');
        if (elHpp && !elHpp.value && hpp) elHpp.value = formatRupiah(hpp);

        const elUnt = document.getElementById('keuntungan');
        if (elUnt && !elUnt.value && untung) elUnt.value = formatRupiah(untung);

        const elSatuan = document.getElementById('satuan');
        if (elSatuan) elSatuan.value = satuan;

        updateSatuanBadges();

        try {
            hitungExwork();
            hitungFOB();
            hitungCFR();
            hitungCIF();
        } catch (e) {}
    }

    // ==== Debounce helper ====
    function debounce(fn, delay) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    // ==== Autosave Satuan (JSON) ====
    const satuanInput = document.getElementById('satuan');
    const satuanStatus = document.getElementById('satuanStatus');

    function setSatuanStatus(text, ok = null) {
        if (!satuanStatus) return;
        satuanStatus.textContent = text || '';
        if (ok === true) {
            satuanStatus.classList.remove('text-danger');
            satuanStatus.classList.add('text-success');
        } else if (ok === false) {
            satuanStatus.classList.remove('text-success');
            satuanStatus.classList.add('text-danger');
        } else {
            satuanStatus.classList.remove('text-success', 'text-danger');
        }
    }
    const autosaveSatuan = debounce(async function() {
        try {
            const val = (satuanInput?.value || '').trim();
            updateSatuanBadges();
            setSatuanStatus('');
            const body = new URLSearchParams();
            body.set('satuan', val);

            const res = await fetch('<?= base_url('satuan/upsert-json') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body
            });
            const json = await res.json();
            if (res.ok && json.ok) {
                NS.set(SS_KEYS.satuan, val);
                setSatuanStatus('', true);
                try {
                    hitungExwork();
                    hitungFOB();
                    hitungCFR();
                    hitungCIF();
                } catch (e) {}
            } else {
                setSatuanStatus(json.msg || '', false);
            }
        } catch (e) {
            setSatuanStatus('', false);
        }
    }, 500);

    if (satuanInput) {
        satuanInput.addEventListener('input', function() {
            NS.set(SS_KEYS.satuan, (satuanInput.value || '').trim());
            updateSatuanBadges();
            autosaveSatuan();
        });
        satuanInput.addEventListener('change', function() {
            NS.set(SS_KEYS.satuan, (satuanInput.value || '').trim());
            updateSatuanBadges();
            autosaveSatuan();
            ['jumlahBarang', 'hpp', 'keuntungan', 'hargaExwork', 'hargaFOB', 'hargaCFR'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            NS.set(SS_KEYS.jumlah, '');
            NS.set(SS_KEYS.hpp, '');
            NS.set(SS_KEYS.untung, '');
            try {
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            } catch (e) {}
        });
    }

    // ==== Dinamis label ukuran + save ====
    document.getElementById('ukuran_kontainer').addEventListener('change', function() {
        var selectedUkuran = this.value;
        var label = document.getElementById('jumlahBarangLabel');
        label.textContent = selectedUkuran ? ('Jumlah Barang Dalam 1 Kontainer ' + selectedUkuran + ':') : 'Jumlah Barang Dalam 1 Kontainer:';
        saveStateOnce();
        updateUkuranHints();
    });

    // ==== PERHITUNGAN ====
    function hitungExwork() {
        let jumlahBarang = bersihkanRupiah(document.getElementById('jumlahBarang').value);
        let hpp = bersihkanRupiah(document.getElementById('hpp').value);
        let keuntungan = bersihkanRupiah(document.getElementById('keuntungan').value);

        if (!jumlahBarang || !hpp || !keuntungan) {
            document.querySelector('.result-harga-exwork').innerText = 'Rekomendasi Harga Exwork: ';
            return;
        }
        jumlahBarang = parseFloat(jumlahBarang);
        hpp = parseFloat(hpp);
        keuntungan = parseFloat(keuntungan);

        let jb_hpp_keuntungan = (hpp + keuntungan) * jumlahBarang;
        let exworkLainnya = 0;

        <?php foreach ($exwork as $item): ?>
                (function() {
                    const el = document.getElementById('exwork_<?= $item['id_exwork'] ?>');
                    if (el) {
                        const val = bersihkanRupiah(el.value);
                        if (val) exworkLainnya += parseFloat(val);
                    }
                })();
        <?php endforeach; ?>

        document.querySelectorAll('input[name="biayaExwork[]"]').forEach(function(el) {
            const val = bersihkanRupiah(el.value);
            if (val) exworkLainnya += parseFloat(val);
        });

        let hargaExwork = (jb_hpp_keuntungan + exworkLainnya) / jumlahBarang;
        var unit = getSatuanText();
        var suffixUnit = unit ? (' / ' + unit) : '';
        document.querySelector('.result-harga-exwork').innerText =
            'Rekomendasi Harga Exwork: Rp. ' + formatRupiah(hargaExwork.toFixed(0)) + suffixUnit;
        document.getElementById('hargaExwork').value = formatRupiah(hargaExwork.toFixed(0));
    }

    function hitungFOB() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaExwork = parseFloat(bersihkanRupiah(document.getElementById('hargaExwork').value));
        if (!jumlahBarang || !hargaExwork) {
            document.querySelector('.result-harga-fob').innerText = 'Rekomendasi Harga FOB: ';
            return;
        }
        let jb_he = hargaExwork * jumlahBarang;
        let fobLainnya = 0;

        <?php foreach ($fob as $item): ?>
                (function() {
                    const el = document.getElementById('fob_<?= $item['id_fob'] ?>');
                    if (el) {
                        const val = bersihkanRupiah(el.value);
                        if (val) fobLainnya += parseFloat(val);
                    }
                })();
        <?php endforeach; ?>

        document.querySelectorAll('input[name="biayaFOB[]"]').forEach(function(el) {
            const val = bersihkanRupiah(el.value);
            if (val) fobLainnya += parseFloat(val);
        });

        let hargaFOB = (jb_he + fobLainnya) / jumlahBarang;
        var unit = getSatuanText();
        var suffixUnit = unit ? (' / ' + unit) : '';
        document.querySelector('.result-harga-fob').innerText =
            'Rekomendasi Harga FOB: Rp. ' + formatRupiah(hargaFOB.toFixed(0)) + suffixUnit;
        document.getElementById('hargaFOB').value = formatRupiah(hargaFOB.toFixed(0));
    }

    function hitungCFR() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaFOB = parseFloat(bersihkanRupiah(document.getElementById('hargaFOB').value));
        if (!jumlahBarang || !hargaFOB) {
            document.querySelector('.result-harga-cfr').innerText = 'Rekomendasi Harga CFR: ';
            return;
        }
        let jb_hfob = hargaFOB * jumlahBarang;
        let cfrLainnya = 0;

        <?php foreach ($cfr as $item): ?>
                (function() {
                    const el = document.getElementById('cfr_<?= $item['id_cfr'] ?>');
                    if (el) {
                        const val = bersihkanRupiah(el.value);
                        if (val) cfrLainnya += parseFloat(val);
                    }
                })();
        <?php endforeach; ?>

        document.querySelectorAll('input[name="biayaCFR[]"]').forEach(function(el) {
            const val = bersihkanRupiah(el.value);
            if (val) cfrLainnya += parseFloat(val);
        });

        let hargaCFR = (jb_hfob + cfrLainnya) / jumlahBarang;
        var unit = getSatuanText();
        var suffixUnit = unit ? (' / ' + unit) : '';
        document.querySelector('.result-harga-cfr').innerText =
            'Rekomendasi Harga CFR: Rp. ' + formatRupiah(hargaCFR.toFixed(0)) + suffixUnit;
        document.getElementById('hargaCFR').value = formatRupiah(hargaCFR.toFixed(0));
    }

    function hitungCIF() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaCFR = parseFloat(bersihkanRupiah(document.getElementById('hargaCFR').value));
        if (!jumlahBarang || !hargaCFR) {
            document.querySelector('.result-harga-cif').innerText = 'Rekomendasi Harga CIF: ';
            return;
        }
        let jb_hcfr = hargaCFR * jumlahBarang;
        let cifLainnya = 0;

        <?php foreach ($cif as $item): ?>
                (function() {
                    const el = document.getElementById('cif_<?= $item['id_cif'] ?>');
                    if (el) {
                        const val = bersihkanRupiah(el.value);
                        if (val) cifLainnya += parseFloat(val);
                    }
                })();
        <?php endforeach; ?>

        document.querySelectorAll('input[name="biayaCIF[]"]').forEach(function(el) {
            const val = bersihkanRupiah(el.value);
            if (val) cifLainnya += parseFloat(val);
        });

        let hargaCIF = (jb_hcfr + cifLainnya) / jumlahBarang;
        var unit = getSatuanText();
        var suffixUnit = unit ? (' / ' + unit) : '';
        document.querySelector('.result-harga-cif').innerText =
            'Rekomendasi Harga CIF: Rp. ' + formatRupiah(hargaCIF.toFixed(0)) + suffixUnit;
    }

    // ==== LISTENER INPUT ====
    document.querySelectorAll('#jumlahBarang, #hpp, #keuntungan, #hargaExwork, #hargaFOB, #hargaCFR').forEach(function(element) {
        element.addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungExwork();
            hitungFOB();
            hitungCFR();
            hitungCIF();
            if (['jumlahBarang', 'hpp', 'keuntungan'].includes(e.target.id)) saveStateOnce();
        });
        element.addEventListener('change', function(e) {
            if (['jumlahBarang', 'hpp', 'keuntungan'].includes(e.target.id)) saveStateOnce();
        });
    });

    // Listener Nama Produk
    (function() {
        const el = document.getElementById('namaProduk');
        if (el) {
            el.addEventListener('keyup', saveStateOnce);
            el.addEventListener('change', saveStateOnce);
        }
    })();

    // ==== Exwork add rows & submit ====
    (function() {
        const container = document.getElementById('komponenExworkContainer');
        const btnAdd = document.getElementById('tambahKolomExwork');
        const btnSubmit = document.getElementById('submitKomponenExworkButton');

        function updateCounter() {
            const n = container.querySelectorAll('.komponenRow').length;
            btnSubmit.textContent = 'Simpan Perubahan & Komponen (' + n + ')';
        }

        function ensureVisible() {
            container.style.display = 'block';
            btnSubmit.style.display = 'inline-block';
        }
        btnAdd.addEventListener('click', function() {
            ensureVisible();
            const row = document.createElement('div');
            row.className = 'card p-3 mb-2 komponenRow';
            row.innerHTML = `
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Masukkan Komponen Exwork</label>
                    <input type="text" name="komponenExwork[]" class="form-control" placeholder="Masukkan Komponen Exwork" autocomplete="off" required>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label fw-bold biaya-col-header">Biaya (Rp.)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" name="biayaExwork[]" class="form-control input-biaya-exwork" placeholder="0" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                </div>
                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>`;
            row.querySelector('.input-biaya-exwork').addEventListener('keyup', function(e) {
                e.target.value = formatRupiah(e.target.value);
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            });
            row.querySelector('.btn-hapus-baris').addEventListener('click', function() {
                row.remove();
                updateCounter();
                if (container.querySelectorAll('.komponenRow').length === 0) {
                    container.style.display = 'none';
                    btnSubmit.style.display = 'none';
                }
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
                updateUkuranHints();
            });
            container.appendChild(row);
            updateCounter();
            updateUkuranHints();
        });
        document.getElementById('formExworkAll').addEventListener('submit', function() {
            try {
                saveStateOnce();
            } catch (e) {}
            document.querySelectorAll('.exwork-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaExwork[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // ==== FOB add rows & submit ====
    (function() {
        const container = document.getElementById('komponenFOBContainer');
        const btnAdd = document.getElementById('tambahKolomFOB');
        const btnSubmit = document.getElementById('submitKomponenFOBButton');
        const form = document.getElementById('formFOBAll');

        function updateCounter() {
            btnSubmit.textContent = 'Simpan Perubahan & Komponen (' + container.querySelectorAll('.komponenRow').length + ')';
        }

        function ensureVisible() {
            container.style.display = 'block';
            btnSubmit.style.display = 'inline-block';
        }
        btnAdd.addEventListener('click', function() {
            ensureVisible();
            const row = document.createElement('div');
            row.className = 'card p-3 mb-2 komponenRow';
            row.innerHTML = `
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Masukkan Komponen FOB</label>
                    <input type="text" name="komponenFOB[]" class="form-control" placeholder="Masukkan Komponen FOB" autocomplete="off" required>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label fw-bold biaya-col-header">Biaya (Rp.)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" name="biayaFOB[]" class="form-control input-biaya-fob" placeholder="0" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                </div>
                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>`;
            row.querySelector('.input-biaya-fob').addEventListener('keyup', function(e) {
                e.target.value = formatRupiah(e.target.value);
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            });
            row.querySelector('.btn-hapus-baris').addEventListener('click', function() {
                row.remove();
                updateCounter();
                if (container.querySelectorAll('.komponenRow').length === 0) {
                    container.style.display = 'none';
                    btnSubmit.style.display = 'none';
                }
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
                updateUkuranHints();
            });
            container.appendChild(row);
            updateCounter();
            updateUkuranHints();
        });
        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
            } catch (e) {}
            document.querySelectorAll('.fob-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaFOB[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // ==== CFR add rows & submit ====
    (function() {
        const container = document.getElementById('komponenCFRContainer');
        const btnAdd = document.getElementById('tambahKolomCFR');
        const btnSubmit = document.getElementById('submitKomponenCFRButton');
        const form = document.getElementById('formCFRAll');

        function updateCounter() {
            btnSubmit.textContent = 'Simpan Perubahan & Komponen (' + container.querySelectorAll('.komponenRow').length + ')';
        }

        function ensureVisible() {
            container.style.display = 'block';
            btnSubmit.style.display = 'inline-block';
        }
        btnAdd.addEventListener('click', function() {
            ensureVisible();
            const row = document.createElement('div');
            row.className = 'card p-3 mb-2 komponenRow';
            row.innerHTML = `
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Masukkan Komponen CFR</label>
                    <input type="text" name="komponenCFR[]" class="form-control" placeholder="Masukkan Komponen CFR" autocomplete="off" required>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label fw-bold biaya-col-header">Biaya (Rp.)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" name="biayaCFR[]" class="form-control input-biaya-cfr" placeholder="0" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                </div>
                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>`;
            row.querySelector('.input-biaya-cfr').addEventListener('keyup', function(e) {
                e.target.value = formatRupiah(e.target.value);
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            });
            row.querySelector('.btn-hapus-baris').addEventListener('click', function() {
                row.remove();
                updateCounter();
                if (container.querySelectorAll('.komponenRow').length === 0) {
                    container.style.display = 'none';
                    btnSubmit.style.display = 'none';
                }
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
                updateUkuranHints();
            });
            container.appendChild(row);
            updateCounter();
            updateUkuranHints();
        });
        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
            } catch (e) {}
            document.querySelectorAll('.cfr-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaCFR[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // ==== CIF add rows & submit ====
    (function() {
        const container = document.getElementById('komponenCIFContainer');
        const btnAdd = document.getElementById('tambahKolomCIF');
        const btnSubmit = document.getElementById('submitKomponenCIFButton');
        const form = document.getElementById('formCIFAll');

        function updateCounter() {
            btnSubmit.textContent = 'Simpan Perubahan & Komponen (' + container.querySelectorAll('.komponenRow').length + ')';
        }

        function ensureVisible() {
            container.style.display = 'block';
            btnSubmit.style.display = 'inline-block';
        }
        btnAdd.addEventListener('click', function() {
            ensureVisible();
            const row = document.createElement('div');
            row.className = 'card p-3 mb-2 komponenRow';
            row.innerHTML = `
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">Masukkan Komponen CIF</label>
                    <input type="text" name="komponenCIF[]" class="form-control" placeholder="Masukkan Komponen CIF" autocomplete="off" required>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label fw-bold biaya-col-header">Biaya (Rp.)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" name="biayaCIF[]" class="form-control input-biaya-cif" placeholder="0" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                </div>
                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>`;
            row.querySelector('.input-biaya-cif').addEventListener('keyup', function(e) {
                e.target.value = formatRupiah(e.target.value);
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            });
            row.querySelector('.btn-hapus-baris').addEventListener('click', function() {
                row.remove();
                updateCounter();
                if (container.querySelectorAll('.komponenRow').length === 0) {
                    container.style.display = 'none';
                    btnSubmit.style.display = 'none';
                }
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
                updateUkuranHints();
            });
            container.appendChild(row);
            updateCounter();
            updateUkuranHints();
        });
        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
            } catch (e) {}
            document.querySelectorAll('.cif-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaCIF[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // ==== Restore saat halaman dibuka ====
    document.addEventListener('DOMContentLoaded', function() {
        restoreStateServerFirstThenStorage(); // server dulu, baru storage per-user
        updateUkuranHints();
        updateSatuanBadges();
    });
</script>


<?= $this->endSection(); ?>