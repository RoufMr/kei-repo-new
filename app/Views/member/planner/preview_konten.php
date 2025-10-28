<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Preview Konten</h3>
    <div class="d-flex gap-2">
      <a class="btn btn-primary"
        href="<?= base_url('/sosmed-planner/konten-planner/edit/' . $konten['slug']); ?>">
        Edit
      </a>
      <a href="<?= base_url('/sosmed-planner') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title"><?= esc($konten['judul']); ?></h4>

      <div class="mb-3">
        <span class="badge bg-<?= $konten['status'] === 'Published' ? 'success' : ($konten['status'] === 'Scheduled' ? 'info' : ($konten['status'] === 'Ready' ? 'warning' : 'secondary')); ?>">
          <?= esc($konten['status']); ?>
        </span>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <dl class="row mb-0">
            <dt class="col-5">Link Canva</dt>
            <dd class="col-7">
              <?php if (!empty($konten['link_canva'])): ?>
                <a href="<?= esc($konten['link_canva']) ?>"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="text-primary text-decoration-underline">
                  <?= esc($konten['link_canva']) ?>
                </a>
              <?php else: ?>
                <span class="text-muted">Belum ada link</span>
              <?php endif; ?>
            </dd>

            <dt class="col-5">Tanggal Posting</dt>
            <dd class="col-7"><?= esc($konten['tanggal_posting']); ?> <?= esc(substr($konten['jam_posting'], 0, 5)); ?></dd>

            <dt class="col-5">Konten Tipe</dt>
            <dd class="col-7"><?= esc($kontenTypeName ?? '-'); ?></dd>

            <dt class="col-5">Konten Pilar</dt>
            <dd class="col-7"><?= esc($kontenPillarName ?? '-'); ?></dd>

            <dt class="col-5">Platform</dt>
            <dd class="col-7">
              <?php
              $ids = array_filter(explode(',', (string)$konten['content_platform_id']));
              $names = [];
              foreach ($ids as $id) {
                foreach ($kontenplatform as $pf) {
                  if ((string)$pf['id'] === (string)$id) {
                    $names[] = $pf['nama'];
                    break;
                  }
                }
              }
              if (!$names) echo '<span class="text-muted">-</span>';
              foreach ($names as $nm): ?>
                <span class="badge bg-light text-dark border me-1"><?= esc($nm); ?></span>
              <?php endforeach; ?>
            </dd>
          </dl>
        </div>

        <div class="col-lg-6">
          <?php if (!empty($konten['media'])): ?>
            <img src="<?= base_url($konten['media']); ?>" alt="media" class="img-fluid rounded border">
          <?php else: ?>
            <div class="p-4 border rounded text-muted text-center">Tidak ada media.</div>
          <?php endif; ?>
        </div>

        <div class="col-12">
          <h6>Caption</h6>
          <div class="border rounded p-3"><?= nl2br(esc($konten['caption'])); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>