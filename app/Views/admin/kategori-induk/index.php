<?= $this->extend('admin/template/template'); ?>
<?= $this->Section('content'); ?>

<style>
    .table-hover tbody tr:hover {
        background-color: #f2f2f2;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
        border-bottom: 2px solid #dee2e6;
        text-align: center;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 12px;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Tooltip for long content */
    .tooltip-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Adding fixed width for certain columns */
    .col-fixed {
        width: 150px;
    }

    .text-wrap {
        white-space: normal;
        max-height: 100px;
        overflow-y: auto;
    }

    .btn-sm {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
</style>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0" style="color: #03AADE;">List Kategori Induk</h1>
            </div>

            <div class="col-auto">
                <a href="<?= base_url('/admin-kategori-induk-tambah') ?>" class="btn text-white" style="background-color: #03AADE;"> + Tambah Kategori Induk</a>
            </div>
        </div>

        <div class="tab-content" id="orders-table-tab-content">
            <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                <div class="app-card app-card-orders-table shadow-sm mb-5">
                    <div class="app-card-body">
                        <div class="table-responsive">
                            <table class="table app-table-hover table-bordered mb-0 text-left">
                                <thead>
                                    <tr>
                                        <th class="text-center" valign="middle">No</th>
                                        <th class="text-center" valign="middle">Kategori Induk ID</th>
                                        <th class="text-center" valign="middle">Kategori Induk EN</th>
                                        <th class="text-center" valign="middle">Aksi</th>
                                    </tr>
                                </thead>
                                <?php if (empty($kategori_induk)): ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center">Masih belum ada Data Kategori Induk.</td>
                                        </tr>
                                    </tbody>
                            </table>
                        <?php else: ?>
                            <tbody>
                                <?php $start = ($page - 1) * $perPage + 1; ?>
                                <?php foreach ($kategori_induk as $item): ?>
                                    <tr>
                                        <td class="text-center" valign="middle"><?= $start++; ?></td>
                                        <td class="text-center" valign="middle"><?= $item['nama_kategori_induk']; ?></td>
                                        <td class="text-center" valign="middle"><?= $item['nama_kategori_induk_en']; ?></td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="<?= base_url('/admin-kategori-induk-delete/' . $item['id_kategori_induk']) ?>" class="btn btn-sm text-white me-2" style="background-color: #F2BF02;">Hapus</a>
                                                <a href="<?= base_url('/admin-kategori-induk-edit/' . $item['id_kategori_induk']) ?>" class="btn btn-sm text-white" style="background-color: #03AADE;">Ubah</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            </table>
                            <div class="mt-2">
                                <?= $pager->links('default', 'bootstrap_pagination') ?>
                            </div>
                        <?php endif; ?>
                        </div><!--//table-responsive-->
                    </div><!--//app-card-body-->
                </div><!--//app-card-->
            </div><!--//tab-pane-->
        </div><!--//container-fluid-->
    </div><!--//app-content-->
</div><!--//app-wrapper-->

<?= $this->endSection(); ?>