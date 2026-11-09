<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/helpers/security.php';
require_once APP_ROOT . '/includes/shared/alert.php';
require_once APP_ROOT . '/app/config/database.php';
requireRole('admin');
checkCsrfToken(); // also makes sure this page is only used by a POST form

$action = $_POST['action'] ?? '';
$electionId = filter_input(INPUT_POST, 'election_id', FILTER_VALIDATE_INT);
$backUrl = app_url('views/common/elections.php');

if (!$electionId) {
    http_response_code(400);
    exit('Invalid election.');
}

$stmt = $pdo->prepare('SELECT id, status, start_date, end_date, result_declared_at FROM elections WHERE id = ?');
$stmt->execute([$electionId]);
$election = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$election) {
    http_response_code(404);
    exit('Election not found');
}

// Stop the election now and save the final results.
if ($action === 'close') {
    if ($election['status'] !== 'ongoing' || $election['result_declared_at'] !== null) {
        setFlashAlert('Only an ongoing election can be stopped.', 'error');
    } elseif (declareElectionResults($pdo, $electionId)) {
        setFlashAlert('Election stopped and results were generated.', 'success');
    } else {
        setFlashAlert('The results could not be generated. Please try again.', 'error');
    }
    header('Location: ' . $backUrl);
    exit();
}

// Start an upcoming election, only inside its start and end dates.
if ($action === 'golive') {
    $now = date('Y-m-d H:i:s');
    if ($election['status'] === 'upcoming' && $election['start_date'] <= $now && $election['end_date'] > $now) {
        $stmt = $pdo->prepare("UPDATE elections SET status = 'ongoing' WHERE id = ? AND status = 'upcoming'");
        $stmt->execute([$electionId]);
        setFlashAlert('The election is now live.', 'success');
    } else {
        setFlashAlert('This election cannot be started right now.', 'error');
    }
    header('Location: ' . $backUrl);
    exit();
}

// Delete an election. An ongoing election cannot be deleted because it may have votes.
if ($action === 'delete') {
    if ($election['status'] === 'ongoing') {
        setFlashAlert('An ongoing election cannot be deleted. Stop it first.', 'error');
    } else {
        $stmt = $pdo->prepare('DELETE FROM elections WHERE id = ?');
        $stmt->execute([$electionId]);
        setFlashAlert('Election deleted.', 'success');
    }
    header('Location: ' . $backUrl);
    exit();
}

http_response_code(400);
exit('Unknown action.');
