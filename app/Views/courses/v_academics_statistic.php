<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title bg-dark text-white p-2 rounded">
                    Total Course
                </h5>
                <h3 class="card-text">
                    <?= $academics?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title bg-dark text-white p-2 rounded">
                    Total Student
                </h5>
                <h3 class="card-text">
                    <?= $students ?>
                </h3>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>