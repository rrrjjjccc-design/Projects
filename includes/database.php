<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Generic query method
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Get all crime incidents
    public function getAllCrimes($limit = null, $offset = 0) {
        $sql = "SELECT c.*, cat.name as category_name
                FROM crime_incidents c
                LEFT JOIN crime_categories cat ON c.category_id = cat.id
                ORDER BY c.incident_date DESC";

        if ($limit) {
            $limit = intval($limit);
            $offset = intval($offset);
            $sql .= " LIMIT $offset, $limit";
            return $this->query($sql);
        }

        return $this->query($sql);
    }

    // Get crime by ID
    public function getCrimeById($id) {
        return $this->query(
            "SELECT c.*, cat.name as category_name
             FROM crime_incidents c
             LEFT JOIN crime_categories cat ON c.category_id = cat.id
             WHERE c.id = ?",
            [$id]
        )->fetch();
    }

    // Get crime statistics
    public function getCrimeStats() {
        $stats = [];

        // Total crimes
        $result = $this->query("SELECT COUNT(*) as total FROM crime_incidents")->fetch();
        $stats['total'] = $result['total'];

        // Crimes by status
        $result = $this->query("SELECT status, COUNT(*) as count FROM crime_incidents GROUP BY status");
        $stats['by_status'] = $result->fetchAll();

        // Crimes by category
        $result = $this->query("SELECT cat.name, COUNT(c.id) as count
                               FROM crime_categories cat
                               LEFT JOIN crime_incidents c ON cat.id = c.category_id
                               GROUP BY cat.id, cat.name");
        $stats['by_category'] = $result->fetchAll();

        // Recent crimes (last 30 days)
        $result = $this->query("SELECT COUNT(*) as recent FROM crime_incidents
                               WHERE incident_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $stats['recent'] = $result->fetch()['recent'];

        return $stats;
    }

    // Add new crime incident
    public function addCrime($data) {
        $sql = "INSERT INTO crime_incidents
                (category_id, title, description, location, latitude, longitude, incident_date, status, severity)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->query($sql, [
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['location'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['incident_date'],
            $data['status'] ?? 'reported',
            $data['severity'] ?? 'medium'
        ]);
    }

    // Update crime incident
    public function updateCrime($id, $data) {
        $sql = "UPDATE crime_incidents SET
                category_id = ?, title = ?, description = ?, location = ?,
                latitude = ?, longitude = ?, incident_date = ?, status = ?, severity = ?
                WHERE id = ?";

        return $this->query($sql, [
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['location'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['incident_date'],
            $data['status'],
            $data['severity'],
            $id
        ]);
    }

    // Delete crime incident
    public function deleteCrime($id) {
        return $this->query("DELETE FROM crime_incidents WHERE id = ?", [$id]);
    }

    // Get all categories
    public function getCategories() {
        return $this->query("SELECT * FROM crime_categories ORDER BY name");
    }

    // Authenticate admin
    public function authenticateAdmin($username, $password) {
        $stmt = $this->query("SELECT * FROM admins WHERE username = ?", [$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            return $admin;
        }

        return false;
    }
}
?>
