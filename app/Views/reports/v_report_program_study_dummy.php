<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2 class="text-center mb-4"><?= $title ?></h2>
    <div class="card mb-4">
        <div class="card-body">
            <form class="row" action="<?= base_url('report/students-by-program-study-pdf') ?>" method="post"
                target="_blank">
                <div class="col-md-3">
                    <select class="form-control" name="study_program" required>
                        <option value="">Pilih Program Studi</option>
                        <?php foreach ($study_programs as $program): ?>
                            <option value="<?= $program->study_program ?>"> <?= $program->study_program ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="entry_year">
                        <option value="">Pilih Tahun Masuk</option>
                        <?php foreach ($entry_years as $year): ?>
                            <option value="<?= $year->entry_year ?>"> <?= $year->entry_year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>