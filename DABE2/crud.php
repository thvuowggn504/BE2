
<<<<<<< Updated upstream
=======
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


// Lấy tất cả sản phẩm
$products = $productDB->getAllProducts();
?>
>>>>>>> Stashed changes

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<<<<<<< Updated upstream
    <title>Shop Item - Start Bootstrap Template</title>
=======
    <title>TPV E-Commerce Admin</title>
>>>>>>> Stashed changes
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="public/css/styles1.css" rel="stylesheet" />
<<<<<<< Updated upstream
=======
    <!-- Custom CSS for crud.php -->
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
>>>>>>> Stashed changes
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">TPV E-COMMERCE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
<<<<<<< Updated upstream
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="home1.php">Home</a></li>
=======
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home1.php">
                        <i class="bi bi-house-door me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product_crud.php">
                            <i class="bi bi-grid me-1"></i>Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category_crud.php">
                            <i class="bi bi-grid me-1"></i>Categories</a>
                    </li>
>>>>>>> Stashed changes
                </ul>
                <div class="d-flex">
                    <form class="me-3">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                    <a href="crud.php?logout=true" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </nav>

<<<<<<< Updated upstream
    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Manage <b>Product</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                                        class="bi bi-pencil"></i><span>Add New Product</span></a>
                            </div>
                        </div>
                    </div>
                                                            <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><span class="custom-checkbox"><input type="checkbox" id="selectAll"><label
                                            for="selectAll"></label></span></th>
                                <th>ProductID</th>
                                <th>ProductName</th>
                                <th>CategoryID</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Description</th>
                                <th>ImageURL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                                                                <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox1" name="options[]"
                                                    value="1"><label
                                                    for="checkbox1"></label></span></td>
                                        <td>1</td>
                                        <td>MacBook Air M2</td>
                                        <td>1</td>
                                        <td>1199.99</td>
                                        <td>10</td>
                                        <td>Laptop siêu nhẹ, mạnh mẽ với chip M2.</td>
                                        <td>
                                                                                            <img src="public/img/78446_laptop_lenovo_ideapad_gami-removebg-preview.png"
                                                alt="MacBook Air M2"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="1"
                                                data-name="MacBook Air M2"
                                                data-price="1199.99"
                                                data-category="1"
                                                data-stock="10"
                                                data-description="Laptop siêu nhẹ, mạnh mẽ với chip M2."
                                                data-image="78446_laptop_lenovo_ideapad_gami-removebg-preview.png"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="1"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox2" name="options[]"
                                                    value="2"><label
                                                    for="checkbox2"></label></span></td>
                                        <td>2</td>
                                        <td>MacBook Pro 16</td>
                                        <td>1</td>
                                        <td>2499.99</td>
                                        <td>5</td>
                                        <td>Dành cho dân chuyên nghiệp với màn hình Retina.</td>
                                        <td>
                                                                                            <img src="public/img/images (9).jpg"
                                                alt="MacBook Pro 16"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="2"
                                                data-name="MacBook Pro 16"
                                                data-price="2499.99"
                                                data-category="1"
                                                data-stock="5"
                                                data-description="Dành cho dân chuyên nghiệp với màn hình Retina."
                                                data-image="images (9).jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="2"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox3" name="options[]"
                                                    value="3"><label
                                                    for="checkbox3"></label></span></td>
                                        <td>3</td>
                                        <td>iPhone 14 Pro</td>
                                        <td>2</td>
                                        <td>1099.99</td>
                                        <td>15</td>
                                        <td>Smartphone cao cấp với camera Pro.</td>
                                        <td>
                                                                                            <img src="public/img/iphone_14_pro.jpg"
                                                alt="iPhone 14 Pro"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="3"
                                                data-name="iPhone 14 Pro"
                                                data-price="1099.99"
                                                data-category="2"
                                                data-stock="15"
                                                data-description="Smartphone cao cấp với camera Pro."
                                                data-image="iphone_14_pro.jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="3"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox4" name="options[]"
                                                    value="4"><label
                                                    for="checkbox4"></label></span></td>
                                        <td>4</td>
                                        <td>iPhone SE 2022</td>
                                        <td>2</td>
                                        <td>429.99</td>
                                        <td>20</td>
                                        <td>Giá rẻ nhưng mạnh mẽ với chip A15.</td>
                                        <td>
                                                                                            <img src="public/img/iphone_se_2022.jpg"
                                                alt="iPhone SE 2022"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="4"
                                                data-name="iPhone SE 2022"
                                                data-price="429.99"
                                                data-category="2"
                                                data-stock="20"
                                                data-description="Giá rẻ nhưng mạnh mẽ với chip A15."
                                                data-image="iphone_se_2022.jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="4"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox5" name="options[]"
                                                    value="6"><label
                                                    for="checkbox5"></label></span></td>
                                        <td>6</td>
                                        <td>AirPods Pro 2</td>
                                        <td>3</td>
                                        <td>249.99</td>
                                        <td>18</td>
                                        <td>Tai nghe chống ồn với chất lượng âm thanh tuyệt vời.</td>
                                        <td>
                                                                                            <img src="public/img/images (7).jpg"
                                                alt="AirPods Pro 2"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="6"
                                                data-name="AirPods Pro 2"
                                                data-price="249.99"
                                                data-category="3"
                                                data-stock="18"
                                                data-description="Tai nghe chống ồn với chất lượng âm thanh tuyệt vời."
                                                data-image="images (7).jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="6"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox6" name="options[]"
                                                    value="7"><label
                                                    for="checkbox6"></label></span></td>
                                        <td>7</td>
                                        <td>iPhone 16 Plus</td>
                                        <td>2</td>
                                        <td>1244.00</td>
                                        <td>100</td>
                                        <td>HIệu năng cao</td>
                                        <td>
                                                                                            <img src="public/img/iphone16pm.jpg"
                                                alt="iPhone 16 Plus"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="7"
                                                data-name="iPhone 16 Plus"
                                                data-price="1244.00"
                                                data-category="2"
                                                data-stock="100"
                                                data-description="HIệu năng cao"
                                                data-image="iphone16pm.jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="7"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox7" name="options[]"
                                                    value="9"><label
                                                    for="checkbox7"></label></span></td>
                                        <td>9</td>
                                        <td>AirPods Pro 4</td>
                                        <td>3</td>
                                        <td>1.00</td>
                                        <td>123</td>
                                        <td>Chống ồn tốt</td>
                                        <td>
                                                                                            <img src="public/img/images(10).jpg"
                                                alt="AirPods Pro 4"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="9"
                                                data-name="AirPods Pro 4"
                                                data-price="1.00"
                                                data-category="3"
                                                data-stock="123"
                                                data-description="Chống ồn tốt"
                                                data-image="images(10).jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="9"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox8" name="options[]"
                                                    value="13"><label
                                                    for="checkbox8"></label></span></td>
                                        <td>13</td>
                                        <td>Ip5s</td>
                                        <td>2</td>
                                        <td>399.00</td>
                                        <td>34</td>
                                        <td>Hiệu năng cao</td>
                                        <td>
                                                                                            <img src="public/img/images.jpg"
                                                alt="Ip5s"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                                                                    </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="13"
                                                data-name="Ip5s"
                                                data-price="399.00"
                                                data-category="2"
                                                data-stock="34"
                                                data-description="Hiệu năng cao"
                                                data-image="images.jpg"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="13"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                                                                    </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="hint-text">Showing <b>8</b> out of
                            <b>8</b> entries
                        </div>
                        <ul class="pagination">
                            <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                            <li class="page-item active"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
