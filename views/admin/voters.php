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
    <title>All Candidates | Online Voting System</title>
    <link rel="stylesheet" href="../../public/css/app.css">
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
            <main id="dashboard" class="min-w-0 flex-1">
                <div class="mx-auto max-w-7xl space-y-7 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

                    <section id="voters" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div
                            class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center">
                            <div>
                                <h2 class="font-bold text-slate-900">Recent Voters</h2>
                                <p class="mt-1 text-sm text-slate-500">Token and account overview</p>
                            </div>

                        </div>
                        <div class="overflow-x-auto">
                            <?php
                            $limit = false;
                            include_once APP_ROOT . '/includes/admin/voters_list.php' ?>
                        </div>
                    </section>

                    <?php include_once APP_ROOT . '/includes/admin/footer.php' ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>