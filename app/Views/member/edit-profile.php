<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<style>
    .lingkaran {
        margin: auto;
        overflow: hidden;
        border-radius: 50%;
        position: relative;
        width: 250px;
        height: 250px;
    }

    .lingkaran img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .btn-custom {
        text-align: center;
        color: #ffffff;
    }

    .btn-custom:hover {
        color: #fff;
        box-shadow: 0px 0px 10px #F2BF02;
        background-color: #F2BF02 !important;
        /* Mengubah warna saat hover menjadi #F2BF02 */
    }

    .img-fluid {
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        object-fit: cover;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
        font-size: 28px;
        font-weight: bold;
    }

    .card p {
        margin: 5px 0;
        color: #666;
    }

    .badge-lg {
        font-size: 16px;
        padding: 10px 15px;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        margin-top: auto;
        border-radius: 8px;
        width: 100%;
    }

    .tab-content {
        margin-top: 20px;
    }

    .custom-tab {
        color: #000;
    }

    .custom-tab:hover {
        color: #007bff;
    }

    .nav-link.active {
        color: #007bff !important;
    }

    .section-title {
        margin-bottom: 30px;
    }

    .text-truncate-description {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-body h5 {
        font-size: 16px;
        font-weight: bold;
    }

    .hover-card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .hover-card:hover {
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    /* Mengatur warna teks tombol aktif */
    .nav-tabs .nav-link.active {
        color: #03AADE !important;
    }

    /* Opsional: Mengatur efek hover untuk konsistensi */
    .nav-tabs .nav-link:hover {
        color: #03AADE;
    }


    /* Animasi */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .container h2,
    .container p,
    .card h5,
    .form-label,
    .btn-warning {
        opacity: 0;
        animation: fadeIn 0.5s ease forwards;
    }

    .container h2 {
        animation-delay: 0.2s;
    }

    .container p {
        animation-delay: 0.4s;
    }

    .card h5 {
        animation-delay: 0.6s;
    }

    .form-label {
        animation-delay: 0.8s;
    }

    .btn-warning {
        animation-delay: 1s;
    }

    /* end */

    @media (max-width: 768px) {
        .card {
            margin-left: 50px;
        }

        .card-body h5 {
            font-size: 1.25rem;
            /* Adjust title size */
        }

        .card-img-top {
            height: 180px;
            /* Adjust image height */
        }

        .row .col-md-4 {
            flex-basis: 50%;
            /* Two cards per row on tablet */
        }
    }

    @media (max-width: 576px) {
        .card {
            margin-left: 40px;
        }

        .row .col-md-4 {
            flex-basis: 100%;
            /* Full width card on mobile */
        }

        .card-img-top {
            height: 150px;
            /* Smaller image height */
        }

        .card-body h5 {
            font-size: 1rem;
            /* Smaller title on mobile */
        }
    }

    @media (max-width: 425px) {
        .card {
            margin-right: 40px;
        }
    }

    @media (max-width: 375px) {
        .card {
            margin-right: 40px;
        }
    }

    @media (max-width: 320px) {
        .lingkaran {
            width: 200px;
            height: 200px;
            right: 5px;
        }
    }
</style>

<div class="container mt-4">
    <div class="judul text-center mt-5">
        <h2>Edit Profile</h2>
        <p>Anda Dapat mengubah data diri anda</p>
    </div>
    <div class="card p-4 shadow-sm mt-5">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= is_array(session()->getFlashdata('error')) ? implode('<br>', session()->getFlashdata('error')) : session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <!-- Form untuk upload foto -->
        <form action="<?= base_url('/update-foto-profil'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="text-center mb-3 shadow lingkaran">
                <!-- Image Preview -->
                <img id="profileImage" src="<?= base_url('img/' . ($member['foto_profil'] ?? 'default.jpg')); ?>"
                    alt="Foto Profil">
            </div>

            <!-- Upload Button -->
            <div>
                <div class="text-center" style="position: relative; top: -40px;">
                    <button type="button" class="btn btn-custom" onclick="document.getElementById('fileInput').click()"
                        style="width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; background-color: #03AADE;">
                        <i class="fas fa-edit" style="font-size: 17px; color: #fff;"></i>
                    </button>
                </div>

                <!-- Hidden File Input -->
                <input type="file" id="fileInput" name="foto_profil" accept="image/*" style="display: none;"
                    onchange="previewImage(event)">
            </div>

            <!-- Submit Button -->
            <div class="text-center mb-3">
                <button type="submit" class="btn btn-custom"
                    style="width: 100px; background-color: #03AADE; color: #fff;">Submit</button>
            </div>
        </form>

        <h4 class="text-center mt-1"><?= $member['username'] ?></h4>
        <?php if (session()->get('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->get('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs justify-content-center gap-3 mt-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active custom-tab" id="informasi-tab" data-bs-toggle="tab"
                    data-bs-target="#informasi" type="button" role="tab" aria-controls="informasi"
                    aria-selected="true">Informasi Akun</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link custom-tab" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil"
                    type="button" role="tab" aria-controls="profil" aria-selected="false">Profil Perusahaan</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-4" id="myTabContent">
            <!-- Informasi Perusahaan -->
            <div class="tab-pane fade show active" id="informasi" role="tabpanel" aria-labelledby="informasi-tab">
                <h5 class="mb-4">Informasi Akun</h5>
                <form action="<?= base_url('/ubah-informasi-akun'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="Masukkan Password">
                        </div>
                        <button type="submit" class="btn btn-custom" style="background-color: #03AADE;">Submit</button>
                    </div>
                </form>
            </div>

            <!-- Profil Perusahaan -->
            <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                <h5 class="mb-4">Profil Perusahaan</h5>
                <form action="<?= base_url('/ubah-profil-perusahaan'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                            <input type="text"
                                class="form-control <?= isset($errors['nama_perusahaan']) ? 'is-invalid' : '' ?>"
                                id="nama_perusahaan" name="nama_perusahaan"
                                value="<?= old('nama_perusahaan', $member['nama_perusahaan']) ?>"
                                placeholder="Masukkan Nama Perusahaan" required>
                            <?php if (isset($errors['nama_perusahaan'])): ?>
                                <div class="invalid-feedback"><?= $errors['nama_perusahaan'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deskripsi_perusahaan" class="form-label">Deskripsi Perusahaan ID</label>
                            <textarea
                                class="form-control <?= isset($errors['deskripsi_perusahaan']) ? 'is-invalid' : '' ?>"
                                id="deskripsi_perusahaan" name="deskripsi_perusahaan"
                                placeholder="Masukkan Deskripsi Perusahaan"
                                required><?= old('deskripsi_perusahaan', $member['deskripsi_perusahaan']) ?></textarea>
                            <?php if (isset($errors['deskripsi_perusahaan'])): ?>
                                <div class="invalid-feedback"><?= $errors['deskripsi_perusahaan'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="deskripsi_perusahaan_en" class="form-label">Deskripsi Perusahaan EN</label>
                            <textarea
                                class="form-control <?= isset($errors['deskripsi_perusahaan_en']) ? 'is-invalid' : '' ?>"
                                id="deskripsi_perusahaan_en" name="deskripsi_perusahaan_en"
                                placeholder="Masukkan Deskripsi Perusahaan (Inggris)"
                                required><?= old('deskripsi_perusahaan_en', $member['deskripsi_perusahaan_en']) ?></textarea>
                            <?php if (isset($errors['deskripsi_perusahaan_en'])): ?>
                                <div class="invalid-feedback"><?= $errors['deskripsi_perusahaan_en'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                            <textarea
                                class="form-control <?= isset($errors['alamat_perusahaan']) ? 'is-invalid' : '' ?>"
                                id="alamat_perusahaan" name="alamat_perusahaan" placeholder="Masukkan Alamat Perusahaan"
                                required><?= old('alamat_perusahaan', $member['alamat_perusahaan']) ?></textarea>
                            <?php if (isset($errors['alamat_perusahaan'])): ?>
                                <div class="invalid-feedback"><?= $errors['alamat_perusahaan'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat_website" class="form-label">Alamat Website</label>
                            <textarea class="form-control <?= isset($errors['alamat_website']) ? 'is-invalid' : '' ?>"
                                id="alamat_website" name="alamat_website" placeholder="Masukkan Alamat Website"
                                required><?= old('alamat_website', $member['alamat_website']) ?></textarea>
                            <?php if (isset($errors['alamat_website'])): ?>
                                <div class="invalid-feedback"><?= $errors['alamat_website'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="tahun_berdiri" class="form-label">Tahun Dibentuk</label>
                            <select id="tahun_berdiri" name="tahun_dibentuk"
                                class="form-control <?= isset($errors['tahun_dibentuk']) ? 'is-invalid' : '' ?>"
                                required>
                                <option value="" disabled selected>-- Pilih Tahun Dibentuk --</option>
                                <?php
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= 1900; $year--) {
                                    // Ambil tahun yang dipilih, pertama kali coba dari old() jika ada error, atau dari database jika sudah disimpan
                                    $selected = (old('tahun_dibentuk', $member['tahun_dibentuk']) == $year) ? ' selected' : '';
                                    echo "<option value=\"$year\"$selected>$year</option>";
                                }
                                ?>
                            </select>
                            <?php if (isset($errors['tahun_dibentuk'])): ?>
                                <div class="invalid-feedback"><?= $errors['tahun_dibentuk'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori_produk" class="form-label">Kategori Produk ID</label>
                            <select id="kategori_produk" name="kategori_produk"
                                class="form-control <?= isset($errors['kategori_produk']) ? 'is-invalid' : '' ?>"
                                required>
                                <option value="" disabled selected>-- Pilih Kategori Produk --</option>
                                <?php foreach ($kategori_induk as $item): ?>
                                    <optgroup label='<?= $item['nama_kategori_induk'] ?>'>
                                        <?php if (!empty($kategori_produk_terkelompok[$item['id_kategori_induk']])): ?>
                                            <?php foreach ($kategori_produk_terkelompok[$item['id_kategori_induk']] as $produk): ?>
                                                <option value="<?= $produk['nama_kategori_produk'] ?>" <?= old('kategori_produk', $member['kategori_produk']) == $produk['nama_kategori_produk'] ? 'selected' : '' ?>>
                                                    <?= $produk['nama_kategori_produk'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['kategori_produk'])): ?>
                                <div class="invalid-feedback"><?= $errors['kategori_produk'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kategori_produk_en" class="form-label">Kategori Produk EN</label>
                            <select id="kategori_produk_en" name="kategori_produk_en"
                                class="form-control <?= isset($errors['kategori_produk_en']) ? 'is-invalid' : '' ?>"
                                required>
                                <option value="" disabled selected>-- Pilih Kategori Produk --</option>
                                <?php foreach ($kategori_induk as $item): ?>
                                    <optgroup label='<?= $item['nama_kategori_induk_en'] ?>'>
                                        <?php if (!empty($kategori_produk_terkelompok[$item['id_kategori_induk']])): ?>
                                            <?php foreach ($kategori_produk_terkelompok[$item['id_kategori_induk']] as $produk): ?>
                                                <option value="<?= $produk['nama_kategori_produk_en'] ?>"
                                                    <?= old('kategori_produk_en', $member['kategori_produk_en']) == $produk['nama_kategori_produk_en'] ? 'selected' : '' ?>>
                                                    <?= $produk['nama_kategori_produk_en'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['kategori_produk_en'])): ?>
                                <div class="invalid-feedback"><?= $errors['kategori_produk_en'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="produk_utama" class="form-label">Produk Utama ID</label>
                            <textarea class="form-control <?= isset($errors['produk_utama']) ? 'is-invalid' : '' ?>"
                                id="produk_utama" name="produk_utama" placeholder="Masukkan Produk Utama"
                                required><?= old('produk_utama', $member['produk_utama']) ?></textarea>
                            <?php if (isset($errors['produk_utama'])): ?>
                                <div class="invalid-feedback"><?= $errors['produk_utama'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="produk_utama_en" class="form-label">Produk Utama EN</label>
                            <textarea class="form-control <?= isset($errors['produk_utama_en']) ? 'is-invalid' : '' ?>"
                                id="produk_utama_en" name="produk_utama_en"
                                placeholder="Masukkan Produk Utama (Inggris)"
                                required><?= old('produk_utama_en', $member['produk_utama_en']) ?></textarea>
                            <?php if (isset($errors['produk_utama_en'])): ?>
                                <div class="invalid-feedback"><?= $errors['produk_utama_en'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pic" class="form-label">Nama PIC</label>
                            <input type="text" class="form-control <?= isset($errors['pic']) ? 'is-invalid' : '' ?>"
                                id="pic" name="pic" value="<?= old('pic', $member['pic']) ?>" placeholder="Masukkan PIC"
                                required>
                            <?php if (isset($errors['pic'])): ?>
                                <div class="invalid-feedback"><?= $errors['pic'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pic_phone" class="form-label">Nomor HP PIC</label>
                            <input type="text"
                                class="form-control <?= isset($errors['pic_phone']) ? 'is-invalid' : '' ?>"
                                id="pic_phone" name="pic_phone" value="<?= old('pic_phone', $member['pic_phone']) ?>"
                                placeholder="Masukkan No.Telp PIC" required>
                            <?php if (isset($errors['pic_phone'])): ?>
                                <div class="invalid-feedback"><?= $errors['pic_phone'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email PIC</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?= $member['email'] ?>" placeholder="Masukkan Email">
                        </div>

                        <button type="submit" class="btn btn-custom" style="background-color: #03AADE;">Submit</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const image = document.getElementById('profileImage');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result; // Update pratinjau gambar dengan file yang dipilih
            };
            reader.readAsDataURL(file);
        }
    }
</script><?= $this->endSection(); ?>