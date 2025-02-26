<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= isset($student) ? 'Edit Student' : 'Add Student'; ?></h4>
        </div>
        <div class="card-body">
            <!-- <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?> -->

            <form
                action="<?= isset($student) ? base_url('students/update/' . $student->id) : base_url('students/create') ?>"
                method="post">
                <?= csrf_field() ?>
                <?php if (isset($student)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" name="student_id"
                        class="form-control <?= session('errors.student_id') ? 'is-invalid' : '' ?>"
                        value="<?= old('student_id', isset($student) ? $student->student_id : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.student_id') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name"
                        class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                        value="<?= old('name', isset($student) ? $student->name : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.name') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="study_program" class="form-label">Study Program</label>
                    <input type="text" name="study_program"
                        class="form-control <?= session('errors.study_program') ? 'is-invalid' : '' ?>"
                        value="<?= old('study_program', isset($student) ? $student->study_program : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.study_program') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="current_semester" class="form-label">Current Semester</label>
                    <input type="number" name="current_semester"
                        class="form-control <?= session('errors.current_semester') ? 'is-invalid' : '' ?>"
                        value="<?= old('current_semester', isset($student) ? $student->current_semester : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.current_semester') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="academic_status" class="form-label">Academic Status</label>
                    <select name="academic_status"
                        class="form-select <?= session('errors.academic_status') ? 'is-invalid' : '' ?>">
                        <option value="Active" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="On Leave" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                        <option value="Graduated" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'Graduated' ? 'selected' : '' ?>>Graduated</option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.academic_status') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="entry_year" class="form-label">Entry Year</label>
                    <input type="text" name="entry_year"
                        class="form-control <?= session('errors.entry_year') ? 'is-invalid' : '' ?>"
                        value="<?= old('entry_year', isset($student) ? $student->entry_year : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.entry_year') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="gpa" class="form-label">GPA</label>
                    <input type="text" name="gpa" class="form-control <?= session('errors.gpa') ? 'is-invalid' : '' ?>"
                        value="<?= old('gpa', isset($student) ? $student->gpa : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.gpa') ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-success">Save Student</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>