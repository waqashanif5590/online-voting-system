<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/helpers/security.php';
require_once APP_ROOT . '/app/config/database.php';
requireRole('admin');
checkCsrfToken();

if (isset($_GET['election'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['election']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }

    $stmt = $pdo->prepare("SELECT votes.election_id,
    votes.application_id,
    votes.position_id,
    votes.party_id,
    COUNT(votes.id) AS vote_count
    FROM votes
    WHERE votes.election_id = ?
    GROUP BY votes.election_id, votes.application_id, votes.position_id, votes.party_id");
    $stmt->execute([$_GET['election']]);
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Insert the vote counts into the results table
    $stmt = $pdo->prepare("INSERT INTO results 
    (election_id, application_id, position_id, party_id, vote_count)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE vote_count = VALUES(vote_count)");
    foreach ($votes as $vote) {
        $stmt->execute([
            $vote['election_id'],
            $vote['application_id'],
            $vote['position_id'],
            $vote['party_id'],
            $vote['vote_count']
        ]);
    }

    $stmt = $pdo->prepare("UPDATE elections SET status=?, result_declared_at=NOW() WHERE id=?");
    $stmt->execute(['completed', $_GET['election']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}

if (isset($_GET['golive'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['golive']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }
    $stmt = $pdo->prepare("UPDATE elections SET status=? WHERE id=?");
    $stmt->execute(['ongoing', $_GET['golive']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }
    $stmt = $pdo->prepare("DELETE FROM elections WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}
