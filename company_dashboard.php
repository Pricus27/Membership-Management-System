<?php
require 'config.php';
require_role('company');

$company_id = $_SESSION['company_id'];

$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$company_id]);
$company = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM members WHERE company_id = ? ORDER BY created_at DESC");
$stmt->execute([$company_id]);
$members = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($company['name'] ?? 'Company') ?> - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3><?= htmlspecialchars($company['name'] ?? 'Company') ?> Memberships</h3>
            <p class="text-muted mb-0">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></p>
        </div>
        <div>
            <a href="member_form.php" class="btn btn-primary">Add Member</a>
            <a href="logout.php" class="btn btn-outline-secondary">Logout</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Members</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th><th>Name</th><th>Type</th><th>Status</th>
                    <th>Join Date</th><th>Expires</th><th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['membership_id']) ?></td>
                        <td><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></td>
                        <td><?= htmlspecialchars($m['membership_type']) ?></td>
                        <td><?= htmlspecialchars($m['status']) ?></td>
                        <td><?= $m['join_date'] ?></td>
                        <td><?= $m['expiration_date'] ?></td>
                        <td>
                            <a href="member_form.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$members): ?>
                    <tr><td colspan="7" class="text-center text-muted">No members yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
