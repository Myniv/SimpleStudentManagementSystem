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
                <nav class="nav">
                    <?php if (logged_in()): ?>
                        <?php if (in_groups('lecturer')): ?>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/lecturer/dashboard') ?>">Dashboard</a>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/lecturer/courses') ?>">Courses</a>
                        <?php endif; ?>
                        <?php if (in_groups('admin')): ?>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/admin/dashboard') ?>">Dashboard</a>
                            <a class="btn btn-primary nav-link text-white me-2" href="<?= base_url('/admin/users') ?>">Users</a>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/admin/student') ?>">Students</a>
                        <?php endif; ?>
                        <?php if (in_groups('student')): ?>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/student/dashboard') ?>">Dashboard</a>
                            <a class="btn btn-primary nav-link text-white me-2"
                                href="<?= base_url('/enrollments') ?>">Enrollments</a>
                            <a class="btn btn-primary nav-link text-white me-2" href="<?= base_url('/profile') ?>">Profile</a>
                        <?php endif; ?>
                        <a class="btn btn-danger nav-link text-white me-2" href="<?= base_url('/logout') ?>">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-primary nav-link text-white me-2" href="<?= base_url('/') ?>">Home</a>
                        <a class="btn btn-success nav-link text-white me-2" href="<?= base_url('/login') ?>">Login</a>
                        <a class="btn btn-warning nav-link text-white me-2"
                            href="<?= base_url('/register-student') ?>">Register</a>
                    <?php endif; ?>
                </nav>
                <?php if (logged_in()): ?>
                    <div class="align-text-center">
                        <p>Welcome <?= user()->username ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <style>
        .bg-navy {
            background-color: rgb(0, 72, 144) !important;
        }

        .nav-link.active {
            /* highlight */
            background-color: rgb(0, 98, 255) !important;

            /* text color*/
            color: white !important;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentPath = window.location.pathname; // Get the current URL path

            document.querySelectorAll('.nav-link').forEach(link => {
                let linkPath = new URL(link.href).pathname; // Get the path of the link
                if (linkPath === currentPath) {
                    link.classList.add('active'); // Add active class if it matches
                }
            });
        });
    </script>
    </main>
    <main></main>