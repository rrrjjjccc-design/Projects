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
    $stats = $db->getCrimeStats();

    echo json_encode($stats);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to load statistics',
        'message' => $e->getMessage()
    ]);
}
?>
