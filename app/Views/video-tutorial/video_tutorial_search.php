<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<style>
    /* ===================================================
       Section header & layout
       =================================================== */
    .artikel-detail-section {
        padding: 0 15px;
    }

    /* ===================================================
       Search bar
       =================================================== */
    .form {
        --timing: 0.3s;
        --width-of-input: 600px;
        --height-of-input: 50px;
        --border-height: 4px;
        --input-bg: #03AADE;
        --border-color: #F2BF02;
        --border-radius: 30px;
        --after-border-radius: 1px;

        position: relative;
        width: 100%;
        max-width: var(--width-of-input);
        height: var(--height-of-input);
        display: flex;
        align-items: center;
        padding-inline: 0.8em;
        border-radius: var(--border-radius);
        transition: border-radius 0.5s ease;
        background: var(--input-bg, #fff);
        margin: 0 auto;
    }

    .form button {
        border: none;
        background: none;
        color: #fff;
    }

    .input {
        font-size: 0.9rem;
        background-color: transparent;
        width: 100%;
        height: 100%;
        padding-inline: 0.5em;
        padding-block: 0.7em;
        border: none;
        color: #fff;
    }

    .input::placeholder {
        color: #fff;
    }

    .form:before {
        content: "";
        position: absolute;
        background: var(--border-color);
        transform: scaleX(0);
        transform-origin: center;
        width: 100%;
        height: var(--border-height);
        left: 0;
        bottom: 0;
        border-radius: 1px;
        transition: transform var(--timing) ease;
    }

    .form:focus-within {
        border-radius: var(--after-border-radius);
    }

    .input:focus {
        outline: none;
    }

    .form:focus-within:before {
        transform: scale(1);
    }

    .reset {
        border: none;
        background: none;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease;
    }

    input:not(:placeholder-shown) ~ .reset {
        opacity: 1;
        visibility: visible;
    }

    .form svg {
        width: 17px;
        margin-top: 3px;
    }

    /* ===================================================
       Card video
       =================================================== */
    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0px 0px 25px #03AADE !important;
        transform: translateY(-5px) !important;
    }

    .badge {
        font-weight: normal;
        color: #fff;
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 3px;
        background-color: #03AADE;
        display: inline-block;
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

    .card-content-inner {
        max-width: 340px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .alert {
        margin: 0 auto;
        max-width: 600px;
    }

    /* ===================================================
       RESPONSIVE BREAKPOINTS
       =================================================== */

    /* ≤ 992px */
    @media (max-width: 992px) {
        .artikel-detail-section {
            padding-inline: 20px;
        }

        .text-custom-title {
            font-size: 1.8rem;
        }

        .text-custom-paragraph {
            font-size: 1rem;
        }
    }

    /* ≤ 768px */
    @media (max-width: 768px) {
        .form {
            --width-of-input: 100%;
            --height-of-input: 45px;
        }

        .text-custom-title {
            font-size: 1.6rem;
        }

        .text-custom-paragraph {
            font-size: 0.95rem;
        }

        .card-title {
            font-size: 0.9rem;
        }

        .card-content-inner {
            max-width: 320px;
        }

        .card-body p {
            font-size: 13px;
        }

        .card-content-inner a {
            font-size: 13px;
        }
    }

    /* ≤ 576px */
    @media (max-width: 576px) {
        .artikel-detail-section {
            padding-inline: 10px;
        }

        .text-custom-title {
            font-size: 1.4rem;
        }

        .text-custom-paragraph {
            font-size: 0.9rem;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.3em 0.6em;
        }

        .card {
            margin: 0 20px;
            border-radius: 14px;
        }

        .card-content-inner {
            max-width: 420px;
        }
    }

    /* ≤ 425px */
    @media (max-width: 425px) {
        .text-custom-title {
            font-size: 1.25rem;
        }

        .text-custom-paragraph {
            font-size: 0.85rem;
        }

        .form {
            --height-of-input: 42px;
        }

        .badge {
            font-size: 0.7rem;
        }

        .card-title {
            font-size: 0.8rem;
        }

        .card-body p {
            font-size: 10px;
        }

        .card-content-inner a {
            font-size: 10px;
        }
    }

    /* ≤ 360px */
    @media (max-width: 360px) {
        .artikel-detail-section {
            padding-inline: 8px;
        }

        .text-custom-title {
            font-size: 1.1rem;
        }

        .text-custom-paragraph {
            font-size: 0.8rem;
        }

        .form {
            --height-of-input: 40px;
        }
    }
</style>

<!-- Header + Search -->
<div class="artikel-detail-section py-5 text-center">
    <h2 class="text-custom-title"><?= lang('Blog.videoTutorialTitle') ?></h2>

    <?php if (!empty($keyword)): ?>
        <p class="text-custom-paragraph mt-2">
            <?= lang('Blog.searchResults') ?> <strong><?= esc($keyword) ?></strong>
        </p>
    <?php else: ?>
        <p class="text-custom-paragraph mt-2">
            <?= lang('Blog.videoTutorialDescription') ?>
        </p>
    <?php endif; ?>

    <form class="form mt-4" action="#" method="GET" onsubmit="
        event.preventDefault();
        const lang = '<?= $lang === 'en' ? 'en' : 'id' ?>';
        const base = lang === 'en' ? 'en/videos/keyword=' : 'id/video/keyword=';
        const input = this.querySelector('input[name=keyword]');
        let kw = (input.value || '').trim();
        if (!kw) { input.focus(); return false; }
        kw = encodeURIComponent(kw).replace(/%20/g, '+');
        window.location.href = '<?= base_url() ?>' + base + kw;
        return false;
    ">
        <button type="submit" aria-label="Search">
            <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img">
                <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                    stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <input
            id="searchInput"
            class="input"
            autocomplete="off"
            placeholder="<?= lang('Blog.searchPlaceholder') ?>"
            name="keyword"
            required
            type="text"
            value="<?= isset($keyword) ? esc($keyword) : '' ?>">
        <button
            id="searchReset"
            type="button"
            class="reset"
            aria-label="Clear search"
            data-reset-url="<?= base_url(($lang === 'en') ? 'en/videos' : 'id/video'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </form>
</div>

<section class="container">
    <div class="row g-4 mb-5">
        <?php if (!empty($hasilPencarian)): ?>
            <?php foreach ($hasilPencarian as $video): ?>
                <!-- 2 kolom di tablet (sm), 3 kolom di desktop (lg) -->
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img
                            src="<?= base_url('/img/' . $video['thumbnail']); ?>"
                            class="card-img-top img-fluid"
                            alt="<?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>"
                            style="object-fit: cover; object-position: center; aspect-ratio: 16/9;"
                            loading="lazy">
                        <div class="card-body d-flex flex-column">
                            <div class="card-content-inner">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <p class="mb-0">
                                        <?= date('d F Y', strtotime($video['created_at'])); ?>
                                    </p>
                                    <span class="badge">
                                        <?= ($lang === 'en') ? $video['nama_kategori_video_en'] : $video['nama_kategori_video']; ?>
                                    </span>
                                </div>
                                <h5 class="card-title"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= ($lang === 'en') ? $video['judul_video_en'] : $video['judul_video']; ?>
                                </h5>
                                <p
                                    style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= ($lang === 'en') ? $video['deskripsi_video_en'] : $video['deskripsi_video']; ?>
                                </p>
                                <a href="<?= base_url(($lang === 'en' ? 'en/videos/' : 'id/video/')
                                                . ($lang === 'en' ? $video['slug_en'] : $video['slug'])); ?>"
                                   class="btn btn-custom mt-auto w-100">
                                    <?= lang('Blog.watchNow') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="mt-5">
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>
        <?php else: ?>
            <div class="col-12 d-flex justify-content-center">
                <div class="alert alert-info text-center" role="alert">
                    <?= lang('Blog.noVideosFound') ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
  (function () {
    const resetBtn = document.getElementById('searchReset');
    const input = document.getElementById('searchInput');

    if (!resetBtn || !input) return;

    const toggleReset = () => {
      const hasText = input.value && input.value.trim() !== '';
      resetBtn.style.opacity = hasText ? '1' : '0';
      resetBtn.style.visibility = hasText ? 'visible' : 'hidden';
    };

    input.addEventListener('input', toggleReset);
    toggleReset();

    resetBtn.addEventListener('click', function () {
      input.value = '';
      const url = this.dataset.resetUrl;
      if (url) window.location.href = url; // kembali ke listing awal video
    });
  })();
</script>

<?= $this->endSection(); ?>
