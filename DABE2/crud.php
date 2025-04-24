<?php
session_start(); // Bắt đầu session để kiểm tra trạng thái đăng nhập

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: lg&rgt.php");
    exit();
}

// Kiểm tra quyền truy cập
if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['userType'] !== 'Admin' || $_SESSION['currentUser']['email'] !== 'admin') {
    header("Location: lg&rgt.php");
    exit();
}

require_once 'Product_Database.php';
$productDB = new Product_Database();

// Khởi tạo thông báo
$success_message = '';
$error_message = '';

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    if ($productDB->addProduct($name, $price, $category_id, $description, $stock, $image_url)) {
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Error adding product.";
    }
}

// Xử lý sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    if ($productDB->updateProduct($id, $name, $price, $category_id, $description, $stock, $image_url)) {
        $success_message = "Product updated successfully!";
    } else {
        $error_message = "Error updating product.";
    }
}

// Xử lý xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    if ($productDB->deleteProduct($id)) {
        $success_message = "Product deleted successfully!";
    } else {
        $error_message = "Error deleting product.";
    }
}

// Lấy tất cả sản phẩm
$products = $productDB->getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>TPV E-Commerce - Product Management</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/styles1.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3b7ddd;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background: linear-gradient(to right, #3b7ddd, #4e92e3);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }

        .navbar-light .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: white !important;
        }

        .btn-outline-dark {
            color: white;
            border-color: white;
        }

        .btn-outline-dark:hover {
            background-color: white;
            color: var(--primary-color);
        }

        .btn-outline-danger {
            color: white;
            border-color: white;
        }

        .btn-outline-danger:hover {
            background-color: white;
            color: var(--danger-color);
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }

        .dashboard-header {
            padding: 1.5rem 0;
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }

        .table-wrapper {
            background: white;
            padding: 20px;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }

        .table-title {
            padding-bottom: 15px;
            background: white;
            color: #333;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn {
            float: right;
            font-size: 13px;
            border-radius: 2px;
            border: none;
            min-width: 50px;
            margin-left: 10px;
        }

        .table {
            border-radius: 3px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child {
            width: 130px;
        }

        table.table td a {
            display: inline-block;
            margin: 0 5px;
            min-width: 24px;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #E34724;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            transition: transform 0.2s;
        }

        .product-image:hover {
            transform: scale(3);
            z-index: 1000;
        }

        .btn-add {
            background-color: var(--success-color);
            color: white;
            border-radius: 50px;
            padding: 8px 16px;
        }

        .btn-add:hover {
            background-color: #218838;
            color: white;
        }

        .btn-action {
            font-size: 18px;
            padding: 5px;
            margin: 2px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .btn-edit {
            color: var(--warning-color);
        }

        .btn-edit:hover {
            color: #e0a800;
        }

        .btn-delete {
            color: var(--danger-color);
        }

        .btn-delete:hover {
            color: #c82333;
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: 5px 5px 0 0;
        }

        .modal-header .close {
            color: white;
        }

        .modal-body {
            padding: 20px 25px;
        }

        .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 5px 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(59, 125, 221, 0.1);
        }

        .alert {
            border-radius: 4px;
            margin-bottom: 20px;
        }

        footer {
            background: linear-gradient(to right, #343a40, #4e555b);
            color: white;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            cursor: pointer;
        }

        .custom-checkbox label {
            position: relative;
            cursor: pointer;
            padding-left: 25px;
            margin-bottom: 0;
        }

        .custom-checkbox label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 3px;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            content: '\2713';
            position: absolute;
            top: -1px;
            left: 3px;
            font-size: 14px;
            color: var(--primary-color);
        }

        /* Stats Cards */
        .stats-card {
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card-products {
            background-image: linear-gradient(135deg, #3B7DDD 0%, #2d62b2 100%);
        }

        .stats-card-categories {
            background-image: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .stats-card-stock {
            background-image: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);
        }

        .stats-card-value {
            background-image: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        }

        .stats-icon {
            font-size: 48px;
            opacity: 0.6;
        }

        .stats-number {
            font-size: 28px;
            font-weight: 700;
        }

        .stats-title {
            font-size: 16px;
            opacity: 0.8;
        }

        /* Responsive table */
        @media (max-width: 768px) {

            .table-responsive-stack td,
            .table-responsive-stack th {
                display: block;
                text-align: center;
                width: 100%;
            }

            .table-responsive-stack tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
            }

            .table-responsive-stack th {
                background-color: #f8f9fa;
                border-bottom: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="home1.php"><i class="bi bi-shop me-2"></i>TPV E-COMMERCE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="home1.php"><i
                                class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="crud.php"><i class="bi bi-grid me-1"></i>Products</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-dark me-2">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-light text-dark ms-1 rounded-pill">0</span>
                    </a>
                    <a href="crud.php?logout=true" class="btn btn-outline-danger">
                        <i class="bi-box-arrow-right me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="fw-bold">Product Management</h1>
                    <p class="text-muted">Manage your store's product inventory</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <section class="py-2">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card stats-card-products">
                        <div class="row">
                            <div class="col-4">
                                <i class="bi bi-box-seam stats-icon"></i>
                            </div>
                            <div class="col-8 text-end">
                                <div class="stats-number"><?php echo count($products); ?></div>
                                <div class="stats-title">Total Products</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stats-card stats-card-categories">
                        <div class="row">
                            <div class="col-4">
                                <i class="bi bi-tag stats-icon"></i>
                            </div>
                            <div class="col-8 text-end">
                                <div class="stats-number">
                                    <?php
                                    $categories = array_unique(array_column($products, 'CategoryID'));
                                    echo count($categories);
                                    ?>
                                </div>
                                <div class="stats-title">Categories</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stats-card stats-card-stock">
                        <div class="row">
                            <div class="col-4">
                                <i class="bi bi-archive stats-icon"></i>
                            </div>
                            <div class="col-8 text-end">
                                <div class="stats-number">
                                    <?php
                                    $totalStock = 0;
                                    foreach ($products as $product) {
                                        $totalStock += $product['Stock'] ?? 0;
                                    }
                                    echo $totalStock;
                                    ?>
                                </div>
                                <div class="stats-title">Items in Stock</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stats-card stats-card-value">
                        <div class="row">
                            <div class="col-4">
                                <i class="bi bi-currency-dollar stats-icon"></i>
                            </div>
                            <div class="col-8 text-end">
                                <div class="stats-number">
                                    <?php
                                    $totalValue = 0;
                                    foreach ($products as $product) {
                                        $totalValue += ($product['Price'] * ($product['Stock'] ?? 0));
                                    }
                                    echo number_format($totalValue, 2);
                                    ?>
                                </div>
                                <div class="stats-title">Inventory Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product section-->
    <section class="py-3">
        <div class="container">
            <div class="table-wrapper">
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0"><i class="bi bi-grid me-2"></i>Product List</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Search products...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle table-responsive-stack"
                                id="productTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th width="5%">ID</th>
                                        <th width="15%">Image</th>
                                        <th width="20%">Product Name</th>
                                        <th width="10%">Category</th>
                                        <th width="10%">Price</th>
                                        <th width="10%">Stock</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($products)): ?>
                                        <?php foreach ($products as $index => $product): ?>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="checkbox<?php echo $index + 1; ?>" name="options[]"
                                                            value="<?php echo $product['ProductID']; ?>">
                                                        <label class="form-check-label"
                                                            for="checkbox<?php echo $index + 1; ?>"></label>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary"><?php echo $product['ProductID']; ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($product['ImageURL'])): ?>
                                                        <img src="public/img/<?php echo htmlspecialchars($product['ImageURL']); ?>"
                                                            alt="<?php echo $product['ProductName']; ?>" class="product-image"
                                                            loading="lazy">
                                                    <?php else: ?>
                                                        <img src="public/img/placeholder.png" alt="No image" class="product-image"
                                                            loading="lazy">
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo $product['ProductName']; ?></strong></td>
                                                <td><span class="badge bg-info"><?php echo $product['CategoryID']; ?></span>
                                                </td>
                                                <td>$<?php echo number_format($product['Price'], 2); ?></td>
                                                <td>
                                                    <?php if (($product['Stock'] ?? 0) > 10): ?>
                                                        <span
                                                            class="badge bg-success"><?php echo $product['Stock'] ?? 'N/A'; ?></span>
                                                    <?php elseif (($product['Stock'] ?? 0) > 0): ?>
                                                        <span
                                                            class="badge bg-warning"><?php echo $product['Stock'] ?? 'N/A'; ?></span>
                                                    <?php else: ?>
                                                        <span
                                                            class="badge bg-danger"><?php echo $product['Stock'] ?? 'N/A'; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="View details">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning edit"
                                                            data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                            data-id="<?php echo $product['ProductID']; ?>"
                                                            data-name="<?php echo $product['ProductName']; ?>"
                                                            data-price="<?php echo $product['Price']; ?>"
                                                            data-category="<?php echo $product['CategoryID']; ?>"
                                                            data-stock="<?php echo $product['Stock']; ?>"
                                                            data-description="<?php echo $product['Description']; ?>"
                                                            data-image="<?php echo $product['ImageURL']; ?>"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete"
                                                            data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal"
                                                            data-id="<?php echo $product['ProductID']; ?>"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>No products found in the database.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hint-text">Showing <b><?php echo count($products); ?></b> out of
                                <b><?php echo count($products); ?></b> entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Modal -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><i class="bi bi-tag me-1"></i>Product Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="category_id"><i class="bi bi-grid me-1"></i>Category ID</label>
                                    <input type="number" id="category_id" name="category_id" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="price"><i class="bi bi-currency-dollar me-1"></i>Price</label>
                                    <input type="number" step="0.01" id="price" name="price" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock"><i class="bi bi-archive me-1"></i>Stock</label>
                                    <input type="number" id="stock" name="stock" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="description"><i class="bi bi-file-text me-1"></i>Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"
                                        required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image_url"><i class="bi bi-image me-1"></i>Image URL</label>
                                    <input type="text" id="image_url" name="image_url" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="add_product" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Add
                            Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name"><i class="bi bi-tag me-1"></i>Product Name</label>
                                    <input type="text" id="edit_name" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_category_id"><i class="bi bi-grid me-1"></i>Category ID</label>
                                    <input type="number" id="edit_category_id" name="category_id" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_price"><i class="bi bi-currency-dollar me-1"></i>Price</label>
                                    <input type="number" step="0.01" id="edit_price" name="price" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_stock"><i class="bi bi-archive me-1"></i>Stock</label>
                                    <input type="number" id="edit_stock" name="stock" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_description"><i
                                            class="bi bi-file-text me-1"></i>Description</label>
                                    <textarea id="edit_description" name="description" class="form-control" rows="3"
                                        required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="edit_image_url"><i class="bi bi-image me-1"></i>Image URL</label>
                                    <input type="text" id="edit_image_url" name="image_url" class="form-control"
                                        required>
                                    <div class="mt-2" id="image_preview"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="edit_product_id">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="edit_product" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="crud.php">
                    <div class="modal-header bg-danger text-white">
                        <h4 class="modal-title"><i class="bi bi-trash me-2"></i>Delete Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
                            <h4 class="mt-3">Are you sure?</h4>
                            <p class="text-muted">Do you really want to delete this product? This process cannot be
                                undone.</p>
                            <input type="hidden" name="product_id" id="delete_product_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="delete_product" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title"><i class="bi bi-info-circle me-2"></i>Product Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div id="detail_image_container" class="text-center mb-3">
                                <!-- Product image will be displayed here -->
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h4 id="detail_name" class="fw-bold"></h4>
                            <p id="detail_description" class="text-muted"></p>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td id="detail_id"></td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td id="detail_category"></td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td id="detail_price"></td>
                                    </tr>
                                    <tr>
                                        <th>Stock</th>
                                        <td id="detail_stock"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary edit-from-details"><i
                            class="bi bi-pencil me-1"></i>Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer-->
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-white">
                        <strong>TPV E-COMMERCE</strong> © 2025
                    </p>
                    <p class="text-white-50 small">
                        A complete e-commerce solution for your business
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#" class="text-white-50">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="#" class="text-white-50">Terms of Use</a></li>
                        <li class="list-inline-item"><a href="#" class="text-white-50">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for Edit and Delete -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Handle select all checkbox
            document.getElementById('selectAll').addEventListener('change', function () {
                const isChecked = this.checked;
                document.querySelectorAll('tbody input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });

            // Handle search functionality
            document.getElementById('searchInput').addEventListener('keyup', function () {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll("#productTable tbody tr");

                tableRows.forEach(row => {
                    const productName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    if (productName.includes(searchValue)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            // Điền dữ liệu vào modal sửa
            document.querySelectorAll('.edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const price = this.getAttribute('data-price');
                    const category = this.getAttribute('data-category');
                    const stock = this.getAttribute('data-stock');
                    const description = this.getAttribute('data-description');
                    const image = this.getAttribute('data-image');

                    document.getElementById('edit_product_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_price').value = price;
                    document.getElementById('edit_category_id').value = category;
                    document.getElementById('edit_stock').value = stock;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_image_url').value = image;

                    // Show image preview
                    const imagePreview = document.getElementById('image_preview');
                    if (image) {
                        imagePreview.innerHTML = `<img src="public/img/${image}" alt="${name}" class="img-fluid img-thumbnail" style="max-height: 100px">`;
                    } else {
                        imagePreview.innerHTML = '';
                    }
                });
            });

            // Điền ID vào modal xóa
            document.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    document.getElementById('delete_product_id').value = id;
                });
            });

            // Image preview in edit form
            document.getElementById('edit_image_url').addEventListener('change', function () {
                const imagePreview = document.getElementById('image_preview');
                if (this.value) {
                    imagePreview.innerHTML = `<img src="public/img/${this.value}" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 100px" onerror="this.src='public/img/placeholder.png'">`;
                } else {
                    imagePreview.innerHTML = '';
                }
            });

            // Setup view details functionality
            document.querySelectorAll('button[title="View details"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const id = row.querySelector('td:nth-child(2)').textContent;
                    const name = row.querySelector('td:nth-child(4)').textContent;
                    const category = row.querySelector('td:nth-child(5)').textContent;
                    const price = row.querySelector('td:nth-child(6)').textContent;
                    const stock = row.querySelector('td:nth-child(7)').textContent;
                    const imgSrc = row.querySelector('.product-image')?.src || 'public/img/placeholder.png';
                    const description = row.querySelector('.edit').getAttribute('data-description');

                    document.getElementById('detail_id').textContent = id;
                    document.getElementById('detail_name').textContent = name;
                    document.getElementById('detail_category').textContent = category;
                    document.getElementById('detail_price').textContent = price;
                    document.getElementById('detail_stock').textContent = stock;
                    document.getElementById('detail_description').textContent = description;
                    document.getElementById('detail_image_container').innerHTML = `<img src="${imgSrc}" alt="${name}" class="img-fluid rounded">`;

                    // Store ID for edit button
                    document.querySelector('.edit-from-details').setAttribute('data-id', id);

                    // Show modal
                    const viewModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
                    viewModal.show();
                });
            });

            // Edit from details modal
            document.querySelector('.edit-from-details').addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const editButton = document.querySelector(`.edit[data-id="${id}"]`);
                if (editButton) {
                    // Close view modal
                    bootstrap.Modal.getInstance(document.getElementById('viewDetailsModal')).hide();

                    // Trigger edit button click
                    setTimeout(() => {
                        editButton.click();
                    }, 400);
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Make table responsive
            function handleResponsive() {
                if (window.innerWidth < 768) {
                    const tdElements = document.querySelectorAll('td');
                    tdElements.forEach(function (td) {
                        const headerText = td.closest('table').querySelector('th:nth-child(' + (Array.from(td.parentNode.children).indexOf(td) + 1) + ')').textContent;
                        td.setAttribute('data-title', headerText);
                    });
                }
            }

            handleResponsive();
            window.addEventListener('resize', handleResponsive);
        });
    </script>
</body>

</html>