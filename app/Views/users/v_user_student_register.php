<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<div class="container mt-4 mb-4">
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/store-register-student') ?>" method="post">
        <?= csrf_field(); ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-3">Create User</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                        name="username" id="username" placeholder="Username" value="<?= old('username') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.username') ?? '' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                        name="email" id="email" placeholder="Email" value="<?= old('email') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?? '' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                        name="password" id="password" placeholder="Password" value="<?= old('password') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?? '' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pass_confirm" class="form-label">Confirmation Password</label>
                    <input type="password"
                        class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>"
                        name="pass_confirm" id="pass_confirm" placeholder="Confirmation Password"
                        value="<?= old('pass_confirm') ?>" required>
                    <div class="invalid-feedback">
                        <?= session('errors.pass_confirm') ?? '' ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-3">Create Student Data</h4>
            </div>
            <div class="card-body">

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
                    <select name="study_program"
                        class="form-select <?= session('errors.academic_status') ? 'is-invalid' : '' ?>"
                        data-pristine-required data-pristine-required-message="Study Program is required">
                        <option value="" <?= old('study_program', isset($student) ? $student->study_program : '') ? 'disabled' : '' ?>>Select Proram</option>
                        <option value="Artificial Intelligence" <?= old('study_program', isset($student) ? $student->study_program : '') == 'Artificial Intelligence' ? 'selected' : '' ?>>Artificial
                            Intelligence</option>
                        <option value="Cyber Security" <?= old('study_program', isset($student) ? $student->study_program : '') == 'Cyber Security' ? 'selected' : '' ?>>Cyber Security</option>
                        <option value="Programming Expert" <?= old('study_program', isset($student) ? $student->study_program : '') == 'Programming Expert' ? 'selected' : '' ?>>Programming Expert
                        </option>
                    </select>
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


                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>

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