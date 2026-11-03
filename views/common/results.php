<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireLogin();
$portalRole = $_SESSION['user_role'] ?? '';
$pageTitle = 'Election Results';
$pageDescription = 'Review published election results and compare candidates by position.';

$electionId = filter_input(INPUT_GET, 'election', FILTER_VALIDATE_INT);
if (!$electionId) {
    http_response_code(404);
    exit('No election found');
}
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id = :id");
$stmt->execute(['id' => $electionId]);
$election = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$election) {
    http_response_code(404);
    exit('No election found');
}

// Fetch candidates and their results for the election
$stmt = $pdo->prepare("SELECT r.application_id,
    r.position_id,
    r.party_id,
    r.vote_count,
    r.created_at AS result_date,
    a.user_id,
    u.name AS candidate_name,
    p.title AS position_title,
    pa.name AS party_name,
    pa.symbol AS party_symbol
    FROM results r
    JOIN applications a ON r.application_id = a.id
    JOIN users u ON a.user_id = u.id
    JOIN positions p ON r.position_id = p.id
    JOIN parties pa ON r.party_id = pa.id
    WHERE r.election_id = :election_id
    ORDER BY p.id, r.vote_count DESC
");
$stmt->execute(['election_id' => $electionId]);
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resultsByPosition = [];
foreach ($candidates as $candidate) {
    $positionId = $candidate['position_id'];
    if (!isset($resultsByPosition[$positionId])) {
        $resultsByPosition[$positionId] = [
            'position_title' => $candidate['position_title'],
            'candidates' => [],
            'total_votes' => 0,
        ];
    }
    $resultsByPosition[$positionId]['candidates'][] = $candidate;
    $resultsByPosition[$positionId]['total_votes'] += $candidate['vote_count'];
}
// Election totals are stored in election_summary after the election is completed.
$stmt = $pdo->prepare("SELECT * FROM election_summary WHERE election_id=:election_id LIMIT 1");
$stmt->execute(['election_id' => $electionId]);
$summary = $stmt->fetch(PDO::FETCH_ASSOC);

$totalVotesCast = $summary ? (int) $summary['total_votes'] : 0;
$totalVoters = $summary ? (int) $summary['total_voters'] : 0;
$totalCandidates = $summary ? (int) $summary['total_candidates'] : 0;
$rejectedVotes = $summary ? (int) $summary['rejected_votes'] : 0;
$totalParticipatedVoters = max(0, $totalVoters - $rejectedVotes);
$voterTurnoutPercentage = $totalVoters > 0 ? ($totalParticipatedVoters / $totalVoters) * 100 : 0;

// available positions
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_positions FROM election_positions WHERE election_id = :election_id");
$stmt->execute(['election_id' => $electionId]);
$totalPositions = $stmt->fetch(PDO::FETCH_ASSOC)['total_positions'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results | LiveVoting</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <script src="https://kit.fontawesome.com/1359e2cfd9.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/shared/portal_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/shared/portal_header.php'; ?>
            <main class="min-w-0 flex-1 pb-8">
                <div class="mx-auto max-w-7xl space-y-7 px-5 py-7 md:px-10 md:py-10">
                    <!-- Page actions -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <a href="elections.php" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-purple-200 hover:text-purple-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6M9 12h12" />
                            </svg>
                            Back to Elections
                        </a>
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800" aria-label="Print results (placeholder)">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M7 14h10v7H7z" />
                            </svg>
                            Print Results
                        </button>
                    </div>

                    <!-- Election information -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="election-title">
                        <div class="border-l-4 border-orange-500 p-6 md:p-8">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span>Completed</span>
                                    <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700">Results Published</span>
                                </div>
                                <span class="text-xs font-medium text-slate-500">Election ID: <?= htmlspecialchars($electionId); ?> <span class="text-slate-300">(sample)</span></span>
                            </div>
                            <h2 id="election-title" class="mt-5 text-2xl font-bold text-slate-900 md:text-3xl"><?= htmlspecialchars($election['title']); ?></h2>
                            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600 md:text-base"><?= htmlspecialchars($election['description']); ?></p>
                            <div class="mt-6 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2 lg:grid-cols-3">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z" />
                                        </svg></div>
                                    <div>
                                        <p class="text-xs text-slate-500">Election Period</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800"><?=
                                                                                                date('M j', strtotime($election['start_date'])) ?> – <?= date('M j, Y', strtotime($election['end_date'])) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg></div>
                                    <div>
                                        <p class="text-xs text-slate-500">Registered Voters</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800"><?= number_format($totalVoters) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                        </svg></div>
                                    <div>
                                        <p class="text-xs text-slate-500">Results Published</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800"><?= date('F j, Y', strtotime($election['result_declared_at'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Summary statistics -->
                    <section aria-label="Election statistics" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-500">Total Votes Cast</p>
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 5.5 10.5 8 16 2.5M7 13h10M7 17h6M5 3h3m8 0h3a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />
                                    </svg></span>
                            </div>
                            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900"><?= number_format($totalVotesCast) ?></p>
                            <p class="mt-2 text-xs text-slate-500">Of <?= number_format($totalVoters) ?> registered voters</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-500">Voter Turnout</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M7 15l4-4 3 2 5-6" />
                                    </svg></span>
                            </div>
                            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900"><?= number_format($voterTurnoutPercentage, 1) ?><span class="text-xl">%</span></p>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-green-500" style="width: <?= max(0, min(100, $voterTurnoutPercentage)) ?>%;"></div>
                            </div>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-500">Candidates</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg></span>
                            </div>
                            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900"><?= number_format($totalCandidates) ?></p>
                            <p class="mt-2 text-xs text-slate-500">Across <?= number_format($totalPositions) ?> contested positions</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-500">Valid Votes</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                    </svg></span>
                            </div>
                            <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900"><?= number_format($totalVotesCast) ?></p>
                            <p class="mt-2 text-xs text-slate-500"><?= number_format($rejectedVotes) ?> rejected or unanswered ballots</p>
                        </article>
                    </section>

                    <!-- Overall winner / result summary -->
                    <section class="grid gap-5 xl:grid-cols-5">
                        <?php foreach ($resultsByPosition as $position => $results): ?>
                            <?php $winner = $results['candidates'][0];
                            if (!$winner) {
                                continue;
                            }
                            $positionTotal = $results['total_votes'];
                            $winnerVotes = (int) $winner['vote_count'];
                            $voteShare = $positionTotal > 0
                                ? round(($winnerVotes / $positionTotal) * 100, 1)
                                : 0;

                            $initials = implode('', array_map(
                                fn($part) => strtoupper($part[0]),
                                array_slice(explode(' ', trim($winner['candidate_name'])), 0, 2)
                            ));
                            ?>

                            <article class="relative overflow-hidden rounded-2xl border border-purple-200 bg-white p-6 shadow-sm xl:col-span-2 md:p-7">
                                <div class="absolute right-0 top-0 h-28 w-28 rounded-bl-full bg-purple-50"></div>
                                <div class="relative">
                                    <div class="flex items-center gap-2"><span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="m12 2 2.5 6.2 6.7.5-5.1 4.4 1.6 6.5L12 16.1l-5.7 3.5 1.6-6.5-5.1-4.4 6.7-.5L12 2Z" />
                                            </svg>Featured Winner</span><span class="text-xs text-slate-400">Sample result</span></div>
                                    <div class="mt-6 flex items-center gap-4">
                                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-purple-100 text-xl font-bold text-purple-700"><?php echo $initials; ?></div>
                                        <div>
                                            <h2 class="text-xl font-bold text-slate-900"><?php echo $winner['candidate_name']; ?></h2>
                                            <p class="mt-1 text-sm text-slate-500"><?php echo $winner['party_name']; ?> · <?php echo $winner['position_title']; ?></p>
                                        </div>
                                    </div>
                                    <div class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                                        <div>
                                            <p class="text-xs text-slate-500">Votes received</p>
                                            <p class="mt-1 text-2xl font-bold text-slate-900"><?php echo $winnerVotes; ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Share of valid votes</p>
                                            <p class="mt-1 text-2xl font-bold text-purple-700"><?php echo $voteShare; ?>%</p>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-xs leading-5 text-slate-500">Featured winner is for the <?= $winner['position_title']; ?> position only. Each position has its own ranking and winner.</p>
                                </div>
                            </article>
                        <?php endforeach; ?>


                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-3 md:p-7">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Election snapshot</h2>
                                    <p class="mt-1 text-sm text-slate-500">Top candidates by position</p>
                                </div><span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600"><?php echo count($resultsByPosition); ?> positions</span>
                            </div>
                            <?php foreach ($resultsByPosition as $position => $results): ?>

                                <?php $winner = $results['candidates'][0];
                                if (!$winner) {
                                    continue;
                                }
                                $positionTotal = $results['total_votes'];
                                $winnerVotes = (int) $winner['vote_count'];
                                $voteShare = $positionTotal > 0
                                    ? round(($winnerVotes / $positionTotal) * 100, 1)
                                    : 0;

                                $initials = implode('', array_map(
                                    fn($part) => strtoupper($part[0]),
                                    array_slice(explode(' ', trim($winner['candidate_name'])), 0, 2)
                                ));
                                $barColors = ['bg-purple-600', 'bg-slate-950', 'bg-orange-500', 'bg-green-500'];
                                $barColorClass = $barColors[array_rand($barColors)];
                                ?>
                                <div class="mt-6 space-y-6">
                                    <div>
                                        <div class="mb-2 flex items-center justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800"><?= $winner['position_title']; ?></p>
                                                <p class="text-xs text-slate-500"><?= $winner['candidate_name'] ?> · <?= $winner['party_name']; ?></p>
                                            </div>
                                            <p class="text-sm font-bold text-slate-900"><?= $winner['vote_count'] ?> votes <span class="font-medium text-slate-500">(<?= $voteShare ?>%)</span></p>
                                        </div>
                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full <?= $barColorClass ?>" style="width: <?= max(0, min(100, (float) $voteShare)) ?>%;"></div>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                            <p class="mt-6 rounded-xl bg-slate-50 p-3 text-xs leading-5 text-slate-600">Vote shares are calculated from valid votes for the relevant position. Candidate totals can differ between positions.</p>
                        </article>
                    </section>

                    <!-- Results by position -->
                    <section aria-labelledby="position-results-title" class="space-y-5">
                        <div>
                            <h2 id="position-results-title" class="text-xl font-bold text-slate-900 md:text-2xl">
                                Results by Position
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Rankings and vote percentages are calculated separately for each position.
                            </p>
                        </div>

                        <?php foreach ($resultsByPosition as $positionId => $positionResult):
                            $positionTotal = (int) $positionResult['total_votes'];
                            $candidateCount = count($positionResult['candidates']);
                            $winner = $positionResult['candidates'][0] ?? null;
                        ?>
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-5 md:p-6">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-purple-700">
                                            Position <?= htmlspecialchars((string) $positionId) ?>
                                        </p>
                                        <h3 class="mt-1 text-lg font-bold text-slate-900">
                                            <?= htmlspecialchars($positionResult['position_title']) ?>
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500">
                                            <?= $candidateCount ?> candidates ·
                                            <?= number_format($positionTotal) ?> votes for this position
                                        </p>
                                    </div>

                                    <?php if ($winner): ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Winner declared
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[900px] text-left text-sm">
                                        <!-- Keep your existing <thead> here -->

                                        <tbody class="divide-y divide-slate-100">
                                            <?php foreach ($positionResult['candidates'] as $index => $candidate):
                                                $votes = (int) $candidate['vote_count'];
                                                $share = $positionTotal > 0
                                                    ? round(($votes / $positionTotal) * 100, 1)
                                                    : 0;

                                                $isWinner = $index === 0;
                                                $nameParts = preg_split('/\s+/', trim($candidate['candidate_name']));
                                                $initials = '';

                                                foreach (array_slice($nameParts, 0, 2) as $part) {
                                                    $initials .= strtoupper(substr($part, 0, 1));
                                                }
                                            ?>
                                                <tr class="<?= $isWinner ? 'bg-purple-50/50' : '' ?>">
                                                    <td class="px-5 py-4">
                                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full <?= $isWinner ? 'bg-purple-700 text-white' : 'bg-slate-100 text-slate-700' ?> text-xs font-bold">
                                                            <?= $index + 1 ?>
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 font-bold text-purple-700">
                                                                <?= htmlspecialchars($initials) ?>
                                                            </span>
                                                            <div>
                                                                <p class="font-semibold text-slate-900">
                                                                    <?= htmlspecialchars($candidate['candidate_name']) ?>
                                                                </p>
                                                                <p class="text-xs text-slate-500">
                                                                    Candidate ID: <?= (int) $candidate['application_id'] ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-4 font-medium text-slate-700">
                                                        <?= htmlspecialchars($candidate['party_name']) ?>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <i class="fas fa-<?= htmlspecialchars($candidate['party_symbol'] ?? 'question') ?> text-xl text-slate-500 "></i>
                                                    </td>

                                                    <td class="px-5 py-4 font-bold tabular-nums text-slate-900">
                                                        <?= number_format($votes) ?>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <span class="w-12 font-semibold text-slate-700">
                                                                <?= number_format($share, 1) ?>%
                                                            </span>
                                                            <div class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                                                <div
                                                                    class="h-full rounded-full <?= $isWinner ? 'bg-purple-600' : 'bg-blue-500' ?>"
                                                                    style="width: <?= max(0, min(100, $share)) ?>%;"></div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <?php if ($isWinner): ?>
                                                            <span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                                                Winner
                                                            </span>
                                                        <?php elseif ($index === 1): ?>
                                                            <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                                                Runner-up
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                                                Participated
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="border-t border-slate-100 bg-slate-50 px-5 py-3 text-xs text-slate-500">
                                    Total recorded votes for this position: <?= number_format($positionTotal) ?>.
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </section>

                    <!-- Analytics -->
                    <section class="grid gap-5 xl:grid-cols-2" aria-label="Election analytics">
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Ballot summary</h2>
                                <p class="mt-1 text-sm text-slate-500">Overall voter participation</p>
                            </div>
                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Registered voters</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900"><?= number_format($totalVoters) ?></p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Votes cast</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900"><?= number_format($totalVotesCast) ?></p>
                                </div>
                                <div class="rounded-xl bg-green-50 p-4">
                                    <p class="text-sm text-green-700">Valid ballots</p>
                                    <p class="mt-2 text-2xl font-bold text-green-800">982</p>
                                </div>
                                <div class="rounded-xl bg-orange-50 p-4">
                                    <p class="text-sm text-orange-700">Invalid / rejected</p>
                                    <p class="mt-2 text-2xl font-bold text-orange-800">18</p>
                                </div>
                            </div>
                            <div class="mt-5">
                                <div class="mb-2 flex justify-between text-xs font-medium text-slate-600"><span>Voter turnout</span><span><?= $voterTurnoutPercentage ?>%</span></div>
                                <div class="flex h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full w-4/5 bg-purple-600"></div>
                                    <div class="h-full w-1/5 bg-slate-200"></div>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">250 registered voters did not cast a ballot.</p>
                            </div>
                        </article>
                       
                    </section>

                    <!-- Official notice -->
                    <section class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 md:p-5">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.7 17.1A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                        </svg>
                        <div>
                            <h2 class="text-sm font-semibold text-amber-900">Official results notice</h2>
                            <p class="mt-1 text-sm leading-6 text-amber-800">
                                The results displayed on this page are for demonstration purposes only. They do not represent actual election outcomes. For official results, please refer to the relevant election authority or official channels.
                            </p>
                        </div>
                    </section>

                    <footer class="border-t border-slate-200 pt-6 text-center text-sm text-slate-500">© 2026 LiveVoting. Online Voting System.</footer>
                </div>
            </main>
        </div>
    </div>
</body>

</html>