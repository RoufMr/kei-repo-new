<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<style>
    .result-harga-exwork,
    .result-harga-fob,
    .result-harga-cfr,
    .result-harga-cif {
        color: red;
        font-size: 1.5em;
    }

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

    .table-responsive {
        overflow-x: auto;
        width: 100%;
    }

    .table {
        min-width: 500px;
    }

    .nav-link {
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
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
    }
</style>

<!-- judul -->
<div class="py-5" style="text-align: center;">
    <h2 class="text-custom-title">Kalkulator Ekspor</h2>
    <p class="text-custom-paragraph mt-2">Berikut aplikasi Kalkulator Ekspor Indonesia</p>
</div>

<div class="container py-2 mt-3">
    <div class="form-group mb-3">
        <label for="ukuran_kontainer">Ukuran Kontainer:</label>
        <div class="input-group">
            <select required class="form-control" id="ukuran_kontainer" name="ukuran_kontainer">
                <option value="">Pilih Ukuran Kontainer</option>
                <option value="20 Feet">20 Feet</option>
                <option value="40 Feet">40 Feet</option>
                <option value="40 Feet HC">40 Feet HC</option>
                <option value="45 Feet HC">45 Feet HC</option>
            </select>
        </div>
    </div>

    <form action="<?= base_url('/ganti-satuan/' . $satuan[0]['id_satuan']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="satuan">Satuan:</label>
            <div class="input-group">
                <input required type="text" class="form-control" id="satuan" name="satuan" placeholder="Masukkan Satuan"
                    value="<?= $satuan[0]['satuan']; ?>" autocomplete="off" disabled>
                <div class="input-group-prepend">
                    <button id="editButton" type="button" class="btn btn-custom" style="margin-left: 20px; background-color:#FFA500">
                        Edit Satuan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Exwork Form -->
    <div class="card shadow p-4">
        <h1 class="text-center mb-4" id="exwork">Exwork Form</h1>

        <!-- Input Jumlah Barang -->
        <div class="form-group">
            <div class="col-md-6">
                <label id="jumlahBarangLabel" for="jumlahBarang">Jumlah Barang Dalam 1 Kontainer:</label>
                <div class="input-group">
                    <input required type="text" class="form-control" id="jumlahBarang" name="jumlahBarang"
                        placeholder="Masukkan Jumlah Barang" autocomplete="off">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= $satuan[0]['satuan']; ?></span>
                    </div>
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
                    <div class="input-group-prepend"><span class="input-group-text">/ <?= $satuan[0]['satuan']; ?></span></div>
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
                    <div class="input-group-prepend"><span class="input-group-text">/ <?= $satuan[0]['satuan']; ?></span></div>
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
                            <th>Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($exwork)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen Exwork yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($exwork as $index => $item): ?>
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
                                        <a href="<?= base_url('/komponen-exwork/delete/' . $item['id_exwork']) ?>"
                                            class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Baris untuk input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color: #03AADE;" id="tambahKolomExwork">
                                    Tambah Komponen Baru
                                </button>
                                <div id="komponenExworkContainer"></div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="submit" class="btn btn-custom" style="background-color: #77DD77;" id="submitKomponenExworkButton">
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
            <h3 class="result-harga-exwork mt-2">
                Harga Exwork:
                <?php if (session()->getFlashdata('harga_exwork')): ?>
                    <?= session()->getFlashdata('harga_exwork') ?>
                <?php endif; ?>
            </h3>
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
                    <div class="input-group-prepend"><span class="input-group-text">/ <?= $satuan[0]['satuan']; ?></span></div>
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
                            <th>Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fob)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen FOB yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fob as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_fob']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required
                                                type="text"
                                                class="form-control fob-existing"
                                                id="fob_<?= $item['id_fob'] ?>"
                                                name="fob_<?= $item['id_fob'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_fob']) ?>"
                                                autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-fob/delete/' . $item['id_fob']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomFOB">
                                    Tambah Komponen Baru
                                </button>
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
            <h3 class="result-harga-fob mt-2">Harga FOB: </h3>
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
                    <div class="input-group-prepend"><span class="input-group-text">/ <?= $satuan[0]['satuan']; ?></span></div>
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
                            <th>Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cfr)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen CFR yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cfr as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_cfr']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required
                                                type="text"
                                                class="form-control cfr-existing"
                                                id="cfr_<?= $item['id_cfr'] ?>"
                                                name="cfr_<?= $item['id_cfr'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_cfr']) ?>"
                                                autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-cfr/delete/' . $item['id_cfr']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomCFR">
                                    Tambah Komponen Baru
                                </button>

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
            <h3 class="result-harga-cfr mt-2">Harga CFR: </h3>
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
                    <div class="input-group-prepend"><span class="input-group-text">/ <?= $satuan[0]['satuan']; ?></span></div>
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
                            <th>Biaya (Rp.)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cif)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada Komponen CIF yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cif as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($item['komponen_cif']) ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                                            <input required
                                                type="text"
                                                class="form-control cif-existing"
                                                id="cif_<?= $item['id_cif'] ?>"
                                                name="cif_<?= $item['id_cif'] ?>"
                                                value="<?= number_format((int)($item['biaya'] ?? 0), 0, ',', '.') ?>"
                                                placeholder="Masukkan <?= esc($item['komponen_cif']) ?>"
                                                autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/komponen-cif/delete/' . $item['id_cif']) ?>" class="btn btn-outline-danger btn-sm align-center">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Baris input komponen baru -->
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-custom mb-2" style="background-color:#03AADE;" id="tambahKolomCIF">
                                    Tambah Komponen Baru
                                </button>
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
            <h3 class="result-harga-cif mt-2">Harga CIF: </h3>
        </div>

        <hr class="mt-2" style="border: 1px solid black; background-color: black;">
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('ukuran_kontainer').addEventListener('change', function() {
        var selectedUkuran = this.value;
        var label = document.getElementById('jumlahBarangLabel');
        label.textContent = selectedUkuran ?
            'Jumlah Barang Dalam 1 Kontainer ' + selectedUkuran + ':' :
            'Jumlah Barang Dalam 1 Kontainer:';
        // simpan ke sessionStorage
        saveStateOnce();
    });

    document.getElementById('editButton').addEventListener('click', function() {
        document.getElementById('satuan').disabled = false;
        this.outerHTML = '<button type="submit" class="btn btn-custom" style="margin-left: 20px; background-color: #8FD14F">Simpan Satuan</button>';
    });

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

    const STATE_GET_URL = "<?= base_url('kalkulator-state/json'); ?>";
    const STATE_POST_URL = "<?= base_url('kalkulator-state/upsert'); ?>";
    const CSRF_TOKEN_NAME = "<?= csrf_token() ?>";
    let CSRF_TOKEN_HASH = "<?= csrf_hash() ?>";

    // Debounce helper
    function debounce(fn, delay) {
        let t;
        return (...a) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...a), delay);
        };
    }

    async function loadStateFromServer() {
        try {
            const res = await fetch(STATE_GET_URL, {
                credentials: 'same-origin'
            });
            if (!res.ok) return null;
            const data = await res.json();
            if (data && data.csrf_token) CSRF_TOKEN_HASH = data.csrf_token;
            return data;
        } catch (e) {
            return null;
        }
    }

    const postStateDebounced = debounce(async function payloadSender(payload) {
        try {
            const body = new URLSearchParams();
            body.set(CSRF_TOKEN_NAME, CSRF_TOKEN_HASH);
            Object.keys(payload).forEach(k => body.set(k, payload[k]));

            const res = await fetch(STATE_POST_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                credentials: 'same-origin',
                body
            });
            if (res.ok) {
                const j = await res.json().catch(() => ({}));
                if (j && j.csrf_token) CSRF_TOKEN_HASH = j.csrf_token;
            }
        } catch (e) {}
    }, 500);

    function autosaveToServer() {
        const jumlah = bersihkanRupiah(document.getElementById('jumlahBarang')?.value || '');
        const hpp = bersihkanRupiah(document.getElementById('hpp')?.value || '');
        const untung = bersihkanRupiah(document.getElementById('keuntungan')?.value || '');
        if (jumlah !== '' || hpp !== '' || untung !== '') {
            postStateDebounced({
                jumlah_barang: jumlah === '' ? null : jumlah,
                hpp: hpp === '' ? null : hpp,
                keuntungan: untung === '' ? null : untung
            });
        }
    }

    const SS_KEYS = {
        ukuran: 'kalk_s_ukuran_kontainer',
        jumlah: 'kalk_s_jumlahBarang',
        hpp: 'kalk_s_hpp',
        untung: 'kalk_s_keuntungan',
    };
    const SS_FLAG = 'kalk_s_preserve_after_post';

    function getNavType() {
        const nav = performance.getEntriesByType && performance.getEntriesByType('navigation');
        if (nav && nav[0] && nav[0].type) return nav[0].type;
        return performance.navigation && performance.navigation.type === 1 ? 'reload' : 'navigate';
    }

    function saveStateOnce() {
        const ukuran = document.getElementById('ukuran_kontainer')?.value || '';
        const jumlah = document.getElementById('jumlahBarang')?.value || '';
        const hpp = document.getElementById('hpp')?.value || '';
        const untung = document.getElementById('keuntungan')?.value || '';
        sessionStorage.setItem(SS_KEYS.ukuran, ukuran);
        sessionStorage.setItem(SS_KEYS.jumlah, bersihkanRupiah(jumlah));
        sessionStorage.setItem(SS_KEYS.hpp, bersihkanRupiah(hpp));
        sessionStorage.setItem(SS_KEYS.untung, bersihkanRupiah(untung));
    }

    async function restoreStatePreferringServer() {
        const shouldPreserve = sessionStorage.getItem(SS_FLAG) === '1';
        let restoredFromServer = false;

        if (shouldPreserve) {
            const server = await loadStateFromServer();
            if (server && (server.jumlah_barang || server.hpp || server.keuntungan)) {
                const jumlah = server.jumlah_barang || '';
                const hpp = server.hpp || '';
                const untung = server.keuntungan || '';

                const elJumlah = document.getElementById('jumlahBarang');
                if (elJumlah && jumlah !== '') elJumlah.value = formatRupiah(jumlah);
                const elHpp = document.getElementById('hpp');
                if (elHpp && hpp !== '') elHpp.value = formatRupiah(hpp);
                const elUnt = document.getElementById('keuntungan');
                if (elUnt && untung !== '') elUnt.value = formatRupiah(untung);

                restoredFromServer = true;
            }
        }

        // Selalu pulihkan UKURAN dari sessionStorage saat reload atau saat preserve,
        // meskipun kita sudah restore dari server
        const navType = getNavType();
        if (navType === 'reload' || shouldPreserve) {
            const ukuran = sessionStorage.getItem(SS_KEYS.ukuran) || '';
            const sel = document.getElementById('ukuran_kontainer');
            if (sel && ukuran) {
                sel.value = ukuran;
                const label = document.getElementById('jumlahBarangLabel');
                if (label) label.textContent = 'Jumlah Barang Dalam 1 Kontainer ' + ukuran + ':';
            }
        }

        // Jika belum restore apa pun (mis. first reload), fallback lengkap dari sessionStorage
        if (!restoredFromServer && (navType === 'reload' || shouldPreserve)) {
            const jumlah = sessionStorage.getItem(SS_KEYS.jumlah) || '';
            const hpp = sessionStorage.getItem(SS_KEYS.hpp) || '';
            const untung = sessionStorage.getItem(SS_KEYS.untung) || '';

            const elJumlah = document.getElementById('jumlahBarang');
            if (elJumlah && jumlah) elJumlah.value = formatRupiah(jumlah);
            const elHpp = document.getElementById('hpp');
            if (elHpp && hpp) elHpp.value = formatRupiah(hpp);
            const elUnt = document.getElementById('keuntungan');
            if (elUnt && untung) elUnt.value = formatRupiah(untung);
        }

        try {
            hitungExwork();
            hitungFOB();
            hitungCFR();
            hitungCIF();
        } catch (e) {}

        if (shouldPreserve) sessionStorage.removeItem(SS_FLAG);
    }

    document.addEventListener('DOMContentLoaded', restoreStatePreferringServer);

    window.addEventListener('beforeunload', function() {
        if (sessionStorage.getItem(SS_FLAG) === '1') return;
        Object.values(SS_KEYS).forEach(k => sessionStorage.removeItem(k));
    });

    // reset jika back/forward cache
    window.addEventListener('pageshow', function(e) {
        if (e.persisted || getNavType() === 'back_forward') {
            Object.values(SS_KEYS).forEach(k => sessionStorage.removeItem(k));
            ['jumlahBarang', 'hpp', 'keuntungan'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            const sel = document.getElementById('ukuran_kontainer');
            if (sel) sel.value = '';
            const label = document.getElementById('jumlahBarangLabel');
            if (label) label.textContent = 'Jumlah Barang Dalam 1 Kontainer:';
            try {
                hitungExwork();
                hitungFOB();
                hitungCFR();
                hitungCIF();
            } catch (e) {}
        }
    });

    function hitungExwork() {
        let jumlahBarang = bersihkanRupiah(document.getElementById('jumlahBarang').value);
        let hpp = bersihkanRupiah(document.getElementById('hpp').value);
        let keuntungan = bersihkanRupiah(document.getElementById('keuntungan').value);

        if (!jumlahBarang || !hpp || !keuntungan) {
            document.querySelector('.result-harga-exwork').innerText = 'Harga Exwork: ';
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
        document.querySelector('.result-harga-exwork').innerText =
            'Harga Exwork: Rp. ' + formatRupiah(hargaExwork.toFixed(0)) + ' / <?= $satuan[0]['satuan']; ?>';
        document.getElementById('hargaExwork').value = formatRupiah(hargaExwork.toFixed(0));
    }

    function hitungFOB() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaExwork = parseFloat(bersihkanRupiah(document.getElementById('hargaExwork').value));
        if (!jumlahBarang || !hargaExwork) {
            document.querySelector('.result-harga-fob').innerText = 'Harga FOB: ';
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
        document.querySelector('.result-harga-fob').innerText =
            'Harga FOB: Rp. ' + formatRupiah(hargaFOB.toFixed(0)) + ' / <?= $satuan[0]['satuan']; ?>';
        document.getElementById('hargaFOB').value = formatRupiah(hargaFOB.toFixed(0));
    }

    function hitungCFR() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaFOB = parseFloat(bersihkanRupiah(document.getElementById('hargaFOB').value));
        if (!jumlahBarang || !hargaFOB) {
            document.querySelector('.result-harga-cfr').innerText = 'Harga CFR: ';
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
        document.querySelector('.result-harga-cfr').innerText =
            'Harga CFR: Rp. ' + formatRupiah(hargaCFR.toFixed(0)) + ' / <?= $satuan[0]['satuan']; ?>';
        document.getElementById('hargaCFR').value = formatRupiah(hargaCFR.toFixed(0));
    }

    function hitungCIF() {
        let jumlahBarang = parseFloat(bersihkanRupiah(document.getElementById('jumlahBarang').value));
        let hargaCFR = parseFloat(bersihkanRupiah(document.getElementById('hargaCFR').value));
        if (!jumlahBarang || !hargaCFR) {
            document.querySelector('.result-harga-cif').innerText = 'Harga CIF: ';
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
        document.querySelector('.result-harga-cif').innerText =
            'Harga CIF: Rp. ' + formatRupiah(hargaCIF.toFixed(0)) + ' / <?= $satuan[0]['satuan']; ?>';
    }

    document.querySelectorAll('#jumlahBarang, #hpp, #keuntungan, #hargaExwork, #hargaFOB, #hargaCFR').forEach(function(element) {
        element.addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungExwork();
            hitungFOB();
            hitungCFR();
            hitungCIF();
            if (['jumlahBarang', 'hpp', 'keuntungan'].includes(e.target.id)) {
                saveStateOnce();
                autosaveToServer();
            }
        });
        element.addEventListener('change', function(e) {
            if (['jumlahBarang', 'hpp', 'keuntungan'].includes(e.target.id)) {
                saveStateOnce();
                autosaveToServer();
            }
        });
    });

    // Existing inputs -> format & hitung
    document.querySelectorAll('.exwork-existing').forEach(function(el) {
        el.addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungExwork();
            hitungFOB();
            hitungCFR();
            hitungCIF();
        });
    });
    <?php foreach ($fob as $item): ?>
        document.getElementById('fob_<?= $item['id_fob'] ?>').addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungFOB();
            hitungCFR();
            hitungCIF();
        });
    <?php endforeach; ?>
    <?php foreach ($cfr as $item): ?>
        document.getElementById('cfr_<?= $item['id_cfr'] ?>').addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungCFR();
            hitungCIF();
        });
    <?php endforeach; ?>
    <?php foreach ($cif as $item): ?>
        document.getElementById('cif_<?= $item['id_cif'] ?>').addEventListener('keyup', function(e) {
            e.target.value = formatRupiah(e.target.value);
            hitungCIF();
        });
    <?php endforeach; ?>

        // Exwork add rows & submit
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
                        <label class="form-label fw-bold">Biaya (Rp.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="biayaExwork[]" class="form-control input-biaya-exwork" placeholder="0" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 btn-hapus-baris">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            `;
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
                });
                container.appendChild(row);
                updateCounter();
            });

            document.getElementById('formExworkAll').addEventListener('submit', function() {
                try {
                    saveStateOnce();
                    autosaveToServer();
                } catch (e) {}
                sessionStorage.setItem(SS_FLAG, '1');
                document.querySelectorAll('.exwork-existing').forEach(el => el.value = bersihkanRupiah(el.value));
                document.querySelectorAll('input[name="biayaExwork[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
            });
        })();

    // FOB add rows & submit
    (function() {
        const container = document.getElementById('komponenFOBContainer');
        const btnAdd = document.getElementById('tambahKolomFOB');
        const btnSubmit = document.getElementById('submitKomponenFOBButton');
        const form = document.getElementById('formFOBAll');

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
                        <label class="form-label fw-bold">Masukkan Komponen FOB</label>
                        <input type="text" name="komponenFOB[]" class="form-control" placeholder="Masukkan Komponen FOB" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label fw-bold">Biaya (Rp.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="biayaFOB[]" class="form-control input-biaya-fob" placeholder="0" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            `;
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
            });
            container.appendChild(row);
            updateCounter();
        });

        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
                autosaveToServer();
            } catch (e) {}
            sessionStorage.setItem(SS_FLAG, '1');
            document.querySelectorAll('.fob-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaFOB[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // CFR add rows & submit
    (function() {
        const container = document.getElementById('komponenCFRContainer');
        const btnAdd = document.getElementById('tambahKolomCFR');
        const btnSubmit = document.getElementById('submitKomponenCFRButton');
        const form = document.getElementById('formCFRAll');

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
                        <label class="form-label fw-bold">Masukkan Komponen CFR</label>
                        <input type="text" name="komponenCFR[]" class="form-control" placeholder="Masukkan Komponen CFR" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label fw-bold">Biaya (Rp.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="biayaCFR[]" class="form-control input-biaya-cfr" placeholder="0" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            `;
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
            });
            container.appendChild(row);
            updateCounter();
        });

        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
                autosaveToServer();
            } catch (e) {}
            sessionStorage.setItem(SS_FLAG, '1');
            document.querySelectorAll('.cfr-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaCFR[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    // CIF add rows & submit
    (function() {
        const container = document.getElementById('komponenCIFContainer');
        const btnAdd = document.getElementById('tambahKolomCIF');
        const btnSubmit = document.getElementById('submitKomponenCIFButton');
        const form = document.getElementById('formCIFAll');

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
                        <label class="form-label fw-bold">Masukkan Komponen CIF</label>
                        <input type="text" name="komponenCIF[]" class="form-control" placeholder="Masukkan Komponen CIF" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label fw-bold">Biaya (Rp.)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="biayaCIF[]" class="form-control input-biaya-cif" placeholder="0" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <small class="text-muted">Masukkan angka, otomatis diformat.</small>
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 btn-hapus-baris"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
            `;
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
            });
            container.appendChild(row);
            updateCounter();
        });

        form.addEventListener('submit', function() {
            try {
                saveStateOnce();
                autosaveToServer();
            } catch (e) {}
            sessionStorage.setItem(SS_FLAG, '1');
            document.querySelectorAll('.cif-existing').forEach(el => el.value = bersihkanRupiah(el.value));
            document.querySelectorAll('input[name="biayaCIF[]"]').forEach(el => el.value = bersihkanRupiah(el.value));
        });
    })();

    (function() {
        function hookDeleteLinks(selector) {
            document.addEventListener('click', function(e) {
                const a = e.target.closest('a');
                if (!a) return;
                if (!a.matches(selector)) return;
                try {
                    saveStateOnce();
                    autosaveToServer();
                } catch (err) {}
                sessionStorage.setItem(SS_FLAG, '1');
            }, true);
        }
        hookDeleteLinks('a[href*="/komponen-exwork/delete/"]');
        hookDeleteLinks('a[href*="/komponen-fob/delete/"]');
        hookDeleteLinks('a[href*="/komponen-cfr/delete/"]');
        hookDeleteLinks('a[href*="/komponen-cif/delete/"]');
    })();
</script>

<?= $this->endSection(); ?>