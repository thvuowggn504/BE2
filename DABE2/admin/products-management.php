<?php
require_once '../includes/database.php';
$conn = Database::getConnection();

$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';

// POST: add / edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['ProductName']);
    $catid  = intval($_POST['CategoryID']);
    $price  = floatval($_POST['Price']);
    $stock  = intval($_POST['Stock']);
    $desc   = trim($_POST['Description']);
    $img    = ''; // Mặc định là rỗng
    
    // Xử lý upload ảnh
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = "../public/img/";
        
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Lấy thông tin file
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Tạo tên file mới để tránh trùng lặp
        $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadPath = $uploadDir . $newFileName;
        
        // Danh sách các định dạng ảnh cho phép
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Kiểm tra định dạng file
        if(in_array($fileExt, $allowedTypes)) {
            // Di chuyển file tạm thời đến thư mục đích
            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $img = '../public/img/' . $newFileName; // Đường dẫn tương đối để lưu vào DB
            }
        }
    }
    
    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare(
          "INSERT INTO Products (ProductName, CategoryID, Price, Stock, Description, ImageURL)
           VALUES (?,?,?,?,?,?)"
        );
        $stmt->bind_param("sidiss", $name, $catid, $price, $stock, $desc, $img);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'edit' && !empty($_POST['ProductID'])) {
        $pid = intval($_POST['ProductID']);
        
        // Nếu không upload ảnh mới, giữ nguyên ảnh cũ
        if(empty($img)) {
            $img = $_POST['current_image'];
        } else {
            // Nếu upload ảnh mới và có ảnh cũ, xóa ảnh cũ
            $oldImage = $_POST['current_image'];
            if(!empty($oldImage) && strpos($oldImage, '../public/img/') === 0 && file_exists($oldImage)) {
                unlink($oldImage);
            }
        }
        
        $stmt = $conn->prepare(
          "UPDATE Products SET ProductName=?, CategoryID=?, Price=?, Stock=?, Description=?, ImageURL=?
           WHERE ProductID=?"
        );
        $stmt->bind_param("sidissi", $name, $catid, $price, $stock, $desc, $img, $pid);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'delete' && !empty($_POST['ProductID'])) {
        $pid = intval($_POST['ProductID']);
        
        // Xóa file ảnh khi xóa sản phẩm
        $stmt = $conn->prepare("SELECT ImageURL FROM Products WHERE ProductID = ?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $imageUrl = $row['ImageURL'];
            // Chỉ xóa nếu là ảnh trong thư mục của chúng ta
            if(strpos($imageUrl, '../public/img/') === 0 && file_exists($imageUrl)) {
                unlink($imageUrl);
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
    }
    header("Location: products-management.php");
    exit;
}

// DELETE (giữ lại để tương thích ngược)
if ($action === 'delete' && !empty($id)) {
    $pid = intval($id);
    
    // Xóa file ảnh khi xóa sản phẩm
    $stmt = $conn->prepare("SELECT ImageURL FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $imageUrl = $row['ImageURL'];
        // Chỉ xóa nếu là ảnh trong thư mục của chúng ta
        if(strpos($imageUrl, '../public/img/') === 0 && file_exists($imageUrl)) {
            unlink($imageUrl);
        }
    }
    
    $stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    header("Location: products-management.php");
    exit;
}

// EDIT mode (giữ để tương thích ngược)
$editMode = false;
$editRow  = ['ProductID'=>'','ProductName'=>'','CategoryID'=>'','Price'=>'','Stock'=>'','Description'=>'','ImageURL'=>''];
if ($action === 'edit' && !empty($id)) {
    $pid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($r = $res->fetch_assoc()) {
        $editMode = true;
        $editRow  = $r;
    }
}
// Lấy danh sách categories cho <select>
$cats = $conn->query("SELECT CategoryID, CategoryName FROM Categories");

// TRUY VẤN products
$stmt = $conn->prepare(
  "SELECT p.*, c.CategoryName 
   FROM Products p
   JOIN Categories c ON p.CategoryID = c.CategoryID
   WHERE p.ProductName LIKE ?"
);
$like = "%$search%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        .product-img {
            max-width: 70px;
            max-height: 70px;
            object-fit: contain;
        }
        .price {
            font-weight: bold;
            color: #e74c3c;
        }
        .stock {
            font-weight: bold;
        }
        .stock.low {
            color: #e74c3c;
        }
        .stock.medium {
            color: #f39c12;
        }
        .stock.high {
            color: #27ae60;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .current-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Quản lý sản phẩm</h2>
    
    <!-- Tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách sản phẩm -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="header-actions">
                <h5 class="mb-0">Danh sách sản phẩm</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Kho</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['ProductID'] ?></td>
                            <td>
                                <?php if ($row['ImageURL']): ?>
                                    <img src="<?= htmlspecialchars($row['ImageURL']) ?>" class="product-img" alt="<?= htmlspecialchars($row['ProductName']) ?>">
                                <?php else: ?>
                                    <div class="placeholder-img bg-light d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                                        <i class="bi bi-image text-secondary" style="font-size:1.5rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['ProductName']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($row['CategoryName']) ?></span></td>
                            <td class="price"><?= number_format($row['Price'], 0, ',', '.') ?> đ</td>
                            <td>
                                <?php 
                                $stockClass = 'high';
                                if ($row['Stock'] <= 5) {
                                    $stockClass = 'low';
                                } elseif ($row['Stock'] <= 20) {
                                    $stockClass = 'medium';
                                }
                                ?>
                                <span class="stock <?= $stockClass ?>"><?= $row['Stock'] ?></span>
                            </td>
                            <td class="actions">
                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                        data-id="<?= $row['ProductID'] ?>"
                                        data-name="<?= htmlspecialchars($row['ProductName']) ?>"
                                        data-category="<?= $row['CategoryID'] ?>"
                                        data-price="<?= $row['Price'] ?>"
                                        data-stock="<?= $row['Stock'] ?>"
                                        data-desc="<?= htmlspecialchars($row['Description']) ?>"
                                        data-image="<?= htmlspecialchars($row['ImageURL']) ?>">
                                    <i class="bi bi-pencil"></i> Sửa
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?= $row['ProductID'] ?>"
                                        data-name="<?= htmlspecialchars($row['ProductName']) ?>">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Form thêm/sửa sản phẩm - giữ lại phần này nhưng ẩn đi khi đã có modal -->
    <?php if ($editMode): ?>
    <div class="card d-none">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Cập nhật sản phẩm</h5>
        </div>
        <div class="card-body">
            <!-- Form sẽ được giữ lại nhưng ẩn đi, chỉ để tương thích với code cũ -->
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Thêm Sản Phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add-productname" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="add-productname" name="ProductName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="add-category" class="form-label">Danh mục</label>
                            <select class="form-select" id="add-category" name="CategoryID" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php 
                                // Reset con trỏ của $cats
                                $cats->data_seek(0);
                                while($c = $cats->fetch_assoc()): 
                                ?>
                                    <option value="<?= $c['CategoryID'] ?>">
                                        <?= htmlspecialchars($c['CategoryName']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add-price" class="form-label">Giá</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="add-price" name="Price" step="0.01" required>
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="add-stock" class="form-label">Kho</label>
                            <input type="number" class="form-control" id="add-stock" name="Stock" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add-description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="add-description" name="Description" rows="4"></textarea>
                    </div>
                    
                    <!-- Phần Upload Ảnh -->
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="add-image" name="image" accept="image/*" onchange="previewImage(this, 'add-imagePreview')">
                        <div class="form-text">Định dạng cho phép: JPG, JPEG, PNG, GIF, WEBP</div>
                        <img id="add-imagePreview" src="#" alt="Preview" class="image-preview">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editProductModalLabel">Cập nhật sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="ProductID" id="edit-productid">
                <input type="hidden" name="current_image" id="edit-current-image">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit-productname" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="edit-productname" name="ProductName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit-category" class="form-label">Danh mục</label>
                            <select class="form-select" id="edit-category" name="CategoryID" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php 
                                // Reset con trỏ của $cats
                                $cats->data_seek(0);
                                while($c = $cats->fetch_assoc()): 
                                ?>
                                    <option value="<?= $c['CategoryID'] ?>">
                                        <?= htmlspecialchars($c['CategoryName']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit-price" class="form-label">Giá</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="edit-price" name="Price" step="0.01" required>
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit-stock" class="form-label">Kho</label>
                            <input type="number" class="form-control" id="edit-stock" name="Stock" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit-description" name="Description" rows="4"></textarea>
                    </div>
                    
                    <!-- Phần Upload Ảnh -->
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm</label>
                        
                        <div class="mb-3" id="edit-current-image-container">
                            <p>Ảnh hiện tại:</p>
                            <img id="edit-current-image-preview" src="#" alt="Current product image" class="current-image">
                        </div>
                        
                        <div class="mb-3">
                            <input type="file" class="form-control" id="edit-image" name="image" accept="image/*" onchange="previewImage(this, 'edit-imagePreview')">
                            <div class="form-text">Định dạng cho phép: JPG, JPEG, PNG, GIF, WEBP</div>
                            <img id="edit-imagePreview" src="#" alt="Preview" class="image-preview">
                            <div class="form-text mt-2">
                                <em>Chỉ cần tải ảnh mới nếu bạn muốn thay đổi ảnh hiện tại.</em>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Sản Phẩm -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteProductModalLabel">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="delete-product-name"></strong>?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="ProductID" id="delete-productid">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Hiển thị xem trước ảnh khi tải lên
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý khi nhấn nút sửa
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const category = this.getAttribute('data-category');
                const price = this.getAttribute('data-price');
                const stock = this.getAttribute('data-stock');
                const desc = this.getAttribute('data-desc');
                const image = this.getAttribute('data-image');
                
                // Điền dữ liệu vào form sửa
                document.getElementById('edit-productid').value = id;
                document.getElementById('edit-productname').value = name;
                document.getElementById('edit-category').value = category;
                document.getElementById('edit-price').value = price;
                document.getElementById('edit-stock').value = stock;
                document.getElementById('edit-description').value = desc;
                document.getElementById('edit-current-image').value = image;
                
                // Hiển thị ảnh hiện tại
                const imagePreview = document.getElementById('edit-current-image-preview');
                const imageContainer = document.getElementById('edit-current-image-container');
                if (image && image !== '') {
                    imagePreview.src = image;
                    imageContainer.style.display = 'block';
                } else {
                    imageContainer.style.display = 'none';
                }
                
                // Hiển thị modal sửa
                const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                editModal.show();
            });
        });
        
        // Xử lý khi nhấn nút xóa
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Điền dữ liệu vào modal xác nhận xóa
                document.getElementById('delete-productid').value = id;
                document.getElementById('delete-product-name').textContent = name;
                
                // Hiển thị modal xóa
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
                deleteModal.show();
            });
        });
    });
</script>
</body>
</html>