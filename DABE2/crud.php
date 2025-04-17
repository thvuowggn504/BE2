

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