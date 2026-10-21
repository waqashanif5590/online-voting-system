<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body>
    <div class="bg-slate-50">
        <h1 class="text-purple-700 text-2xl">Admin Portal</h1>
        <h1 class="text-purple-700">Welcome! <?php echo $_SESSION['user_email'] ?></h1>
    </div>
    <a href="<?= htmlspecialchars(app_url('views/auth/logout.php'), ENT_QUOTES, 'UTF-8') ?>" class="p3 bg-purple-700 text-slate-50">Logout</a>
</body>
</html>