CREATE TABLE elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('upcoming', 'ongoing', 'completed') NOT NULL DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE parties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    symbol VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    identity_type VARCHAR(20) NOT NULL,
    identity_number VARCHAR(20) NOT NULL UNIQUE,
    dob DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    role ENUM('voter', 'candidate', 'admin') NOT NULL DEFAULT 'voter',
    status tinyint NOT NULL DEFAULT 1,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    election_id INT NOT NULL,
    position_id INT NOT NULL,
    constituency_area VARCHAR(100) NOT NULL,
    party_id INT DEFAULT NULL,
    qualifications TEXT NOT NULL,
    experience TEXT NOT NULL,
    manifesto TEXT NOT NULL,
    certificates VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
    FOREIGN KEY (party_id) REFERENCES parties(id) ON DELETE SET NULL,
    UNIQUE (user_id, election_id, position_id)
);

CREATE TABLE voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(10) NOT NULL UNIQUE,
    election_id INT NOT NULL,
    has_voted TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    UNIQUE (user_id, election_id)
);

CREATE TABLE votes (
   id INT AUTO_INCREMENT PRIMARY KEY,
   election_id INT NOT NULL,
   application_id INT NOT NULL,
   position_id INT NOT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
   FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
   FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
   UNIQUE (election_id, application_id)
);

CREATE TABLE voter_participation (
   id INT AUTO_INCREMENT PRIMARY KEY,
   voter_id INT NOT NULL,
   election_id INT NOT NULL,
   position_id INT NOT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE,
   FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
   FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
   UNIQUE (voter_id, election_id, position_id)
)

CREATE TABLE election_positions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   election_id INT NOT NULL,
   position_id INT NOT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
   FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
   UNIQUE (election_id, position_id)
)

CREATE TABLE election_parties (
   id INT AUTO_INCREMENT PRIMARY KEY,
   election_id INT NOT NULL,
   party_id INT NOT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
   FOREIGN KEY (party_id) REFERENCES parties(id) ON DELETE CASCADE,
   UNIQUE (election_id, party_id)
)
