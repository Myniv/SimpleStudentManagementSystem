<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Unauthorized!!</h1>
<a href="<?= base_url('/') ?>" class="btn btn-primary">Home</a>

<?= $this->endSection() ?>