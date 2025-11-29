<?php
// ============================================
// API: Admin Statistics
// File: /api/admin/stats.php
// ============================================

require_once '../../db_config.php';

// Get admin's telegram_id
$admin_telegram_id = $_POST['telegram_id'] ?? $_GET['telegram_id'] ?? null;

// Verify admin
if (!$admin_telegram_id || !isAdmin($admin_telegram_id)) {
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized'
    ]);
    exit;
}

try {
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $total_users = $stmt->fetch()['count'];

    // Total revenue (sum of all approved deposits)
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM deposits 
        WHERE status = 'approved'
    ");
    $total_revenue = $stmt->fetch()['total'];

    // Pending deposits
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM deposits 
        WHERE status = 'pending'
    ");
    $pending_deposits = $stmt->fetch()['count'];

    // Active premium/vip plans
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM users 
        WHERE plan IN ('premium', 'vip') 
        AND (plan_expiry IS NULL OR plan_expiry > NOW())
    ");
    $active_plans = $stmt->fetch()['count'];

    // Recent transactions (last 10)
    $stmt = $pdo->query("
        SELECT 
            t.*, 
            u.username, 
            u.first_name 
        FROM transactions t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC 
        LIMIT 10
    ");
    $recent_transactions = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_users' => $total_users,
            'total_revenue' => number_format($total_revenue, 2),
            'pending_deposits' => $pending_deposits,
            'active_plans' => $active_plans
        ],
        'recent_transactions' => $recent_transactions
    ]);

} catch (Exception $e) {
    error_log("Error in admin/stats.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch statistics'
    ]);
}
?>
