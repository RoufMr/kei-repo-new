<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title" style="color: #03AADE;">Ubah Data Meta</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="card-body">
                        <form action="<?= base_url('admin-update-meta') ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Meta Title Beranda ID</label>
                                <textarea class="form-control" name="meta_title_beranda" style="height: 120px;" placeholder="Masukkan Meta Title Beranda ID"><?= $meta['meta_title_beranda'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Beranda EN</label>
                                <textarea class="form-control" name="meta_title_beranda_en" style="height: 120px;" placeholder="Masukkan Meta Title Beranda EN"><?= $meta['meta_title_beranda_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Beranda ID</label>
                                <textarea class="form-control" name="meta_description_beranda" style="height: 120px;" placeholder="Masukkan Meta Title Beranda ID"><?= $meta['meta_description_beranda'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Beranda EN</label>
                                <textarea class="form-control" name="meta_description_beranda_en" style="height: 120px;" placeholder="Masukkan Meta Title Beranda EN"><?= $meta['meta_description_beranda_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Tentang ID</label>
                                <textarea class="form-control" name="meta_title_tentang" style="height: 120px;" placeholder="Masukkan Meta Title Tentang ID"><?= $meta['meta_title_tentang'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Tentang EN</label>
                                <textarea class="form-control" name="meta_title_tentang_en" style="height: 120px;" placeholder="Masukkan Meta Title Tentang EN"><?= $meta['meta_title_tentang_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Tentang ID</label>
                                <textarea class="form-control" name="meta_description_tentang" style="height: 120px;" placeholder="Masukkan Meta Title Tentang ID"><?= $meta['meta_description_tentang'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Tentang EN</label>
                                <textarea class="form-control" name="meta_description_tentang_en" style="height: 120px;" placeholder="Masukkan Meta Title Tentang EN"><?= $meta['meta_description_tentang_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Materi ID</label>
                                <textarea class="form-control" name="meta_title_materi" style="height: 120px;" placeholder="Masukkan Meta Title Materi ID"><?= $meta['meta_title_materi'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Materi EN</label>
                                <textarea class="form-control" name="meta_title_materi_en" style="height: 120px;" placeholder="Masukkan Meta Title Materi EN"><?= $meta['meta_title_materi_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Materi ID</label>
                                <textarea class="form-control" name="meta_description_materi" style="height: 120px;" placeholder="Masukkan Meta Title Materi ID"><?= $meta['meta_description_materi'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Materi EN</label>
                                <textarea class="form-control" name="meta_description_materi_en" style="height: 120px;" placeholder="Masukkan Meta Title Materi EN"><?= $meta['meta_description_materi_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Tutorial ID</label>
                                <textarea class="form-control" name="meta_title_tutorial" style="height: 120px;" placeholder="Masukkan Meta Title Tutorial ID"><?= $meta['meta_title_tutorial'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Tutorial EN</label>
                                <textarea class="form-control" name="meta_title_tutorial_en" style="height: 120px;" placeholder="Masukkan Meta Title Tutorial EN"><?= $meta['meta_title_tutorial_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Tutorial ID</label>
                                <textarea class="form-control" name="meta_description_tutorial" style="height: 120px;" placeholder="Masukkan Meta Title Tutorial ID"><?= $meta['meta_description_tutorial'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Tutorial EN</label>
                                <textarea class="form-control" name="meta_description_tutorial_en" style="height: 120px;" placeholder="Masukkan Meta Title Tutorial EN"><?= $meta['meta_description_tutorial_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Member ID</label>
                                <textarea class="form-control" name="meta_title_member" style="height: 120px;" placeholder="Masukkan Meta Title Member ID"><?= $meta['meta_title_member'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Member EN</label>
                                <textarea class="form-control" name="meta_title_member_en" style="height: 120px;" placeholder="Masukkan Meta Title Member EN"><?= $meta['meta_title_member_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Member ID</label>
                                <textarea class="form-control" name="meta_description_member" style="height: 120px;" placeholder="Masukkan Meta Title Member ID"><?= $meta['meta_description_member'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Member EN</label>
                                <textarea class="form-control" name="meta_description_member_en" style="height: 120px;" placeholder="Masukkan Meta Title Member EN"><?= $meta['meta_description_member_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Daftar ID</label>
                                <textarea class="form-control" name="meta_title_daftar" style="height: 120px;" placeholder="Masukkan Meta Title Daftar ID"><?= $meta['meta_title_daftar'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Title Daftar EN</label>
                                <textarea class="form-control" name="meta_title_daftar_en" style="height: 120px;" placeholder="Masukkan Meta Title Daftar EN"><?= $meta['meta_title_daftar_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Daftar ID</label>
                                <textarea class="form-control" name="meta_description_daftar" style="height: 120px;" placeholder="Masukkan Meta Title Daftar ID"><?= $meta['meta_description_daftar'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description Daftar EN</label>
                                <textarea class="form-control" name="meta_description_daftar_en" style="height: 120px;" placeholder="Masukkan Meta Title Daftar EN"><?= $meta['meta_description_daftar_en'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn text-white" style="background-color: #03AADE;">Simpan</button>
                                <a href="<?= base_url('admin-meta') ?>" class="btn btn-secondary">Kembali</a>
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