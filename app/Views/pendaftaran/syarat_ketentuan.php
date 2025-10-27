<?= $this->extend('layout/app'); ?>
<?= $this->section('content'); ?>

<?php
$this->setData([
    'title' => ($lang == 'id') ? $meta['title_syarat'] : $meta['title_syarat_en']
]);
?>

<div class="container my-5">
    <!-- judul -->
    <div class="pendaftaran-section text-center">
        <h1><?= lang('Blog.syaratketentuan'); ?></h1>
        <p><?= lang('Blog.syaratketentuandeskripi'); ?></p>
    </div>

    <h3>1. Pendaftaran</h3>
    <p>Pengguna wajib memberikan data yang benar saat melakukan pendaftaran.</p>

    <h3>2. Penggunaan Layanan</h3>
    <p>Layanan hanya boleh digunakan untuk tujuan yang sah dan tidak melanggar hukum.</p>

    <h3>3. Hak dan Kewajiban</h3>
    <p>Pengguna dan penyedia layanan masing-masing memiliki hak dan kewajiban yang harus dipatuhi.</p>

    <h3>4. Perubahan Syarat</h3>
    <p>Kami berhak mengubah syarat dan ketentuan kapan saja tanpa pemberitahuan sebelumnya.</p>
</div>

<?= $this->endSection(); ?>