-- Symbols are from fontawesome.com
INSERT INTO `parties` (`name`, `description`, `symbol`) VALUES
('Democratic Party', 'DP', 'bell'),
('Republican Party', 'RP', 'elephant'),
('Green Party', 'GP', 'leaf'),
('Libertarian Party', 'LP', 'balance-scale'),
('Constitution Party', 'CP', 'scroll');

INSERT INTO `positions` (`title`, `description`) VALUES
('President', 'The President is the head of state and government, responsible for executing laws and leading the country.'),
('Vice President', 'The Vice President assists the President and may assume the presidency if necessary.'),
('Senator', 'Senators represent their states in the legislative branch, making and voting on laws.'),
('Governor', 'Governors are the chief executives of their states, overseeing state government operations.'),
('Mayor', 'Mayors are the heads of city governments, responsible for local administration and services.');

INSERT INTO `elections` (`title`, `description`, `start_date`, `end_date`, `status`) VALUES
('2027 General Election', 'The 2027 General Election will determine the next President, Vice President, and members of Congress.', '2027-11-02 08:00:00', '2027-11-02 20:00:00', 'upcoming'),
('2026 State Elections', 'State elections for governors and state legislators across various states.', '2026-11-03 08:00:00', '2026-11-03 20:00:00', 'upcoming'),
('2025 Local Elections', 'Local elections for mayors and city councils in various municipalities.', '2025-09-09 08:00:00', '2025-09-09 20:00:00', 'completed');

-- Seeded admin password: admin123; all other seeded accounts: user1234.
INSERT INTO `users` (`name`, `email`, `identity_type`, `identity_number`, `dob`, `gender`, `role`, `password`) VALUES
('System Administrator', 'admin@example.com', 'passport', 'ADM0000001', '1980-01-15', 'other', 'admin', '$2y$12$m85VqbD3EhLSc0ioz.em.eNSEW4Tlv0J72oVKiV5nhGSwoYUN4fyq'),
('Ava Patel', 'ava.patel@example.com', 'cnic', '3520212345671', '1985-04-12', 'female', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Jordan Lee', 'jordan.lee@example.com', 'cnic', '3520212345672', '1978-09-23', 'male', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Morgan Reed', 'morgan.reed@example.com', 'passport', 'CAN0000001', '1975-02-08', 'other', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Sam Rivera', 'sam.rivera@example.com', 'cnic', '3520212345681', '1992-06-19', 'male', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Noor Ahmed', 'noor.ahmed@example.com', 'cnic', '3520212345682', '1989-11-04', 'female', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Taylor Brooks', 'taylor.brooks@example.com', 'passport', 'VOT0000001', '2000-03-27', 'other', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W');

INSERT INTO `candidates` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '14 Market Street', 'Springfield', 'Illinois', 'United States', '62701', elections.id, positions.id, 'Springfield Central', parties.id,
       'Master of Public Administration', 'Served two terms on the city council.', 'Improve public transport, expand parks, and publish transparent city budgets.', 'approved'
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
JOIN positions ON positions.title = 'Mayor'
JOIN parties ON parties.name = 'Democratic Party'
WHERE users.email = 'ava.patel@example.com';

INSERT INTO `candidates` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '82 Oak Avenue', 'Springfield', 'Illinois', 'United States', '62702', elections.id, positions.id, 'Springfield Central', parties.id,
       'Bachelor of Economics', 'Led neighborhood business and community development programs.', 'Support local businesses, improve road maintenance, and strengthen public safety.', 'approved'
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
JOIN positions ON positions.title = 'Mayor'
JOIN parties ON parties.name = 'Republican Party'
WHERE users.email = 'jordan.lee@example.com';

INSERT INTO `candidates` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '5 Capitol Plaza', 'Washington', 'District of Columbia', 'United States', '20001', elections.id, positions.id, 'National District', parties.id,
       'Doctor of Political Science', 'Worked in public policy and served on a non-profit board.', 'Protect civil liberties, invest in clean energy, and make public services accessible.', 'pending'
FROM users
JOIN elections ON elections.title = '2027 General Election'
JOIN positions ON positions.title = 'President'
JOIN parties ON parties.name = 'Green Party'
WHERE users.email = 'morgan.reed@example.com';

INSERT INTO `voters` (`user_id`, `token`, `election_id`, `has_voted`)
SELECT users.id, 'VOTE000001', elections.id, 1
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
WHERE users.email = 'sam.rivera@example.com';

INSERT INTO `voters` (`user_id`, `token`, `election_id`, `has_voted`)
SELECT users.id, 'VOTE000002', elections.id, 1
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
WHERE users.email = 'noor.ahmed@example.com';

INSERT INTO `voters` (`user_id`, `token`, `election_id`, `has_voted`)
SELECT users.id, 'VOTE000003', elections.id, 0
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
WHERE users.email = 'taylor.brooks@example.com';

INSERT INTO `votes` (`election_id`, `candidate_id`, `position_id`, `voter_id`)
SELECT elections.id, candidates.id, positions.id, voters.id
FROM elections
JOIN candidates ON candidates.election_id = elections.id
JOIN users AS candidate_users ON candidate_users.id = candidates.user_id
JOIN positions ON positions.id = candidates.position_id
JOIN voters ON voters.election_id = elections.id
JOIN users AS voter_users ON voter_users.id = voters.user_id
WHERE elections.title = '2025 Local Elections'
  AND candidate_users.email = 'ava.patel@example.com'
  AND voter_users.email = 'sam.rivera@example.com';

INSERT INTO `votes` (`election_id`, `candidate_id`, `position_id`, `voter_id`)
SELECT elections.id, candidates.id, positions.id, voters.id
FROM elections
JOIN candidates ON candidates.election_id = elections.id
JOIN users AS candidate_users ON candidate_users.id = candidates.user_id
JOIN positions ON positions.id = candidates.position_id
JOIN voters ON voters.election_id = elections.id
JOIN users AS voter_users ON voter_users.id = voters.user_id
WHERE elections.title = '2025 Local Elections'
  AND candidate_users.email = 'jordan.lee@example.com'
  AND voter_users.email = 'noor.ahmed@example.com';
