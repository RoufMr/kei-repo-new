<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>
<?php
$this->setData([
    'title' => $video['title_video'] ,
    'meta_description' => $video['meta_deskripsi_video'] 
]);
?>
<style>
    .rounded {
        border-radius: 8px;
    }

    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-weight: normal;
        color: #fff;
        font-size: 0.9rem;
        padding: 0.8em 1.5em;
        border-radius: 3px;
        background-color: #03AADE;
        display: inline-block;
    }

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0px 0px 25px #03AADE !important;
        transform: translateY(-5px) !important;
    }

    .thumbnail-wrapper {
        position: relative;
        display: inline-block;
    }

    .thumbnail-wrapper img {
        filter: brightness(70%);
        transition: filter 0.3s ease;
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

<!-- Video Details Start -->
<div class="container-fluid pt-5 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="position-relative mb-3">
                    <div class="bg-white border border-top-0 p-4 rounded shadow-sm">

                        <!-- Tags Badges -->
                        <div style="display: flex;">
                            <div class="badge py-2">
                                <?= esc($kategori['nama_kategori_video']); ?>
                            </div>
                        </div>

                        <!-- Video Title -->
                        <h4 class="py-3 text-uppercase font-weight-bold"><?= esc($video['judul_video']); ?></h4>

                        <!-- Plyr Video Player Start -->
                        <div class="mb-3 text-center">
                            <a href="<?= esc($video['video_url']); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                <div class="thumbnail-wrapper">
                                    <img src="<?= base_url('/img/' . $video['thumbnail']); ?>"
                                        alt="<?= esc($video['judul_video']); ?>"
                                        class="img-fluid rounded shadow-sm mb-3" />
                                    <div class="play-button"></div>
                                </div>
                            </a>
                        </div>
                        <!-- Plyr Video Player End -->

                        <!-- Description -->
                        <div class="mb-3">
                            <h5 class="font-weight-bold py-2">Deskripsi</h5>
                            <p><?= esc($video['deskripsi_video']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Video Lainnya -->
                <div class="mb-3">
                    <div class="section-title mb-0">
                        <h4 class="m-0 py-4 font-weight-bold">Video Lainnya</h4>
                    </div>

                    <?php foreach ($related_video as $related_video): ?>
                        <div class="card bg-white border border-top-0 p-3 rounded shadow-sm mb-3">
                            <a href="<?= base_url('video/' . esc($related_video['slug'])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center bg-white rounded border border-light overflow-hidden shadow-sm">
                                    <img class="img-fluid" style="object-fit: cover; width: 100px; height: 100px;" src="<?= base_url('/img/' . esc($related_video['thumbnail'])); ?>" alt="Thumbnail Video">
                                    <div class="w-100 h-100 px-3 d-flex flex-column justify-content-center">
                                        <h3 class="text-uppercase font-weight-bold text-dark" style="font-size: 18px; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                            <?= esc($related_video['judul_video']); ?>
                                        </h3>
                                        <p class="text-dark" style="font-size: 14px; margin-bottom: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                            <?= esc($related_video['deskripsi_video']); ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Video Lainnya End -->
            </div>
        </div>
    </div>
</div>
<!-- Video Details End -->

<!-- Init Plyr + Disable Right Click -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const player = new Plyr('#plyr-video');

        // Disable right click on video
        const video = document.getElementById('plyr-video');
        video.addEventListener('contextmenu', e => e.preventDefault());
        video.addEventListener('dragstart', e => e.preventDefault());
    });
</script> -->

<?= $this->endSection(); ?>