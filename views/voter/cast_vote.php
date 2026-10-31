<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/views/auth/check_auth.php';

if (isset($_GET['token'])) {
    $stmt = $pdo->prepare("SELECT * FROM voters WHERE token = ?");
    $stmt->execute([$_GET['token']]);
    $voter = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voter || $_SESSION['user_id'] != $voter['user_id']) {
        http_response_code(403);
        exit('Access denied.');
    }
}
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id=?");
$stmt->execute([$voter['election_id']]);
$election = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetching candidates data
$stmt = $pdo->prepare("SELECT applications.id AS candidate_id,
        users.name AS candidate_name,
        parties.name AS party,
        positions.title AS position,
        positions.id AS position_id,
        elections.id AS election
        FROM applications
        INNER JOIN users ON applications.user_id=users.id
        INNER JOIN parties ON applications.party_id=parties.id
        INNER JOIN positions ON applications.position_id=positions.id
        INNER JOIN elections ON applications.election_id=elections.id
        WHERE applications.election_id=? AND applications.status=?
");
$stmt->execute([$election['id'], 'approved']);
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Casting Vote method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredToken = trim($_POST['entered_token'] ?? '');
    $electionId = filter_input(INPUT_POST, 'election_id', FILTER_VALIDATE_INT);
    $selections = $_POST['candidate'] ?? [];

    if (!$electionId || !is_array($selections) || empty($selections)) {
        http_response_code(400);
        exit('Invalid vote submission.');
    }

    try {
        $pdo->beginTransaction();

        // Lock the voter's election record to prevent duplicate submissions.
        $stmt = $pdo->prepare("
            SELECT id, has_voted
            FROM voters
            WHERE token = ?
              AND user_id = ?
              AND election_id = ?
            LIMIT 1
            FOR UPDATE
        ");
        $stmt->execute([
            $enteredToken,
            $_SESSION['user_id'],
            $electionId
        ]);

        $voter = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voter || (int) $voter['has_voted'] === 1) {
            throw new RuntimeException('Token is invalid or has already been used.');
        }

        // Confirm the election is currently ongoing.
        $stmt = $pdo->prepare("
            SELECT id
            FROM elections
            WHERE id = ?
              AND status = 'ongoing'
              AND NOW() BETWEEN start_date AND end_date
            LIMIT 1
        ");
        $stmt->execute([$electionId]);

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new RuntimeException('This election is not currently open.');
        }

        // Get the positions that have approved candidates in this election.
        // This must match the positions shown by the voting page.
        $stmt = $pdo->prepare("
            SELECT DISTINCT position_id
            FROM applications
            WHERE election_id = ?
              AND status = 'approved'
        ");
        $stmt->execute([$electionId]);

        $requiredPositions = array_map(
            'intval',
            $stmt->fetchAll(PDO::FETCH_COLUMN)
        );

        // Require exactly one submitted candidate for each enabled position.
        $submittedPositions = array_map('intval', array_keys($selections));
        sort($requiredPositions);
        sort($submittedPositions);
       
        if ($requiredPositions !== $submittedPositions) {
            throw new RuntimeException('Please select one candidate for every position.');
        }

        foreach ($selections as $positionId => $applicationId) {
            $positionId = filter_var($positionId, FILTER_VALIDATE_INT);
            $applicationId = filter_var($applicationId, FILTER_VALIDATE_INT);

            if (!$positionId || !$applicationId) {
                throw new RuntimeException('Invalid candidate selection.');
            }

            // Confirm the candidate is approved and belongs to this
            // election and the submitted position.
            $stmt = $pdo->prepare("
                SELECT id
                FROM applications
                WHERE id = ?
                  AND election_id = ?
                  AND position_id = ?
                  AND status = 'approved'
                LIMIT 1
            ");
            $stmt->execute([
                $applicationId,
                $electionId,
                $positionId
            ]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new RuntimeException('A selected candidate is not valid for this election.');
            }

            // Store the candidate choice without storing the voter's ID.
            $stmt = $pdo->prepare("
                INSERT INTO votes (election_id, application_id, position_id)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $electionId,
                $applicationId,
                $positionId
            ]);

            // Record participation separately from the candidate choice.
            $stmt = $pdo->prepare("
                INSERT INTO voter_participation
                    (voter_id, election_id, position_id)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $voter['id'],
                $electionId,
                $positionId
            ]);
        }

        // Mark the voter as having submitted their ballot.
        $stmt = $pdo->prepare("
            UPDATE voters
            SET has_voted = 1
            WHERE id = ?
        ");
        $stmt->execute([$voter['id']]);

        $pdo->commit();

        header('Location: ' . app_url('views/voter/dashboard.php'));
        exit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        http_response_code(400);
        exit(htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}
?>
<?php
$portalRole = 'voter';
$pageTitle = 'Cast Your Vote';
$pageDescription = 'Review the candidates and securely submit your choice for this election.';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote | LiveVoting</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="../../public/css/app.css">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        <?php include_once APP_ROOT . '/includes/voter/voter_sidebar.php' ?>
        <div class="min-w-0 flex-1">
            <?php include_once APP_ROOT . '/includes/voter/voter_header.php' ?>

            <!-- Main Content -->
            <main class="min-w-0 flex-1 pb-8">

                <!-- Page Content -->
                <div class="mx-auto max-w-7xl space-y-7 px-5 py-7 md:px-10 md:py-10">

                    <!-- Election Information -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-l-4 border-orange-500 p-6 md:p-8">

                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                    <?= ucfirst($election['status']) ?>
                                </span>

                                <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                    <?= date('Y', strtotime($election['start_date'])) ?> Election
                                </span>
                            </div>

                            <h2 class="mt-5 text-2xl font-bold text-slate-900 md:text-3xl">
                                <?= $election['title'] ?>
                            </h2>

                            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600 md:text-base">
                                Review the available candidates and their party affiliations
                                carefully. Select your preferred candidate and submit your vote.
                            </p>

                            <div class="mt-6 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2 lg:grid-cols-3">

                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Election Starts</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            <?= date('M j, Y - h:i A', strtotime($election['start_date'])) ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Election Ends</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            <?= date('M j, Y - h:i A', strtotime($election['end_date'])) ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5 2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Voting Status</p>
                                        <p class="mt-1 text-sm font-semibold text-green-700">
                                            <?= $voter['has_voted'] == true ? 'Vote Casted' : 'Not Voted' ?>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- Voting Instructions -->
                    <section class="rounded-xl border border-blue-100 bg-blue-50 p-4 md:p-5">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 16v-4m0-4h.01M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                            </svg>

                            <div>
                                <h3 class="font-semibold text-blue-900">Before you vote</h3>
                                <p class="mt-1 text-sm leading-6 text-blue-800">
                                    Check each candidate's name, party, and position.
                                    Select your preferred candidate, then review your choice
                                    before submitting. Your vote should reflect your own decision.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Candidates Section -->
                    <section>
                        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 md:text-2xl">
                                    Available Candidates
                                </h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Select one candidate for each position.
                                </p>
                            </div>

                            <span class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-600 shadow-sm ring-1 ring-slate-200">
                                <?= count($candidates) ?> Candidates
                            </span>
                        </div>

                        <?php
                        if ($voter['has_voted'] == true) {
                            echo 'Already vote casted';
                        } elseif ($election['status'] !== 'ongoing') {
                            echo 'Election not started yet, will be live soon.';
                        } elseif (empty($candidates)) {
                            echo 'No candidates registered available for this election.';
                        } else {
                            // Group candidates by position
                            $candidatesByPosition = [];

                            foreach ($candidates as $candidate) {
                                $positionId = (int) $candidate['position_id'];
                                $candidatesByPosition[$positionId][] = $candidate;
                            }
                        ?>

                            <form
                                action="cast_vote.php?token=<?= urlencode($voter['token']) ?>"
                                method="POST">
                                <input
                                    type="hidden"
                                    name="election_id"
                                    value="<?= (int) $election['id'] ?>">

                                <?php foreach ($candidatesByPosition as $positionId => $positionCandidates): ?>
                                    <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="mb-4">
                                            <h3 class="text-lg font-bold text-slate-900">
                                                <?= htmlspecialchars($positionCandidates[0]['position'], ENT_QUOTES, 'UTF-8') ?>
                                            </h3>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Select one candidate for this position.
                                            </p>
                                        </div>

                                        <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
                                            <?php foreach ($positionCandidates as $candidate): ?>
                                                <?php
                                                $nameParts = preg_split(
                                                    '/\s+/',
                                                    trim($candidate['candidate_name']),
                                                    2
                                                );

                                                $firstName = $nameParts[0] ?? '';
                                                $lastName = $nameParts[1] ?? '';
                                                $initials = strtoupper(
                                                    mb_substr($firstName, 0, 1) .
                                                        mb_substr($lastName, 0, 1)
                                                );
                                                ?>

                                                <label class="group relative block cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="candidate[<?= (int) $positionId ?>]"
                                                        value="<?= (int) $candidate['candidate_id'] ?>"
                                                        required
                                                        class="peer sr-only">

                                                    <div class="h-full rounded-2xl border-2 border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-purple-300 hover:shadow-md peer-checked:border-purple-600 peer-checked:bg-purple-50/40 peer-focus-visible:ring-4 peer-focus-visible:ring-purple-200">

                                                        <div class="flex items-start justify-between gap-3">
                                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-xl font-bold text-purple-700">
                                                                <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?>
                                                            </div>

                                                            <div class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 text-white transition peer-checked:border-purple-700 peer-checked:bg-purple-700">
                                                                <svg
                                                                    class="h-4 w-4"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="3"
                                                                        d="m5 12 4 4L19 6" />
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-purple-700">
                                                            Candidate
                                                        </p>

                                                        <h4 class="mt-1 text-lg font-bold text-slate-900">
                                                            <?= htmlspecialchars($candidate['candidate_name'], ENT_QUOTES, 'UTF-8') ?>
                                                        </h4>

                                                        <div class="mt-4 space-y-3 border-t border-slate-100 pt-4">
                                                            <div>
                                                                <p class="text-xs text-slate-500">Political Party</p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                                                    <?= htmlspecialchars($candidate['party'], ENT_QUOTES, 'UTF-8') ?>
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <p class="text-xs text-slate-500">Position</p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                                                    <?= htmlspecialchars($candidate['position'], ENT_QUOTES, 'UTF-8') ?>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-5 flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 peer-checked:bg-purple-100 peer-checked:text-purple-800">
                                                            <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                                                            Select this candidate
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </section>
                                <?php endforeach; ?>

                                <!-- Submission Section -->
                                <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="flex items-start gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v4m0 4h.01M10.3 3.9 2.7 17.1A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                                                </svg>
                                            </div>

                                            <div>
                                                <h3 class="font-semibold text-slate-900">
                                                    Review before submitting
                                                </h3>
                                                <p class="mt-1 max-w-xl text-sm leading-5 text-slate-500">
                                                    Make sure you have selected one candidate for each position.
                                                    Your selections will be submitted together.
                                                </p>
                                            </div>
                                        </div>

                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-purple-700 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200 sm:w-auto">
                                            Submit Vote

                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 12h14m-7-7 7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        <?php } ?>
                    </section>

                    <!-- Security Notice -->
                    <section class="flex items-start gap-3 rounded-xl border border-green-100 bg-green-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Zm3-10V6a3 3 0 0 1 6 0v3" />
                        </svg>

                        <div>
                            <h3 class="text-sm font-semibold text-green-900">
                                Secure voting
                            </h3>
                            <p class="mt-1 text-sm leading-5 text-green-800">
                                Keep your voting credentials private. The server must verify
                                your eligibility and election status before recording a vote.
                            </p>
                        </div>
                    </section>

                    <!-- Footer -->
                    <footer class="border-t border-slate-200 pt-6 text-center text-sm text-slate-500">
                        © 2026 LiveVoting. Online Voting System.
                    </footer>

                </div>
            </main>
        </div>

        <script>
            const form = document.querySelector('form[action*="cast_vote.php"]');

            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const token = prompt("Enter your token number to cast vote");

                    if (token === null || token.trim() === "") {
                        alert("Token is required");
                        return;
                    }

                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "entered_token";
                    input.value = token.trim();
                    form.appendChild(input);
                    form.submit();
                });
            }
        </script>
</body>

</html>