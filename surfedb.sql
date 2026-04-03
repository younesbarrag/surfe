-- Drop existing tables if they exist
DROP TABLE IF EXISTS lesson_student;
DROP TABLE IF EXISTS lessons;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

-- Create database
CREATE DATABASE IF NOT EXISTS surf_school;
USE surf_school;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert test admin user (email: admin@test.com, password: admin123)
INSERT INTO users (id, username, email, password, role) VALUES 
(1, 'admin', 'admin@test.com', '$2y$10$dXsubSbe7jlKYcLJ8z1mBeJ8r4Zc6xZEJLuVCF1PxP3PzjPflPMWa', 'admin');

-- Insert test student users
INSERT INTO users (id, username, email, password, role) VALUES 
(2, 'student1', 'student1@test.com', '$2y$10$n52DOy5f.8z1L3S5wJ8hH.vZQvV6c2JO8YVqP1WrzOc1Bm9A6PRYG', 'student'),
(3, 'student2', 'student2@test.com', '$2y$10$n52DOy5f.8z1L3S5wJ8hH.vZQvV6c2JO8YVqP1WrzOc1Bm9A6PRYG', 'student');

-- Create students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100),
    level ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert sample students linked to user accounts
INSERT INTO students (user_id, name, country, level) VALUES 
(2, 'Student One', 'Morocco', 'Beginner'),
(3, 'Student Two', 'France', 'Intermediate');

-- Create lessons table
CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    coach VARCHAR(100),
    lesson_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample lessons
INSERT INTO lessons (title, coach, lesson_date) VALUES 
('Morning Surf', 'Ali', '2026-04-05 09:00:00'),
('Evening Surf', 'Omar', '2026-04-05 17:00:00'),
('Afternoon Session', 'Fatima', '2026-04-06 14:00:00');

-- Create lesson_student junction table
CREATE TABLE lesson_student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    lesson_id INT,
    payment_status ENUM('payé', 'en attente') DEFAULT 'en attente',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

-- Insert sample relations
INSERT INTO lesson_student (student_id, lesson_id, payment_status) VALUES 
(1, 1, 'payé'),
(1, 2, 'en attente'),
(2, 2, 'payé'),
(2, 3, 'en attente');





