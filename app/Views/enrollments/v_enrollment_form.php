<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= isset($enrollment) ? 'Edit Enrollment' : 'Add Enrollment'; ?></h4>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errorGrades')): ?>
            <div class="alert alert-danger">
                <?php foreach (session('errorGrades') as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card-body">
            <form
                action="<?= isset($enrollment) ? base_url('enrollments/update/' . $enrollment->id) : base_url('enrollments/create') ?>"
                method="post">
                <?= csrf_field() ?>
                <?php if (isset($enrollment)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select <?= session('errors.student_id') ? 'is-invalid' : '' ?>" disabled>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student->id ?>" <?= old('student_id', isset($enrollment) ? $enrollment->student_id : '') == $student->id ? 'selected' : '' ?>>
                                <?= $student->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="student_id"
                        value="<?= old('student_id', isset($enrollment) ? $enrollment->student_id : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.student_id') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="course_id" class="form-label">Course</label>
                    <select class="form-select <?= session('errors.course_id') ? 'is-invalid' : '' ?>" disabled>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course->id ?>" <?= old('course_id', isset($enrollment) ? $enrollment->course_id : '') == $course->id ? 'selected' : '' ?>>
                                <?= $course->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="course_id"
                        value="<?= old('course_id', isset($enrollment) ? $enrollment->course_id : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.course_id') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <input type="number" name="academic_year"
                        class="form-control <?= session('errors.academic_year') ? 'is-invalid' : '' ?>"
                        value="<?= old('academic_year', isset($enrollment) ? $enrollment->academic_year : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.academic_year') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" name="semester"
                        class="form-control <?= session('errors.semester') ? 'is-invalid' : '' ?>"
                        value="<?= old('semester', isset($enrollment) ? $enrollment->semester : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.semester') ?? '' ?></div>
                </div>

                <?php if (logged_in()): ?>
                    <?php $isStudent = in_groups('student'); ?>
                    <?php $autoSelectStatus = $isStudent ? 'On Progress' : ''; ?>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>">
                        <option value="Pass" <?= old('status', isset($enrollment) ? $enrollment->status : $autoSelectStatus) == 'Pass' ? 'selected' : '' ?> <?= $isStudent ? 'disabled' : '' ?>>
                            Pass
                        </option>
                        <option value="On Progress" <?= old('status', isset($enrollment) ? $enrollment->status : $autoSelectStatus) == 'On Progress' ? 'selected' : '' ?>>
                            On Progress
                        </option>
                        <option value="Failed" <?= old('status', isset($enrollment) ? $enrollment->status : $autoSelectStatus) == 'Failed' ? 'selected' : '' ?> <?= $isStudent ? 'disabled' : '' ?>>
                            Failed
                        </option>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.status') ?? '' ?></div>
                </div>


                <button type="submit" class="btn btn-success">Save Enrollment</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>