-- ==========================================================
-- College Management System - Database Initialization Script
-- Target: MySQL 8+ / MariaDB 10.5+
-- ==========================================================

CREATE DATABASE IF NOT EXISTS clg_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clg_cms;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS materials;
DROP TABLE IF EXISTS gallery;
DROP TABLE IF EXISTS notices;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS staff_users;
DROP TABLE IF EXISTS admin_users;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS pages;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  duration VARCHAR(64) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_courses_department (department_id),
  CONSTRAINT fk_courses_department FOREIGN KEY (department_id)
    REFERENCES departments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE staff_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NULL,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_staff_department (department_id),
  CONSTRAINT fk_staff_department FOREIGN KEY (department_id)
    REFERENCES departments(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NULL,
  course_id INT UNSIGNED NULL,
  enrollment_no VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_students_department (department_id),
  INDEX idx_students_course (course_id),
  CONSTRAINT fk_students_department FOREIGN KEY (department_id)
    REFERENCES departments(id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_students_course FOREIGN KEY (course_id)
    REFERENCES courses(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE notices (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_by INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  body TEXT NOT NULL,
  published_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_notices_published (published_at),
  CONSTRAINT fk_notices_staff FOREIGN KEY (created_by)
    REFERENCES staff_users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_by INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  event_date DATE NOT NULL,
  location VARCHAR(180) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_events_date (event_date),
  CONSTRAINT fk_events_staff FOREIGN KEY (created_by)
    REFERENCES staff_users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE gallery (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_by INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_gallery_created (created_at),
  CONSTRAINT fk_gallery_staff FOREIGN KEY (created_by)
    REFERENCES staff_users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,
  title VARCHAR(180) NOT NULL,
  content MEDIUMTEXT NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE materials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(180) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_messages_created (created_at)
) ENGINE=InnoDB;

-- Seed data
INSERT INTO departments (name, description) VALUES
('Science', 'Science department with modern labs and research-focused education.'),
('Commerce', 'Commerce and business studies with practical industry exposure.'),
('Arts', 'Humanities, languages and social sciences programs.');

INSERT INTO courses (department_id, name, duration) VALUES
(1, 'B.Sc. Computer Science', '3 Years'),
(2, 'B.Com', '3 Years'),
(3, 'B.A.', '3 Years');

-- Default password for all seeded users: Password@123
INSERT INTO admin_users (full_name, email, password_hash) VALUES
('System Admin', 'admin@huparicollege.edu', '$2y$12$i8SxHHfE2gF3M5/rfI.g/.6aW5hRD0Rm40aq0TwmoWIY9XzWKk4i6');

INSERT INTO staff_users (department_id, full_name, email, password_hash) VALUES
(1, 'Staff User', 'staff@huparicollege.edu', '$2y$12$i8SxHHfE2gF3M5/rfI.g/.6aW5hRD0Rm40aq0TwmoWIY9XzWKk4i6');

INSERT INTO students (department_id, course_id, enrollment_no, full_name, email, password_hash) VALUES
(1, 1, 'ACSCH2026001', 'Student User', 'student@huparicollege.edu', '$2y$12$i8SxHHfE2gF3M5/rfI.g/.6aW5hRD0Rm40aq0TwmoWIY9XzWKk4i6');

INSERT INTO notices (created_by, title, body) VALUES
(1, 'Semester Exam Schedule', 'Final semester exam timetable is now available.'),
(1, 'Library Timing Update', 'Library remains open until 8 PM on weekdays.');

INSERT INTO events (created_by, title, description, event_date, location) VALUES
(1, 'Annual Science Fair', 'Project displays and innovation challenge.', CURDATE() + INTERVAL 15 DAY, 'Main Auditorium'),
(1, 'Cultural Fest', 'Music, dance and drama competitions.', CURDATE() + INTERVAL 30 DAY, 'College Ground');

INSERT INTO gallery (created_by, title, image_path) VALUES
(1, 'Campus Front View', 'assets/images/sample.jpg'),
(1, 'Science Lab', 'assets/images/sample.jpg');

INSERT INTO pages (slug, title, content) VALUES
('about-us', 'About Us', 'This page content is editable from the admin CMS.'),
('principal-message', 'Principal Message', 'Welcome to our college.');

INSERT INTO materials (title, file_path) VALUES
('Academic Calendar PDF', 'uploads/docs/academic-calendar.pdf');
