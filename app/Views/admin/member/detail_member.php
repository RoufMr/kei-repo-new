<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title" style="color:#03AADE;">Detail Member</h1>

        <div class="card mt-4 shadow-sm">
            <div class="card-body">

                <table class="table table-bordered table-striped align-middle">
                    <tbody>
                        <tr>
                            <th>Username</th>
                            <td><?= esc($member['username']) ?></td>
                        </tr>
                        <tr>
                            <th>Foto Profil</th>
                            <td>
                                <?php if (!empty($member['foto_profil'])): ?>
                                    <img src="<?= base_url('uploads/foto_usaha/' . $member['foto_profil']) ?>"
                                        alt="Foto Profil"
                                        style="width:100px; height:100px; border-radius:10px; object-fit:cover;">
                                <?php else: ?>
                                    <span class="text-muted">Belum ada foto</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Kode Referal</th>
                            <td><?= esc($member['kode_referral']) ?></td>
                        </tr>
                        <tr>
                            <th>Popular Point</th>
                            <td><?= esc($member['popular_point']) ?></td>
                        </tr>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <td><?= esc($member['nama_perusahaan']) ?></td>
                        </tr>
                        <tr>
                            <th>Deskripsi Perusahaan</th>
                            <td><?= esc($member['deskripsi_perusahaan']) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?= esc($member['status_text']) ?></td>
                        </tr>

                        <tr>
                            <th>Tipe Bisnis</th>
                            <td><?= esc($member['tipe_bisnis']) ?></td>
                        </tr>
                        <tr>
                            <th>Produk Utama</th>
                            <td><?= esc($member['produk_utama']) ?></td>
                        </tr>
                        <tr>
                            <th>Tahun Dibentuk</th>
                            <td><?= esc($member['tahun_dibentuk']) ?></td>
                        </tr>
                        <tr>
                            <th>Skala Bisnis</th>
                            <td><?= esc($member['skala_bisnis']) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= esc($member['email']) ?></td>
                        </tr>
                        <tr>
                            <th>PIC</th>
                            <td><?= esc($member['pic']) ?></td>
                        </tr>
                        <tr>
                            <th>PIC Phone</th>
                            <td><?= esc($member['pic_phone']) ?></td>
                        </tr>
                        <tr>
                            <th>Kategori Produk</th>
                            <td><?= esc($member['kategori_produk']) ?></td>
                        </tr>
                        <tr>
                            <th>Alamat Perusahaan</th>
                            <td><?= esc($member['alamat_perusahaan']) ?></td>
                        </tr>
                        <tr>
                            <th>Alamat Web</th>
                            <td><a href="<?= esc($member['alamat_website']) ?>" target="_blank"><?= esc($member['alamat_website']) ?></a></td>
                        </tr>
                        <tr>
                            <th>Bukti Transfer</th>
                            <td>
                                <?php if (!empty($member['bukti_transfer'])): ?>
                                    <img src="<?= base_url('uploads/bukti_transfer/' . $member['bukti_transfer']) ?>"
                                        alt="Bukti Transfer"
                                        style="width:100px; height:100px; border-radius:10px; object-fit:cover;">
                                <?php else: ?>
                                    <span class="text-muted">Belum ada foto</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <a href="<?= base_url('admin-calon-member') ?>" class="btn btn-secondary">Kembali</a>
                <a href="#"
                    class="btn btn-sm text-white"
                    style="background-color:#03AADE;"
                    data-bs-toggle="modal"
                    data-bs-target="#konfirmasiModal<?= $member['id_member'] ?>">
                    Konfirmasi
                </a>

                <!-- Modal Bootstrap -->
                <div class="modal fade" id="konfirmasiModal<?= $member['id_member'] ?>" tabindex="-1" aria-labelledby="konfirmasiLabel" aria-hidden="true">
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
                                <a href="<?= base_url('admin-konfirmasi-member/' . $member['id_member']) ?>" class="btn text-white" style="background-color:#03AADE;">Ya, Konfirmasi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>