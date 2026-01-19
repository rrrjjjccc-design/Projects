-- Crime Monitoring Database Schema
-- Run this file to set up the database

CREATE DATABASE IF NOT EXISTS crime_monitor;
USE crime_monitor;

-- Admin users table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crime categories
CREATE TABLE crime_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Crime incidents table
CREATE TABLE crime_incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    incident_date DATE NOT NULL,
    reported_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('reported', 'investigating', 'resolved') DEFAULT 'reported',
    severity ENUM('low', 'medium', 'high') DEFAULT 'medium',
    FOREIGN KEY (category_id) REFERENCES crime_categories(id)
);

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admins (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@crimemonitor.com');

-- Insert sample crime categories
INSERT INTO crime_categories (name, description) VALUES
('Theft', 'Theft and burglary incidents'),
('Assault', 'Physical assault cases'),
('Vandalism', 'Property damage and vandalism'),
('Fraud', 'Financial fraud and scams'),
('Traffic', 'Traffic violations and accidents'),
('Other', 'Other types of crimes');

-- Insert sample crime data
INSERT INTO crime_incidents (category_id, title, description, location, latitude, longitude, incident_date, status, severity) VALUES
(1, 'Burglary at residential area', 'Break-in at 123 Main St, valuables stolen', '123 Main Street', 40.7128, -74.0060, '2024-01-15', 'investigating', 'high'),
(2, 'Street assault', 'Assault reported in downtown area', 'Downtown Plaza', 40.7589, -73.9851, '2024-01-14', 'resolved', 'medium'),
(3, 'Graffiti on public property', 'Vandalism on city hall wall', 'City Hall', 40.7505, -73.9934, '2024-01-13', 'reported', 'low');
