<?php


require_once 'Category_Database.php';
$categoryDB = new Category_Database();  

// Khởi tạo thông báo
$success_message = '';
$error_message = '';

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($categoryDB->addCategory($name, $description)) {
        $success_message = "Category added successfully!";
    } else {
        $error_message = "Error adding category.";
    }
}

// Xử lý sửa danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($categoryDB->updateCategory($id, $name, $description)) {
        $success_message = "Category updated successfully!";
    } else {
        $error_message = "Error updating category.";
    }
}

// Xử lý xóa danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $id = $_POST['category_id'];
    if ($categoryDB->deleteCategory($id)) {
        $success_message = "Category deleted successfully!";
    } else {
        $error_message = "Error deleting category.";
    }
}

// Lấy tất cả danh mục
$categories = $categoryDB->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>TPV E-Commerce - Category Management</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/styles1.css" rel="stylesheet" />
</head>

<body>
    

    <!-- Category section-->
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
                            <div class="col-md-6 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-grid me-2"></i>Category List</h5>
                                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="bi bi-plus-circle me-1"></i>Add New Category
                                </button>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Search categories...">
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
                                id="categoryTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="20%">Category Name</th>
                                        <th width="50%">Description</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><span class="badge bg-secondary"><?php echo $category['CategoryID']; ?></span></td>
                                                <td><strong><?php echo $category['CategoryName']; ?></strong></td>
                                                <td><?php echo $category['Description']; ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-warning edit"
                                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                                            data-id="<?php echo $category['CategoryID']; ?>"
                                                            data-name="<?php echo $category['CategoryName']; ?>"
                                                            data-description="<?php echo $category['Description']; ?>"
                                                            title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete"
                                                            data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                                            data-id="<?php echo $category['CategoryID']; ?>"
                                                            title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>No categories found in the database.
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
                            <div class="hint-text">Showing <b><?php echo count($categories); ?></b> out of
                                <b><?php echo count($categories); ?></b> entries
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
    <div id="addCategoryModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="category_crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Category</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name"><i class="bi bi-tag me-1"></i>Category Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="bi bi-file-text me-1"></i>Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="add_category" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Add
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editCategoryModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="category_crud.php">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Category</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name"><i class="bi bi-tag me-1"></i>Category Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description"><i class="bi bi-file-text me-1"></i>Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="category_id" id="edit_category_id">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="edit_category" value="1">
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
    <div id="deleteCategoryModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="category_crud.php">
                    <div class="modal-header bg-danger text-white">
                        <h4 class="modal-title"><i class="bi bi-trash me-2"></i>Delete Category</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
                            <h4 class="mt-3">Are you sure?</h4>
                            <p class="text-muted">Do you really want to delete this category? This process cannot be
                                undone.</p>
                            <input type="hidden" name="category_id" id="delete_category_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="delete_category" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Attach event listeners to all edit buttons
            document.querySelectorAll('.edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const description = this.getAttribute('data-description');

                    // Populate the modal fields with the data
                    document.getElementById('edit_category_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_description').value = description;
                });
            });

            // Attach event listeners to all delete buttons
            document.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');

                    // Set the category ID in the hidden input field of the delete modal
                    document.getElementById('delete_category_id').value = id;
                });
            });
        });
    </script>
</body>

</html>