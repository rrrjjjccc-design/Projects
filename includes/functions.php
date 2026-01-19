<?php
require_once 'config.php';

// Start session if not already started
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        session_set_cookie_params(SESSION_LIFETIME);
    }
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    startSession();
    return isset($_SESSION['admin_id']);
}

// Require admin login
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '../admin/login.php');
        exit;
    }
}

// Logout admin
function logoutAdmin() {
    startSession();
    session_destroy();
    header('Location: ' . SITE_URL . '../admin/login.php');
    exit;
}

// Generate CSRF token
function generateCSRFToken() {
    startSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH / 2));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate date
function isValidDate($date) {
    return (bool)strtotime($date);
}

// Format date for display
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

// Get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'reported': return 'badge-warning';
        case 'investigating': return 'badge-info';
        case 'resolved': return 'badge-success';
        default: return 'badge-secondary';
    }
}

// Get status badge HTML
function getStatusBadge($status) {
    $class = getStatusBadgeClass($status);
    return "<span class=\"badge $class\">" . ucfirst($status) . "</span>";
}

// Get severity badge class
function getSeverityBadgeClass($severity) {
    switch ($severity) {
        case 'low': return 'badge-success';
        case 'medium': return 'badge-warning';
        case 'high': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

// Pagination helper
function getPaginationData($totalItems, $itemsPerPage, $currentPage) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));

    return [
        'total_items' => $totalItems,
        'items_per_page' => $itemsPerPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => ($currentPage - 1) * $itemsPerPage,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'previous_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

// Get current page from URL
function getCurrentPage() {
    return isset($_GET['page']) ? (int)$_GET['page'] : 1;
}

// Redirect with message
function redirectWithMessage($url, $message, $type = 'success') {
    startSession();
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header('Location: ' . $url);
    exit;
}

// Display message
function displayMessage() {
    startSession();
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';
        unset($_SESSION['message'], $_SESSION['message_type']);

        $alertClass = $type === 'error' ? 'alert-danger' : 'alert-success';
        return "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
    return '';
}

// Get base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = str_replace('/public/', '/', dirname($script));

    return $protocol . '://' . $host . $path;
}

// Get status count from status array
function getStatusCount($statusArray, $status) {
    foreach ($statusArray as $item) {
        if ($item['status'] === $status) {
            return $item['count'];
        }
    }
    return 0;
}
?>
