<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';
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
            $pageTitle = 'Admin Dashboard';
            $pageDescription = 'Monitor elections, voter activity, and candidate applications.';
            include APP_ROOT . '/includes/shared/portal_header.php';
            ?>
            <main id="dashboard" class="min-w-0 flex-1">
                <div class="mx-auto max-w-7xl space-y-7 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Welcome back, Administrator
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">Here’s what’s happening across your elections today.</p>
                        </div>
                        <a href="#elections"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <path stroke-linecap="round" d="M12 5v14m-7-7h14" />
                            </svg>
                            Create Election
                        </a>
                    </div>

                    <section aria-label="Election system summary" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Total Voters</p>
                                    <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                                        <?php
                                        $voters = $pdo->query("SELECT COUNT(*) FROM users WHERE role='voter'")->fetchColumn();
                                        echo $voters; ?>
                                    </p>
                                </div>
                                <span
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg
                                        class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round"
                                            d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" />
                                    </svg></span>
                            </div>
                            <p class="mt-3 text-xs font-medium text-green-700">+8.2% <span
                                    class="font-normal text-slate-500">from last month</span></p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Total Candidates</p>
                                    <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                                        <?php
                                        $candidates = $pdo->query("SELECT COUNT(*) FROM users WHERE role='candidate'")->fetchColumn();
                                        echo $candidates; ?>
                                    </p>
                                </div>
                                <span
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-700"><svg
                                        class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="8" r="4" />
                                        <path stroke-linecap="round" d="M5 21v-2a7 7 0 0 1 14 0v2" />
                                    </svg></span>
                            </div>
                            <p class="mt-3 text-xs font-medium text-slate-500">Across all registered elections</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Pending Applications</p>
                                    <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                                        <?php
                                        $pending_applications = $pdo->query("SELECT COUNT(*) FROM candidates WHERE status='pending'")->fetchColumn();
                                        echo $pending_applications; ?>
                                    </p>
                                </div>
                                <span
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-700"><svg
                                        class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" d="M12 7v5l3 2" />
                                    </svg></span>
                            </div>
                            <p class="mt-3 text-xs font-medium text-orange-700">Needs your review</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Active Elections</p>
                                    <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                                        <?php
                                        $active_elections = $pdo->query("SELECT COUNT(*) FROM elections WHERE status!='completed'")->fetchColumn();
                                        echo $active_elections; ?>
                                    </p>
                                </div>
                                <span
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg
                                        class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                                    </svg></span>
                            </div>
                            <p class="mt-3 text-xs font-medium text-green-700">
                                <?php
                                $ongoing_elections = $pdo->query("SELECT COUNT(*) FROM elections WHERE status='ongoing'")->fetchColumn();
                                echo $ongoing_elections; ?> election currently ongoing</p>
                        </article>
                    </section>

                    <section id="applications"
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div
                            class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                            <div>
                                <h2 class="font-bold text-slate-900">Recent Candidate Applications</h2>
                                <p class="mt-1 text-sm text-slate-500">Review candidate submissions and application status.
                                </p>
                            </div>
                            <a href="#candidates" class="text-sm font-bold text-purple-700 hover:text-purple-900">View all
                                applications <span aria-hidden="true">→</span></a>
                        </div>
                        <div class="overflow-x-auto">
                            <?php include_once APP_ROOT . '/includes/admin/applications_list.php' ?>
                        </div>
                    </section>

                    <div class="grid gap-7 xl:grid-cols-1">
                        <section id="voters" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div
                                class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center">
                                <div>
                                    <h2 class="font-bold text-slate-900">Recent Voters</h2>
                                    <p class="mt-1 text-sm text-slate-500">Token and account overview</p>
                                </div>
                                <a href="#voters" class="text-sm font-bold text-purple-700 hover:text-purple-900">View all
                                    <span aria-hidden="true">→</span></a>
                            </div>
                            <div class="overflow-x-auto">
                                <?php include_once APP_ROOT . '/includes/admin/voters_list.php' ?>
                            </div>
                        </section>
                        
                        <section id="elections"
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                            <div>
                                <h2 class="font-bold text-slate-900">Active Elections</h2>
                                <p class="mt-1 text-sm text-slate-500">Upcoming and current election schedule</p>
                            </div>
                            <a href="#elections" class="text-sm font-bold text-purple-700 hover:text-purple-900">View
                                all</a>
                            </div>
                            <div class="overflow-x-auto">
                                <?php include_once APP_ROOT . '/includes/shared/elections_list.php' ?>
                            </div>
                        </section>
                    </div>

                      <?php include_once APP_ROOT . '/includes/admin/footer.php' ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>