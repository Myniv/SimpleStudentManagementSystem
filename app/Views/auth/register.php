<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Registrasi</div>
                <div class="card-body">
                    <?php if (session('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors') as $error): ?>
                                <?= $error ?><br>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <form action="<?= route_to('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?= session('errors.username') ?
                                'is-invalid' : '' ?>" name="username" placeholder="Username"
                                value="<?= old('username') ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= session('errors.email') ?
                                'is-invalid' : '' ?>" name="email" placeholder="Email" value="<?= old('email') ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?= session('errors.password') ?
                                'is-invalid' : '' ?>" name="password" placeholder="Password">
                        </div>

                        <div class="form-group mb-3">
                            <label for="pass_confirm" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control 
                 <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>" name="pass_confirm"
                                placeholder="Konfirmasi Password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                Sudah punya akun? <a href="<?= route_to('login') ?>">Login</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>