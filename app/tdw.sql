-- Nettoyage
CREATE DATABASE IF NOT EXISTS TDW;
USE TDW;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS equipment_maintenance;
DROP TABLE IF EXISTS equipment_reservations;
DROP TABLE IF EXISTS equipment_history;
DROP TABLE IF EXISTS equipment;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS event_participants;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS event_requests;
DROP TABLE IF EXISTS project_partners;
DROP TABLE IF EXISTS project_members;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS publication_authors;
DROP TABLE IF EXISTS publications;
DROP TABLE IF EXISTS team_members;
DROP TABLE IF EXISTS teams;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS specialties;
DROP TABLE IF EXISTS publication_types;
DROP TABLE IF EXISTS event_types;
DROP TABLE IF EXISTS funding_types;
DROP TABLE IF EXISTS equipment_states;
SET FOREIGN_KEY_CHECKS = 1;

-- LOOKUP TABLES ---------------------------------------------------

CREATE TABLE specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE publication_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE event_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE funding_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE equipment_states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT
);

-- USERS ------------------------------------------------------------

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role_id INT,
    permissions JSON DEFAULT (JSON_OBJECT()),
    specialty_id INT,
    status VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
    FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE SET NULL
);

-- ADMINS ------------------------------------------------------------

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- MEMBERS ----------------------------------------------------------

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo_url TEXT,
    last_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    login VARCHAR(255) UNIQUE,
    user_id INT UNIQUE,
    website TEXT,
    specialty_id INT,
    role_in_lab VARCHAR(255),
    team_id INT,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE SET NULL
);

-- TEAMS ------------------------------------------------------------

CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    leader_member_id INT,
    domain TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (leader_member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Mise à jour FK team_id (création après teams)
ALTER TABLE members
    ADD FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL;

-- TEAM MEMBERS -----------------------------------------------------

CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    member_id INT NOT NULL,
    role_in_team VARCHAR(255),
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    left_at TIMESTAMP NULL,
    UNIQUE (team_id, member_id),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- PUBLICATIONS -----------------------------------------------------

CREATE TABLE publications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    team_id INT,
    publication_type_id INT,
    date_published DATE,
    doi TEXT,
    url TEXT,
    pdf_url TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL,
    FOREIGN KEY (publication_type_id) REFERENCES publication_types(id) ON DELETE SET NULL
);

CREATE TABLE publication_authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    publication_id INT NOT NULL,
    member_id INT,
    author_name TEXT NOT NULL,
    author_order INT DEFAULT 0,
    affiliation TEXT,
    UNIQUE (publication_id, author_order),
    FOREIGN KEY (publication_id) REFERENCES publications(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- PROJECTS ---------------------------------------------------------

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    leader_member_id INT,
    theme TEXT,
    funding_type_id INT,
    project_page_url TEXT,
    poster_url TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (leader_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (funding_type_id) REFERENCES funding_types(id) ON DELETE SET NULL
);

CREATE TABLE project_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    member_id INT,
    role_in_project VARCHAR(255),
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (project_id, member_id),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE TABLE project_partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    name TEXT NOT NULL,
    contact_info TEXT,
    role_description TEXT,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- EVENTS -----------------------------------------------------------

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    event_type_id INT,
    event_date TIMESTAMP NULL,
    description TEXT,
    link TEXT,
    /*participation_requests TEXT,
    participation_requests_json JSON,*/
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_type_id) REFERENCES event_types(id) ON DELETE SET NULL
);

CREATE TABLE event_participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    member_id INT,
    role VARCHAR(255),
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (event_id, member_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE TABLE event_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    member_id INT NULL,
    name VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- NEWS -------------------------------------------------------------

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo_url TEXT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- EQUIPMENT --------------------------------------------------------

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255),
    state_id INT,
    description TEXT,
    location TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (state_id) REFERENCES equipment_states(id) ON DELETE SET NULL
);

CREATE TABLE equipment_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    member_id INT,
    reserved_from TIMESTAMP NOT NULL,
    reserved_to TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    purpose TEXT,
    status VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE TABLE equipment_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    event_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    member_id INT,
    note TEXT,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE TABLE equipment_maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    scheduled_at TIMESTAMP,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE
);

-- INDEXES ----------------------------------------------------------

CREATE INDEX idx_members_name ON members(last_name, first_name);
CREATE INDEX idx_publications_date ON publications(date_published);
CREATE INDEX idx_projects_theme ON projects(theme);
CREATE INDEX idx_equipment_name ON equipment(name);

-- DEFAULT DATA ------------------------------------------------------

INSERT IGNORE INTO publication_types (name) VALUES ('Article'), ('Conference'), ('Report'), ('Poster');
INSERT IGNORE INTO event_types (name) VALUES ('Séminaire'), ('Atelier'), ('Conférence'), ('Soutenance');
INSERT IGNORE INTO funding_types (name) VALUES ('ANR'), ('UE'), ('Industriel'), ('Financement interne');
INSERT IGNORE INTO equipment_states (name, description) VALUES
  ('Available', 'ready to reserve'),
  ('Occupies', 'reserved'),
  ('Out of service', 'to maintain');