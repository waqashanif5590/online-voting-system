<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/helpers/security.php';
require_once APP_ROOT . '/app/config/database.php';
requireRole('admin');
checkCsrfToken();
if (isset($_GET['delete_application'])) {
    $applicationId = filter_input(INPUT_GET, 'delete_application', FILTER_VALIDATE_INT);
    $stmt = $pdo->prepare("
        SELECT elections.status
        FROM applications
        INNER JOIN elections ON elections.id = applications.election_id
        WHERE applications.id = ?
    ");
    $stmt->execute([$applicationId]);
    $electionStatus = $stmt->fetchColumn();
    if ($electionStatus === 'completed') {
        $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
        $stmt->execute([$applicationId]);
    }
    header('Location: ' . app_url('views/admin/candidates.php'));
    exit();
}
if (isset($_GET['approve'], $_GET['candidate'])) {
    $candidateId = filter_input(INPUT_GET, 'candidate', FILTER_VALIDATE_INT);
    $stmt = $pdo->prepare("
        SELECT elections.status
        FROM applications
        INNER JOIN elections ON elections.id = applications.election_id
        WHERE applications.id = ?
    ");
    $stmt->execute([$candidateId]);
    $electionStatus = $stmt->fetchColumn();
    if ($electionStatus === false || $electionStatus === 'completed') {
        header('Location: ' . app_url('views/admin/candidates.php'));
        exit();
    }
    if ($_GET['approve'] === 'true') {
        $stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->execute(['approved', $candidateId]);
    } else if ($_GET['approve'] === 'false') {
        $stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->execute(['rejected', $candidateId]);
    }
    header('Location: ' . app_url('views/admin/candidates.php'));
    exit();
}

if (isset($_GET['voter'])) {
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id=?");
    $stmt->execute([$_GET['voter']]);
    $voter = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($voter === false) {
        http_response_code(404);
        exit('Voter not found');
    }
    if ($voter['status'] == true) {
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->execute([0, $_GET['voter']]);
    } else if ($voter['status'] == false) {
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->execute([1, $_GET['voter']]);
    }
    header('Location: ' . app_url('views/admin/voters.php'));
    exit();
}
