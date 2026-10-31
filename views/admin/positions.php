<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Online Voting System administrator dashboard">
    <title>Admin Dashboard | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php $portalRole = 'admin';
        include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php
            $pageTitle = 'Election Positions';
            $pageDescription = 'Monitor elections, voter activity, and candidate applications.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main id="dashboard" class="min-w-0 flex-1">

            </main>
        </div>
    </div>
</body>

</html>