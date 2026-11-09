<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/helpers/security.php';
require_once APP_ROOT . '/includes/shared/alert.php';
require_once APP_ROOT . '/app/config/database.php';
requireRole('admin');
checkCsrfToken(); // also makes sure this page is only used by a POST form

$action = $_POST['action'] ?? '';

// Find the status of the election that an application belongs to.
function getApplicationElectionStatus(PDO $pdo, $applicationId)
{
    $stmt = $pdo->prepare("
        SELECT elections.status
        FROM applications
        INNER JOIN elections ON elections.id = applications.election_id
        WHERE applications.id = ?
    ");
    $stmt->execute([$applicationId]);
    return $stmt->fetchColumn(); // false when the application does not exist
}

// Delete an application (only after its election is completed).
if ($action === 'delete_application') {
    $applicationId = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT);
    $electionStatus = $applicationId ? getApplicationElectionStatus($pdo, $applicationId) : false;
    if ($electionStatus === 'completed') {
        $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
        $stmt->execute([$applicationId]);
        setFlashAlert('Application deleted successfully.', 'success');
    } else {
        setFlashAlert('The application can only be deleted after its election is completed.', 'error');
    }
    header('Location: ' . app_url('views/admin/candidates.php'));
    exit();
}

// Approve or reject an application.
if ($action === 'approve' || $action === 'reject') {
    $applicationId = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT);
    $electionStatus = $applicationId ? getApplicationElectionStatus($pdo, $applicationId) : false;

    // Candidates can only change before voting starts, so the ballot never changes while people vote.
    if ($electionStatus !== 'upcoming') {
        setFlashAlert('Applications can only be reviewed while the election is upcoming.', 'error');
        header('Location: ' . app_url('views/admin/candidates.php'));
        exit();
    }

    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $applicationId]);
    if ($action === 'approve') {
        setFlashAlert('Application approved successfully.', 'success');
    } else {
        setFlashAlert('Application rejected.', 'warning');
    }
    header('Location: ' . app_url('views/admin/candidates.php'));
    exit();
}

// Block or activate a voter or candidate account.
if ($action === 'toggle_user') {
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $stmt = $pdo->prepare("SELECT status, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user === false) {
        http_response_code(404);
        exit('User not found');
    }
    if ($user['role'] === 'admin') {
        setFlashAlert('Administrator accounts cannot be blocked.', 'error');
    } else {
        $newStatus = (int) $user['status'] === 1 ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $userId]);
        setFlashAlert($newStatus === 1 ? 'Account activated.' : 'Account blocked.', 'success');
    }
    header('Location: ' . app_url('views/admin/voters.php'));
    exit();
}

http_response_code(400);
exit('Unknown action.');
