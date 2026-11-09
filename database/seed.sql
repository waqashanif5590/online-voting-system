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
('2027 General Election', 'The 2027 General Election will determine the next President, Vice President, and members of Congress.', '2026-11-08 08:00:00', '2026-11-08 20:00:00', 'upcoming'),
('2026 State Elections', 'State elections for governors and state legislators across various states.', '2026-11-10 08:00:00', '2026-11-10 20:00:00', 'upcoming'),
('2025 Local Elections', 'Local elections for mayors and city councils in various municipalities.', '2026-12-15 08:00:00', '2026-12-15 20:00:00', 'upcoming');

-- Election positions
INSERT INTO `election_positions` (`election_id`, `position_id`) VALUES
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM positions WHERE title = 'President')),
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM positions WHERE title = 'Vice President')),
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM positions WHERE title = 'Senator')),
((SELECT id FROM elections WHERE title = '2026 State Elections'), (SELECT id FROM positions WHERE title = 'Governor')),
((SELECT id FROM elections WHERE title = '2025 Local Elections'), (SELECT id FROM positions WHERE title = 'Mayor'));

-- Election parties
INSERT INTO `election_parties` (`election_id`, `party_id`) VALUES
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM parties WHERE name = 'Democratic Party')),
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM parties WHERE name = 'Republican Party')),
((SELECT id FROM elections WHERE title = '2027 General Election'), (SELECT id FROM parties WHERE name = 'Green Party')),
((SELECT id FROM elections WHERE title = '2026 State Elections'), (SELECT id FROM parties WHERE name = 'Libertarian Party')),
((SELECT id FROM elections WHERE title = '2025 Local Elections'), (SELECT id FROM parties WHERE name = 'Democratic Party')),
((SELECT id FROM elections WHERE title = '2025 Local Elections'), (SELECT id FROM parties WHERE name = 'Republican Party'));
-- Seeded admin password: admin123; all other seeded accounts: user1234.
INSERT INTO `users` (`name`, `email`, `identity_type`, `identity_number`, `dob`, `gender`, `role`, `password`) VALUES
('System Administrator', 'admin@example.com', 'passport', 'ADM0000001', '1980-01-15', 'other', 'admin', '$2y$12$m85VqbD3EhLSc0ioz.em.eNSEW4Tlv0J72oVKiV5nhGSwoYUN4fyq'),
('Ava Patel', 'ava.patel@example.com', 'cnic', '3520212345671', '1985-04-12', 'female', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Jordan Lee', 'jordan.lee@example.com', 'cnic', '3520212345672', '1978-09-23', 'male', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Morgan Reed', 'morgan.reed@example.com', 'passport', 'CAN0000001', '1975-02-08', 'other', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Ethan King', 'ethan.king@example.com', 'passport', 'CAN0000002', '1982-07-16', 'male', 'candidate', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Sam Rivera', 'sam.rivera@example.com', 'cnic', '3520212345681', '1992-06-19', 'male', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Noor Ahmed', 'noor.ahmed@example.com', 'cnic', '3520212345682', '1989-11-04', 'female', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Taylor Brooks', 'taylor.brooks@example.com', 'passport', 'VOT0000001', '2000-03-27', 'other', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Priya Shah', 'priya.shah@example.com', 'cnic', '3520212345683', '1995-08-14', 'female', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Liam Chen', 'liam.chen@example.com', 'passport', 'VOT0000002', '1997-01-29', 'male', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W'),
('Zoe Williams', 'zoe.williams@example.com', 'passport', 'VOT0000003', '1993-12-05', 'female', 'voter', '$2y$12$qWuGMC9GDpKg9r9ukXU9ZuFikXjTfZmkJzXtLJQgmMrhYBkMIy82W');

INSERT INTO `applications` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '14 Market Street', 'Springfield', 'Illinois', 'United States', '62701', elections.id, positions.id, 'Springfield Central', parties.id,
       'Master of Public Administration', 'Served two terms on the city council.', 'Improve public transport, expand parks, and publish transparent city budgets.', 'approved'
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
JOIN positions ON positions.title = 'Mayor'
JOIN parties ON parties.name = 'Democratic Party'
WHERE users.email = 'ava.patel@example.com';

INSERT INTO `applications` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '82 Oak Avenue', 'Springfield', 'Illinois', 'United States', '62702', elections.id, positions.id, 'Springfield Central', parties.id,
       'Bachelor of Economics', 'Led neighborhood business and community development programs.', 'Support local businesses, improve road maintenance, and strengthen public safety.', 'approved'
FROM users
JOIN elections ON elections.title = '2025 Local Elections'
JOIN positions ON positions.title = 'Mayor'
JOIN parties ON parties.name = 'Republican Party'
WHERE users.email = 'jordan.lee@example.com';

INSERT INTO `applications` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '5 Capitol Plaza', 'Washington', 'District of Columbia', 'United States', '20001', elections.id, positions.id, 'National District', parties.id,
       'Doctor of Political Science', 'Worked in public policy and served on a non-profit board.', 'Protect civil liberties, invest in clean energy, and make public services accessible.', 'pending'
FROM users
JOIN elections ON elections.title = '2027 General Election'
JOIN positions ON positions.title = 'President'
JOIN parties ON parties.name = 'Green Party'
WHERE users.email = 'morgan.reed@example.com';

INSERT INTO `applications` (`user_id`, `address`, `city`, `state`, `country`, `postal_code`, `election_id`, `position_id`, `constituency_area`, `party_id`, `qualifications`, `experience`, `manifesto`, `status`)
SELECT users.id, '91 Liberty Road', 'Austin', 'Texas', 'United States', '78701', elections.id, positions.id, 'Central Austin', parties.id,
       'Master of Public Policy', 'Previously served as a state legislative aide.', 'Increase housing affordability, modernize public transit, and support small businesses.', 'approved'
FROM users
JOIN elections ON elections.title = '2026 State Elections'
JOIN positions ON positions.title = 'Governor'
JOIN parties ON parties.name = 'Libertarian Party'
WHERE users.email = 'ethan.king@example.com';
