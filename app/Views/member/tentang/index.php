<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'title'            => $meta['title_tentang'] ,
    'meta_description' => ($lang == 'id') ? $meta['meta_description_tentang'] : $meta['meta_description_tentang_en']
]);
?>

<style>
    /* ===================================================
       Tentang Kami Page
       =================================================== */

    .about-wrapper {
        padding-top: 1.5rem;
        padding-bottom: 2.5rem;
    }

    .about-image {
        object-fit: contain;
        max-width: 300px;
    }

    .about-text-wrapper {
        max-width: 650px;
    }

    .text-justify {
        text-align: justify;
        line-height: 1.7;
        font-weight: 500;
    }

    /* Grid layanan / manfaat */
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
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .manfaat h6 {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.4rem;
    }

    .manfaat p {
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
    }

    /* ===================================================
       Responsif
       =================================================== */

    @media (max-width: 768px) {
        .about-wrapper {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .manfaat-item {
            max-width: 250px;
        }

        .benefit-icon-box {
            width: 90px;
            height: 90px;
        }

        .manfaat h6 {
            font-size: 1rem;
        }

        .manfaat p {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .about-image {
            max-width: 220px;
        }

        .about-text-wrapper {
            padding-left: 20px;
            padding-right: 20px;
        }

        .text-justify {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .manfaat-item {
            max-width: 200px;
        }

        .benefit-icon-box {
            width: 80px;
            height: 80px;
        }

        .manfaat h6 {
            font-size: 0.95rem;
        }

        .manfaat p {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 425px) {
        .text-justify {
            font-size: 0.8rem;
            /* line-height: 1.7; */
        }

        .benefit-icon-box {
            width: 70px;
            height: 70px;
        }

        .manfaat h6 {
            font-size: 0.85rem;
        }

        .manfaat p {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 360px) {
        .text-justify {
            font-size: 0.7rem;
            /* line-height: 1.5; */
        }

        .about-image {
            max-width: 190px;
        }

        .benefit-icon-box {
            width: 60px;
            height: 60px;
        }

        .manfaat h6 {
            font-size: 0.75rem;
        }

        .manfaat p {
            font-size: 0.7rem;
        }
    }
</style>

<div class="container about-wrapper">
    <div class="row align-items-center">
        <!-- Bagian Gambar -->
        <div class="col-md-5 mb-4 mb-md-0 text-center">
            <img
                src="<?= base_url('img/' . esc($tentang_kami['gambar_perusahaan'], 'url')); ?>"
                alt="<?= esc($tentang_kami['slug']); ?>"
                class="img-fluid rounded about-image d-block mx-auto">
        </div>

        <!-- Bagian Teks -->
        <div class="col-md-7 px-md-5">
            <div class="about-text-wrapper mx-auto">
                <h1 class="fw-bold mb-3 text-center text-md-start">
                    <?= lang('Blog.tentangTitle'); ?>
                </h1>
                <p class="text-justify">
                    <?= ($lang == 'en')
                        ? $tentang_kami['deskripsi_perusahaan_en']
                        : $tentang_kami['deskripsi_perusahaan']; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Bagian Layanan / Manfaat -->
    <div class="team-section mt-4 px-2 px-md-4">
        <h2 class="text-center fw-bold mb-4">
            <?= lang('Blog.layananTitle'); ?>
        </h2>
        <div class="container">
            <div class="manfaat row g-4 justify-content-center">
                <?php foreach ($manfaatjoin as $manfaat): ?>
                    <div class="col-12 col-md-4 d-flex flex-column align-items-center text-center manfaat-item">
                        <div class="benefit-icon-box">
                            <?php if (!empty($manfaat['gambar'])): ?>
                                <img src="<?= base_url('img/' . esc($manfaat['gambar'], 'url')); ?>" alt="Icon">
                            <?php else: ?>
                                <img src="<?= base_url('img/icons/default-icon.png'); ?>" alt="Default Icon" width="60" height="60">
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

    </div>
</div>

<?= $this->endSection(); ?>
