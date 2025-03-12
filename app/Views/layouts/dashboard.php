<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Dashboard <?= $role ?></h1>
<h2>Welcome <?= $name ?></h2>

<?= $this->endSection() ?>