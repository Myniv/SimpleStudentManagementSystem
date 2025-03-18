<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email Template</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Your Message</h1>
        </div>
        <div class="content">
            <h2>Halo, <?= $name ?></h2>
            <p><?= $content ?></p>
            <p>Thanks for reading this email.</p>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <strong>Available feature :</strong>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <?php foreach ($features as $feature): ?>
                        <li><?= $feature ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="footer">
            <p>This email is automatically sended. Please do not reply this email</p>
            <p>$copy; <?= date('Y') ?> Myniv</p>
        </div>
</body>
</html>