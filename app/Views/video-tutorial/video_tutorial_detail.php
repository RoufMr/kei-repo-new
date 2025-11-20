<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'title'            => ($lang == 'id') ? $video['title_video'] : $video['title_video_en'],
    'meta_description' => ($lang == 'id') ? $video['meta_deskripsi_video'] : $video['meta_deskripsi_video_en']
]);
?>

<style>
    /* ===================================================
       Layout umum detail video
       =================================================== */
    .video-detail-section {
        padding: 60px 15px 30px;
        background-color: #f9f9f9;
    }

    .video-main-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #ddd;
        padding: 24px 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    }

    .video-detail-title {
        font-size: 2rem;
        margin: 16px 0;
        text-transform: uppercase;
        font-weight: 700;
    }

    .video-desc-title {
        font-weight: 700;
        margin-bottom: 8px;
    }

    .video-desc-text {
        line-height: 1.6;
        text-align: justify;
    }

    /* Badge kategori (samakan gaya dengan materi) */
    .video-badge,
    .video-main-card .badge {
        font-weight: normal;
        color: #fff;
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 3px;
        background-color: #03AADE;
        display: inline-block;
    }

    /* ===================================================
       Overlay lokal hanya di area video
       =================================================== */
    .video-container {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 100%;
    }

    .thumbnail-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        /* penting agar overlay & thumbnail tidak keluar */
        margin-bottom: 16px;
    }

    .thumbnail-wrapper img {
        display: block;
        width: 100%;
        height: auto;
        border-radius: inherit;
        filter: brightness(70%);
        transition: filter 0.3s ease;
        margin-bottom: 0;
    }

    .thumbnail-wrapper:hover img {
        filter: brightness(50%);
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .play-button::before {
        content: '';
        display: block;
        width: 0;
        height: 0;
        border-left: 20px solid #03AADE;
        border-top: 12px solid transparent;
        border-bottom: 12px solid transparent;
    }

    /* Overlay hanya menutupi area video, selalu center */
    .video-overlay {
        position: absolute;
        inset: 0;
        /* top:0; right:0; bottom:0; left:0; */
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        /* mengikuti thumbnail */
        padding: 0 8px;
        /* sedikit ruang agar modal kecil tidak kepotong di hp */
    }

    .video-overlay[hidden] {
        display: none !important;
    }

    /* Modal di tengah thumbnail */
    .video-modal {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        text-align: center;
        z-index: 11;
        animation: fadeIn 0.2s ease-in-out;

        /* default desktop */
        max-width: 320px;
        width: 100%;
        padding: 18px 20px;
    }

    .video-modal h2 {
        margin-bottom: 12px;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .video-modal p {
        margin-bottom: 14px;
        font-size: 0.95rem;
    }

    .video-modal .btn {
        background: #03AADE;
        color: #fff;
        border: none;
        padding: 7px 16px;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 4px;
        font-size: 0.9rem;
        transition: 0.2s;
    }

    .video-modal .btn:hover {
        background: #F2BF02;
        color: #fff;
    }

    /* Animasi */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===================================================
       Related video (sidebar)
       =================================================== */
    .related-title {
        font-weight: 700;
        margin-bottom: 16px;
    }

    .related-card {
        border-radius: 10px;
        border: 1px solid #eee;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .related-thumb {
        object-fit: cover;
        width: 100px;
        height: 100px;
    }

    .related-title-text {
        font-size: 16px;
        margin-bottom: 6px;
        font-weight: 600;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .related-desc-text {
        font-size: 13px;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ===================================================
       Responsive
       =================================================== */
    @media (max-width: 992px) {
        .video-detail-section {
            padding-inline: 20px;
        }

        .video-detail-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .video-detail-section {
            padding: 40px 15px 20px;
        }

        .video-main-card {
            padding: 20px 16px;
        }

        .video-detail-title {
            font-size: 1.6rem;
        }

        .related-title {
            margin-top: 24px;
        }

        .video-modal {
            max-width: 280px;
            padding: 14px 16px;
        }

        .video-modal h2 {
            font-size: 1.05rem;
            margin-bottom: 10px;
        }

        .video-modal p {
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .video-modal .btn {
            padding: 6px 14px;
            font-size: 0.85rem;
        }

    }

    @media (max-width: 576px) {
        .video-detail-title {
            font-size: 1.4rem;
        }

        .video-main-card {
            padding: 18px 14px;
        }

        .video-overlay {
            padding: 0 10px;
        }

        .video-modal {
            max-width: 240px;
            padding: 12px 14px;
            border-radius: 8px;
        }

        .video-modal h2 {
            font-size: 0.95rem;
        }

        .video-modal p {
            font-size: 0.85rem;
        }

        .video-modal .btn {
            padding: 5px 12px;
            font-size: 0.8rem;
            margin: 2px 3px;
        }
    }

    @media (max-width: 425px) {
        .video-detail-title {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 360px) {
        .video-detail-section {
            padding-inline: 10px;
        }

        .video-detail-title {
            font-size: 1.1rem;
        }

        .video-modal {
            max-width: 210px;
            padding: 10px 12px;
        }

        .video-modal h2 {
            font-size: 0.9rem;
        }

        .video-modal p {
            font-size: 0.8rem;
        }

        .video-modal .btn {
            padding: 4px 10px;
            font-size: 0.75rem;
        }
    }
</style>

<section class="video-detail-section">
    <div class="container">
        <div class="row">
            <!-- Kolom utama: video -->
            <div class="col-lg-8">
                <div class="video-main-card mb-3">
                    <!-- Kategori -->
                    <div class="mb-2">
                        <span class="video-badge">
                            <?= ($lang === 'en') ? $kategori['nama_kategori_video_en'] : $kategori['nama_kategori_video']; ?>
                        </span>
                    </div>

                    <!-- Judul -->
                    <h4 class="video-detail-title">
                        <?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>
                    </h4>

                    <!-- Area Video + Overlay Lokal -->
                    <div class="video-container">
                        <div class="thumbnail-wrapper">
                            <a href="#"
                                class="text-decoration-none thumb-trigger"
                                data-video-url="<?= esc($video['video_url']); ?>">
                                <img
                                    src="<?= base_url('/img/' . $video['thumbnail']); ?>"
                                    alt="<?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>"
                                    class="thumb-img"
                                    loading="lazy"
                                    decoding="async" />
                                <div class="play-button"></div>
                            </a>

                            <!-- Overlay lokal -->
                            <div class="video-overlay"
                                role="dialog"
                                aria-modal="false"
                                aria-labelledby="videoGateTitle"
                                aria-describedby="videoGateDesc"
                                hidden>
                                <div class="video-modal">
                                    <h2 id="videoGateTitle"><?= lang('Blog.wantToOpenBE'); ?></h2>
                                    <p id="videoGateDesc"><?= lang('Blog.deskMemberFree'); ?></p>
                                    <div>
                                        <a href="<?= base_url('/login'); ?>" class="btn" rel="nofollow">
                                            <?= lang('Blog.headerMasuk'); ?>
                                        </a>
                                        <a href="<?= base_url($lang . '/' . $pendaftaranLink) ?>" class="btn" rel="nofollow">
                                            <?= lang('Blog.registerSA'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-3">
                        <h5 class="video-desc-title"><?= lang('Blog.titleDesc') ?></h5>
                        <div class="video-desc-text">
                            <?= ($lang === 'en') ? $video['deskripsi_video_en'] : $video['deskripsi_video']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom samping: related video -->
            <div class="col-lg-4">
                <h4 class="related-title"><?= lang('Blog.titleOther') ?></h4>

                <?php foreach ($related_video as $item): ?>
                    <div class="card related-card bg-white p-3 mb-3">
                        <a href="<?= base_url(($lang === 'en' ? 'en/videos' : 'id/video') . '/' . ($lang === 'en' ? $item['slug_en'] : $item['slug'])); ?>"
                            class="text-decoration-none">
                            <div class="d-flex align-items-center bg-white rounded overflow-hidden">
                                <img
                                    class="img-fluid related-thumb"
                                    src="<?= base_url('img/' . $item['thumbnail']); ?>"
                                    alt="<?= ($lang === 'en') ? $item['judul_video_en'] : $item['judul_video']; ?>">
                                <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center">
                                    <h3 class="text-dark related-title-text">
                                        <?= $lang === 'en' ? $item['judul_video_en'] : $item['judul_video']; ?>
                                    </h3>
                                    <div class="text-dark related-desc-text">
                                        <?= $lang === 'en' ? $item['deskripsi_video_en'] : $item['deskripsi_video']; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isGuest = <?= $isGuest ? 'true' : 'false' ?>;
        const triggers = document.querySelectorAll('.thumb-trigger');

        if (!triggers.length) return;

        function openOverlay(overlayEl) {
            overlayEl.hidden = false;
        }

        function closeOverlay(overlayEl) {
            overlayEl.hidden = true;
        }

        triggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-video-url');
                const wrapper = this.closest('.thumbnail-wrapper');
                const overlay = wrapper ? wrapper.querySelector('.video-overlay') : null;
                if (!overlay) return;

                if (isGuest) {
                    openOverlay(overlay);
                } else {
                    window.open(url, '_blank', 'noopener,noreferrer');
                }

                // Klik di luar modal untuk menutup
                const outsideClick = (ev) => {
                    if (ev.target === overlay) {
                        closeOverlay(overlay);
                        overlay.removeEventListener('click', outsideClick);
                        document.removeEventListener('keydown', escClose);
                    }
                };
                overlay.addEventListener('click', outsideClick);

                // ESC untuk menutup
                const escClose = (ev) => {
                    if (ev.key === 'Escape') {
                        closeOverlay(overlay);
                        overlay.removeEventListener('click', outsideClick);
                        document.removeEventListener('keydown', escClose);
                    }
                };
                document.addEventListener('keydown', escClose);
            });
        });
    });
</script>

<?= $this->endSection(); ?>