<?php
try {
    // Use absolute paths to prevent include errors
    $includes_path = dirname(__DIR__) . '/includes/';
    require_once $includes_path . 'config.php';
    require_once $includes_path . 'database.php';
    require_once $includes_path . 'functions.php';
} catch (Exception $e) {
    // Fallback error page
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
    echo "<h1>Application Error</h1><p>Please contact administrator.</p>";
    echo "<!-- Error: " . htmlspecialchars($e->getMessage()) . " -->";
    echo "</body></html>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Crime Statistics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt me-2"></i><?php echo SITE_NAME; ?>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?php echo str_replace('/public/', '/admin/', SITE_URL) . 'index.php'; ?>">
                    <i class="fas fa-user-shield"></i> Admin Panel
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="fade-in-up">
                    <i class="fas fa-chart-line me-2"></i>Crime Statistics Dashboard
                </h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4" id="stats-cards">
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i> Total Crimes
                        </h5>
                        <h3 id="total-crimes" class="pulse-animation">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clock"></i> Reported
                        </h5>
                        <h3 id="reported-crimes" class="pulse-animation">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-search"></i> Investigating
                        </h5>
                        <h3 id="investigating-crimes" class="pulse-animation">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card fade-in">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-check-circle"></i> Resolved
                        </h5>
                        <h3 id="resolved-crimes" class="pulse-animation">-</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Crimes Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Recent Crime Incidents
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="crimes-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Severity</th>
                                    </tr>
                                </thead>
                                <tbody id="crimes-table-body">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
