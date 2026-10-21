<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

$stmt = $pdo->prepare("SELECT * FROM `elections` ORDER BY start_date ASC");
$stmt->execute();
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$electionCounts = ['upcoming' => 0, 'ongoing' => 0, 'completed' => 0];
foreach ($elections as $election) {
    if (isset($electionCounts[$election['status']])) {
        $electionCounts[$election['status']]++;
    }
}

$statusStyles = [
    'upcoming' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
    'ongoing' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    'completed' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
];
$escape = static fn($value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
$formatDate = static function ($date): string {
    $timestamp = strtotime($date);
    return $timestamp === false ? 'Date unavailable' : date('M j, Y', $timestamp);
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elections | Online Voting System</title>
    <link rel="stylesheet" href="../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <?php include '../includes/candidate/candidate_header.php'; ?>
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include '../includes/candidate/candidate_sidebar.php'; ?>

        <main class="min-w-0 flex-1 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">
            <div class="mx-auto max-w-6xl">
                <header class="mb-8 rounded-3xl bg-gradient-to-br from-purple-800 via-purple-700 to-indigo-700 px-6 py-8 text-white shadow-lg shadow-purple-900/10 sm:px-9 sm:py-10">
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-purple-200">Candidate Portal</p>
                    <div class="mt-3 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                        <div>
                            <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Elections</h1>
                            <p class="mt-3 max-w-2xl leading-7 text-purple-100">
                                Explore election schedules and check the current status of each election.
                            </p>
                        </div>
                        <p class="shrink-0 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold">
                            <?= count($elections) ?> <?= count($elections) === 1 ? 'election' : 'elections' ?> listed
                        </p>
                    </div>
                </header>

                <section aria-label="Election status summary" class="mb-8 grid gap-4 sm:grid-cols-3">
                    <?php foreach ([
                        ['Upcoming', $electionCounts['upcoming'], 'bg-blue-100 text-blue-700'],
                        ['Ongoing', $electionCounts['ongoing'], 'bg-emerald-100 text-emerald-700'],
                        ['Completed', $electionCounts['completed'], 'bg-slate-100 text-slate-700'],
                    ] as [$label, $count, $colorClass]): ?>
                        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl text-lg font-extrabold <?= $colorClass ?>">
                                <?= $count ?>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-500"><?= $label ?></p>
                                <p class="font-bold text-slate-900"><?= $count ?> <?= $count === 1 ? 'election' : 'elections' ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <?php if (!$elections): ?>
                    <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-700">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" />
                            </svg>
                        </span>
                        <h2 class="mt-5 text-lg font-bold text-slate-900">No elections available</h2>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            There are no elections to display right now. Please check back later for updates.
                        </p>
                    </section>
                <?php else: ?>
                    <section aria-label="All elections">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-slate-900">All elections</h2>
                            <p class="mt-1 text-sm text-slate-500">Browse election details and important dates.</p>
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">
                            <?php foreach ($elections as $election):
                                $status = $election['status'] ?? '';
                                $statusClass = $statusStyles[$status] ?? 'bg-gray-100 text-gray-700 ring-gray-500/20';
                            ?>
                                <article class="group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-purple-200 hover:shadow-lg sm:p-7">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 16 2 2 4-4" />
                                            </svg>
                                        </div>
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold capitalize ring-1 ring-inset <?= $statusClass ?>">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            <?= ucfirst($escape($status ?: 'unknown')) ?>
                                        </span>
                                    </div>

                                    <h3 class="mt-5 text-xl font-bold leading-snug text-slate-900">
                                        <?= $escape($election['title'] ?? 'Untitled election') ?>
                                    </h3>
                                    <p class="mt-2 min-h-12 whitespace-pre-line text-sm leading-6 text-slate-600">
                                        <?= $escape($election['description'] ?? 'No election description is available.') ?>
                                    </p>

                                    <div class="mt-5 grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Starts</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700"><?= $escape($formatDate($election['start_date'] ?? '')) ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Ends</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700"><?= $escape($formatDate($election['end_date'] ?? '')) ?></p>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex-1"></div>
                                    <div class="mt-6 border-t border-slate-100 pt-5">
                                        <?php if ($status === 'upcoming'): ?>
                                            <a href="../candidate/registration.php" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-purple-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                                                Apply for candidacy
                                                <span aria-hidden="true">&rarr;</span>
                                            </a>
                                        <?php elseif ($status === 'completed'): ?>
                                            <a href="results.php" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-purple-200 bg-purple-50 px-4 py-3 text-sm font-bold text-purple-800 transition hover:bg-purple-100 focus:outline-none focus:ring-4 focus:ring-purple-100">
                                                View results
                                                <span aria-hidden="true">&rarr;</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                                                Election in progress
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
