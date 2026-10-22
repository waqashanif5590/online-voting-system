<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
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

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen md:flex">
        <aside class="bg-purple-950 text-purple-100 md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <div class="flex items-center gap-3 border-b border-white/10 px-5 py-5 md:px-6">
                <span
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500 text-xl font-extrabold text-white"
                    aria-hidden="true">V</span>
                <div>
                    <p class="font-extrabold tracking-tight text-white">Vote<span class="text-orange-400">System</span>
                    </p>
                    <p class="text-xs text-purple-300">Administration</p>
                </div>
            </div>

            <nav aria-label="Admin navigation" class="overflow-x-auto px-3 py-4 md:flex-1 md:overflow-x-hidden md:overflow-y-auto">
                <p class="hidden px-3 pb-3 text-xs font-bold uppercase tracking-widest text-purple-400 md:block">
                    Workspace</p>
                <ul class="flex w-max gap-1 md:w-auto md:flex-col">
                    <li>
                        <a href="#dashboard" aria-current="page"
                            class="flex items-center gap-3 rounded-xl bg-white/10 px-3 py-2.5 text-sm font-semibold text-white ring-1 ring-inset ring-white/10">
                            <svg class="h-5 w-5 shrink-0 text-orange-300" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="3" y="3" width="8" height="8" rx="2" />
                                <rect x="13" y="3" width="8" height="5" rx="2" />
                                <rect x="13" y="10" width="8" height="11" rx="2" />
                                <rect x="3" y="13" width="8" height="8" rx="2" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li><a href="#elections"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                <path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18" />
                            </svg><span>Elections</span></a></li>
                    <li><a href="#voters"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round"
                                    d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" />
                            </svg><span>Voters</span></a></li>
                    <li><a href="#candidates"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path stroke-linecap="round" d="M5 21v-2a7 7 0 0 1 14 0v2M19 8h3m-1.5-1.5v3" />
                            </svg><span>Candidates</span></a></li>
                    <li><a href="#applications"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path stroke-linecap="round" d="M14 2v6h6M8 13h8M8 17h8" />
                            </svg><span>Candidate Applications</span></a></li>
                    <li><a href="#positions"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.2A2 2 0 0 0 5.8 17l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 1 1 0-4h.2A2 2 0 0 0 3 4.2l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 1 1 4 0v.2A2 2 0 0 0 17.2 1l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 1 1 0 4h-.2a2 2 0 0 0-2.1 3.8Z"
                                    transform="translate(1 2) scale(.9)" />
                            </svg><span>Positions</span></a></li>
                    <li><a href="#parties"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3 3 8l9 5 9-5-9-5ZM3 12l9 5 9-5M3 16l9 5 9-5" />
                            </svg><span>Political Parties</span></a></li>
                    <li><a href="#election-results"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 19V5m0 14h17M8 15v-4m5 4V7m5 8v-6" />
                            </svg><span>Election Results</span></a></li>
                    <li><a href="#settings"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.2A2 2 0 0 0 5.8 17l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 1 1 0-4h.2A2 2 0 0 0 3 4.2l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 1 1 4 0v.2A2 2 0 0 0 17.2 1l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 1 1 0 4h-.2a2 2 0 0 0-1.6 2.3Z"
                                    transform="translate(1 2) scale(.9)" />
                            </svg><span>Settings</span></a></li>
                    <li class="md:hidden"><a href="#"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14 5-5-5-5m5 5H9" />
                            </svg><span>Logout</span></a></li>
                </ul>
            </nav>

            <div class="hidden border-t border-white/10 p-3 md:block">
                <a href="#"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-purple-200 transition hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4m7 14 5-5-5-5m5 5H9" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <main id="dashboard" class="min-w-0 flex-1 md:ml-64">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Overview</p>
                        <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">Admin Dashboard
                        </h1>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-5">
                        <button type="button" aria-label="Notifications"
                            class="relative rounded-xl border border-slate-200 p-2.5 text-slate-500 transition hover:bg-slate-50">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                            </svg>
                            <span
                                class="absolute right-2 top-2 h-2 w-2 rounded-full bg-orange-500 ring-2 ring-white"></span>
                        </button>
                        <div class="hidden h-9 border-l border-slate-200 sm:block"></div>
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 text-sm font-bold text-purple-800">AD</span>
                            <div class="hidden sm:block">
                                <p class="text-sm font-bold text-slate-800">Alex Morgan</p>
                                <p class="text-xs text-slate-500">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

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
                        <table class="w-full min-w-[760px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold">Candidate Name</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Election</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Position</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Application Status</th>
                                    <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-700',
                                    'approved' => 'bg-green-50 text-green-700',
                                    'rejected' => 'bg-red-50 text-red-700'
                                ];
                                $candidates = $pdo->query("SELECT candidates.status AS status, 
                                candidates.id AS candidate_id, 
                                users.name AS username,
                                elections.title AS election,
                                positions.title AS position FROM candidates
                                INNER JOIN users ON candidates.user_id=users.id
                                INNER JOIN elections ON candidates.election_id=elections.id
                                INNER JOIN positions ON candidates.position_id=positions.id
                                LIMIT 4
                                ")->fetchAll();
                                if (empty($candidates)) {
                                    echo '<tr>
                                <td class="px-6 py-4 font-semibold text-slate-600">
                                No Application submitted by candidates
                                </td>
                                </tr>';
                                } else {

                                    foreach ($candidates as $key => $candidate) {
                                        echo ' <tr>
                                    <td class="px-6 py-4 font-semibold text-slate-800">' . $candidate['username'] . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . $candidate['election'] . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . $candidate['position'] . '</td>
                                    <td class="px-6 py-4"><span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ' . $statusColors[$candidate['status']] . '">' . ucfirst($candidate['status']) . '</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                        <a href="#applications"
                                                class="font-semibold text-blue-700 hover:underline">View</a>';
                                        if ($candidate['status'] === 'pending') {
                                            echo '<a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=true"
                                                    class="rounded-lg bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-700">Approve</a>
                                                    <a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=false"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">Reject</a>';
                                        }
                                        echo ' </div>
                                    </td>
                                 </tr>';
                                    }
                                } ?>

                            </tbody>
                        </table>
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
                            <table class="w-full min-w-[780px] text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 font-semibold">Voter Name</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Identity Number</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Election</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Token Status</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Account Status</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php
                                    $statusColors = [
                                        true => 'bg-green-50 text-green-700',
                                        false => 'bg-red-50 text-red-700'
                                    ];
                                    $token = 0;
                                    $token_status = '';
                                    $voters = $pdo->query("SELECT voters.token AS assigned_token,
                                                    users.id AS user_id,
                                                    users.name AS username,
                                                    users.identity_number AS user_identity,
                                                    users.status AS account_status,
                                                    elections.title AS election
                                                FROM users
                                                LEFT JOIN voters ON voters.user_id = users.id
                                                LEFT JOIN elections ON voters.election_id = elections.id
                                                WHERE users.role = 'voter'
                                                LIMIT 4
                                            ")->fetchAll(PDO::FETCH_ASSOC);
                                    if (empty($voters)) {
                                        echo ' <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">No Voters Exists as no election is completed.</td>
                                        </tr>';
                                    } else {
                                        foreach ($voters as $key => $voter) {
                                            if ($voter['assigned_token'] !== null) {
                                                $token = 1;
                                                $token_status = 'Assigned';
                                            } else {
                                                $token = 0;
                                                $token_status = 'Not Assigned';
                                            }
                                            if ($voter['account_status'] == true) {
                                                $btn_text = 'Block';
                                                $status_text = 'Active';
                                            } else {
                                                $btn_text = 'Unblock';
                                                $status_text = 'Blocked';
                                            }

                                            echo '
                                           <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">' . $voter['username'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . $voter['user_identity'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . $voter['election'] . '</td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold 
                                                ' . $statusColors[$token] . '">
                                                ' . $token_status . '</span>
                                        </td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold
                                                ' . $statusColors[$voter['account_status']] . '">
                                                ' . $status_text . '
                                                </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2"><a href="#voters"
                                                    class="font-semibold text-blue-700 hover:underline">View</a>
                                                    <a href="../../app/controllers/actions.php?voter=' . $voter['user_id'] . '"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">'
                                                . $btn_text .
                                                '
                                                    </a>';
                                            if ($token == false) {
                                                echo '<a href="assign_token.php?user=' . $voter['user_id'] . '" class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700">Assign Token</a>';
                                            }
                                            echo '
                                            </div>
                                        </td>
                                    </tr>';
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
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
                            <table class="w-full min-w-[520px] text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 font-semibold">Election Title</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Start Date</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">End Date</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">

                                    <?php
                                    $elections = $pdo->query("SELECT * FROM elections LIMIT 4")->fetchAll();
                                    $statusColors = [
                                        'upcoming' => 'bg-blue-50 text-blue-700',
                                        'ongoing' => 'bg-green-50 text-green-700',
                                        'completed' => 'bg-slate-100 text-slate-600'
                                    ];
                                    foreach ($elections as $key => $election) {
                                        echo '
                                          <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">' . $election['title'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . date('M j, Y - h:i A', strtotime($election['start_date']))  . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . date('M j, Y - h:i A', strtotime($election['end_date'])) . '</td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold 
                                                ' . $statusColors[$election['status']] . '">' . ucfirst($election['status']) . '</span>
                                        </td>
                                    </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <footer
                    class="flex flex-col justify-between gap-2 border-t border-slate-200 py-4 text-xs text-slate-500 sm:flex-row">
                    <p>© 2026 VoteSystem. Admin dashboard preview.</p>
                    <p>Static interface · Sample data only</p>
                </footer>
            </div>
        </main>
    </div>
</body>

</html>