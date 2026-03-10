<?php
require 'config.php';
require_role('admin');

$sql = "SELECT m.*, c.name AS company_name
        FROM members m
        JOIN companies c ON c.id = m.company_id
        ORDER BY m.created_at DESC";
$members = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Members - MMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>All Members</h3>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Company</th><th>Membership ID</th><th>Name</th>
                    <th>Type</th><th>Status</th><th>Join</th><th>Expires</th><th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['company_name']) ?></td>
                        <td><?= htmlspecialchars($m['membership_id']) ?></td>
                        <td><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></td>
                        <td><?= htmlspecialchars($m['membership_type']) ?></td>
                        <td><?= htmlspecialchars($m['status']) ?></td>
                        <td><?= $m['join_date'] ?></td>
                        <td><?= $m['expiration_date'] ?></td>
                        <td>
                            <a href="member_form.php?id=<?= $m['id'] ?>&company_id=<?= $m['company_id'] ?>"
                               class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$members): ?>
                    <tr><td colspan="8" class="text-center text-muted">No members found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

