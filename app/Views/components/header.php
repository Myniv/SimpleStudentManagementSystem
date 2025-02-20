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
                <nav>
                    <a class="text-white" href="<?= base_url('/') ?>">Home</a>
                    <a class="text-white" href="<?= base_url('/academics') ?>">Academics</a>
                    <a class="text-white" href="<?= base_url('/students') ?>">Students</a>
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