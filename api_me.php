<?php
// ============================================
// API: Get User Information
// File: /api/me.php
// ============================================

require_once '../db_config.php';

// Get telegram_id from request
$telegram_id = $_POST['telegram_id'] ?? $_GET['telegram_id'] ?? null;

if (!$telegram_id) {
    echo json_encode([
        'success' => false,
        'error' => 'Telegram ID required'
    ]);
    exit;
}

try {
    // Get user from database
    $user = getUserByTelegramId($telegram_id);

    if (!$user) {
        // Create new user if doesn't exist
        $stmt = $pdo->prepare("
            INSERT INTO users (telegram_id, username, first_name, balance, plan) 
            VALUES (?, ?, ?, 0.00, 'free')
        ");
        $stmt->execute([
            $telegram_id,
            $_POST['username'] ?? 'user',
            $_POST['first_name'] ?? 'User'
        ]);

        // Get the newly created user
        $user = getUserByTelegramId($telegram_id);
    }

    // Update last_active
    $stmt = $pdo->prepare("UPDATE users SET last_active = NOW() WHERE telegram_id = ?");
    $stmt->execute([$telegram_id]);

    // Return user data
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'telegram_id' => $user['telegram_id'],
            'username' => $user['username'],
            'balance' => number_format($user['balance'], 2),
            'plan' => $user['plan'],
            'plan_expiry' => $user['plan_expiry'],
            'total_checks' => $user['total_checks'],
            'is_admin' => (bool)$user['is_admin'],
            'created_at' => $user['created_at']
        ]
    ]);

} catch (Exception $e) {
    error_log("Error in me.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch user data'
    ]);
}
?>
