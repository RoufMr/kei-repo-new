<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'title' => ($lang == 'id') ? $video['title_video'] : $video['title_video_en'],
    'meta_description' => ($lang == 'id') ? $video['meta_deskripsi_video'] : $video['meta_deskripsi_video_en']
]);
?>

<style>
    /* === OVERLAY LOKAL HANYA DI AREA VIDEO === */
    .video-container {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 100%;
    }

    /* Overlay hanya menutupi area video */
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* Tidak keluar dari card */
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        /* mengikuti thumbnail */
    }

    .video-overlay[hidden] {
        display: none !important;
    }

    /* Modal kecil di tengah area video */
    .video-modal {
        background: #fff;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        max-width: 340px;
        width: 90%;
        text-align: center;
        z-index: 11;
        animation: fadeIn 0.2s ease-in-out;
    }

    /* anchor bergaya tombol (biar tak perlu <button> di dalam <a>) */
    .video-modal .btn {
        background: #03AADE;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 5px;
    }

    .video-modal .btn:hover {
        background: #F2BF02;
        color: #fff;
    }

    .video-modal h2 {
        margin-bottom: 15px;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .video-modal p {
        margin-bottom: 16px;
    }

    .video-modal button {
        background: #03AADE;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 5px;
        transition: 0.2s;
    }

    .video-modal button:hover {
        background: #F2BF02;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Thumbnail & play button */
    .thumbnail-wrapper {
        position: relative;
        display: inline-block;
        border-radius: 12px;
        /* sesuaikan dengan radius card */
        overflow: hidden;
        /* 🔥 penting: potong isi (termasuk overlay & gambar) agar tidak keluar */
    }


    .thumbnail-wrapper img {
        display: block;
        /* hilangkan gap inline */
        width: 100%;
        height: auto;
        border-radius: inherit;
        /* ikuti radius wrapper */
        filter: brightness(70%);
        transition: filter 0.3s ease;
        margin-bottom: 0;
        /* 🔥 pastikan tidak ada ruang bawah yang bikin overlay turun */
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
</style>

<section class="video-detail-section pt-5 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="position-relative mb-3">
                    <div class="bg-white border border-top-0 p-4 rounded shadow-sm">

                        <!-- Kategori -->
                        <div style="display:flex;">
                            <div class="badge py-2 bg-primary text-white">
                                <?= ($lang === 'en') ? $kategori['nama_kategori_video_en'] : $kategori['nama_kategori_video']; ?>
                            </div>
                        </div>

                        <!-- Judul -->
                        <h4 class="py-3 text-uppercase font-weight-bold">
                            <?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>
                        </h4>

                        <!-- Area Video + Overlay Lokal -->
                        <div class="video-container text-center">
                            <div class="thumbnail-wrapper mb-3">
                                <a href="#" class="text-decoration-none thumb-trigger" data-video-url="<?= esc($video['video_url']); ?>">
                                    <img
                                        src="<?= base_url('/img/' . $video['thumbnail']); ?>"
                                        alt="<?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>"
                                        class="thumb-img"
                                        loading="lazy" decoding="async" />
                                    <div class="play-button"></div>
                                </a>

                                <!-- Overlay lokal, tanpa id global -->
                                <div class="video-overlay" role="dialog" aria-modal="false" aria-labelledby="videoGateTitle" aria-describedby="videoGateDesc" hidden>
                                    <div class="video-modal">
                                        <h2 id="videoGateTitle"><?= lang('Blog.wantToOpenBE'); ?></h2>
                                        <p id="videoGateDesc"><?= lang('Blog.deskMemberFree'); ?></p>
                                        <div>
                                            <a href="<?= base_url('/login'); ?>" class="btn btn-primary" rel="nofollow"><?= lang('Blog.headerMasuk'); ?></a>
                                            <a href="<?= base_url($lang . '/' . $pendaftaranLink) ?>" class="btn btn-primary" rel="nofollow"><?= lang('Blog.registerSA'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <h5 class="font-weight-bold py-2"><?= lang('Blog.titleDesc') ?></h5>
                            <p><?= ($lang === 'en') ? $video['deskripsi_video_en'] : $video['deskripsi_video']; ?></p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Related Videos -->
            <div class="col-lg-4">
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 py-4 font-weight-bold"><?= lang('Blog.titleOther') ?></h4>
                    </div>

                    <?php foreach ($related_video as $item): ?>
                        <div class="card bg-white border border-top-0 p-3 rounded shadow-sm mb-3">
                            <a href="<?= base_url(($lang === 'en' ? 'en/videos' : 'id/video') . '/' . ($lang === 'en' ? $item['slug_en'] : $item['slug'])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center bg-white rounded border border-light overflow-hidden shadow-sm">
                                    <img class="img-fluid" style="object-fit:cover;width:100px;height:100px;" src="<?= base_url('/img/' . $item['thumbnail']); ?>" alt="<?= ($lang === 'en') ? $item['judul_video_en'] : $item['judul_video']; ?>">
                                    <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center">
                                        <h3 class="text-uppercase font-weight-bold text-dark" style="font-size:18px;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                            <?= $lang === 'en' ? $item['judul_video_en'] : $item['judul_video']; ?>
                                        </h3>
                                        <p class="text-dark" style="font-size:14px;margin-bottom:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                            <?= $lang === 'en' ? $item['deskripsi_video_en'] : $item['deskripsi_video']; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const isGuest = <?= $isGuest ? 'true' : 'false' ?>;
  const triggers = document.querySelectorAll('.thumb-trigger');

  if (!triggers.length) return;

  function openOverlay(overlayEl) {
    overlayEl.hidden = false;      // tampilkan
  }
  function closeOverlay(overlayEl) {
    overlayEl.hidden = true;       // sembunyikan
  }

  // untuk tiap trigger, cari overlay di wrapper terdekat
  triggers.forEach(trigger => {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const url = this.getAttribute('data-video-url');
      const wrapper = this.closest('.thumbnail-wrapper');
      const overlay = wrapper?.querySelector('.video-overlay');
      if (!overlay) return;

      if (isGuest) {
        openOverlay(overlay);
      } else {
        window.open(url, '_blank', 'noopener,noreferrer');
      }

      // Tutup saat klik di luar modal
      const outsideClick = (ev) => {
        if (ev.target === overlay) {
          closeOverlay(overlay);
          overlay.removeEventListener('click', outsideClick);
          document.removeEventListener('keydown', escClose);
        }
      };
      overlay.addEventListener('click', outsideClick);

      // Tutup via ESC
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