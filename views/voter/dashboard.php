<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = explode(' ', $user['name'], 2);
$firstname = $username[0];
$lastname = $username[1];
$username = ucfirst($firstname) . ' ' . ucfirst($lastname);

$stmt = $pdo->prepare("SELECT voters.token AS token_number,
                    voters.has_voted AS token_status,
                    elections.title AS election,
                    elections.start_date AS election_start_date,
                    elections.end_date AS election_end_date,
                    elections.status AS election_status
                    FROM voters
                    INNER JOIN elections ON voters.election_id=elections.id
                    WHERE user_id=?
                    ");
$stmt->execute([$_SESSION['user_id']]);
$voter_data = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT
                voters.has_voted AS token_status,
                elections.title AS election
                FROM voters
                INNER JOIN elections
                    ON voters.election_id = elections.id
                WHERE voters.user_id = ?
                AND elections.status = 'completed'
                ORDER BY elections.end_date DESC
                ");

$stmt->execute([$_SESSION['user_id']]);
$votingHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusColors = [
    true => 'bg-green-50 text-green-700',
    false => 'bg-orange-50 text-orange-700'
];
$portalRole = 'voter';
$pageTitle = 'Voter Dashboard';
$pageDescription = 'Track your election participation, voting token, and recent voting history.';
// $pageDescription = 'انتخابات میں اپنی شرکت، ووٹنگ ٹوکن اور حالیہ ووٹنگ کی تاریخ کو ٹریک کریں۔';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f8f7fc">
    <title>Voter Dashboard | Ballot</title>
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php include_once APP_ROOT . '/includes/voter/voter_sidebar.php' ?>
        <div class="min-w-0 flex-1">
            <?php include_once APP_ROOT . '/includes/voter/voter_header.php' ?>

            <main id="dashboard" class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10 lg:px-10">
                <section class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="mb-2 text-sm font-medium text-purple-700"><?= date('l, F d, Y') ?></p>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Welcome back, <?= ucfirst($firstname) ?>!</h2>
                        <p class="mt-2 text-sm text-slate-500 sm:text-base">Here’s what’s happening with your vote today.</p>
                    </div>
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Account verified
                    </span>
                </section>

                <section id="profile" aria-labelledby="voter-info-title" class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 0 0-16 0m8-10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 id="voter-info-title" class="font-semibold text-slate-900">Voter information</h2>
                                <p class="text-xs text-slate-500">Your registered voting details</p>
                            </div>
                        </div>
                        <a href="#profile" class="text-sm font-semibold text-purple-700 transition hover:text-purple-900">My profile</a>
                    </div>
                    <div class="grid gap-5 border-t border-slate-100 pt-5 sm:grid-cols-3 sm:gap-4">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Full name</p>
                            <p class="mt-1.5 text-sm font-semibold text-slate-800"><?= $username ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Email address</p>
                            <p class="mt-1.5 break-all text-sm font-semibold text-slate-800"><?= $user['email'] ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Identity Number</p>
                            <p class="mt-1.5 font-mono text-sm font-semibold tracking-wider text-purple-700"><?= $user['identity_number'] ?></p>
                        </div>
                    </div>
                </section>

                <div class="grid gap-6 xl:grid-cols-5">

                    <section id="elections" aria-labelledby="election-title" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-3">
                        <?php
                        if (empty($voter_data)) {
                            echo '
                         <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">Token Assigned for</p>
                                <h2 id="election-title" class="mt-1 text-lg font-bold text-slate-900">No token number is assigned for casting a vote</h2>
                            </div>  
                        </div>';
                        } else {
                            echo '  <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">Token Assigned for</p>
                                <h2 id="election-title" class="mt-1 text-lg font-bold text-slate-900">' . $voter_data['election'] . '</h2>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                ' . ucfirst($voter_data['election_status']) . '
                            </span>
                        </div>
                        <p class="max-w-2xl text-sm leading-6 text-slate-500">Choose the representatives who will help guide our community. Review the candidates and make your voice heard before polls close.</p>
                        <div class="mt-5 grid gap-4 rounded-xl bg-slate-50 p-4 sm:grid-cols-2">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-purple-700 shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs text-slate-400">Voting starts</p>
                                    <p class="text-sm font-semibold text-slate-700">'
                                . date('F d, Y - h:i A', strtotime($voter_data['election_start_date'])) .
                                '</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-orange-500 shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-xs text-slate-400">Voting ends</p>
                                    <p class="text-sm font-semibold text-slate-700">'
                                . date('F d, Y - h:i A', strtotime($voter_data['election_end_date'])) .
                                '</p>
                                </div>
                            </div>
                        </div>';
                        } ?>
                    </section>

                    <section aria-labelledby="token-title"
                        class="flex flex-col rounded-2xl border border-purple-100 bg-purple-50/70 p-5 shadow-sm sm:p-6 xl:col-span-2">
                        <?php
                        if (empty($voter_data)) {
                            echo ' <h2 id="token-title" class="text-lg font-bold text-slate-900">
                            Your Voting Token
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            No token number assigned for casting a vote
                        </p>';
                        } else {
                            echo '  <span class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-purple-700 shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 14a4 4 0 1 1 3.9-4.9L21 9v4h-3v3h-4v-3h-3.1A4 4 0 0 1 7 14Z" />
                                <circle cx="7" cy="10" r="1" />
                            </svg>
                        </span>

                        <h2 id="token-title" class="text-lg font-bold text-slate-900">
                            Your Voting Token
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Your assigned token for this election.
                        </p>

                        <div class="mt-5 rounded-xl border border-purple-100 bg-white p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Token Number
                            </p>
                            <p class="mt-2 font-mono text-2xl font-bold tracking-widest text-purple-700">
                                 '
                                . $voter_data['token_number'] .
                                '
                            </p>
                        </div>

                        <div class="mt-5 flex items-start gap-3 rounded-xl bg-white p-4">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m5 12 4 4L19 6" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Token Assigned
                                </p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    A token has been assigned for this sample election.
                                </p>
                            </div>
                        </div>';
                            if ($voter_data['token_status'] == true) {
                                echo '<span
                            class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-x bg-gray-100 px-5 py-3 text-sm font-semibold text-black shadow-sm transition focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                         Vote Casted
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </span>';
                            } else {
                                echo '<a href="./cast_vote.php?token=' . $voter_data['token_number'] . '"
                            class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-purple-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cast Vote
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </a>';
                            }

                            echo '
                        <p class="mt-3 text-center text-xs text-slate-500">
                            This token number can be used only once.
                        </p>';
                        }
                        ?>
                    </section>
                </div>

                <section aria-labelledby="history-title" class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between gap-4 px-5 py-5 sm:px-6">
                        <div>
                            <h2 id="history-title" class="font-bold text-slate-900">Voting history</h2>
                            <p class="mt-1 text-sm text-slate-500">A record of your past participation</p>
                        </div>
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                            <?= count($votingHistory) ?> elections</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[520px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                                <tr>
                                    <th scope="col" class="px-5 py-3 font-semibold sm:px-6">Election</th>
                                    <th scope="col" class="px-5 py-3 font-semibold">Date</th>
                                    <th scope="col" class="px-5 py-3 font-semibold sm:px-6">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php

                                if (empty($votingHistory)) {
                                    echo '<tr>
                                    <td class="px-5 py-4 font-medium text-slate-700 sm:px-6">No elections found in history</td>
                                </tr>';
                                } else {
                                    echo '  <tr>
                                    <td class="px-5 py-4 font-medium text-slate-700 sm:px-6">
                                    ' . $votingHistory['election'] . '</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                        </td>
                                    <td class="px-5 py-4 sm:px-6"><span class="inline-flex rounded-full'
                                        . $statusColors[$votingHistory['token_status']] . ' px-2.5 py-1 text-xs font-semibold">'
                                        . $votingHistory['token_status'] == true ? 'Voted' : 'Not Participated' .
                                        '</span></td>
                                </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <div class="mt-4 hidden rounded-2xl bg-purple-50 p-4 md:block">
                    <p class="text-sm font-semibold text-slate-800">Your voice matters.</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Every vote helps shape the future of your community.</p>
                </div>
                <footer class="py-7 text-center text-xs text-slate-400">
                    Secure, fair, and accessible voting for everyone.
                </footer>
            </main>
        </div>
    </div>

</body>

</html>