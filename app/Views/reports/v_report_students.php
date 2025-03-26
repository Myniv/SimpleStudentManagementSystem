<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Report Students</h2>
    <form method="get" action="<?= base_url('admin/student/report') ?>">
        <div class="d-flex justify-content-between mb-3">
            <div class="row">

                <div class="col-md-6">
                    <select class="form-select" name="study_program" required onchange="this.form.submit()">
                        <option value="">Pilih Program Studi</option>
                        <?php foreach ($study_program as $program): ?>
                            <option value="<?= $program->study_program ?>"
                                <?= ($study_program_selected == $program->study_program) ? 'selected' : '' ?>>
                                <?= $program->study_program ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select" name="entry_year" onchange="this.form.submit()">
                        <option value="">Pilih Tahun Masuk</option>
                        <?php foreach ($entry_year as $year): ?>
                            <option value="<?= $year->entry_year ?>" <?= ($entry_year_selected == $year->entry_year) ? 'selected' : '' ?>>
                                <?= $year->entry_year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="">
                <a href="<?= base_url('admin/student/report/pdf') . '?' . http_build_query([
                    'study_program' => $study_program_selected,
                    'entry_year' => $entry_year_selected
                ]) ?>" class="btn btn-success" target="_blank">
                    <i class="bi bi-file-excel me-1"></i> Export Pdf
                </a>
                <a href="<?= base_url('admin/student/report') ?>" class="btn btn-secondary ml-2">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Name</th>
                    <th>Program Study</th>
                    <th>Semester</th>
                    <th>Academic Status</th>
                    <th>Entry Year</th>
                    <th>Gpa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data yang ditemukan</td>
                    </tr>

                <?php else: ?>
                    <?php $no = 1;
                    foreach ($students as $studendt): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $studendt->student_id ?></td>
                            <td><?= $studendt->name ?></td>
                            <td><?= $studendt->study_program ?></td>
                            <td><?= $studendt->current_semester ?></td>
                            <td><?= $studendt->academic_status ?></td>
                            <td><?= $studendt->entry_year ?></td>
                            <td><?= $studendt->gpa ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>