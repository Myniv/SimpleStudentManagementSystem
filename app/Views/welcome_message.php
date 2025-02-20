<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view_cell("AcademicStatusCell", ["status"=> "Active"]) ?>

<?= $this->endSection() ?>