-- LiveVoting: safe fixes for an EXISTING database. No data is deleted.
-- Back up first (see README notes): mysqldump -u root livevoting_db > backup.sql
-- Run each part in phpMyAdmin (SQL tab) or the mysql command line.

-- PART 1: add the column that stores when results were published.
-- Run ONLY if this check shows no rows:   SHOW COLUMNS FROM elections LIKE 'result_declared_at';
ALTER TABLE elections ADD COLUMN result_declared_at DATETIME NULL AFTER status;

-- PART 2: one result row per candidate (the old key did not stop duplicates when party_id is NULL).
-- Add the new key first, then drop the old one (the foreign key needs an index at all times).
-- Find the old key name with:  SHOW INDEX FROM results;   (it is normally called application_id)
ALTER TABLE results ADD UNIQUE KEY uq_results_application (application_id);
ALTER TABLE results DROP INDEX application_id;

-- PART 3: one summary row per election.
ALTER TABLE election_summaries ADD UNIQUE KEY uq_summary_election (election_id);

-- PART 4: speed up common queries (optional).
ALTER TABLE votes ADD INDEX idx_votes_election_position (election_id, position_id);
ALTER TABLE applications ADD INDEX idx_applications_election_status (election_id, status);
