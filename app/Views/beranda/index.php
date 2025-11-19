<?= $this->extend('layout/app'); ?>

<?= $this->section('meta'); ?>
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php
$this->setData([
    'title' => ($lang == 'id') ? $meta['title_beranda'] : $meta['title_beranda_en'],
    'meta_description' => ($lang == 'id') ? $meta['meta_description_beranda'] : $meta['meta_description_beranda_en']
]);
?>

<style>
    :root {
        --font-size-title-cta: 38px;
        --font-size-desc-cta: 18px;
    }

    /* ===================================================
       1. Slider
       =================================================== */
    .carousel-item img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
    }

    .carousel-caption {
        bottom: 12%;
    }

    /* ===================================================
       2. CTA "Daftar Sekarang"
       =================================================== */
    .daftar-section {
        background-color: var(--c-primary);
        width: 90%;
        max-width: 1100px;
        margin: 80px auto 40px;
        padding: 40px 28px;
        border-radius: 28px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .daftar-section .card-body .title-cta {
        font-family: "Poetsen One", sans-serif;
    }

    .title-cta {
        font-size: var(--font-size-title-cta);
    }

    .daftar-section .card-body {
        font-size: var(--font-size-desc-cta);
    }

    .desc-cta {
        font-size: var(--font-size-desc-cta);
        font-family: "Lato", sans-serif;
    }

    .daftar-section img.daftar-img {
        width: 90%;
        max-width: 400px;
        height: auto;
        border-radius: 16px;
    }

    /* ===================================================
       3. Benefit Join
       =================================================== */
    .benefit p {
        color: var(--c-primary);
    }

    .benefit .border-top-small {
        width: 30px;
        height: 2px;
        margin: 10px 0;
        background-color: var(--c-primary);
    }

    /* wrapper setiap kartu manfaat */
    .manfaat-item {
        max-width: 320px;
        margin-inline: auto;
    }

    .benefit-icon-box {
        background-color: #ffffff;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .benefit-icon-box img {
        max-width: 80%;
        max-height: 80%;
        object-fit: cover;
    }

    .manfaat h6 {
        font-weight: 700;
        font-size: 1rem;
    }

    .manfaat p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    /* ===================================================
       4. Ajakan (banner biru tengah)
       =================================================== */
    .footer-custom {
        background-color: var(--c-primary);
    }

    .background-image {
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 16px 70px;
    }

    .background-image p {
        max-width: 700px;
        margin: 10px auto 0;
    }

    .centered-button .btn {
        margin-top: 12px;
        font-weight: 500;
        font-size: var(--font-size-desc-cta);
    }

    /* ===================================================
       5. Garis dekoratif (dipakai di beberapa section)
       =================================================== */
    .border-top6,
    .border-top7 {
        width: 40px;
        height: 2px;
        background-color: var(--c-primary);
    }

    /* ===================================================
       6. Paket (Visitor / Member)
       =================================================== */
    .kata1 h5 {
        font-weight: 300;
    }

    .package-section .card {
        transition: transform 0.3s, box-shadow 0.3s;
        max-width: 420px;
        margin-inline: auto;
    }

    .package-section .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .package-section .card-body {
        padding: 1.5rem 1.25rem;
    }

    .package-section .card-header,
    .package-section .card-footer {
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
    }

    .recommended-label {
        background-color: #FF5733;
        font-size: 0.9rem;
        font-weight: bold;
        top: -10px;
        left: 0;
        border-radius: 5px;
        z-index: 2;
    }

    .benefits-list {
        max-height: 220px;
        overflow-y: auto;
        text-align: left;
        font-size: 0.95rem;
        padding-right: 6px;
        scrollbar-width: thin;
    }

    .benefits-list::-webkit-scrollbar {
        width: 6px;
    }

    .benefits-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    /* ===================================================
       7. Peta Member
       =================================================== */
    .peta h5 {
        font-weight: 300;
    }

    .peta span {
        color: var(--c-primary);
    }

    .peta2 .map {
        width: 100%;
        height: 700px;
        border-radius: 16px;
        overflow: hidden;
    }

    /* ===================================================
       8. Responsif
       =================================================== */

    @media (max-width: 992px) {
        :root {
            --font-size-title-cta: 32px;
            --font-size-desc-cta: 16px;
        }

        .peta2 .map {
            max-height: 550px;
            max-width: 550px;
        }
    }

    @media (max-width: 768px) {
        :root {
            --font-size-title-cta: 32px;
            --font-size-desc-cta: 15px;
        }

        .carousel-item img {
            max-height: 380px;
        }

        .carousel-caption h5 {
            font-size: 1.1rem;
        }

        .carousel-caption p {
            font-size: 0.85rem;
        }

        .daftar-section {
            width: 92%;
            margin: 50px auto 32px;
            padding: 28px 18px;
        }

        .daftar-section .card-body {
            padding: 0 !important;
        }

        .daftar-section .card-body .title-cta {
            font-size: var(--font-size-title-cta);
        }

        .daftar-section .desc-cta {
            font-size: var(--font-size-desc-cta);
        }

        .daftar-section img.daftar-img {
            width: 90%;
            max-width: 300px;
        }

        .manfaat-item {
            max-width: 280px;
        }

        .benefit-icon-box {
            width: 80px;
            height: 80px;
        }

        .manfaat h6 {
            font-size: 0.95rem;
        }

        .manfaat p {
            font-size: 0.8rem;
        }

        .background-image {
            padding: 40px 16px 45px;
        }

        .package-section .card {
            max-width: 360px;
        }

        .package-section .card-body {
            padding: 1.25rem 1rem;
        }

        .benefits-list {
            max-height: 180px;
            font-size: 0.85rem;
        }

        .peta2 .map {
            max-height: 450px;
            max-width: 450px;
        }
    }

    @media (max-width: 576px) {
        :root {
            --font-size-title-cta: 28px;
            --font-size-desc-cta: 12px;
        }

        .manfaat-item {
            max-width: 250px;
        }

        .benefit-icon-box {
            width: 70px;
            height: 70px;
        }

        .manfaat h6 {
            font-size: 0.9rem;
        }

        .manfaat p {
            font-size: 0.75rem;
        }

        .package-section .card {
            max-width: 320px;
        }

        .package-section .card-body {
            padding: 1rem 0.85rem;
        }

        .benefits-list {
            max-height: 150px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 425px) {
        :root {
            --font-size-title-cta: 22px;
            --font-size-desc-cta: 12px;
        }

        .manfaat-item {
            max-width: 220px;
        }

        .benefit-icon-box {
            width: 60px;
            height: 60px;
        }

        .manfaat h6 {
            font-size: 0.85rem;
        }

        .manfaat p {
            font-size: 0.7rem;
        }

        .package-section .card {
            max-width: 280px;
        }

        .benefits-list {
            max-height: 140px;
        }
    }

    @media (max-width: 375px) {
        :root {
            --font-size-title-cta: 20px;
            --font-size-desc-cta: 12px;
        }

        .peta2 .map {
            max-height: 250px;
        }
    }

    @media (max-width: 320px) {
        :root {
            --font-size-title-cta: 18px;
            --font-size-desc-cta: 10px;
        }
    }
</style>


<?php if (empty($slider)): ?>
    <div class="container">
        <div class="col-12 mt-2">
            <div class="alert alert-info text-center" role="alert">
                <?= lang('Blog.alertSliderBeranda'); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Slider Dinamis -->
    <div id="carouselExampleDark" class="carousel carousel-dark slide">
        <div class="carousel-indicators">
            <?php foreach ($slider as $index => $s): ?>
                <button type="button"
                    data-bs-target="#carouselExampleDark"
                    data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>"
                    <?= $index === 0 ? 'aria-current="true"' : '' ?>
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($slider as $index => $s): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>"
                    data-bs-interval="<?= $index === 0 ? 10000 : 2000 ?>">
                    <img src="<?= base_url('img/' . $s['img_slider']); ?>"
                        class="d-block w-100"
                        alt="Slide <?= $index + 1 ?>">
                    <div class="carousel-caption d-block text-light mb-3">
                        <h5><?= ($lang == 'en') ? $s['judul_slider_en'] : $s['judul_slider'] ?></h5>
                        <p><?= ($lang == 'en') ? $s['deskripsi_slider_en'] : $s['deskripsi_slider'] ?></p>
                        <a href="<?= ($lang == 'en') ? base_url('/en/registration') : base_url('/id/pendaftaran') ?>">
                            <button type="button" class="btn btn-outline-light"><?= lang('Blog.btnCarousel'); ?></button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
<?php endif; ?>

<!-- CTA: Daftar -->
<section class="container-fluid text-dark daftar-section">
    <div class="row align-items-center text-center text-md-start">
        <!-- Teks -->
        <div class="col-md-6 mb-4 mb-md-0 d-flex flex-column align-items-center align-items-md-start">
            <div class="card-body p-0 p-md-3">
                <p class="text-light fw-bold mb-0 title-cta">
                    <?= ($lang == 'en') ? $webprofile[0]['judul_ajakan_en'] : $webprofile[0]['judul_ajakan'] ?>
                </p>
                <p class="text-light mb-0 desc-cta">
                    <?= ($lang == 'en') ? $webprofile[0]['deskripsi_ajakan_en'] : $webprofile[0]['deskripsi_ajakan'] ?>
                </p>
                <div class="centered-button">
                    <a href="<?= ($lang == 'en') ? base_url('/en/registration') : base_url('/id/pendaftaran') ?>"
                        class="btn btn-outline-light">
                        <?= lang('Blog.btnCarousel'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gambar -->
        <div class="col-md-6 d-flex justify-content-center">
            <img src="/img/slider-2.jpg" class="rounded shadow daftar-img" alt="Image Description">
        </div>
    </div>
</section>

<!-- Keuntungan -->
<section>
    <div class="benefit container text-center py-5 mt-2">
        <p class="fw-bold mb-0 title-cta"><?= lang('Blog.benefitsJoinTitle'); ?></p>
        <div class="d-flex justify-content-center align-items-center">
            <div class="border-top-small"></div>
        </div>
        <p class="text-dark mb-0 desc-cta">
            <?= lang('Blog.benefitsJoinDescription'); ?>
        </p>
    </div>
    <div class="container">
        <div class="manfaat row g-4 justify-content-center">
            <?php foreach ($manfaatjoin as $manfaat): ?>
                <div class="col-12 col-md-4 d-flex flex-column align-items-center text-center manfaat-item">
                    <div class="benefit-icon-box">
                        <?php if (!empty($manfaat['gambar'])): ?>
                            <img src="<?= base_url('img/' . esc($manfaat['gambar'], 'url')); ?>" alt="Icon" />
                        <?php else: ?>
                            <img src="<?= base_url('img/icons/default-icon.png'); ?>" alt="Default Icon" width="60" height="60" />
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <h6><b><?= ($lang == 'en') ? $manfaat['judul_manfaat_en'] : $manfaat['judul_manfaat']; ?></b></h6>
                        <p><?= ($lang == 'en') ? $manfaat['deskripsi_manfaat_en'] : $manfaat['deskripsi_manfaat']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ajakan tengah -->
<section class="mt-5 footer-custom">
    <div class="background-image animated-element text-center">
        <div class="text-light title-cta">
            <b><?= ($lang == 'en') ? $webprofile[0]['judul_ajakan_en'] : $webprofile[0]['judul_ajakan'] ?></b>
        </div>
        <p class="text-light desc-cta">
            <?= ($lang == 'en') ? $webprofile[0]['deskripsi_ajakan_en'] : $webprofile[0]['deskripsi_ajakan'] ?>
        </p>
        <div class="centered-button">
            <a href="<?= ($lang == 'en') ? base_url('/en/registration') : base_url('/id/pendaftaran') ?>"
                class="btn btn-outline-light">
                <?= lang('Blog.btnCarousel'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Paket Visitor / Member -->
<section class="container mt-5">
    <div class="kata1 text-center">
        <div class="d-flex justify-content-center align-items-center">
            <div class="border-top6 mx-2"></div>
            <h5 class="fw-lighter"><?= lang('Blog.yourPackageTitle'); ?></h5>
            <div class="border-top7 ms-2"></div>
        </div>
        <div class="fw-bold title-cta">
            <?= lang('Blog.chooseTitle'); ?>
            <span style="color: #03AADE;"><?= lang('Blog.forYouTitle'); ?></span>
        </div>
    </div>

    <div class="package-section row mt-3 g-4">
        <!-- Visitor Card -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-lg rounded text-center">
                <div class="card-header bg-secondary text-white py-4">
                    <h5 class="mb-0 fw-bold">Visitor</h5>
                </div>
                <div class="card-body">
                    <i class="fas fa-user-slash fa-3x text-secondary mb-4"></i>
                    <h6 class="fw-bold"><?= lang('Blog.basicAccess'); ?></h6>
                    <h6 class="fw-bold text-secondary"><?= lang('Blog.freeTitle'); ?></h6>
                    <p><?= lang('Blog.deskNonMember'); ?></p>
                    <div class="benefits-list">
                        <?php foreach ($fitur_visitor as $item): ?>
                            <hr>
                            <p class="mb-2">- <?= ($lang == 'en') ? $item['nama_fitur_en'] : $item['nama_fitur']; ?></p>
                        <?php endforeach; ?>
                        <hr>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <?= lang('Blog.currentPackage'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Member Card -->
        <div class="col-md-6 position-relative">
            <div class="card h-100 border-0 shadow-lg rounded text-center">
                <div class="recommended-label position-absolute text-white px-3 py-1">
                    <?= lang('Blog.recommendedTitle'); ?>
                </div>
                <div class="card-header bg-primary text-white py-4">
                    <h5 class="mb-0 fw-bold">Member</h5>
                </div>
                <div class="card-body">
                    <i class="fas fa-crown fa-3x text-primary mb-4"></i>
                    <h6 class="fw-bold"><?= lang('Blog.fullAccess'); ?></h6>
                    <h6 class="fw-bold text-primary"><?= lang('Blog.packageRegistered'); ?></h6>
                    <p><?= lang('Blog.deskMemberFree'); ?></p>
                    <div class="benefits-list">
                        <?php foreach ($fitur_member as $item): ?>
                            <hr>
                            <p class="mb-2">- <?= ($lang == 'en') ? $item['nama_fitur_en'] : $item['nama_fitur']; ?></p>
                        <?php endforeach; ?>
                        <hr>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="<?= ($lang == 'en') ? base_url('/en/registration') : base_url('/id/pendaftaran') ?>">
                        <button class="btn btn-primary btn-sm">
                            <?= lang('Blog.joinNow'); ?>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peta Member -->
<section class="container peta2">
    <div class="peta mt-5">
        <div class="d-flex justify-content-center align-items-center">
            <div class="border-top6 mx-2"></div>
            <h5 class="fw-lighter"><?= lang('Blog.memberMapTitle'); ?></h5>
            <div class="border-top7 ms-2"></div>
        </div>
        <div class="text-center fw-bold title-cta">
            <?= lang('Blog.communityMemberSpotlightTitle'); ?>
            <span><?= lang('Blog.communityMemberSpotlightTitle2'); ?></span>
        </div>
    </div>
    <div class="container mt-5 d-flex justify-content-center">
        <div id="map" class="map"></div>
    </div>
</section>

<script>
    var map = L.map('map').setView([-2.5489, 118.0149], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var markers = L.markerClusterGroup();

    <?php foreach ($member as $item): ?>
        <?php if (!empty($item['latitude']) && !empty($item['longitude'])): ?>
                (function() {
                    var marker = L.marker([<?= $item['latitude'] ?>, <?= $item['longitude'] ?>]);
                    marker.bindPopup(
                        '<div style="width: 200px; font-family: Arial, sans-serif;">' +
                        '<a href="<?= ($item['role'] == 'premium') ? base_url($lang . '/detail-member/' . esc($item['slug'], 'url')) : '#' ?>" style="text-decoration: none;">' +
                        '<div class="card h-100 shadow-sm" style="cursor: pointer; border-radius: 12px; overflow: hidden;">' +
                        '<img src="<?= base_url('img/' . esc($item['foto_profil'], 'url')); ?>" class="card-img-top" alt="Member Image" style="height: 120px; object-fit: cover;">' +
                        '<div class="card-body">' +
                        '<h6 class="card-title text-center" style="font-weight: bold; word-wrap: break-word; white-space: normal;"><?= esc($item['username']); ?></h6>' +
                        '<p class="card-text text-center text-muted" style="font-size: 0.9rem; word-wrap: break-word; white-space: normal;"><?= esc($item['nama_perusahaan']); ?></p>' +
                        <?php if ($item['role'] == 'premium'): ?> '<span class="btn btn-primary btn-sm mt-2" style="border-radius: 8px; width: 100%;"><?= lang("Blog.btndataMember") ?></span>' +
                        <?php endif; ?> '</div>' +
                        '</div>' +
                        '</a>' +
                        '</div>'
                    );
                    markers.addLayer(marker);
                })();
        <?php endif; ?>
    <?php endforeach; ?>

    map.addLayer(markers);
</script>

<?= $this->endSection(); ?>