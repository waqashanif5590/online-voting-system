<?php
require_once __DIR__ . '/../../app/paths.php';
require_once APP_ROOT . '/app/config/database.php';
require_once APP_ROOT . '/app/helpers/security.php';
requireRole('candidate');

header('Content-Type: application/json');

$electionId = filter_input(INPUT_GET, 'election_id', FILTER_VALIDATE_INT);

if (!$electionId) {
    echo json_encode(['positions' => [], 'parties' => []]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.id, p.title
    FROM election_positions ep
    INNER JOIN positions p ON ep.position_id = p.id
    WHERE ep.election_id = ?
");
$stmt->execute([$electionId]);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT p.id, p.name
    FROM election_parties ep
    INNER JOIN parties p ON ep.party_id = p.id
    WHERE ep.election_id = ?
");
$stmt->execute([$electionId]);
$parties = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'positions' => $positions,
    'parties' => $parties
]);
