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
                method="post">
                <?= csrf_field() ?>
                <?php if (isset($course)): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="code" class="form-label">Course Code</label>
                    <input type="text" name="code"
                        class="form-control <?= session('errors.code') ? 'is-invalid' : '' ?>"
                        value="<?= old('code', isset($course) ? $course->code : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.code') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Course Name</label>
                    <input type="text" name="name"
                        class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                        value="<?= old('name', isset($course) ? $course->name : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.name') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" name="credits"
                        class="form-control <?= session('errors.credits') ? 'is-invalid' : '' ?>"
                        value="<?= old('credits', isset($course) ? $course->credits : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.credits') ?? '' ?></div>
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" name="semester"
                        class="form-control <?= session('errors.semester') ? 'is-invalid' : '' ?>"
                        value="<?= old('semester', isset($course) ? $course->semester : '') ?>">
                    <div class="invalid-feedback"><?= session('errors.semester') ?? '' ?></div>
                </div>

                <button type="submit" class="btn btn-success">Save Course</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>