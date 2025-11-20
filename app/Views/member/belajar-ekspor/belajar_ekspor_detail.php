<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'title'            => $artikel['title_belajar_ekspor'],
    'meta_description' => $artikel['meta_deskripsi']
]);
?>
<style>
    /* Artikel Detail Section */
    .artikel-detail-section {
        position: relative;
        /* supaya overlay & modal absolute menempel di sini */
        padding: 60px 15px;
        background-color: #f9f9f9;
        border-bottom: 1px solid #ddd;
        overflow: hidden;
        /* ➜ modal & overlay tidak bisa keluar area ini */
    }

    .artikel-detail-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    /* Artikel Text Styling */
    .artikel-text h2,
    .artikel-text h3 {
        margin-top: 20px;
        margin-bottom: 10px;
    }

    /* Spasi antar elemen dalam artikel */
    .artikel-text *+* {
        margin-top: 20px;
    }

    .image-wrapper {
        text-align: center;
        margin-top: 30px;
    }

    .artikel-text {
        line-height: 1.6;
        font-size: 1.1rem;
        padding-inline-start: 50px;
        padding-inline-end: 50px;
        text-align: justify;
    }

    /* Recommended Articles Section */
    .recommended-articles-section {
        padding: 60px 15px;
        background-color: #fff;
    }

    .recommended-articles-section h2 {
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .card-text {
        font-size: 1rem;
        color: #03AADE;
        flex-grow: 1;
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

    .badge {
        font-weight: normal;
        color: #fff;
        font-size: 0.9rem;
        padding: 0.8em 1.5em;
        border-radius: 3px;
        background-color: #03AADE;
        display: inline-block;
        width: auto;
    }

    .line-separator {
        width: 100%;
        height: 2px;
        background-color: #000;
        margin: 10px 0;
    }

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0px 0px 25px #03AADE !important;
        transform: translateY(-5px) !important;
    }

    .fake-lorem {
        margin-top: 20px;
        line-height: 1.6;
        text-align: justify;
    }

    /* Paywall modal */
    .paywall-overlay {
        position: absolute;
        top: 20%;
        /* mulai blur dari 20% tinggi artikel */
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none;
        z-index: 10;
    }

    .paywall-modal {
        position: absolute;
        /* relative ke artikel-detail-section */
        top: 50%;
        /* posisi awal di tengah, nanti diatur JS */
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
        text-align: center;
        z-index: 20;
        display: none;
    }

    .paywall-modal h2 {
        margin-bottom: 20px;
    }

    .paywall-modal button {
        background: #03AADE;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin: 4px;
    }

    .paywall-modal button:hover {
        background: #F2BF02;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .artikel-text {
            padding: 0;
            font-size: 1rem;
        }

        .artikel-detail-header h1 {
            font-size: 2rem;
        }

        .artikel-detail-content {
            padding: 0 15px;
        }

        .paywall-overlay {
            top: 8%;
        }
    }

    @media (max-width: 576px) {
        .artikel-detail-section {
            padding: 40px 10px;
        }

        .artikel-detail-header h1 {
            font-size: 1.8rem;
        }

        .artikel-text {
            font-size: 0.95rem;
        }

        .paywall-modal {
            padding: 22px 20px;
        }
    }

    @media (max-width: 425px) {
        .artikel-detail-header h1 {
            font-size: 1.6rem;
        }

        .artikel-text {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 360px) {
        .artikel-detail-header h1 {
            font-size: 1.4rem;
        }

        .artikel-text {
            font-size: 0.85rem;
        }
    }
</style>

<!-- artikel-detail section start -->
<section class="artikel-detail-section">
    <div class="container">
        <!-- Article Header -->
        <div class="artikel-detail-header text-center">
            <h1><?= esc($artikel['judul_belajar_ekspor']); ?></h1>
            <p class="text-muted mt-4"><?= date('d F Y', strtotime($artikel['created_at'])); ?></p>
        </div>

        <div class="artikel-detail-content">
            <div class="image-wrapper">
                <img
                    src="<?= base_url('img/' . esc($artikel['foto_belajar_ekspor'], 'url')); ?>"
                    class="artikel-img img-fluid"
                    alt="<?= esc($artikel['judul_belajar_ekspor']); ?>"
                    style="width: 750px; max-width: 100%; aspect-ratio: 16/9; object-fit: cover;"
                    loading="lazy">
            </div>

            <!-- Tags Badges -->
            <div class="d-flex justify-content-center align-items-center">
                <div class="badge mt-4">
                    <?= esc($kategori['nama_kategori']); ?>
                </div>
            </div>

            <div class="py-4">
                <hr class="line-separator">
            </div>

            <!-- Deskripsi artikel -->
            <div class="artikel-text">
                <!-- Teks hanya dipanggil sekali -->
                <div class="text-container">
                    <?= nl2br($artikel['deskripsi_belajar_ekspor']); ?>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="artikel-detail-footer text-center mt-5">
            <a href="<?= base_url('materi'); ?>"
                class="btn btn-custom">
                <?= lang('Blog.backtoArticle') ?>
            </a>
        </div>
    </div>
</section>
<!-- artikel-detail section end -->

<!-- recommended articles section start -->
<section class="recommended-articles-section">
    <div class="container">
        <h2 class="text-center"><?= 'Baca lainnya'; ?></h2>
        <div class="row">
            <?php foreach ($belajar_ekspor as $item): ?>
                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <img
                            src="<?= base_url('img/' . esc($item['foto_belajar_ekspor'], 'url')); ?>"
                            class="card-img-top img-fluid"
                            alt="<?= esc($item['judul_belajar_ekspor']); ?>"
                            style="object-fit: cover; object-position: center; aspect-ratio: 16/9;"
                            loading="lazy">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <p class="card-text mb-0" style="font-size: 1rem;">
                                    <?= date('d F Y', strtotime($item['created_at'])); ?>
                                </p>
                                <span class="badge">
                                    <?= esc($item['kategori']['nama_kategori']); ?>
                                </span>
                            </div>
                            <h5 class="card-title"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <?= esc($item['judul_belajar_ekspor']); ?>
                            </h5>
                            <div style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <?= esc($item['deskripsi_belajar_ekspor']); ?>
                            </div>
                            <a href="<?= base_url(
                                            ('materi/') .
                                                ($item['slug'])
                                        ); ?>"
                                class="btn btn-custom mt-auto"
                                style="width: 100%; display: block; text-align: center;">
                                <?= 'Baca Selengkapnya'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- recommended articles section end -->


<?= $this->endSection(); ?>