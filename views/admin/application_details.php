<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';
$candidateId = filter_input(INPUT_GET, 'candidate', FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Application Details | Online Voting System</title>

    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php $portalRole = 'admin';
        include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php
            $pageTitle = 'Available Voters';
            $pageDescription = 'Monitor elections, voter activity, and candidate applications.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main class="min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">

                <?php
                $applicationCandidateId = $candidateId;
                $showReviewActions = true;
                include APP_ROOT . '/includes/candidate/submitted_application.php';
                ?>
            </main>
        </div>
    </div>


    <footer class="border-t border-slate-200 bg-white px-5 py-6 text-center text-sm text-slate-500">
        &copy; <span id="year"></span> Online Voting System. All rights reserved.
    </footer>

</body>

</html>