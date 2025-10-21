## Database

```
CREATE DATABASE IF NOT EXISTS ctrike;
USE ctrike;

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- MILEAGE TABLE
CREATE TABLE IF NOT EXISTS mileage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    distance FLOAT,
    travel_time FLOAT,
    average_speed FLOAT,
    week_start DATE,
    week_end DATE,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mileage_user FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- BATTERY TABLE
CREATE TABLE IF NOT EXISTS battery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    voltage FLOAT,
    temperature FLOAT,
    status VARCHAR(20) DEFAULT 'Normal',
    week_start DATE,
    week_end DATE,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_battery_user FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- MOTOR TABLE
CREATE TABLE IF NOT EXISTS motor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    vibration FLOAT,
    temperature FLOAT,
    status VARCHAR(20) DEFAULT 'Normal',
    week_start DATE,
    week_end DATE,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_motor_user FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- TIRE TABLE
CREATE TABLE IF NOT EXISTS tire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    pressure FLOAT,
    tire_condition VARCHAR(20) DEFAULT 'Good',
    week_start DATE,
    week_end DATE,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tire_user FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- TEST USER
INSERT INTO users (username, password)
VALUES ('admin', 'admin123');

-- DEFINE WEEK RANGE
SET @week_start = '2025-10-20';
SET @week_end = '2025-10-26';

-- BATTERY DATA (one entry per day)
INSERT INTO battery (owner_id, voltage, temperature, week_start, week_end) VALUES
(1, 48.0, 34, @week_start, @week_end),
(1, 47.8, 35, @week_start, @week_end),
(1, 47.6, 36, @week_start, @week_end),
(1, 47.5, 35, @week_start, @week_end),
(1, 47.3, 34, @week_start, @week_end),
(1, 47.2, 34, @week_start, @week_end),
(1, 47.0, 33, @week_start, @week_end);

-- MOTOR DATA
INSERT INTO motor (owner_id, vibration, temperature, week_start, week_end) VALUES
(1, 2.1, 36, @week_start, @week_end),
(1, 2.2, 37, @week_start, @week_end),
(1, 2.3, 35, @week_start, @week_end),
(1, 2.4, 38, @week_start, @week_end),
(1, 2.5, 36, @week_start, @week_end),
(1, 2.4, 37, @week_start, @week_end),
(1, 2.3, 35, @week_start, @week_end);

-- MILEAGE DATA
INSERT INTO mileage (owner_id, distance, travel_time, average_speed, week_start, week_end) VALUES
(1, 20, 2.5, 45, @week_start, @week_end),
(1, 25, 3, 48, @week_start, @week_end),
(1, 22, 2.8, 50, @week_start, @week_end),
(1, 28, 4, 52, @week_start, @week_end),
(1, 30, 3.5, 55, @week_start, @week_end),
(1, 35, 5, 60, @week_start, @week_end),
(1, 33, 4.5, 58, @week_start, @week_end);

-- TIRE DATA
INSERT INTO tire (owner_id, pressure, tire_condition, week_start, week_end) VALUES
(1, 32.0, 'Good', @week_start, @week_end),
(1, 31.9, 'Good', @week_start, @week_end),
(1, 31.8, 'Good', @week_start, @week_end),
(1, 31.7, 'Good', @week_start, @week_end),
(1, 31.6, 'Good', @week_start, @week_end),
(1, 31.5, 'Good', @week_start, @week_end),
(1, 31.4, 'Good', @week_start, @week_end);
```