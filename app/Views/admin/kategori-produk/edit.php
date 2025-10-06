<?= $this->extend('admin/template/template'); ?>
<?= $this->Section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title" style="color: #03AADE;">Ubah Kategori Produk</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="card-body">
                        <form action="<?= base_url('/admin-kategori-produk-update/' . $kategori_produk['id_kategori_produk']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>

                            <!-- Dropdown untuk kategori induk -->
                            <div class="mb-3">
                                <label for="kategori_induk" class="form-label">Kategori Induk</label>
                                <select id="kategori_induk" class="form-control" name="id_kategori_induk" required>
                                    <option value="" disabled hidden selected>-- Pilih Kategori Induk --</option>
                                    <?php foreach ($kategori_induk as $induk) : ?>
                                        <option value="<?= $induk['id_kategori_induk']; ?>" <?= ($induk['id_kategori_induk'] == $kategori_produk['id_kategori_induk']) ? 'selected' : '' ?>><?= $induk['nama_kategori_induk']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Produk ID</label>
                                <input type="text" class="form-control" name="nama_kategori_produk" value="<?= esc($kategori_produk['nama_kategori_produk']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori produk EN</label>
                                <input type="text" class="form-control" name="nama_kategori_produk_en" value="<?= esc($kategori_produk['nama_kategori_produk_en']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn text-white" style="background-color: #03AADE;">Simpan</button>
                                <a href="<?= base_url('admin-kategori-produk') ?>" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div><!--//app-card-->
            </div>
        </div><!--//row-->
        <hr class="my-4">
    </div><!--//container-fluid-->
</div><!--//app-content-->

<?= $this->endSection(); ?>