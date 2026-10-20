<?php
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../public/login.php');
}
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Dashboard</title>
    <link rel="stylesheet" href="../public/css/app.css">
</head>

<body>
    <div class="bg-slate-50">
        <h1 class="text-purple-700 text-2xl">Voter Portal</h1>
        <h1 class="text-purple-700">Welcome! <?php echo $_SESSION['user_email'] ?></h1>
    </div>
    <a href="../public/logout.php" class="p3 bg-purple-700 text-slate-50">Logout</a>
</body>

</html>