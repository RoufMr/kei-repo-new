```php
<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0" style="color: #03AADE;">List Calon Member</h1>
            </div>
        </div>

        <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Username</th>
                                <th class="text-center align-middle">Nama Perusahaan</th>
                                <th class="text-center align-middle">Email</th>
                                <th class="text-center align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($member)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada calon member.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1;
                                foreach ($member as $item): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $item['username'] ?></td>
                                        <td class="text-center"><?= $item['nama_perusahaan'] ?></td>
                                        <td class="text-center"><?= $item['email'] ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin-detail-member/' . $item['id_member']) ?>"
                                                class="btn btn-info btn-sm text-white">Detail</a>
                                            <!-- Tombol Konfirmasi -->
                                            <a href="#"
                                                class="btn btn-sm text-white"
                                                style="background-color:#03AADE;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#konfirmasiModal<?= $item['id_member'] ?>">
                                                Konfirmasi
                                            </a>

                                            <!-- Modal Bootstrap -->
                                            <div class="modal fade" id="konfirmasiModal<?= $item['id_member'] ?>" tabindex="-1" aria-labelledby="konfirmasiLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="border-radius:10px;">
                                                        <div class="modal-header" style="background-color:#03AADE; color:white;">
                                                            <h5 class="modal-title" id="konfirmasiLabel">Konfirmasi Member</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                                                        </div>
                                                        <div class="modal-body text-center" style="color:#000;">
                                                            <p>Apakah kamu yakin ingin mengkonfirmasi member ini?</p>
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-center">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:#FFD700; color:#000;">Batal</button>
                                                            <a href="<?= base_url('admin-konfirmasi-member/' . $item['id_member']) ?>" class="btn text-white" style="background-color:#03AADE;">Ya, Konfirmasi</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
```