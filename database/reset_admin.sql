-- First, remove existing admin user if exists
DELETE FROM users WHERE username = 'admin';

-- Then insert the admin user with password 'admin123'
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@animalshelter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); 