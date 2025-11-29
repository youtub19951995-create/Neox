<?php
// ============================================
// DATABASE CONNECTION CONFIGURATION
// ============================================
// EDIT THESE VALUES TO MATCH YOUR SERVER

// Database credentials
define('DB_HOST', 'localhost');      // Change if using remote DB
define('DB_NAME', 'cyborx_bot');     // Your database name
define('DB_USER', 'root');           // Your database username
define('DB_PASS', '');               // Your database password
define('DB_CHARSET', 'utf8mb4');

// Create PDO connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Log error and show generic message
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed. Please contact admin.'
    ]);
    exit;
}

// Helper function to get user by telegram_id
function getUserByTelegramId($telegram_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE telegram_id = ?");
    $stmt->execute([$telegram_id]);
    return $stmt->fetch();
}

// Helper function to update user balance
function updateUserBalance($user_id, $amount, $type = 'add') {
    global $pdo;

    // Get current balance
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $balance_before = $user['balance'];

    // Calculate new balance
    $balance_after = $type === 'add' 
        ? $balance_before + $amount 
        : $balance_before - $amount;

    // Update balance
    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$balance_after, $user_id]);

    return [
        'balance_before' => $balance_before,
        'balance_after' => $balance_after
    ];
}

// Helper function to log transaction
function logTransaction($user_id, $telegram_id, $type, $amount, $description = '') {
    global $pdo;

    // Get balances
    $balances = updateUserBalance($user_id, 0); // Get current without changing

    $stmt = $pdo->prepare("
        INSERT INTO transactions 
        (user_id, telegram_id, type, amount, description, balance_before, balance_after) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    return $stmt->execute([
        $user_id,
        $telegram_id,
        $type,
        $amount,
        $description,
        $balances['balance_before'],
        $balances['balance_after']
    ]);
}

// Helper function to check if user is admin
function isAdmin($telegram_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE telegram_id = ?");
    $stmt->execute([$telegram_id]);
    $user = $stmt->fetch();
    return $user && $user['is_admin'] == 1;
}

// CORS headers (if needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
