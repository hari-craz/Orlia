-- Ensure the application user exists (runs on first init)
CREATE USER IF NOT EXISTS 'orlia'@'%' IDENTIFIED BY 'orlia';
GRANT ALL PRIVILEGES ON `orlia`.* TO 'orlia'@'%';
FLUSH PRIVILEGES;
