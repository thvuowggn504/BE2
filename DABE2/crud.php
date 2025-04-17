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
    <title>Shop Item - Start Bootstrap Template</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="public/css/styles1.css" rel="stylesheet" />
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
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="home1.php">Home</a></li>
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
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
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
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $index => $product): ?>
                                    <tr>
                                        <td><span class="custom-checkbox"><input type="checkbox"
                                                    id="checkbox<?php echo $index + 1; ?>" name="options[]"
                                                    value="<?php echo $product['ProductID']; ?>"><label
                                                    for="checkbox<?php echo $index + 1; ?>"></label></span></td>
                                        <td><?php echo $product['ProductID']; ?></td>
                                        <td><?php echo $product['ProductName']; ?></td>
                                        <td><?php echo $product['CategoryID']; ?></td>
                                        <td><?php echo $product['Price']; ?></td>
                                        <td><?php echo $product['Stock'] ?? 'N/A'; ?></td>
                                        <td><?php echo $product['Description']; ?></td>
                                        <td>
                                            <?php if (!empty($product['ImageURL'])): ?>
                                                <img src="public/img/<?php echo htmlspecialchars($product['ImageURL']); ?>"
                                                alt="<?php echo $product['ProductName']; ?>"
                                                style="max-width: 100px; height: auto;" loading="lazy">
                                            <?php else: ?>
                                                No image
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="edit" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                data-id="<?php echo $product['ProductID']; ?>"
                                                data-name="<?php echo $product['ProductName']; ?>"
                                                data-price="<?php echo $product['Price']; ?>"
                                                data-category="<?php echo $product['CategoryID']; ?>"
                                                data-stock="<?php echo $product['Stock']; ?>"
                                                data-description="<?php echo $product['Description']; ?>"
                                                data-image="<?php echo $product['ImageURL']; ?>"><i
                                                    class="bi bi-pencil"></i></a>
                                            <a href="#" class="delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteEmployeeModal"
                                                data-id="<?php echo $product['ProductID']; ?>"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="hint-text">Showing <b><?php echo count($products); ?></b> out of
                            <b><?php echo count($products); ?></b> entries
                        </div>
                        <ul class="pagination">
                            <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                            <li class="page-item active"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Shrimp © TPV E-COMMERCE 2025</p>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
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
            });
        });
    </script>
</body>

</html>