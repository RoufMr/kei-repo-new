<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title" style="color: #03AADE;">Tambah Data Fitur</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="card-body">
                        <form action="<?= base_url('admin-create-fitur') ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Role Fitur</label>
                                <input type="text" class="form-control" name="role" placeholder="Masukkan Role Fitur" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Fitur ID</label>
                                <input type="text" class="form-control" name="nama_fitur" placeholder="Masukkan Nama Fitur ID" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Fitur EN</label>
                                <input type="text" class="form-control" name="nama_fitur_en" placeholder="Masukkan Nama Fitur EN" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn text-white" style="background-color: #03AADE;">Tambah</button>
                                <a href="<?= base_url('admin-fitur') ?>" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div><!--//app-card-->
            </div>
        </div><!--//row-->
        <hr class="my-4">
    </div><!--//container-fluid-->
</div><!--//app-content-->

<?= $this->endSection('content') ?>