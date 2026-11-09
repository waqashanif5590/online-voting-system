<?php

date_default_timezone_set('Asia/Karachi');

// Runs on every page load that uses the database.
// 1. Elections whose end date has passed are closed and their results are saved.
// 2. Upcoming elections whose start date has arrived become ongoing.
try {
    $endedElections = $pdo->query("
        SELECT id FROM elections
        WHERE end_date <= NOW() AND result_declared_at IS NULL
    ")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($endedElections as $endedElectionId) {
        declareElectionResults($pdo, (int) $endedElectionId);
    }

    $pdo->exec("
        UPDATE elections
        SET status = 'ongoing'
        WHERE status = 'upcoming'
          AND start_date <= NOW()
          AND end_date > NOW()
    ");
} catch (Throwable $error) {
    // A problem here must not break the page. The details go to the server log.
    error_log('Election status update failed: ' . $error->getMessage());
}
