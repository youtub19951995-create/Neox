-- ============================================
-- DATABASE SCHEMA FOR BOT SYSTEM
-- ============================================
-- Run this SQL file to create all necessary tables

-- Create database (change name if needed)
CREATE DATABASE IF NOT EXISTS cyborx_bot;
USE cyborx_bot;

-- ============================================
-- 1. USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  telegram_id BIGINT UNIQUE NOT NULL,
  username VARCHAR(255),
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  balance DECIMAL(10, 2) DEFAULT 0.00,
  plan VARCHAR(50) DEFAULT 'free',
  plan_expiry DATETIME NULL,
  total_checks INT DEFAULT 0,
  is_admin BOOLEAN DEFAULT FALSE,
  is_banned BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_telegram_id (telegram_id),
  INDEX idx_plan (plan),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. DEPOSITS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS deposits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  telegram_id BIGINT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  crypto_type VARCHAR(20) NOT NULL, -- BTC, USDT, LTC, TRX, etc.
  crypto_amount DECIMAL(20, 8),
  wallet_address VARCHAR(255) NOT NULL,
  tx_hash VARCHAR(255),
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  screenshot_url VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  approved_at TIMESTAMP NULL,
  approved_by INT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. TRANSACTIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  telegram_id BIGINT NOT NULL,
  type ENUM('deposit', 'purchase', 'redeem', 'admin_add', 'admin_deduct') NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  description TEXT,
  balance_before DECIMAL(10, 2),
  balance_after DECIMAL(10, 2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_type (type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. REDEEM CODES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS redeem_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  max_uses INT DEFAULT 1,
  current_uses INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_by INT,
  expires_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_code (code),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. REDEEM HISTORY TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS redeem_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  telegram_id BIGINT NOT NULL,
  code_id INT NOT NULL,
  code VARCHAR(50) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  redeemed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (code_id) REFERENCES redeem_codes(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_code_id (code_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. PROXIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS proxies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  proxy_string TEXT NOT NULL,
  proxy_type ENUM('http', 'socks4', 'socks5') DEFAULT 'http',
  is_active BOOLEAN DEFAULT TRUE,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. CHECKER HISTORY TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS checker_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  telegram_id BIGINT NOT NULL,
  card_hash VARCHAR(64),
  result ENUM('approved', 'declined', 'error') NOT NULL,
  response_text TEXT,
  api_used VARCHAR(100),
  checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_result (result),
  INDEX idx_checked_at (checked_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. SETTINGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. ADMIN LOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS admin_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  action VARCHAR(255) NOT NULL,
  target_user_id INT,
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_admin_id (admin_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT SETTINGS
-- ============================================
INSERT INTO settings (setting_key, setting_value) VALUES
('bot_name', 'CyborX'),
('maintenance_mode', '0'),
('min_deposit', '1.00'),
('xcoin_to_usdt_rate', '1.00')
ON DUPLICATE KEY UPDATE setting_value=setting_value;

-- ============================================
-- CREATE ADMIN USER (CHANGE TELEGRAM_ID)
-- ============================================
INSERT INTO users (telegram_id, username, first_name, is_admin, balance)
VALUES (123456789, 'admin', 'Admin', TRUE, 0.00)
ON DUPLICATE KEY UPDATE is_admin=TRUE;

-- Success message
SELECT 'Database setup complete!' AS message;
