<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('candidate');

$userId = (int) $_SESSION['user_id'];
$message = '';

$stmt = $pdo->prepare("
    SELECT applications.*, elections.status AS election_status
    FROM applications
    INNER JOIN elections ON elections.id = applications.election_id
    WHERE applications.user_id = ?
    ORDER BY applications.created_at DESC
");
$stmt->execute([$userId]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 1
    FROM applications
    INNER JOIN elections ON elections.id = applications.election_id
    WHERE applications.user_id = ?
      AND applications.status IN ('pending', 'approved')
      AND elections.status <> 'completed'
    LIMIT 1
");
$stmt->execute([$userId]);
$hasActiveApplication = (bool) $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$electionsStmt = $pdo->prepare("
    SELECT elections.*
    FROM elections
    WHERE elections.status = 'upcoming'
      AND NOT EXISTS (
          SELECT 1 FROM applications
          WHERE applications.election_id = elections.id
            AND applications.user_id = ?
      )
    ORDER BY elections.start_date ASC
");
$electionsStmt->execute([$userId]);
$elections = $electionsStmt->fetchAll(PDO::FETCH_ASSOC);

$positions = [];
$parties = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    if ($hasActiveApplication) {
        $message = 'You already have an active application. You can apply again after it is rejected or its election is completed.';
    } else {
        $electionId = (int) ($_POST['election_id'] ?? 0);
        $positionId = (int) ($_POST['position'] ?? 0);
        $partyId = (int) ($_POST['party'] ?? 0);

        $stmt = $pdo->prepare("SELECT id FROM elections WHERE id = ? AND status = 'upcoming'");
        $stmt->execute([$electionId]);
        $validElection = $stmt->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT 1 FROM election_positions
            WHERE election_id = ? AND position_id = ?
        ");
        $stmt->execute([$electionId, $positionId]);
        $validPosition = (bool) $stmt->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT 1 FROM applications
            WHERE user_id = ? AND election_id = ?
        ");
        $stmt->execute([$userId, $electionId]);
        $alreadyApplied = (bool) $stmt->fetchColumn();

        if (!$validElection || !$validPosition || $alreadyApplied) {
            $message = 'Please select a valid upcoming election and position. You can only submit once for each election.';
        } else {
            $partyValue = $partyId > 0 ? $partyId : null;
            if ($partyValue !== null) {
                $stmt = $pdo->prepare("
                    SELECT 1
                    FROM election_parties
                    WHERE election_id = ? AND party_id = ?
                ");
                $stmt->execute([$electionId, $partyValue]);
                if (!$stmt->fetchColumn()) {
                    $message = 'Please select a party that belongs to this election, or leave the party empty.';
                }
            }
            if ($message === '') {
            $stmt = $pdo->prepare("
                INSERT INTO applications
                (user_id, address, city, state, country, postal_code, election_id, position_id,
                 constituency_area, party_id, qualifications, experience, manifesto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                trim($_POST['address'] ?? ''),
                trim($_POST['city'] ?? ''),
                trim($_POST['province'] ?? ''),
                trim($_POST['country'] ?? ''),
                trim($_POST['postal_code'] ?? ''),
                $electionId,
                $positionId,
                trim($_POST['constituency'] ?? ''),
                $partyValue,
                trim($_POST['qualification'] ?? ''),
                trim($_POST['experience'] ?? ''),
                trim($_POST['manifesto'] ?? '')
            ]);
            header('Location: ' . app_url('views/candidate/registration.php') . '?message=' . rawurlencode('Application submitted successfully.'));
            exit;
            }
        }
    }
}

?>
<?php
$portalRole = 'candidate';
$pageTitle = $applications ? 'Submitted Applications' : 'Candidate Registration';
$pageDescription = $applications
    ? 'Review the applications you submitted for elections.'
    : 'Complete the information required for your candidate application.';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Candidate Registration | Online Voting System</title>

    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('public/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="flex min-h-[calc(100vh-65px)] flex-col md:flex-row">
        <?php include APP_ROOT . '/includes/candidate/candidate_sidebar.php'; ?>
        <div class="min-w-0 flex-1">
            <?php include APP_ROOT . '/includes/candidate/candidate_header.php'; ?>
            <main class="min-w-0 px-4 py-8 sm:px-6 sm:py-10 lg:px-10">

                <?php if (!empty($message)): ?>
                    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php
                if (!empty($applications)) {
                    foreach ($applications as $submittedApplication) {
                    $applicationCandidateId = (int) $submittedApplication['id'];
                    $showReviewActions = false;
                    include APP_ROOT . '/includes/shared/submitted_application.php';
                    }
                }
                if (!$hasActiveApplication && !empty($elections)) {
                    $data;
                    $elections;
                    $positions;
                    $parties;
                    include APP_ROOT . '/includes/candidate/registration_form.php';
                } elseif (!$hasActiveApplication && empty($elections)) {
                    echo '<div class="mx-auto max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">
                        No upcoming elections are currently available for a new application.
                    </div>';
                }
                ?>

            </main>
        </div>
    </div>


    <footer class="border-t border-slate-200 bg-white px-5 py-6 text-center text-sm text-slate-500">
        &copy; <span id="year"></span> Online Voting System. All rights reserved.
    </footer>
    <script src="<?= htmlspecialchars(app_url('public/js/candidate-registration.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>