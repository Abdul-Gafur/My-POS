-- SQL script to add dateAdded column to eventlog table
-- Run this query in your database to fix the missing column issue

-- For MySQL/MariaDB:
ALTER TABLE `eventlog` ADD COLUMN `dateAdded` DATETIME DEFAULT CURRENT_TIMESTAMP;

-- For SQLite:
-- ALTER TABLE `eventlog` ADD COLUMN `dateAdded` DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Update existing records (if any) with current timestamp
UPDATE `eventlog` SET `dateAdded` = NOW() WHERE `dateAdded` IS NULL;

