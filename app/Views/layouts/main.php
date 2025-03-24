<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.css') ?>" type="text/css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo base_url('js/bootstrap.js') ?>"><</script>
    <script src="<?= base_url('js/pristine/dist/pristine.js') ?>" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<div class="d-flex flex-column min-vh-100">
    <?= $this->include('components/header') ?>
    <div class="flex-grow-1">
        <?php if (logged_in()): ?>
            <?php if (in_groups('admin')): ?>
                <?= $this->include('components/sidebar') ?>
            <?php endif; ?>
        <?php endif; ?>
        <div class="container mt-4">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    <?= $this->include('components/footer') ?>
    <?= $this->renderSection('scripts') ?>

</div>