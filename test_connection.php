<?php
require_once 'includes/database.php';

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    // Test with a simple query
    $stmt = $connection->query("SELECT 1 as test");
    $result = $stmt->fetch();

    echo "Database connection successful! Test query result: " . $result['test'];

    // Test actual data
    $crimes = $db->getAllCrimes(1); // Get 1 crime
    echo "<br>Found " . $crimes->rowCount() . " crime records in database.";

} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
