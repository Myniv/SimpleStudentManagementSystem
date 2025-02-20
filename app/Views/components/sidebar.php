<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Academics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            height: 100%;
            width: 250px;
            background: #343a40;
            padding-top: 20px;
            transition: left 0.3s ease-in-out;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar.active {
            left: 0;
        }

        .menu-btn {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: #343a40;
        }
    </style>
</head>

<body>

    <!-- Burger Button -->
    <button class="menu-btn btn text-white" onclick="toggleSidebar()">
        ☰
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="menu-btn btn text-white" onclick="toggleSidebar()">
            ☰
        </button>
        <h4 class="text-light text-center">Academics</h4>
        <hr class="text-light">
        <nav>
            <a class="text-white" href="<?= base_url('/academics') ?>">Academics</a>
            <a class="text-white" href="<?= base_url('/academics/statistics') ?>">Academics statistics</a>
        </nav>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>

</body>

</html>