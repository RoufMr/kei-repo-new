<?= $this->extend('member/layout/app'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <h3 class="mb-3">Edit Konten</h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

    <form action="<?= base_url('sosmed-planner/konten-planner/update/' . $konten['slug']) ?>" method="post" enctype="multipart/form-data" class="row g-4">
        <?= csrf_field(); ?>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Judul</label>
            <input type="text" name="title" class="form-control" value="<?= esc($konten['judul']); ?>" required>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select" required>
                <?php foreach (['Draft', 'Ready', 'Scheduled', 'Published'] as $st): ?>
                    <option value="<?= $st; ?>" <?= $konten['status'] === $st ? 'selected' : ''; ?>><?= $st; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Konten Tipe</label>
            <select name="content_type_id" class="form-select" required>
                <option value="">-- Pilih --</option>
                <?php foreach (($kontentype ?? []) as $t): ?>
                    <option value="<?= $t['id']; ?>" <?= (int)($konten['content_type_id'] ?? 0) === (int)$t['id'] ? 'selected' : ''; ?>>
                        <?= esc($t['nama']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Konten Pilar</label>
            <select name="content_pillar_id" class="form-select" required>
                <option value="">-- Pilih --</option>
                <?php foreach (($kontenpilar ?? []) as $p): ?>
                    <option value="<?= $p['id']; ?>" <?= (int)($konten['content_pillar_id'] ?? 0) === (int)$p['id'] ? 'selected' : ''; ?>>
                        <?= esc($p['nama']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Caption</label>
            <textarea name="caption" class="form-control" rows="4" maxlength="2200"><?= esc($konten['caption']); ?></textarea>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Tanggal Posting</label>
            <input type="date" name="posting_date" class="form-control" value="<?= esc($konten['tanggal_posting']); ?>" required>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Waktu Posting</label>
            <input type="time" name="posting_time" class="form-control"
                value="<?= esc(substr($konten['jam_posting'], 0, 5)); ?>" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold d-block mb-2">Platform</label>
            <?php
            // Jika disimpan CSV di DB
            $selectedPlatforms = is_array($konten['content_platform_id'])
                ? $konten['content_platform_id']
                : array_filter(explode(',', (string)$konten['content_platform_id']));
            ?>
            <div class="d-flex flex-wrap gap-3">
                <?php foreach ($kontenplatform as $pl): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pl<?= $pl['id']; ?>"
                            name="platforms[]" value="<?= $pl['id']; ?>"
                            <?= in_array((string)$pl['id'], array_map('strval', $selectedPlatforms), true) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="pl<?= $pl['id']; ?>">
                            <?= esc($pl['nama']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <label class="form-label fw-semibold">Link Canva</label>
            <input type="text" name="link_canva" class="form-control"
                value="<?= esc($konten['link_canva']); ?>">
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold">Upload Media (opsional)</label>
            <input type="file" name="uploadFoto" id="uploadFotoEdit" accept="image/*" class="form-control">
            <small class="text-muted">jpg/jpeg/png, maks 2MB</small>
        </div>

        <div class="col-lg-6">
            <label class="form-label fw-semibold d-block">Preview Media Saat Ini</label>
            <?php if (!empty($konten['media'])): ?>
                <img src="<?= base_url($konten['media']); ?>" alt="media" style="max-height:200px;" class="rounded border">
            <?php else: ?>
                <span class="text-muted">Belum ada media.</span>
            <?php endif; ?>
            <img id="previewEdit" src="" style="max-height:200px; display:none;" class="rounded border mt-2">
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="<?= base_url('/sosmed-planner'); ?>" class="btn btn-secondary">Batal</a>
            <a href="<?= base_url('sosmed-planner/konten-planner/delete/' . $konten['id']); ?>"
                class="btn btn-outline-danger"
                onclick="return confirm('Hapus konten ini?');">Hapus
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('uploadFotoEdit')?.addEventListener('change', function() {
        const f = this.files?.[0];
        const img = document.getElementById('previewEdit');
        if (!f) {
            img.style.display = 'none';
            img.src = '';
            return;
        }
        const r = new FileReader();
        r.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
        };
        r.readAsDataURL(f);
    });
</script>

<?= $this->endSection(); ?>