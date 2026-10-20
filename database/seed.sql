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
('2024 General Election', 'The 2024 General Election will determine the next President, Vice President, and members of Congress.', '2024-11-05 08:00:00', '2024-11-05 20:00:00', 'upcoming'),
('2024 State Elections', 'State elections for governors and state legislators across various states.', '2024-10-15 08:00:00', '2024-10-15 20:00:00', 'upcoming'),
('2024 Local Elections', 'Local elections for mayors and city councils in various municipalities.', '2024-09-10 08:00:00', '2024-09-10 20:00:00', 'upcoming');