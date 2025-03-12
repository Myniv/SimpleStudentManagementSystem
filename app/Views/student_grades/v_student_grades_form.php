<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= isset($student_grades) ? 'Edit Grades' : 'Add Grades'; ?></h4>
        </div>
        <div class="card-body">
            <form
                action="<?= isset($student_grades) ? base_url('lecturer/student-grades/update/' . $student_grades->id) : base_url('lecturer/student-grades/create') ?>"
                method="post" id="formData" novalidate>
                <?= csrf_field() ?>
                <?php if (isset($student_grades)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="enrollments" class="form-label">Enrollment</label>
                    <select name="enrollments"
                        class="form-select <?= session('errors.enrollments') ? 'is-invalid' : '' ?>"
                        <?= isset($student_grades) ? 'disabled' : '' ?>>
                        <option value="">Select Enrollment</option>
                        <?php foreach ($enrollments as $enrollment): ?>
                            <?php
                            $selected = "";
                            if (isset($student_grades) && $student_grades->enrollment_id == $enrollment->id && $student_grades->course_id == $enrollment->course_id) {
                                $selected = "selected";
                            }
                            ?>
                            <option value="<?= $enrollment->id . "," . $enrollment->course_id ?>" <?= $selected ?>>
                                <?= $enrollment->student_name . " - " . $enrollment->course_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= session('errors.enrollments') ?? '' ?></div>
                </div>


                <div class="mb-3">
                    <label for="grade_value" class="form-label">Grade Value</label>
                    <input type="number" name="grade_value"
                        class="form-control <?= session('errors.grade_value') ? 'is-invalid' : '' ?>"
                        value="<?= old('grade_value', isset($student_grades) ? $student_grades->grade_value : '') ?>"
                        required>
                    <div class="text-danger"><?= session('errors.grade_value') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="completed_at" class="form-label">Completed At</label>
                    <input type="date" name="completed_at"
                        class="form-control <?= session('errors.completed_at') ? 'is-invalid' : '' ?>"
                        value="<?= old('completed_at', isset($student_grades) ? date('Y-m-d', strtotime($student_grades->completed_at)) : '') ?>"
                        required>
                    <div class="text-danger"><?= session('errors.completed_at') ?? '' ?></div>
                </div>


                <button type="submit" class="btn btn-success">Save</button>
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