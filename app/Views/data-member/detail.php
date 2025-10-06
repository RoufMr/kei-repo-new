<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'meta_title' => ($lang == 'id') ? $meta['meta_title_member'] : $meta['meta_title_member_en'],
    'meta_description' => ($lang == 'id') ? $meta['meta_description_member'] : $meta['meta_description_member_en']
]);
?>

<style>
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

    .btn-custom {
        background-color: #03AADE;
        text-align: center;
        color: #ffffff;
        border: none;
        margin-top: auto;
        border-radius: 8px;
        width: 100%;
    }

    .btn-custom:hover {
        background-color: #F2BF02;
        color: #ffffff;
    }

    .tab-content {
        margin-top: 20px;
    }

    .custom-tab {
        color: #000;
    }

    .custom-tab:hover {
        color: #03AADE;
    }

    .nav-link.active {
        color: #03AADE !important;
    }

    .section-title {
        margin-bottom: 30px;
    }

    .text-truncate-description {
        display: -webkit-box;
        -webkit-line-clamp: 4;
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
        box-shadow: 0px 0px 25px #03AADE !important;
        transform: translateY(-5px) !important;
    }

    .member-img {
        margin: 10px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: calc(100% - 20px);
        object-fit: cover;
    }

    .card .btn:hover {
        background-color: #F2BF02 !important;
        color: #fff;
        border: none;
    }

    .container {
        margin: 0 auto;
    }

    .member-section {
        text-align: center;
    }
</style>

<div class="container mt-4">
    <!-- Member Details (Full Width) -->
    <div class="col-lg-12">
        <div class="card p-4 shadow-sm">
            <!-- Image at the top -->
            <div class="text-center mb-4 shadow" style="width: 250px; height: 250px; margin: auto; overflow: hidden; border-radius: 50%; position: relative;">
                <?php if (empty($member['foto_profil'])): ?>
                    <img src="https://via.placeholder.com/500" class="img-fluid" alt="" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <?php else: ?>
                    <img src="<?= base_url('img/' . $member['foto_profil']); ?>" class="img-fluid" alt="" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <?php endif; ?>
            </div>

            <!-- Member Information -->
            <h2 class="text-center mb-2"><?= strtoupper($member['username']) ?></h2>

            <!-- Badge with Code Referral -->
            <div class="text-center mb-3">
                <span class="badge badge-lg bg-light text-dark p-2" style="font-size: 18px;">
                    <?php echo lang('Blog.referralCode') ?><?= $member['username'] ?>
                </span>
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs justify-content-center gap-3 mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active custom-tab" id="profil-perusahaan-tab" data-bs-toggle="tab"
                        data-bs-target="#profil-perusahaan" type="button" role="tab" aria-controls=" profil-perusahaan"
                        aria-selected="true">
                        <?php echo lang('Blog.companyProfile') ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom-tab" id="tabPIC-tab" data-bs-toggle="tab"
                        data-bs-target="#tabPIC" type="button" role="tab" aria-controls=" tabPIC"
                        aria-selected="false">
                        <?php echo lang('Blog.tabPIC') ?>
                    </button>
                </li>
            </ul>


            <!-- Tabs Content -->
            <div class="tab-content mt-4" id="myTabContent">
                <div class="tab-pane fade show active" id="profil-perusahaan" role="tabpanel"
                    aria-labelledby="profil-perusahaan-tab">
                    <h5 class=" mb-4"><?php echo lang('Blog.profilPerusahaan') ?></h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-building fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.companyName') ?>
                                    </strong>
                                    <?= $member['nama_perusahaan'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class=" card p-3 shadow-sm bg-light d-flex flex-column">
                                <i class="fas fa-file-alt fa-lg mb-2"></i>
                                <label class="form-label">
                                    <strong>
                                        <?php echo lang('Blog.companyDesc') ?>
                                    </strong>
                                </label>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars(($lang == 'en') ? $member['deskripsi_perusahaan_en'] : $member['deskripsi_perusahaan'])) ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-calendar-alt fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.yearEstablished') ?>
                                    </strong>
                                    <?= $member['tahun_dibentuk'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-map-marker-alt fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.companyAddress') ?>
                                    </strong>
                                    <?= $member['alamat_perusahaan'] ?>
                                    <!-- harusnya 'alamat_perusahaan' -->
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-box fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.mainProduct') ?>
                                    </strong>
                                    <?= ($lang == 'en') ? $member['produk_utama_en'] : $member['produk_utama'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-leaf fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.productCategory') ?>
                                    </strong>
                                    <?= ($lang == 'en') ? $member['kategori_produk_en'] : $member['kategori_produk'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-globe fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.websiteAddress') ?>
                                    </strong>
                                    <?= $member['alamat_website'] ?>
                                    <!-- harusnya 'alamat_website' -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- PIC -->
                <div class="tab-pane fade" id="tabPIC" role="tabpanel" aria-labelledby="tabPIC-tab">
                    <h5 class="mb-4"><?php echo lang('Blog.tabPIC') ?></h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-user fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.picName') ?>
                                    </strong>
                                    <?= $member['pic'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-envelope fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.picEmail') ?>
                                    </strong>
                                    <?= $member['email'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card p-3 shadow-sm bg-light">
                                <i class="fas fa-phone fa-lg mb-2"></i>
                                <p>
                                    <strong>
                                        <?php echo lang('Blog.picPhone') ?>
                                    </strong>
                                    <?= $member['pic_phone'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Lainnya Section (Placed outside of the main card) -->
    <div class="col-lg-12 mt-5">
        <div class="section-title">
            <h4 class=" text-uppercase font-weight-bold text-center mb-4">Member Populer</h4>
        </div>
        <?php if (empty($members)): ?>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="alert alert-info text-center" role="alert">
                    <?php echo lang('Blog.alertOtherDataMember') ?>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-wrap justify-content-center">
                <?php foreach ($members as $item): ?>
                    <a href=" <?= base_url($lang . '/detail-member/' . $item['slug']); ?>" class="text-decoration-none" style="color: inherit;">
                        <div class="card hover-card mx-4 mb-5 shadow-sm" style=" width: 18rem; cursor: pointer; transition: transform 0.2s;">
                            <?php if (empty($item['foto_profil'])): ?>
                                <img src="https://via.placeholder.com/500" class="card-img-top img-fluid member-img" alt="Member Photo" style="height: 220px;">
                            <?php else: ?>
                                <img src="<?= base_url('img/' . $item['foto_profil']) ?>" class="card-img-top img-fluid member-img" alt="Member Photo" style="height: 220px;">
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <?= $item['username'] ?>
                                </h5>
                                <p class="card-text">
                                    <?= $item['nama_perusahaan'] ?>
                                </p>
                                <span class="btn btn-custom mt-auto" style="border-radius: 8px;">
                                    <?php echo lang('Blog.btndataMember') ?>
                                </span>

                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
    </div>
<?php endif; ?>
</div>

<?= $this->endSection(); ?>