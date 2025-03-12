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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-3"><?= $title; ?></h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/users/store') ?>" method="post">
                <?= csrf_field(); ?>

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

                <div class="mb-3">
                    <label for="group" class="form-label">Group</label>
                    <select class="form-select <?= session('errors.group') ? 'is-invalid' : '' ?>" name="group"
                        id="group" required>
                        <option value="">Select Group</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= $group->id ?>" <?= old('group') == $group->id ? 'selected' : '' ?>>
                                <?= $group->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= session('errors.group') ?? '' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>