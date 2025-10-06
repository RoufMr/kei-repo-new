<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'meta_title' => ($lang == 'id') ? $meta['meta_title_daftar'] : $meta['meta_title_daftar_en'],
    'meta_description' => ($lang == 'id') ? $meta['meta_description_daftar'] : $meta['meta_description_daftar_en']
]);
?>

<style>
    /* Artikel Detail Section */
    .pendaftaran-section {
        padding: 0px 15px;
    }

    body {
        overflow-x: hidden;
    }

    /*css*/
    .card {
        margin-top: 20px;
    }

    .custom-card {
        background-color: #F5F5F7;
        color: #333;
        border: 1px solid #ccc;
    }

    .h3 {
        text-align: left;
    }

    .line-separator {
        width: 100%;
        min-width: 50px;
        height: 2px;
        background-color: #000;
        margin: 20px 0;
    }

    .textcontent {
        margin-top: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .line-separatorkecil {
        width: 100%;
        height: 2px;
        background-color: #000;
        margin: 10px 0;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 150px;
        margin-right: 10px;
        text-align: left;
        font-weight: normal;
    }

    .form-group input,
    .form-group select {
        flex: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .showpw {
        margin-left: 160px;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    .required {
        color: red;
    }

    .btn-custom {
        background-color: #03AADE;
        text-align: center;
        color: #ffffff;
    }

    .btn-custom:hover {
        background-color: #F2BF02;
        color: #ffffff;
    }

    .radio-group label {
        display: block;
        /* Membuat radio button dan teks berada dalam satu baris vertikal */
        margin-bottom: 5px;
        /* Memberikan jarak antar radio button */
    }

    .form-group textarea {
        flex: 1;
        /* Membuat textarea menyesuaikan dengan kolom lainnya */
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        /* Menyelaraskan panjang dengan input lainnya */
        min-height: 100px;
        /* Memberikan sedikit lebar ke bawah */
        resize: vertical;
        /* Membolehkan pengguna menyesuaikan tinggi */
    }

    /*responsive mobile*/
    @media (max-width: 768px) {

        .h3 {
            margin-top: 30px;
        }

        .form-group {
            display: block;
        }

        .form-group label {
            width: 100%;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
        }

        .showpw {
            margin-left: 10px;
        }

        .radiobutton {
            margin-left: 10px;
        }
    }
</style>

<!-- judul -->
<div class="pendaftaran-section pt-5 text-center">
    <h2 class="text-custom-title"><?= lang('Blog.pendaftaranJudul'); ?></h2>
    <p class="text-custom-paragraph mt-2"><?= lang('Blog.pendaftaranDeskripsi'); ?></p>
</div>

<!-- Form Pendaftaran -->
<div class="container py-5">
    <div class="row container gx-4">
        <div class="col-md-6 left-section">
            <h3><?= lang('Blog.keuntunganMember'); ?></h3>
            <hr class="line-separator">
            <p><?= lang('Blog.keuntunganDeskripsi'); ?></p>
            <?php $no = 1; ?>
            <?php foreach ($keuntungan as $item): ?>
                <p>
                    <strong>
                        <?= $no++ ?>. <?= ($lang == 'id') ? $item['judul_keuntungan'] : $item['judul_keuntungan_en'] ?>
                    </strong>
                    <br>
                    <?= ($lang == 'id') ? $item['deskripsi_keuntungan'] : $item['deskripsi_keuntungan_en'] ?>
                </p>
            <?php endforeach; ?>
        </div>

        <div class="col-md-6 right-section">
            <h3 class="h3"><?= lang('Blog.daftarJudul'); ?></h3>
            <hr class="line-separator">

            <!-- Card untuk Form Pendaftaran -->
            <div class="card p-3 custom-card">
                <!-- Menampilkan pesan error jika username atau email sudah ada -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('daftar-member') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Hidden field for role -->
                    <input type="hidden" name="role" value="user">

                    <div class="textcontent mt-2">
                        <h5><?= lang('Blog.infoAkun'); ?></h5>
                        <hr class="line-separatorkecil">
                    </div>

                    <!-- Username -->
                    <span id="username-status" class="form-text" style="margin-bottom: 10px; display: block; margin-left: 160px;"></span>
                    <div class="form-group">
                        <label for="username"><?= lang('Blog.username'); ?><span class="required">*</span></label>
                        <input type="text" id="username" name="username" required placeholder="<?= lang('Blog.placeholderUsername'); ?>" value="<?= old('username') ?>">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password"><?= lang('Blog.password'); ?><span class="required">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="<?= lang('Blog.placeholderPassword'); ?>">
                    </div>

                    <!-- Checkbox untuk Show Password -->
                    <div class="showpw">
                        <input type="checkbox" id="show-password" onclick="togglePassword()"> <?= lang('Blog.showPassword'); ?>
                    </div>

                    <!-- Referral -->
                    <span id="referral-status" class="form-text" style="margin-bottom: 10px; display: block; margin-left: 160px;"></span> <!-- Status pengecekan referral -->
                    <div class="form-group">
                        <label for="referral"><?= lang('Blog.referral'); ?></label>
                        <input type="text" id="referral" name="referral" placeholder="<?= lang('Blog.placeholderReferral'); ?>" value="<?= old('referral') ?>">
                    </div>

                    <!-- <div class="form-group">
                        <label>Jenis Member</label>
                        <div class="form-check d-flex align-items-start">
                            <input class="form-check-input" type="radio" name="pilihan" id="memberPremium" value="Member Premium" required>
                            <label class="form-check-label ms-2" for="memberPremium">
                                Member Premium
                            </label>
                        </div>
                        <div class="form-check d-flex align-items-start">
                            <input class="form-check-input" type="radio" name="pilihan" id="memberFree" value="Member Free" required>
                            <label class="form-check-label ms-2" for="memberFree">
                                Member Free
                            </label>
                        </div>
                    </div> -->

                    <div class="textcontent mt-5">
                        <h5><?= lang('Blog.profilPerusahaan'); ?></h5>
                        <hr class="line-separatorkecil">
                    </div>


                    <!-- Nama Perusahaan -->
                    <div class="form-group">
                        <label for="nama_perusahaan"><?= lang('Blog.namaPerusahaan'); ?><span class="required">*</span></label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan" required placeholder="<?= lang('Blog.placeholderNamaPerusahaan'); ?>" value="<?= old('nama_perusahaan') ?>">
                    </div>

                    <!-- Deskripsi Perusahaan -->
                    <div class="form-group">
                        <label for="deskripsi_perusahaan"><?= lang('Blog.deskripsiPerusahaan'); ?><span class="required">*</span></label>
                        <textarea type="text" id="deskripsi_perusahaan" name="deskripsi_perusahaan" required placeholder="<?= lang('Blog.placeholderDeskripsiPerusahaan'); ?>" value="<?= old('deskripsi_perusahaan') ?>"></textarea>
                    </div>

                    <!-- Tahun Berdiri Perusahaan -->
                    <div class="form-group">
                        <label for="tahun_berdiri"><?= lang('Blog.tahunberdiri'); ?><span class="required">*</span></label>
                        <select id="tahun_berdiri" name="tahun_berdiri" required>
                            <option value="" disabled selected><?= lang('Blog.placeholdertahunberdiri'); ?></option>
                            <?php
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year >= 1900; $year--) {
                                echo "<option value=\"$year\"" . (old('tahun_berdiri') == $year ? ' selected' : '') . ">$year</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Alamat Perusahaan -->
                    <div class="form-group">
                        <label for="alamat_perusahaan"><?= lang('Blog.alamatperusahaan'); ?><span class="required">*</span></label>
                        <textarea id="alamat_perusahaan" name="alamat_perusahaan" required placeholder="<?= lang('Blog.placeholderalamatperusahaan'); ?>"><?= old('alamat_perusahaan') ?></textarea>
                    </div>

                    <!-- Produk Perusahaan -->
                    <div class="form-group">
                        <label for="produk_utama"><?= lang('Blog.produkperusahaan'); ?><span class="required">*</span></label>
                        <textarea id="produk_utama" name="produk_utama" required placeholder="<?= lang('Blog.placeholderaprodukperusahaan'); ?>"><?= old('produk_utama') ?></textarea>
                    </div>

                    <!-- Kategori Produk -->
                    <div class="form-group">
                        <label for="kategori_produk"><?= lang('Blog.kategoriProduk'); ?><span class="required">*</span></label>
                        <select id="kategori_produk" name="kategori_produk" required>
                            <option value="" disabled selected><?= lang('Blog.placeholderKategoriProduk'); ?></option>
                            <?php foreach ($kategori_induk as $item): ?>
                                <optgroup label='<?= ($lang == 'id') ? $item['nama_kategori_induk'] : $item['nama_kategori_induk_en'] ?>'>
                                    <?php if (!empty($kategori_produk_terkelompok[$item['id_kategori_induk']])): ?>
                                        <?php foreach ($kategori_produk_terkelompok[$item['id_kategori_induk']] as $produk): ?>
                                            <option value="<?= ($lang == 'id') ? $produk['nama_kategori_produk'] : $produk['nama_kategori_produk_en'] ?>">
                                                <?= ($lang == 'id') ? $produk['nama_kategori_produk'] : $produk['nama_kategori_produk_en'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <!-- Alamat Website Perusahaan -->
                    <div class="form-group">
                        <label for="alamat_website"><?= lang('Blog.alamatwebperusahaan'); ?><span class="required">*</span></label>
                        <textarea id="alamat_website" name="alamat_website" required placeholder="<?= lang('Blog.placeholderalamatwebperusahaan'); ?>"><?= old('alamat_website') ?></textarea>
                    </div>

                    <div class="textcontent mt-5">
                        <h5><?= lang('Blog.profilpic'); ?></h5>
                        <hr class="line-separatorkecil">
                    </div>

                    <!-- Nama PIC -->
                    <div class="form-group">
                        <label for="pic"><?= lang('Blog.pic'); ?><span class="required">*</span></label>
                        <input type="text" id="pic" name="pic" required placeholder="<?= lang('Blog.placeholderPIC'); ?>" value="<?= old('pic') ?>">
                    </div>

                    <!-- Email -->
                    <span id="email-status" class="form-text" style="margin-bottom: 10px; display: block; margin-left: 160px;"></span> <!-- Status pengecekan email di bawah input -->
                    <div class="form-group">
                        <label for="email"><?= lang('Blog.emailPendaftaran'); ?><span class="required">*</span></label>
                        <input type="email" id="email" name="email" required placeholder="<?= lang('Blog.placeholderEmail'); ?>" value="<?= old('email') ?>">
                    </div>

                    <!-- No HP Perusahaan -->
                    <div class="form-group">
                        <label for="nomor_pic"><?= lang('Blog.noPIC'); ?><span class="required">*</span></label>
                        <input type="tel" id="nomor_pic" name="nomor_pic" required placeholder="<?= lang('Blog.placeholderNoPIC'); ?>" value="<?= old('nomor_pic') ?>">
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-custom mt-3" style="width: 100%;"><?= lang('Blog.submitButton'); ?></button>
                </form>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var isUsernameValid = false;
        var isEmailValid = false;
        var isReferralValid = true;

        function checkFormValidity() {
            return isUsernameValid && isEmailValid && isReferralValid;
        }

        // Pengecekan di sisi client ketika form akan disubmit
        $('form').on('submit', function(event) {
            var username = $('#username').val();
            var referral = $('#referral').val();

            if (referral && username === referral) {
                event.preventDefault();
                Swal.fire({
                    title: "Pastikan Input Sesuai Dengan Ketentuan!",
                    icon: "warning",
                    confirmButtonText: "Oke",
                });
                isReferralValid = false;
                return;
            }

            if (!checkFormValidity()) {
                event.preventDefault();
                Swal.fire({
                    title: "Pastikan Input Sesuai Dengan Ketentuan!",
                    icon: "warning",
                    confirmButtonText: "Oke",
                });
            }
        });

        // Pengecekan username dari server
        $('#username').on('input', function() {
            var username = $(this).val();
            var referral = $('#referral').val();

            if (username.length > 0) {
                $.ajax({
                    url: '/user/checkAvailability',
                    type: 'POST',
                    data: {
                        username: username
                    },
                    success: function(response) {
                        if (response.status === 'exists') {
                            $('#username-status').html('<span style="color: red;">Username sudah terdaftar</span>');
                            isUsernameValid = false;
                        } else {
                            $('#username-status').html('<span style="color: green;">Username tersedia</span>');
                            isUsernameValid = true;
                        }
                    }
                });
            } else {
                $('#username-status').html('');
                isUsernameValid = false;
            }

            if (referral && username === referral) {
                $('#referral-status').html('<span style="color: red;">Kode referral tidak boleh sama dengan username</span>');
                isReferralValid = false;
            } else {
                $('#referral-status').html('');
                isReferralValid = true;
            }
        });

        // Pengecekan email dari server
        $('#email').on('input', function() {
            var email = $(this).val();
            if (email.length > 0) {
                $.ajax({
                    url: '/user/checkAvailability',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.status === 'exists') {
                            $('#email-status').html('<span style="color: red;">Email sudah terdaftar</span>');
                            isEmailValid = false;
                        } else {
                            $('#email-status').html('<span style="color: green;">Email tersedia</span>');
                            isEmailValid = true;
                        }
                    }
                });
            } else {
                $('#email-status').html('');
                isEmailValid = false;
            }
        });

        // Pengecekan referral dari server
        $('#referral').on('input', function() {
            var referral = $(this).val();
            var username = $('#username').val();

            if (referral.length > 0) {
                $.ajax({
                    url: '/user/checkAvailability',
                    type: 'POST',
                    data: {
                        referral: referral
                    },
                    success: function(response) {
                        if (response.status === 'invalid') {
                            $('#referral-status').html('<span style="color: red;">' + response.message + '</span>');
                            isReferralValid = false;
                        } else {
                            $('#referral-status').html('<span style="color: green;">Kode referral valid</span>');
                            isReferralValid = true;
                        }
                    }
                });
            } else {
                $('#referral-status').html('');
                isReferralValid = true;
            }


        });
    });

    function togglePassword() {
        var passwordField = document.getElementById("password");
        var showPassword = document.getElementById("show-password");
        if (showPassword.checked) {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

<?= $this->endSection(); ?>