-- 1. EXTEND LOOKUP TABLES
INSERT INTO specialties (name) VALUES 
('Artificial Intelligence'), ('Cybersecurity'), ('Software Engineering'), 
('Human-Computer Interaction'), ('Distributed Systems');

INSERT INTO roles (name, description) VALUES 
('Professor', 'Full faculty member'), 
('PhD Student', 'Doctoral researcher'), 
('Post-Doc', 'Temporary researcher'), 
('System Admin', 'IT infrastructure management');

INSERT INTO users (login, email, password_hash, role_id, specialty_id, status) VALUES 
('jdoe', 'john.doe@lab.edu', 'hash1', 1, 1, 'active'),
('asmith', 'alice.smith@lab.edu', 'hash2', 2, 2, 'active'),
('bwong', 'bob.wong@lab.edu', 'hash3', 3, 3, 'active'),
('clin', 'carol.lin@lab.edu', 'hash4', 1, 1, 'active'),
('mgarcia', 'marco.garcia@lab.edu', 'hash5', 2, 5, 'active');

-- 3. MEMBERS (Initial Insert without team_id to avoid FK issues)
INSERT INTO members (first_name, last_name, login, user_id, specialty_id, role_in_lab) VALUES 
('John', 'Doe', 'jdoe', 1, 1, 'Senior Researcher'),
('Alice', 'Smith', 'asmith', 2, 2, 'Junior Researcher'),
('Bob', 'Wong', 'bwong', 3, 3, 'Post-Doc Fellow'),
('Carol', 'Lin', 'clin', 4, 1, 'Lab Director'),
('Marco', 'Garcia', 'mgarcia', 5, 5, 'Graduate Assistant');

-- 4. TEAMS
INSERT INTO teams (name, leader_member_id, domain) VALUES 
('AI & Robotics', 1, 'Deep Learning and Autonomous Systems'),
('Security Hub', 2, 'Network Security and Cryptography'),
('SoftDev Group', 3, 'Agile Methodologies and DevSecOps');

-- 5. UPDATE MEMBERS WITH TEAM_ID
UPDATE members SET team_id = 1 WHERE id IN (1, 4);
UPDATE members SET team_id = 2 WHERE id = 2;
UPDATE members SET team_id = 3 WHERE id = 3;
UPDATE members SET team_id = 1 WHERE id = 5;

-- 6. PUBLICATIONS
INSERT INTO publications (title, team_id, publication_type_id, date_published, doi) VALUES 
('Neural Networks in Edge Computing', 1, 1, '2025-03-15', '10.1145/12345'),
('Zero Trust Architecture for Labs', 2, 2, '2025-05-20', '10.1109/67890'),
('Scaling Microservices in Cloud', 3, 1, '2024-11-10', '10.1016/j.jss.2024');

INSERT INTO publication_authors (publication_id, member_id, author_name, author_order) VALUES 
(1, 1, 'John Doe', 1),
(1, 4, 'Carol Lin', 2),
(2, 2, 'Alice Smith', 1),
(3, 3, 'Bob Wong', 1);

-- 7. PROJECTS
INSERT INTO projects (title, leader_member_id, theme, funding_type_id) VALUES 
('Autonomous Drone Swarm', 1, 'Robotics', 1),
('Quantum Resistant Encryption', 2, 'Security', 2),
('Open Source Lab Management', 4, 'Tools', 4);

INSERT INTO project_members (project_id, member_id, role_in_project) VALUES 
(1, 1, 'Principal Investigator'),
(1, 5, 'Lead Developer'),
(2, 2, 'Lead Researcher');

-- 8. EVENTS
INSERT INTO events (name, event_type_id, event_date, description) VALUES 
('Annual AI Symposium', 3, '2026-06-15 09:00:00', 'Gathering of AI experts'),
('Ph.D Defense: Alice Smith', 4, '2026-09-10 14:00:00', 'Thesis on Security');

