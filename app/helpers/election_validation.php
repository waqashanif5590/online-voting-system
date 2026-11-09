<?php
// Checks the "create election" and "edit election" forms on the server.
// Returns ['error' => '' or a message, 'data' => cleaned values].
// Parties are optional: an election can have only positions and no parties.

function validateElectionForm(PDO $pdo, array $post): array
{
    $title = trim($post['title'] ?? '');
    $description = trim($post['description'] ?? '');
    $start = DateTime::createFromFormat('Y-m-d\TH:i', $post['start_date'] ?? '');
    $end = DateTime::createFromFormat('Y-m-d\TH:i', $post['end_date'] ?? '');
    $positions = array_values(array_unique(array_map('intval', (array) ($post['positions'] ?? []))));
    $parties = array_values(array_unique(array_map('intval', (array) ($post['parties'] ?? []))));

    $data = [
        'title' => $title,
        'description' => $description,
        'start_date' => $start ? $start->format('Y-m-d H:i:s') : '',
        'end_date' => $end ? $end->format('Y-m-d H:i:s') : '',
        'positions' => $positions,
        'parties' => $parties,
    ];

    if (mb_strlen($title) < 3 || mb_strlen($title) > 100) {
        return ['error' => 'Election title must be between 3 and 100 characters.', 'data' => $data];
    }
    if ($description === '') {
        return ['error' => 'Please enter an election description.', 'data' => $data];
    }
    if (!$start || !$end) {
        return ['error' => 'Please enter a valid start and end date.', 'data' => $data];
    }
    if ($end <= $start) {
        return ['error' => 'The end date must be after the start date.', 'data' => $data];
    }
    if ($end <= new DateTime('now')) {
        return ['error' => 'The end date must be in the future.', 'data' => $data];
    }
    if (empty($positions)) {
        return ['error' => 'Please select at least one position.', 'data' => $data];
    }

    // Every selected position and party must really exist.
    $stmt = $pdo->query('SELECT id FROM positions');
    $existingPositions = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    $stmt = $pdo->query('SELECT id FROM parties');
    $existingParties = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

    if (array_diff($positions, $existingPositions) || array_diff($parties, $existingParties)) {
        return ['error' => 'One of the selected positions or parties is not valid.', 'data' => $data];
    }

    return ['error' => '', 'data' => $data];
}
