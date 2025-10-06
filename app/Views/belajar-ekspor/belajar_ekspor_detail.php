<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'meta_title' => ($lang == 'id') ? $artikel['meta_title'] : $artikel['meta_title_en'],
    'meta_description' => ($lang == 'id') ? $artikel['meta_deskripsi'] : $artikel['meta_deskripsi_en']
]);
?>

<style>
    /* Artikel Detail Section */
    .artikel-detail-section {
        padding: 60px 15px;
        background-color: #f9f9f9;
        border-bottom: 1px solid #ddd;
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

    /* Add spacing between elements */
    .artikel-text *+* {
        margin-top: 20px;
    }


    .image-wrapper {
        text-align: center;
        /* Center the image within this wrapper */
        margin-top: 30px;
    }

    /* Image styling */

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
        /* Allow text to grow and fill available space */
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
        width: auto;
        /* Membuat lebar badge mengikuti panjang teks */
        display: inline-block;
        /* Menjamin badge sesuai dengan teks */
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

    @media (max-width: 768px) {
        .artikel-text {
            padding: 0px;
        }

        .artikel-detail-header h1 {
            font-size: 2rem;
        }

        .artikel-detail-content {
            padding: 0 15px;
        }

        .artikel-text {
            font-size: 1rem;
        }
    }
</style>

<!-- artikel-detail section start -->
<section class="artikel-detail-section">
    <div class="container">
        <!-- Article Header -->
        <div class="artikel-detail-header text-center">
            <h1><?= ($lang == 'en') ? $artikel['judul_belajar_ekspor_en'] : $artikel['judul_belajar_ekspor']; ?></h1>
            <p class="text-muted mt-4"><?= date('d F Y', strtotime($artikel['created_at'])); ?></p>
        </div>

        <div class="artikel-detail-content">
            <div class="image-wrapper">
                <img src="<?= base_url('/img/' . $artikel['foto_belajar_ekspor']); ?>" class="artikel-img img-fluid" alt="<?= ($lang == 'en') ? $artikel['judul_belajar_ekspor_en'] : $artikel['judul_belajar_ekspor']; ?>" style="width: 750px; aspect-ratio: 16/9;" loading="lazy">
            </div>

            <!-- Tags Badges -->
            <div style="display: flex; justify-content: center; align-items: center;">
                <div class="badge mt-4">
                    <?= $kategori['nama_kategori']; ?>
                </div>
            </div>

            <div class="py-4">
                <hr class="line-separator">
            </div>

            <!-- Deskripsi artikel -->
            <div class="artikel-text">
                <!-- Teks hanya dipanggil sekali -->
                <div class="text-container">
                    <?= nl2br(($lang == 'en') ? $artikel['deskripsi_belajar_ekspor_en'] : $artikel['deskripsi_belajar_ekspor']); ?>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="artikel-detail-footer text-center mt-5">
            <a href="<?= base_url(($lang == 'en') ? 'en/export-lessons' : 'id/materi-ekspor'); ?>" class="btn btn-custom"><?= lang('Blog.backtoArticle') ?></a>
        </div>
    </div>
</section>

<!-- artikel-detail section end -->

<!-- recommended articles section start -->
<section class="recommended-articles-section">
    <div class="container">
        <h2 class="text-center"><?= ($lang == 'en') ? 'Read more' : 'Baca lainnya'; ?></h2>
        <div class="row">
            <?php foreach ($belajar_ekspor as $item): ?>
                <div class="col-md-4 mt-4">
                    <div class="card h-100">
                        <img src="<?= base_url('/img/' . $item['foto_belajar_ekspor']); ?>" class="card-img-top img-fluid" alt="<?= ($lang == 'en') ? $item['judul_belajar_ekspor_en'] : $item['judul_belajar_ekspor']; ?>" style="object-fit: cover; object-position: center; aspect-ratio: 16/9;" loading="lazy">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <p class="card-text mb-0" style="font-size: 1rem;"><?= date('d F Y', strtotime($item['created_at'])); ?></p>
                                <span class="badge"><?= ($lang == 'en') ? $item['kategori']['nama_kategori_en'] : $item['kategori']['nama_kategori']; ?></span>
                            </div>
                            <h5 class="card-title" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <?= ($lang == 'en') ? $item['judul_belajar_ekspor_en'] : $item['judul_belajar_ekspor']; ?>
                            </h5>
                            <p style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <?= ($lang == 'en') ? $item['deskripsi_belajar_ekspor_en'] : $item['deskripsi_belajar_ekspor']; ?>
                            </p>
                            <a href="<?= base_url(($lang == 'en' ? 'en/export-lessons/' : 'id/materi-ekspor/') . (($lang == 'en') ? $item['slug_en'] : $item['slug'])); ?>" class="btn btn-custom mt-auto" style="width: 100%; display: block; text-align: center;">
                                <?= ($lang == 'en') ? 'Read More' : 'Baca Selengkapnya'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>