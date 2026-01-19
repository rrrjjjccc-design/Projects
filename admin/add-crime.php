<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// requireAdmin(); // Disabled for now

$db = Database::getInstance();

$message = '';

$categories = $db->getCategories()->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'title' => sanitize($_POST['title'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'location' => sanitize($_POST['location'] ?? ''),
        'latitude' => !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null,
        'longitude' => !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null,
        'incident_date' => $_POST['incident_date'] ?? '',
        'status' => $_POST['status'] ?? 'reported',
        'severity' => $_POST['severity'] ?? 'medium'
    ];

    // Validation
    $errors = [];

    if (empty($data['title'])) {
        $errors[] = 'Title is required.';
    }

    if (empty($data['incident_date']) || !isValidDate($data['incident_date'])) {
        $errors[] = 'Valid incident date is required.';
    }

    if ($data['category_id'] <= 0) {
        $errors[] = 'Please select a crime category.';
    }

    if (empty($errors)) {
        try {
            $db->addCrime($data);
            redirectWithMessage('index.php', 'Crime incident added successfully!', 'success');
        } catch (Exception $e) {
            $message = 'Failed to add crime incident: ' . $e->getMessage();
        }
    } else {
        $message = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Crime Incident - <?php echo SITE_NAME; ?> Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shield-alt me-2"></i><?php echo SITE_NAME; ?> - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Crime Incident</li>
                    </ol>
                </nav>

                <h1 class="mb-4">
                    <i class="fas fa-plus-circle me-2"></i>Add Crime Incident
                </h1>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $message; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading me-1"></i>Title *
                                    </label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="<?php echo sanitize($_POST['title'] ?? ''); ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Category *
                                    </label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>"
                                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitize($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo sanitize($_POST['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Location
                                    </label>
                                    <input type="text" class="form-control" id="location" name="location"
                                           value="<?php echo sanitize($_POST['location'] ?? ''); ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="incident_date" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Incident Date *
                                    </label>
                                    <input type="date" class="form-control" id="incident_date" name="incident_date"
                                           value="<?php echo sanitize($_POST['incident_date'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-info-circle me-1"></i>Status
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="reported" <?php echo (($_POST['status'] ?? 'reported') === 'reported') ? 'selected' : ''; ?>>Reported</option>
                                        <option value="investigating" <?php echo (($_POST['status'] ?? '') === 'investigating') ? 'selected' : ''; ?>>Investigating</option>
                                        <option value="resolved" <?php echo (($_POST['status'] ?? '') === 'resolved') ? 'selected' : ''; ?>>Resolved</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="severity" class="form-label">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Severity
                                    </label>
                                    <select class="form-select" id="severity" name="severity">
                                        <option value="low" <?php echo (($_POST['severity'] ?? 'medium') === 'low') ? 'selected' : ''; ?>>Low</option>
                                        <option value="medium" <?php echo (($_POST['severity'] ?? 'medium') === 'medium') ? 'selected' : ''; ?>>Medium</option>
                                        <option value="high" <?php echo (($_POST['severity'] ?? '') === 'high') ? 'selected' : ''; ?>>High</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-globe me-1"></i>Coordinates (Optional)
                                    </label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" step="any" class="form-control" name="latitude"
                                                   placeholder="Latitude" value="<?php echo sanitize($_POST['latitude'] ?? ''); ?>">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" step="any" class="form-control" name="longitude"
                                                   placeholder="Longitude" value="<?php echo sanitize($_POST['longitude'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Add Incident
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
