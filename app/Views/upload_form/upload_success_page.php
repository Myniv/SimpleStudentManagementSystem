<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h3>Your file was successfully uploaded!</h3>
<ul>
    <li>name: <?= esc($uploaded_fileinfo->getBasename()) ?></li>
    <li>size: <?= esc($uploaded_fileinfo->getSizeByUnit('kb')) ?> KB</li>
    <li>extension: <?= esc($uploaded_fileinfo->guessExtension()) ?></li>
    <li>path: <?= esc($uploaded_fileinfo->getPath()) ?></li>
</ul>
<p><?= anchor('upload', 'Upload Another File!') ?></p>

<?= $this->endSection(); ?>