-- Drop tables if they exist
DROP TABLE IF EXISTS adoption_applications;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS animals;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create admin user
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@animalshelter.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Create sample regular users
INSERT INTO users (username, email, password, role) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Create animals table
CREATE TABLE animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50) NOT NULL,
    description TEXT,
    status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create images table
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);

-- Create adoption applications table
CREATE TABLE adoption_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    applicant_name VARCHAR(100) NOT NULL,
    applicant_email VARCHAR(100) NOT NULL,
    applicant_phone VARCHAR(20),
    home_address TEXT NOT NULL,
    housing_type ENUM('house', 'apartment', 'other') NOT NULL,
    has_yard BOOLEAN,
    other_pets TEXT,
    experience TEXT,
    reason_for_adoption TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample animals
INSERT INTO animals (name, species, description, status) VALUES
('Max', 'Dog', 'A friendly golden retriever who loves to play fetch.', 'available'),
('Luna', 'Cat', 'A gentle calico cat who enjoys lounging in the sun.', 'available'),
('Rocky', 'Dog', 'An energetic German Shepherd mix, great with kids.', 'pending'),
('Bella', 'Cat', 'A playful Siamese cat looking for a forever home.', 'available'),
('Charlie', 'Dog', 'A sweet Beagle puppy who loves attention.', 'available');

-- Insert sample images
INSERT INTO images (animal_id, file_name, file_path) VALUES
(1, 'max.jpg', 'admin/uploads/animals/max.jpg'),
(2, 'luna.jpg', 'admin/uploads/animals/luna.jpg'),
(3, 'rocky.jpg', 'admin/uploads/animals/rocky.jpg'),
(4, 'bella.jpg', 'admin/uploads/animals/bella.jpg'),
(5, 'charlie.jpg', 'admin/uploads/animals/charlie.jpg');

-- Insert sample adoption applications
INSERT INTO adoption_applications (
    animal_id, user_id, status, applicant_name, applicant_email, 
    applicant_phone, home_address, housing_type, has_yard, 
    other_pets, experience, reason_for_adoption
) VALUES
-- John's pending application for Luna
(2, 2, 'pending', 'John Doe', 'john@example.com',
'555-0123', '123 Main St, Anytown, ST 12345', 'house', true,
'One older cat, very friendly', '5 years experience with cats',
'Looking for a companion for my current cat and fell in love with Luna''s gentle personality.'),

-- Jane's approved application for Rocky
(3, 3, 'approved', 'Jane Smith', 'jane@example.com',
'555-0456', '456 Oak Ave, Somewhere, ST 12345', 'house', true,
'No current pets', 'Grew up with German Shepherds',
'I have a large fenced yard and lots of time for exercise and training.'),

-- John's rejected application for Max
(1, 2, 'rejected', 'John Doe', 'john@example.com',
'555-0123', '123 Main St, Anytown, ST 12345', 'apartment', false,
'One cat', 'First-time dog owner',
'Would love to have a dog to go running with.'),

-- Jane's pending application for Charlie
(5, 3, 'pending', 'Jane Smith', 'jane@example.com',
'555-0456', '456 Oak Ave, Somewhere, ST 12345', 'house', true,
'No current pets', 'Previous experience with Beagles',
'Looking for an energetic companion for daily walks and playtime.'); 