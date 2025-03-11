<body>
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-navy text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <title>Student Management System</title>
                <h1 class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 text-white"
                    onclick="window.location.href='<?= base_url('/') ?>';" style="cursor: pointer;">
                    Student Management System
                </h1>
                <?php if (logged_in()): ?>
                    <p>Welcome <?= user()->username ?></p>
                <?php endif; ?>
                <nav>
                    <?php if (logged_in()): ?>
                        <a class="text-white btn btn-primary" href="<?= base_url('/') ?>">Home</a>
                        <?php if (in_groups('lecturer')): ?>
                            <a class="text-white btn btn-primary" href="<?= base_url('/lecturer/courses') ?>">Courses</a>
                        <?php endif; ?>
                        <?php if (in_groups('admin')): ?>
                            <a class="text-white btn btn-primary" href="<?= base_url('/admin/users') ?>">Users</a>
                            <a class="text-white btn btn-primary" href="<?= base_url('/admin/student') ?>">Students</a>
                        <?php endif; ?>
                        <?php if (in_groups('student')): ?>
                            <a class="text-white btn btn-primary" href="<?= base_url('/enrollments') ?>">Enrollments</a>
                        <?php endif; ?>
                        <a class="text-white btn btn-danger" href="<?= base_url('/logout') ?>">Logout</a>
                    <?php else: ?>
                        <a class="text-white btn btn-success" href="<?= base_url('/login') ?>">Login</a>
                        <a class="text-white btn btn-warning" href="<?= base_url('/register') ?>">Register</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <style>
        .bg-navy {
            background-color: #001f3f !important;
        }
    </style>

    <main>