=======
    <!-- Dashboard header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="fw-bold">Product Management</h1>
                    <p class="text-muted">Manage your store's product inventory</p>
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
>>>>>>> Stashed changes
                    </div>
                </div>
            </div>
        </div>
    </section>

<<<<<<< Updated upstream
    <!-- Add Modal -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Product</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>ProductName</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CategoryID</label>
                            <input type="number" name="category_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>ImageURL</label>
                            <input type="text" name="image_url" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="add_product" value="1">
                        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Product</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <div class="form-group">
                            <label>ProductName</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CategoryID</label>
                            <input type="number" name="category_id" id="edit_category_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" id="edit_description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>ImageURL</label>
                            <input type="text" name="image_url" id="edit_image_url" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="edit_product" value="1">
                        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-info" value="Save">
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
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Product</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this product?</p>
                        <input type="hidden" name="product_id" id="delete_product_id">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="delete_product" value="1">
                        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
=======
    <div id="dynamic-content"></div>
>>>>>>> Stashed changes

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Shrimp © TPV E-COMMERCE 2025</p>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
<<<<<<< Updated upstream
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- Custom JS for Edit and Delete -->
    <script>
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
            });
        });

        // Điền ID vào modal xóa
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_product_id').value = id;
=======
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLink = document.querySelector('a[href="category_crud.php"]');
            const contentContainer = document.createElement('div');
            contentContainer.id = 'dynamic-content';
            document.querySelector('section.py-2').insertAdjacentElement('afterend', contentContainer);

            categoryLink.addEventListener('click', function (event) {
                event.preventDefault();

                fetch('category_crud.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        contentContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
>>>>>>> Stashed changes
            });
        });
    </script>
</body>

</html>