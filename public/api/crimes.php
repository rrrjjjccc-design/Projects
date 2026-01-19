<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../../includes/config.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';

try {
    $db = Database::getInstance();

    // Get parameters
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    // Validate limit
    if ($limit && ($limit < 1 || $limit > 100)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid limit parameter (1-100)']);
        exit;
    }

    $crimes = $db->getAllCrimes($limit, $offset)->fetchAll();

    echo json_encode($crimes);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to load crime data',
        'message' => $e->getMessage()
    ]);
}
?>
