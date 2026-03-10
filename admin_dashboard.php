<?php
require 'config.php';
require_role('admin');

$companies_count = $pdo->query("SELECT COUNT(*) AS c FROM companies")->fetch()['c'] ?? 0;
$members_count   = $pdo->query("SELECT COUNT(*) AS c FROM members")->fetch()['c'] ?? 0;
$company_users   = $pdo->query("SELECT COUNT(*) AS c FROM users WHERE role='company'")->fetch()['c'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - MMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f7fb; }
        .sidebar {
            min-height:100vh;
            background:#0f172a;
            color:#e5e7eb;
            padding-top:1.5rem;
        }
        .sidebar-title {
            font-weight:600;
            letter-spacing:.04em;
        }
        .nav-section-title {
            font-size:.75rem;
            text-transform:uppercase;
            letter-spacing:.08em;
            color:#6b7280;
            margin:1.5rem 0 .5rem;
        }
        .sidebar a {
            color:#9ca3af;
            text-decoration:none;
            display:block;
            padding:.55rem 1.3rem;
            border-radius:.75rem;
            font-size:.95rem;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background:#111827;
            color:#fff;
        }
        .content {
            padding:1.5rem 2.5rem 2.5rem;
        }
        .top-bar {
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:1.5rem;
        }
        .search-box {
            max-width:340px;
        }
        .kanban-column {
            background:#ffffff;
            border-radius:1rem;
            padding:1rem;
            box-shadow:0 12px 30px rgba(15,23,42,.08);
        }
        .kanban-header {
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:.75rem;
        }
        .pill {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:22px;
            height:22px;
            border-radius:999px;
            background:#e5e7eb;
            font-size:.8rem;
        }
        .summary-badge {
            font-size:.8rem;
            color:#6b7280;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 sidebar d-flex flex-column">
            <div class="px-3 mb-4">
                <div class="d-flex align-items-center mb-1">
                    <div class="me-2 rounded-circle bg-white text-dark d-flex align-items-center justify-content-center" style="width:30px;height:30px;font-weight:600;">A</div>
                    <span class="sidebar-title">AProjectO</span>
                </div>
                <small class="text-muted">Admin Panel</small>
            </div>
            <div class="px-3">
                <div class="nav-section-title">Project</div>
                <a href="admin_dashboard.php" class="active">Tasks</a>
                <a href="#">Work Logs</a>
                <a href="#">Performance</a>
                <a href="#">Settings</a>
            </div>
            <div class="mt-auto px-3 pb-3">
                <a href="logout.php">Logout</a>
            </div>
        </nav>

        <main class="col-md-10 content">
            <div class="top-bar">
                <div>
                    <h5 class="mb-0">Tasks</h5>
                    <small class="text-muted">Overview</small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-box">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-0"><span class="text-muted">🔍</span></span>
                            <input type="text" class="form-control border-0" placeholder="Search for anything...">
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="text-end me-2">
                            <div class="fw-semibold small"><?= htmlspecialchars($_SESSION['name']) ?></div>
                            <small class="text-muted">Admin</small>
                        </div>
                        <div class="rounded-circle bg-secondary" style="width:36px;height:36px;"></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="summary-badge">
                    System summary: <?= $companies_count ?> companies • <?= $members_count ?> members • <?= $company_users ?> company users
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-secondary">List View</button>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <span class="fw-semibold">Backlog</span>
                            <span class="pill">+</span>
                        </div>
                        <div class="small text-muted mb-2">Setup new company accounts.</div>
                        <a href="companies.php" class="btn btn-sm btn-outline-primary w-100">Manage Companies</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <span class="fw-semibold">In progress</span>
                            <span class="pill">+</span>
                        </div>
                        <div class="small text-muted mb-2">Review and manage members across all companies.</div>
                        <a href="members_admin.php" class="btn btn-sm btn-outline-primary w-100">View All Members</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <span class="fw-semibold">Completed</span>
                            <span class="pill">+</span>
                        </div>
                        <div class="small text-muted mb-2">Use this space for reports and exports later.</div>
                        <button class="btn btn-sm btn-outline-secondary w-100" type="button" disabled>Coming soon</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
</body>
</html>
