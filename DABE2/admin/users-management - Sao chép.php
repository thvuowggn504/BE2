<?php
require_once '../includes/database.php';
$conn = Database::getConnection();

$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';

// POST: add / edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['FullName']);
    $email    = trim($_POST['Email']);
    $phone    = trim($_POST['Phone']);
    $utype    = $_POST['UserType'];
    $password = $_POST['Password'] ?? '';

    if ($_POST['action'] === 'add') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
          "INSERT INTO Users (FullName, Email, PasswordHash, Phone, UserType)
           VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("sssss", $name, $email, $hash, $phone, $utype);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'edit' && !empty($_POST['UserID'])) {
        $uid  = intval($_POST['UserID']);
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
              "UPDATE Users SET FullName=?, Email=?, PasswordHash=?, Phone=?, UserType=? WHERE UserID=?"
            );
            $stmt->bind_param("sssssi", $name, $email, $hash, $phone, $utype, $uid);
        } else {
            $stmt = $conn->prepare(
              "UPDATE Users SET FullName=?, Email=?, Phone=?, UserType=? WHERE UserID=?"
            );
            $stmt->bind_param("ssssi", $name, $email, $phone, $utype, $uid);
        }
        $stmt->execute();
    }
    header("Location: users-management.php");
    exit;
}

// DELETE
if ($action === 'delete' && !empty($id)) {
    $uid = intval($id);
    $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    header("Location: users-management.php");
    exit;
}

// EDIT MODE
$editMode = false;
$userRow  = ['UserID'=>'','FullName'=>'','Email'=>'','Phone'=>'','UserType'=>''];
if ($action === 'edit' && !empty($id)) {
    $uid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($r = $res->fetch_assoc()) {
        $editMode = true;
        $userRow  = $r;
    }
}

// TRUY VẤN LIST
$stmt = $conn->prepare(
  "SELECT * FROM Users WHERE FullName LIKE ? OR Email LIKE ?"
);
$like = "%$search%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
        }
        .page-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table-responsive {
            margin-bottom: 20px;
        }
        .actions {
            white-space: nowrap;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Quản lý người dùng</h2>
    
    <!-- Tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Tìm tên hoặc email..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách người dùng -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Danh sách người dùng</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Loại</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['UserID'] ?></td>
                            <td><?= htmlspecialchars($row['FullName']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['Phone']) ?></td>
                            <td>
                                <span class="badge <?= $row['UserType'] === 'Admin' ? 'bg-danger' : ($row['UserType'] === 'VIP' ? 'bg-success' : 'bg-secondary') ?>">
                                    <?= $row['UserType'] ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="?action=edit&id=<?= $row['UserID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="?action=delete&id=<?= $row['UserID'] ?>" 
                                   onclick="return confirm('Xác nhận xóa người dùng này?')" 
                                   class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Form thêm/sửa người dùng -->
    <div class="card">
        <div class="card-header bg-<?= $editMode ? 'warning' : 'success' ?> text-white">
            <h5 class="mb-0"><?= $editMode ? 'Cập nhật người dùng' : 'Thêm người dùng mới' ?></h5>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editMode ? 'edit' : 'add' ?>">
                <?php if ($editMode): ?>
                    <input type="hidden" name="UserID" value="<?= $userRow['UserID'] ?>">
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fullname" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="fullname" name="FullName" required
                               value="<?= htmlspecialchars($userRow['FullName']) ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="Email" required
                               value="<?= htmlspecialchars($userRow['Email']) ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="Phone"
                               value="<?= htmlspecialchars($userRow['Phone']) ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="usertype" class="form-label">Loại tài khoản</label>
                        <select class="form-select" id="usertype" name="UserType">
                            <?php foreach(['Regular','VIP','Admin'] as $t): ?>
                                <option value="<?= $t ?>" <?= $userRow['UserType']===$t?'selected':'' ?>>
                                    <?= $t ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="Password"
                           placeholder="<?= $editMode ? 'Để trống nếu không đổi' : 'Nhập mật khẩu mới' ?>">
                    <?php if ($editMode): ?>
                        <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu</div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= $editMode ? 'warning' : 'success' ?>">
                        <?= $editMode ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <?php if ($editMode): ?>
                        <a href="users-management.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>