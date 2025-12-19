CREATE DATABASE IF NOT EXISTS `time_attendance_system-db` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `time_attendance_system-db`;
CREATE TABLE IF NOT EXISTS `company` (
    `companyId` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `department` (
    `departmentId` INT AUTO_INCREMENT PRIMARY KEY,
    `departmentName` VARCHAR(100) NOT NULL,
    `companyId` INT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`companyId`) REFERENCES `company`(`companyId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `employee` (
    `employeeid` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` TINYINT NOT NULL DEFAULT 2 COMMENT '1=Admin, 2=Employee, 3=Manager',
    `departmentId` INT,
    `shift` VARCHAR(50),
    `status` ENUM('aktiv', 'jo-aktiv') DEFAULT 'aktiv',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`departmentId`) REFERENCES `department`(`departmentId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `biometric_device` (
    `deviceId` INT AUTO_INCREMENT PRIMARY KEY,
    `serial` VARCHAR(100) UNIQUE NOT NULL,
    `description` VARCHAR(120),
    `location` VARCHAR(100),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `attendance` (
    `attendanceId` INT AUTO_INCREMENT PRIMARY KEY,
    `employeeId` INT NOT NULL,
    `date` DATE NOT NULL,
    `checkIn` DATETIME,
    `checkOut` DATETIME,
    `deviceId` INT,
    `notes` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employeeId`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE,
    FOREIGN KEY (`deviceId`) REFERENCES `biometric_device`(`deviceId`) ON DELETE SET NULL,
    UNIQUE KEY `unique_employee_date` (`employeeId`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `time_off_request` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `leave_type` VARCHAR(50) NOT NULL COMMENT 'pushim, mjekësor, personal, tjetër',
    `startdate` DATE NOT NULL,
    `enddate` DATE NOT NULL,
    `reason` TEXT,
    `medical_document` VARCHAR(255),
    `status` TINYINT DEFAULT 1 COMMENT '1=në pritje, 2=miratuar, 3=refuzuar',
    `approved_by` INT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `employee`(`employeeid`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `medical_verification` (
    `verificationId` INT AUTO_INCREMENT PRIMARY KEY,
    `timeoff_id` INT NOT NULL,
    `doctor_name` VARCHAR(100),
    `reason` TEXT,
    `medical_document` VARCHAR(255),
    `verified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`timeoff_id`) REFERENCES `time_off_request`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `notification` (
    `notificationId` INT AUTO_INCREMENT PRIMARY KEY,
    `sent_to` INT NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body` TEXT,
    `is_read` TINYINT DEFAULT 0 COMMENT '0=pa lexuar, 1=lexuar',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`sent_to`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `systemlog` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employeeId` INT,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT,
    `ip_address` VARCHAR(45),
    `action_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employeeId`) REFERENCES `employee`(`employeeid`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `insights_report` (
    `reportId` INT AUTO_INCREMENT PRIMARY KEY,
    `manager_id` INT NOT NULL,
    `type` VARCHAR(100),
    `period` DATE,
    `content` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`manager_id`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `person_biometric` (
    `employeeid` INT,
    `deviceId` INT,
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`employeeid`, `deviceId`),
    FOREIGN KEY (`employeeid`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE,
    FOREIGN KEY (`deviceId`) REFERENCES `biometric_device`(`deviceId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `leave_balance` (
    `balanceId` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `leave_type` VARCHAR(50) NOT NULL,
    `balance_days` DECIMAL(5,2) DEFAULT 0.00,
    `year` YEAR NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE,
    UNIQUE KEY `unique_employee_leave_year` (`employee_id`, `leave_type`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `holiday` (
    `holidayId` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `date` DATE NOT NULL,
    `is_recurring` TINYINT DEFAULT 0 COMMENT '0=vetëm këtë vit, 1=përsëritet çdo vit',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_holiday_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `system_settings` (
    `settingId` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT,
    `description` VARCHAR(255),
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `password_reset` (
    `resetId` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `used` TINYINT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employee`(`employeeid`) ON DELETE CASCADE,
    INDEX `idx_token` (`token`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP INDEX IF EXISTS `idx_employee_email` ON `employee`;
CREATE INDEX `idx_employee_email` ON `employee`(`email`);

DROP INDEX IF EXISTS `idx_employee_role` ON `employee`;
CREATE INDEX `idx_employee_role` ON `employee`(`role`);
DROP INDEX IF EXISTS `idx_attendance_date` ON `attendance`;
CREATE INDEX `idx_attendance_date` ON `attendance`(`date`);
DROP INDEX IF EXISTS `idx_timeoff_status` ON `time_off_request`;
CREATE INDEX `idx_timeoff_status` ON `time_off_request`(`status`);
DROP INDEX IF EXISTS `idx_attendance_employee_date` ON `attendance`;
CREATE INDEX `idx_attendance_employee_date` ON `attendance`(`employeeId`, `date`);

DROP INDEX IF EXISTS `idx_timeoff_startdate` ON `time_off_request`;
CREATE INDEX `idx_timeoff_startdate` ON `time_off_request`(`startdate`);

DROP INDEX IF EXISTS `idx_timeoff_enddate` ON `time_off_request`;
CREATE INDEX `idx_timeoff_enddate` ON `time_off_request`(`enddate`);

DROP INDEX IF EXISTS `idx_notification_is_read` ON `notification`;
CREATE INDEX `idx_notification_is_read` ON `notification`(`is_read`, `sent_to`);

DROP INDEX IF EXISTS `idx_systemlog_action_time` ON `systemlog`;
CREATE INDEX `idx_systemlog_action_time` ON `systemlog`(`action_time`);

DROP INDEX IF EXISTS `idx_employee_status` ON `employee`;
CREATE INDEX `idx_employee_status` ON `employee`(`status`);
DROP INDEX IF EXISTS `idx_employee_department_role` ON `employee`;
CREATE INDEX `idx_employee_department_role` ON `employee`(`departmentId`, `role`);
INSERT IGNORE INTO `company` (`companyId`, `name`) VALUES (1, 'Kompania Test');
INSERT IGNORE INTO `department` (`departmentId`, `departmentName`, `companyId`) VALUES 
(1, 'IT', 1),
(2, 'Financa', 1),
(3, 'Burimet Njerëzore', 1);
INSERT IGNORE INTO `employee` (`employeeid`, `name`, `email`, `password`, `role`, `departmentId`) VALUES 
(1, 'Administrator', 'admin@test.com', 'admin123', 1, 1);
INSERT IGNORE INTO `employee` (`employeeid`, `name`, `email`, `password`, `role`, `departmentId`) VALUES 
(2, 'Menaxher Test', 'manager@test.com', 'manager123', 3, 1);
INSERT IGNORE INTO `employee` (`employeeid`, `name`, `email`, `password`, `role`, `departmentId`) VALUES 
(3, 'Punonjës Test', 'employee@test.com', 'employee123', 2, 1);
INSERT IGNORE INTO `biometric_device` (`deviceId`, `serial`, `description`, `location`) VALUES 
(1, 'BIOMETRIC-001', 'Fingerprint Scanner - Hyrja Kryesore', 'Hyrja Kryesore'),
(2, 'BIOMETRIC-002', 'Fingerprint Scanner - Zyra', 'Zyra');
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `trg_attendance_checkout_validation`
BEFORE UPDATE ON `attendance`
FOR EACH ROW
BEGIN
    IF NEW.checkOut IS NOT NULL AND NEW.checkIn IS NOT NULL THEN
        IF NEW.checkOut < NEW.checkIn THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'CheckOut time must be after CheckIn time';
        END IF;
    END IF;
END//
DELIMITER ;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `trg_timeoff_date_validation`
BEFORE INSERT ON `time_off_request`
FOR EACH ROW
BEGIN
    IF NEW.enddate < NEW.startdate THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'End date must be after or equal to start date';
    END IF;
END//
DELIMITER ;
DELIMITER //
CREATE TRIGGER IF NOT EXISTS `trg_employee_created_log`
AFTER INSERT ON `employee`
FOR EACH ROW
BEGIN
    INSERT INTO `systemlog` (`employeeId`, `action`, `details`)
    VALUES (NEW.employeeid, 'Employee Created', CONCAT('New employee created: ', NEW.name, ' (', NEW.email, ')'));
END//
DELIMITER ;
CREATE OR REPLACE VIEW `v_attendance_summary` AS
SELECT 
    e.employeeid,
    e.name,
    e.email,
    d.departmentName,
    COUNT(a.attendanceId) as total_days,
    SUM(CASE WHEN a.checkIn IS NOT NULL AND a.checkOut IS NOT NULL THEN 1 ELSE 0 END) as complete_days,
    SUM(CASE WHEN a.checkIn IS NOT NULL AND a.checkOut IS NULL THEN 1 ELSE 0 END) as incomplete_days
FROM employee e
LEFT JOIN department d ON e.departmentId = d.departmentId
LEFT JOIN attendance a ON e.employeeid = a.employeeId
GROUP BY e.employeeid, e.name, e.email, d.departmentName;
CREATE OR REPLACE VIEW `v_timeoff_details` AS
SELECT 
    tor.id,
    tor.employee_id,
    e.name as employee_name,
    e.email,
    d.departmentName,
    tor.leave_type,
    tor.startdate,
    tor.enddate,
    DATEDIFF(tor.enddate, tor.startdate) + 1 as days_requested,
    tor.reason,
    tor.status,
    CASE 
        WHEN tor.status = 1 THEN 'Në pritje'
        WHEN tor.status = 2 THEN 'Miratuar'
        WHEN tor.status = 3 THEN 'Refuzuar'
    END as status_text,
    tor.approved_by,
    approver.name as approved_by_name,
    tor.created_at
FROM time_off_request tor
JOIN employee e ON tor.employee_id = e.employeeid
LEFT JOIN department d ON e.departmentId = d.departmentId
LEFT JOIN employee approver ON tor.approved_by = approver.employeeid;
CREATE OR REPLACE VIEW `v_unread_notifications` AS
SELECT 
    n.notificationId,
    n.sent_to,
    e.name as employee_name,
    n.subject,
    n.body,
    n.created_at,
    TIMESTAMPDIFF(HOUR, n.created_at, NOW()) as hours_ago
FROM notification n
JOIN employee e ON n.sent_to = e.employeeid
WHERE n.is_read = 0
ORDER BY n.created_at DESC;
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_calculate_work_hours`(
    IN p_employee_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT 
        e.name,
        a.date,
        a.checkIn,
        a.checkOut,
        TIMESTAMPDIFF(HOUR, a.checkIn, a.checkOut) as hours_worked,
        TIMESTAMPDIFF(MINUTE, a.checkIn, a.checkOut) / 60.0 as hours_worked_decimal
    FROM attendance a
    JOIN employee e ON a.employeeId = e.employeeid
    WHERE a.employeeId = p_employee_id
    AND a.date BETWEEN p_start_date AND p_end_date
    AND a.checkIn IS NOT NULL
    AND a.checkOut IS NOT NULL
    ORDER BY a.date;
END//
DELIMITER ;
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_department_attendance_stats`(
    IN p_department_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT 
        d.departmentName,
        COUNT(DISTINCT e.employeeid) as total_employees,
        COUNT(a.attendanceId) as total_attendance_records,
        SUM(CASE WHEN a.checkIn IS NOT NULL AND a.checkOut IS NOT NULL THEN 1 ELSE 0 END) as complete_attendance,
        SUM(CASE WHEN a.checkIn IS NOT NULL AND a.checkOut IS NULL THEN 1 ELSE 0 END) as incomplete_attendance
    FROM department d
    LEFT JOIN employee e ON d.departmentId = e.departmentId
    LEFT JOIN attendance a ON e.employeeid = a.employeeId 
        AND a.date BETWEEN p_start_date AND p_end_date
    WHERE d.departmentId = p_department_id
    GROUP BY d.departmentId, d.departmentName;
END//
DELIMITER ;
DROP FUNCTION IF EXISTS `fn_working_days`;

DELIMITER //

CREATE FUNCTION `fn_working_days`(start_date DATE, end_date DATE)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE days_count INT DEFAULT 0;
    DECLARE cur_date DATE DEFAULT start_date;
    
    WHILE cur_date <= end_date DO
        IF DAYOFWEEK(cur_date) NOT IN (1, 7) THEN
            SET days_count = days_count + 1;
        END IF;
        SET cur_date = DATE_ADD(cur_date, INTERVAL 1 DAY);
    END WHILE;
    
    RETURN days_count;
END//

DELIMITER ;
ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `is_late` TINYINT DEFAULT 0 COMMENT '1=late, 0=on time';
ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `is_early_departure` TINYINT DEFAULT 0 COMMENT '1=early departure, 0=normal';
INSERT IGNORE INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('max_leave_days', '20', 'Maximum leave days per year'),
('working_hours_per_day', '8', 'Standard working hours per day'),
('attendance_grace_period', '15', 'Grace period in minutes for late arrival'),
('notification_enabled', '1', 'Enable/disable notifications');
INSERT IGNORE INTO `leave_balance` (`employee_id`, `leave_type`, `balance_days`, `year`) VALUES
(1, 'pushim', 20.00, YEAR(NOW())),
(2, 'pushim', 20.00, YEAR(NOW())),
(3, 'pushim', 20.00, YEAR(NOW()));
INSERT IGNORE INTO `holiday` (`name`, `date`, `is_recurring`) VALUES
('Dita e Vitit të Ri', '2024-01-01', 1),
('Dita e Pavarësisë dhe Flamurit', '2024-11-28', 1),
('Dita e Verës', '2024-03-14', 1);
ALTER TABLE `employee` COMMENT = 'Tabela kryesore për punonjësit, menaxherët dhe administratorët';
ALTER TABLE `attendance` COMMENT = 'Regjistrimi i hyrjes dhe daljes së punonjësve';
ALTER TABLE `time_off_request` COMMENT = 'Kërkesat për pushime dhe leje';
ALTER TABLE `notification` COMMENT = 'Sistemi i njoftimeve për përdoruesit';
DROP TRIGGER IF EXISTS `trg_timeoff_approved_notification`;
