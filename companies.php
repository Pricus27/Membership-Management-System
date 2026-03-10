<?php
require 'config.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO companies (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $desc]);
    }
    header('Location: companies.php');
    exit;
}

$companies = $pdo->query("SELECT * FROM companies ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Companies - MMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Companies</h3>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Add Company</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input name="description" class="form-control">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Existing Companies</div>
        <table class="table mb-0">
            <thead>
            <tr>
                <th>#</th><th>Name</th><th>Description</th><th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($companies as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td><?= $c['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
