<?php
require 'config.php';
require_login();

$is_admin = $_SESSION['role'] === 'admin';
$company_id = $is_admin ? ($_GET['company_id'] ?? null) : $_SESSION['company_id'];

if (!$company_id) {
    die('Company ID required.');
}

$editing = isset($_GET['id']);
$member = null;

if ($editing) {
    if ($is_admin) {
        $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->execute([$_GET['id']]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ? AND company_id = ?");
        $stmt->execute([$_GET['id'], $company_id]);
    }
    $member = $stmt->fetch();
    if (!$member) {
        die('Member not found or access denied.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'membership_id'   => $_POST['membership_id'] ?? '',
        'first_name'      => $_POST['first_name'] ?? '',
        'middle_name'     => $_POST['middle_name'] ?? null,
        'last_name'       => $_POST['last_name'] ?? '',
        'email'           => $_POST['email'] ?? '',
        'phone'           => $_POST['phone'] ?? null,
        'mobile'          => $_POST['mobile'] ?? null,
        'address'         => $_POST['address'] ?? null,
        'date_of_birth'   => $_POST['date_of_birth'] ?: null,
        'sss_number'      => $_POST['sss_number'] ?? null,
        'membership_type' => $_POST['membership_type'] ?? 'Active',
        'join_date'       => $_POST['join_date'] ?: date('Y-m-d'),
        'status'          => $_POST['status'] ?? 'pending',
        'expiration_date' => $_POST['expiration_date'] ?: null,
    ];

    if ($editing) {
        $sql = "UPDATE members SET
                  membership_id=:membership_id,
                  first_name=:first_name,
                  middle_name=:middle_name,
                  last_name=:last_name,
                  email=:email,
                  phone=:phone,
                  mobile=:mobile,
                  address=:address,
                  date_of_birth=:date_of_birth,
                  sss_number=:sss_number,
                  membership_type=:membership_type,
                  join_date=:join_date,
                  status=:status,
                  expiration_date=:expiration_date
                WHERE id=:id";
        if (!$is_admin) {
            $sql .= " AND company_id=:company_id";
        }
        $stmt = $pdo->prepare($sql);
        $data['id'] = $member['id'];
        if (!$is_admin) {
            $data['company_id'] = $company_id;
        }
        $stmt->execute($data);
    } else {
        $sql = "INSERT INTO members
                (company_id, membership_id, first_name, middle_name, last_name, email,
                 phone, mobile, address, date_of_birth, sss_number, membership_type,
                 join_date, status, expiration_date)
                VALUES
                (:company_id, :membership_id, :first_name, :middle_name, :last_name, :email,
                 :phone, :mobile, :address, :date_of_birth, :sss_number, :membership_type,
                 :join_date, :status, :expiration_date)";
        $stmt = $pdo->prepare($sql);
        $data['company_id'] = $company_id;
        $stmt->execute($data);
    }

    if ($is_admin) {
        header('Location: members_admin.php');
    } else {
        header('Location: company_dashboard.php');
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $editing ? 'Edit' : 'Add' ?> Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= $editing ? 'Edit Member' : 'Add Member' ?></h3>
        <a href="<?= $is_admin ? 'members_admin.php' : 'company_dashboard.php' ?>" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Membership ID</label>
                    <input name="membership_id" class="form-control" required
                           value="<?= htmlspecialchars($member['membership_id'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input name="first_name" class="form-control" required
                           value="<?= htmlspecialchars($member['first_name'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input name="middle_name" class="form-control"
                           value="<?= htmlspecialchars($member['middle_name'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input name="last_name" class="form-control" required
                           value="<?= htmlspecialchars($member['last_name'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required
                           value="<?= htmlspecialchars($member['email'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input name="phone" class="form-control"
                           value="<?= htmlspecialchars($member['phone'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input name="mobile" class="form-control"
                           value="<?= htmlspecialchars($member['mobile'] ?? '') ?>">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <input name="address" class="form-control"
                           value="<?= htmlspecialchars($member['address'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input name="date_of_birth" type="date" class="form-control"
                           value="<?= htmlspecialchars($member['date_of_birth'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">SSS Number</label>
                    <input name="sss_number" class="form-control"
                           value="<?= htmlspecialchars($member['sss_number'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Membership Type</label>
                    <select name="membership_type" class="form-select">
                        <?php
                        $types = ['Active','Student','Professional','Organizational'];
                        $cur = $member['membership_type'] ?? 'Active';
                        foreach ($types as $t):
                        ?>
                            <option value="<?= $t ?>" <?= $cur === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Join Date</label>
                    <input name="join_date" type="date" class="form-control"
                           value="<?= htmlspecialchars($member['join_date'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php
                        $statuses = ['active','expired','cancelled','pending'];
                        $cs = $member['status'] ?? 'pending';
                        foreach ($statuses as $s):
                        ?>
                            <option value="<?= $s ?>" <?= $cs === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Expiration Date</label>
                    <input name="expiration_date" type="date" class="form-control"
                           value="<?= htmlspecialchars($member['expiration_date'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <button class="btn btn-primary"><?= $editing ? 'Update' : 'Create' ?> Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

