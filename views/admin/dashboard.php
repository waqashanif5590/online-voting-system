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
        <?php $portalRole = 'admin'; include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
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
    </div>
</body>

</html>