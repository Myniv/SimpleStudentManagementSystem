<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= isset($course) ? 'Edit Course' : 'Add Course'; ?></h4>
        </div>
        <div class="card-body">
            <form
                action="<?= isset($course) ? base_url('courses/update/' . $course->id) : base_url('courses/create') ?>"
                method="post" id="formData" novalidate>
                <?= csrf_field() ?>
                <?php if (isset($course)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="code" class="form-label">Course Code</label>
                    <input type="text" name="code"
                        class="form-control <?= session('errors.code') ? 'is-invalid' : '' ?>"
                        value="<?= old('code', isset($course) ? $course->code : '') ?>" data-pristine-required
                        data-pristine-required-message="Course code is required." data-pristine-minlength="8"
                        data-pristine-minlength-message="Course code must be exactly 8 characters."
                        data-pristine-maxlength="8"
                        data-pristine-minlength-message="Course code must be exactly 8 characters.">
                    <div class="text-danger"><?= session('errors.code') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Course Name</label>
                    <input type="text" name="name"
                        class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                        value="<?= old('name', isset($course) ? $course->name : '') ?>" data-pristine-required
                        data-pristine-required-message="Course name is required." data-pristine-minlength="3"
                        data-pristine-minlength-message="Course name must be at least 3 characters."
                        data-pristine-maxlength="100"
                        data-pristine-minlength-message="Course name must not exceed 100 characters.">
                    <div class="text-danger"><?= session('errors.name') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" name="credits"
                        class="form-control <?= session('errors.credits') ? 'is-invalid' : '' ?>"
                        value="<?= old('credits', isset($course) ? $course->credits : '') ?>" data-pristine-required
                        data-pristine-required-message="Course credits is required" data-pristine-min="1"
                        data-pristine-min-message="Course credits must be at least 1." data-pristine-type="integer"
                        data-pristine-type-message="Course credits must be a number." data-pristine-max="6"
                        data-pristine-max-message="Course credits cannot exceed 6.">
                    <div class="text-danger"><?= session('errors.credits') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" name="semester"
                        class="form-control <?= session('errors.semester') ? 'is-invalid' : '' ?>"
                        value="<?= old('semester', isset($course) ? $course->semester : '') ?>" data-pristine-required
                        data-pristine-required-message="Semester is required." data-pristine-min="1"
                        data-pristine-min-message="Semester must be at least 1." data-pristine-type="integer"
                        data-pristine-type-message="Semester must be a number." data-pristine-max="8"
                        data-pristine-max-message="Semester cannot exceed 8.">
                    <div class="text-danger"><?= session('errors.semester') ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-success">Save Course</button>
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