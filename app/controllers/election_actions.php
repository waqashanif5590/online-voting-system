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

    try {
        $pdo->beginTransaction();
        $electionId = (int) $_GET['election'];

        // Count the election data before the live voting tables are cleared.
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE election_id=?");
        $stmt->execute([$electionId]);
        $totalVotes = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE election_id=? AND status='approved'");
        $stmt->execute([$electionId]);
        $totalCandidates = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM voters WHERE election_id=?");
        $stmt->execute([$electionId]);
        $totalVoters = (int) $stmt->fetchColumn();

        // A registered voter who did not submit a ballot is counted as a rejected vote.
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM voters WHERE election_id=? AND has_voted=0");
        $stmt->execute([$electionId]);
        $rejectedVotes = (int) $stmt->fetchColumn();

        // Save the candidate totals in the results table.
        $stmt = $pdo->prepare("SELECT votes.application_id,
        votes.position_id,
        votes.party_id,
        COUNT(votes.id) AS vote_count
        FROM votes
        WHERE votes.election_id = ?
        GROUP BY votes.application_id, votes.position_id, votes.party_id");
        $stmt->execute([$electionId]);
        $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("DELETE FROM results WHERE election_id=?");
        $stmt->execute([$electionId]);

        $stmt = $pdo->prepare("INSERT INTO results
        (election_id, application_id, position_id, party_id, vote_count)
        VALUES (?, ?, ?, ?, ?)");
        foreach ($votes as $vote) {
            $stmt->execute([
                $electionId,
                $vote['application_id'],
                $vote['position_id'],
                $vote['party_id'],
                $vote['vote_count']
            ]);
        }

        // Save the final totals once, then remove duplicate old summaries.
        $stmt = $pdo->prepare("DELETE FROM election_summary WHERE election_id=?");
        $stmt->execute([$electionId]);

        $stmt = $pdo->prepare("INSERT INTO election_summary
        (election_id, total_votes, total_candidates, total_voters, rejected_votes)
        VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $electionId,
            $totalVotes,
            $totalCandidates,
            $totalVoters,
            $rejectedVotes
        ]);

        $stmt = $pdo->prepare("UPDATE elections SET status=?, result_declared_at=NOW() WHERE id=?");
        $stmt->execute(['completed', $electionId]);

        // Clear temporary voting records after all summary data is saved.
        $stmt = $pdo->prepare("DELETE FROM voter_participation WHERE election_id=?");
        $stmt->execute([$electionId]);
        $stmt = $pdo->prepare("DELETE FROM votes WHERE election_id=?");
        $stmt->execute([$electionId]);
        $stmt = $pdo->prepare("DELETE FROM voters WHERE election_id=?");
        $stmt->execute([$electionId]);

        $pdo->commit();
    } catch (Exception $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        exit('Election could not be completed: ' . $error->getMessage());
    }

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