INSERT INTO event_participants (event_id, member_id, role) VALUES 
(1, 1, 'Speaker'),
(1, 4, 'Organizer'),
(2, 2, 'Candidate'),
(2, 1, 'Jury Member');

-- 9. EQUIPMENT
INSERT INTO equipment (name, type, state_id, location) VALUES 
('NVIDIA H100 GPU Cluster', 'Server', 1, 'Server Room A'),
('3D Printer - Ultimaker', 'Hardware', 2, 'Maker Space'),
('Oculus Quest 3', 'VR Gear', 1, 'HCI Lab');

INSERT INTO equipment_reservations (equipment_id, member_id, reserved_from, reserved_to, status) VALUES 
(2, 5, '2026-01-20 10:00:00', '2026-01-20 18:00:00', 'Confirmed');

-- 10. NEWS
INSERT INTO news (title, description, published_at) VALUES 
(
    'Breakthrough in Neural Network Efficiency', 
    'Our AI & Robotics team, led by Dr. John Doe, has published a landmark paper detailing a new method for reducing the computational overhead of Large Language Models (LLMs) by 40% without sacrificing accuracy. This research, conducted over the last 18 months, utilizes a novel pruning technique that identifies redundant synaptic weights in real-time. The implications for mobile computing and edge devices are significant, as it allows sophisticated AI models to run locally on hardware with limited power budgets. The team is currently looking for industry partners to pilot this technology in autonomous vehicle sensors.',
    '2025-11-15 10:30:00'
),
(
    'Cybersecurity Hub Awarded EU Research Grant', 
    'The European Research Council has officially announced a 3.5 million Euro grant to fund our laboratory’s "Quantum-Resistant Infrastructure" project. Over the next four years, the lab will expand its specialized facilities to include a dedicated cryptography testing suite. This project aims to develop new encryption standards that can withstand the processing power of future quantum computers. Alice Smith will lead the junior research cohort, focusing specifically on post-quantum lattice-based signatures. This funding will also support the recruitment of three new PhD candidates and two post-doctoral fellows starting next semester.',
    '2025-12-01 09:00:00'
),
(
    'Annual Lab Open House and Tech Demo', 
    'Join us next month for our annual Open House, where we open our doors to students, industry professionals, and the local community. This year’s event features live demonstrations of our new autonomous drone swarm and an interactive VR simulation of historical landmarks developed by the HCI group. Visitors will have the opportunity to speak directly with researchers, tour the server rooms, and see our state-of-the-art 3D printing facility in action. The event will conclude with a keynote address by Dr. Carol Lin on the ethics of AI in modern society. Refreshments will be provided, and registration is free but mandatory via the events portal.',
    '2026-01-05 14:15:00'
),
(
    'Strategic Partnership with TechCorp Systems', 
    'We are thrilled to announce a new five-year strategic partnership with TechCorp Systems, a global leader in cloud infrastructure. This collaboration will provide our members with exclusive access to proprietary datasets and high-performance computing clusters. In return, the lab will provide TechCorp with early access to our Software Engineering team’s research on automated bug detection and DevSecOps workflows. This bridge between academia and industry ensures that our research remains grounded in real-world challenges and provides our graduate students with excellent internship and career placement opportunities upon completion of their degrees.',
    '2026-01-10 11:00:00'
),
(
    'Recognition for Excellence in Software Engineering', 
    'The SoftDev Group has been honored with the "Innovation in Open Source" award at this year’s Global Software Summit. The award recognizes the lab’s contribution to the OpenLab Management project, an open-source tool now used by over 200 research facilities worldwide to track equipment and publication workflows. Lead developer Bob Wong accepted the award on behalf of the team, highlighting the importance of collaborative software in the scientific community. This recognition marks the third consecutive year our lab has received international acclaim for its commitment to open-science principles and reproducible research software.',
    '2026-01-12 16:45:00'
);