<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

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
                        <div class="ratio ratio-16x9 mb-3">
                            <video id="plyr-video" class="rounded shadow-sm" controls preload="auto">
                                <!-- <source src="https://drive.google.com/uc?export=download&id=<?= esc($video['video_url']); ?>" type="video/mp4"> -->
                                <source src="https://drive.google.com/file/d/<?= esc($video['video_url']); ?>/view?usp=drive_link" type="video/mp4">
                                Browser Anda tidak mendukung pemutar video HTML5.
                            </video>
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

                    <?php foreach ($related_videos as $related_video): ?>
                        <div class="card bg-white border border-top-0 p-3 rounded shadow-sm mb-3">
                            <a href="<?= base_url('/video-tutorial-detail/' . esc($related_video['slug'])); ?>" class="text-decoration-none">
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const player = new Plyr('#plyr-video');

        // Disable right click on video
        const video = document.getElementById('plyr-video');
        video.addEventListener('contextmenu', e => e.preventDefault());
        video.addEventListener('dragstart', e => e.preventDefault());
    });
</script>

<?= $this->endSection(); ?>