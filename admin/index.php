<?php
try {
    // Use absolute paths to prevent include errors
    $includes_path = dirname(__DIR__) . '/includes/';
    require_once $includes_path . 'config.php';
    require_once $includes_path . 'database.php';
    require_once $includes_path . 'functions.php';

    // requireAdmin(); // Disabled for now
} catch (Exception $e) {
    // Fallback error page
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
    echo "<h1>Application Error</h1><p>Please contact administrator.</p>";
    echo "<!-- Error: " . htmlspecialchars($e->getMessage()) . " -->";
    echo "</body></html>";
    exit;
}

$db = Database::getInstance();
$message = displayMessage();

// Get crime data for admin dashboard
$stats = $db->getCrimeStats();
$recentCrimes = $db->getAllCrimes(5)->fetchAll();
$categories = $db->getCategories()->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt me-2"></i><?php echo SITE_NAME; ?> - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i>Welcome, <?php echo isset($_SESSION['admin_username']) ? sanitize($_SESSION['admin_username']) : 'Admin'; ?>
                </span>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php echo $message; ?>

        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fade-in-up">
                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                </h1>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card slide-in-right">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="add-crime.php" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Add Crime Incident
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="crimes.php" class="btn btn-primary w-100">
                                    <i class="fas fa-list me-2"></i>Manage Crimes
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="categories.php" class="btn btn-info w-100">
                                    <i class="fas fa-tags me-2"></i>Manage Categories
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="../public/index.php" class="btn btn-secondary w-100" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>View Public Site
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i> Total Crimes
                        </h5>
                        <h3 class="pulse-animation"><?php echo $stats['total']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clock"></i> Reported
                        </h5>
                        <h3 class="pulse-animation"><?php echo getStatusCount($stats['by_status'], 'reported'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-search"></i> Investigating
                        </h5>
                        <h3 class="pulse-animation"><?php echo getStatusCount($stats['by_status'], 'investigating'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-check-circle"></i> Resolved
                        </h5>
                        <h3 class="pulse-animation"><?php echo getStatusCount($stats['by_status'], 'resolved'); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Crimes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Crime Incidents
                        </h5>
                        <a href="crimes.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentCrimes)): ?>
                            <p class="text-center text-muted">No crime incidents found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentCrimes as $crime): ?>
                                            <tr>
                                                <td><?php echo formatDate($crime['incident_date']); ?></td>
                                                <td><?php echo sanitize($crime['title']); ?></td>
                                                <td><?php echo sanitize($crime['category_name']); ?></td>
                                                <td><?php echo sanitize($crime['location'] ?: 'N/A'); ?></td>
                                                <td><?php echo getStatusBadge($crime['status']); ?></td>
                                                <td>
                                                    <a href="edit-crime.php?id=<?php echo $crime['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
