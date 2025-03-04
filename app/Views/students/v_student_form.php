<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= isset($student) ? 'Edit Student' : 'Add Student'; ?></h4>
        </div>
        <div class="card-body">
            <form
                action="<?= isset($student) ? base_url('students/update/' . $student->id) : base_url('students/create') ?>"
                method="post" id="formData" novalidate>
                <?= csrf_field() ?>
                <?php if (isset($student)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" name="student_id"
                        class="form-control <?= session('errors.student_id') ? 'is-invalid' : '' ?>"
                        value="<?= old('student_id', isset($student) ? $student->student_id : '') ?>"
                        data-pristine-required data-pristine-required-message="Student ID is required">
                    <div class="text-danger"><?= session('errors.student_id') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name"
                        class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                        value="<?= old('name', isset($student) ? $student->name : '') ?>" data-pristine-required
                        data-pristine-required-message="Student name is required" data-pristine-minlength="3"
                        data-pristine-minlength-message="Name must be at least 3 characters"
                        data-pristine-maxlength="100"
                        data-pristine-minlength-message="Name must not exceed 100 characters.">
                    <div class="text-danger"><?= session('errors.name') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="study_program" class="form-label">Study Program</label>
                    <input type="text" name="study_program"
                        class="form-control <?= session('errors.study_program') ? 'is-invalid' : '' ?>"
                        value="<?= old('study_program', isset($student) ? $student->study_program : '') ?>"
                        data-pristine-required data-pristine-required-message="Study Program is required">
                    <div class="text-danger"><?= session('errors.study_program') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="current_semester" class="form-label">Current Semester</label>
                    <input type="number" name="current_semester"
                        class="form-control <?= session('errors.current_semester') ? 'is-invalid' : '' ?>"
                        value="<?= old('current_semester', isset($student) ? $student->current_semester : '') ?>"
                        data-pristine-required data-pristine-required-message="Current Semester is required"
                        data-pristine-min="1" data-pristine-min-message="Current Semester must be at least 1"
                        data-pristine-max="14" data-pristine-max-message="Current Semester must not exceed 14">
                    <div class="text-danger"><?= session('errors.current_semester') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="academic_status" class="form-label">Academic Status</label>
                    <select name="academic_status"
                        class="form-select <?= session('errors.academic_status') ? 'is-invalid' : '' ?>"
                        data-pristine-required data-pristine-required-message="Academic Status is required">
                        <option value="" <?= old('academic_status', isset($student) ? $student->academic_status : '') ? 'disabled' : '' ?>>Select Academic Status</option>
                        <option value="Active" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="On Leave" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                        <option value="Graduated" <?= old('academic_status', isset($student) ? $student->academic_status : '') == 'Graduated' ? 'selected' : '' ?>>Graduated</option>
                    </select>
                    <div class="text-danger"><?= session('errors.academic_status') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="entry_year" class="form-label">Entry Year</label>
                    <input type="text" name="entry_year"
                        class="form-control <?= session('errors.entry_year') ? 'is-invalid' : '' ?>"
                        value="<?= old('entry_year', isset($student) ? $student->entry_year : '') ?>"
                        data-pristine-required data-pristine-required-message="Entry Year is required"
                        data-pristine-minlength="4" data-pristine-minlength-message="Entry Year must be at least 4"
                        data-pristine-maxlength="4" data-pristine-maxlength-message="Entry Year must be at least 4">
                    <div class="text-danger"><?= session('errors.entry_year') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="gpa" class="form-label">GPA</label>
                    <input type="text" name="gpa" class="form-control <?= session('errors.gpa') ? 'is-invalid' : '' ?>"
                        value="<?= old('gpa', isset($student) ? $student->gpa : '') ?>" data-pristine-required
                        data-pristine-required-message="GPA is required" data-pristine-min="0"
                        data-pristine-min-message="GPA Cannot least than 0" data-pristine-type="decimal"
                        data-pristine-type-message="GPA must be a decimal number" data-pristine-max="4.00"
                        data-pristine-max-message="GPA cannot exceed 4.00">
                    <div class="text-danger"><?= session('errors.gpa') ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-success">Save Student</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let pristine;
    window.onload = function () {
        let form = document.getElementById("formData");

        var pristine = new Pristine(form, {
            classTo: 'mb-3',
            errorClass: 'is-invalid',
            successClass: 'is-valid',
            errorTextParent: 'mb-3',
            errorTextTag: 'div',
            errorTextClass: 'text-danger'
        });


        form.addEventListener('submit', function (e) {
            var valid = pristine.validate();
            if (!valid) {
                e.preventDefault();
            }
        });

    };
</script>

<?= $this->endSection() ?>