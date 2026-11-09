<?php
// Saves the final results of ONE election. It is used in two places:
//   1. when the admin clicks "Stop & Generate Results", and
//   2. automatically when an election's end date has passed.
// It is safe to call more than once: results are saved only the first time.

function declareElectionResults(PDO $pdo, int $electionId): bool
{
    try {
        $pdo->beginTransaction();

        // Lock this election row so two requests cannot declare results at the same time.
        $stmt = $pdo->prepare('SELECT id, result_declared_at FROM elections WHERE id = ? FOR UPDATE');
        $stmt->execute([$electionId]);
        $election = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$election || $election['result_declared_at'] !== null) {
            $pdo->rollBack();
            return false; // missing election, or results were already saved
        }

        // Count the election data before the temporary voting rows are deleted.
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM votes WHERE election_id = ?');
        $stmt->execute([$electionId]);
        $totalVotes = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE election_id = ? AND status = 'approved'");
        $stmt->execute([$electionId]);
        $totalCandidates = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM voters WHERE election_id = ?');
        $stmt->execute([$electionId]);
        $totalVoters = (int) $stmt->fetchColumn();

        // "rejected_votes" column = assigned voters who never voted.
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM voters WHERE election_id = ? AND has_voted = 0');
        $stmt->execute([$electionId]);
        $didNotVote = (int) $stmt->fetchColumn();

        // Every APPROVED candidate gets a row, even with 0 votes.
        $stmt = $pdo->prepare("
            SELECT a.id AS application_id, a.position_id, a.party_id, COUNT(v.id) AS vote_count
            FROM applications a
            LEFT JOIN votes v ON v.application_id = a.id
            WHERE a.election_id = ? AND a.status = 'approved'
            GROUP BY a.id, a.position_id, a.party_id
        ");
        $stmt->execute([$electionId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare('DELETE FROM results WHERE election_id = ?');
        $stmt->execute([$electionId]);

        $insert = $pdo->prepare('INSERT INTO results (election_id, application_id, position_id, party_id, vote_count)
                                 VALUES (?, ?, ?, ?, ?)');
        foreach ($rows as $row) {
            $insert->execute([$electionId, $row['application_id'], $row['position_id'], $row['party_id'], $row['vote_count']]);
        }

        $stmt = $pdo->prepare('DELETE FROM election_summaries WHERE election_id = ?');
        $stmt->execute([$electionId]);
        $stmt = $pdo->prepare('INSERT INTO election_summaries
            (election_id, total_votes, total_candidates, total_voters, rejected_votes) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$electionId, $totalVotes, $totalCandidates, $totalVoters, $didNotVote]);

        $stmt = $pdo->prepare("UPDATE elections SET status = 'completed', result_declared_at = NOW() WHERE id = ?");
        $stmt->execute([$electionId]);

        // Results are saved, so delete the temporary voting rows (votes, participation, voters).
        $stmt = $pdo->prepare('DELETE FROM voter_participation WHERE election_id = ?');
        $stmt->execute([$electionId]);
        $stmt = $pdo->prepare('DELETE FROM votes WHERE election_id = ?');
        $stmt->execute([$electionId]);
        $stmt = $pdo->prepare('DELETE FROM voters WHERE election_id = ?');
        $stmt->execute([$electionId]);

        $pdo->commit();
        return true;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Could not declare results for election ' . $electionId . ': ' . $error->getMessage());
        return false;
    }
}
