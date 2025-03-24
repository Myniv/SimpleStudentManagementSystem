<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2 class="text-center mb-4"><?= $title ?></h2>
    <div class="card mb-4">
        <div class="card-body">
            <form class="row" method="get" action="<?= site_url('report/enrollment') ?>">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan NIM atau Nama"
                        value="<?= $filters['name'] ?? '' ?>">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Lihat Laporan</button>
                    <a href="<?= site_url('report/enrollment') ?>" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <a href="<?= site_url('report/enrollmentExcel') . (!empty($filters['student_id']) || !empty($filters['name']) ? '?' . http_build_query($filters) : '') ?>"
            class="btn btn-success">
            <i class="bi bi-file-excel me-1"></i> Export Excel
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Semester</th>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Tahun Akademik</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enrollments)): ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data yang ditemukan</td>
                            </tr>

                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($enrollments as $enrollment): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $enrollment->student_id ?></td>
                                    <td><?= $enrollment->name ?></td>
                                    <td><?= $enrollment->study_program ?></td>
                                    <td><?= $enrollment->current_semester ?></td>
                                    <td><?= $enrollment->course_code ?></td>
                                    <td><?= $enrollment->course_name ?></td>
                                    <td><?= $enrollment->credits ?></td>
                                    <td><?= $enrollment->academic_year . ' - ' . $enrollment->enrollment_semester ?></td>
                                    <td><?= $enrollment->status ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>