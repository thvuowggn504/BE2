<?php
require_once 'database.php'; // Kết nối database
require_once 'config.php'; // Cấu hình database

class Category_Database extends Database
{
    // Lấy tất cả danh mục
    public function getAllCategories()
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("SELECT * FROM categories");
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("SELECT * FROM categories WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return isset($result[0]) ? $result[0] : null;
    }

    // Thêm danh mục mới
    public function addCategory($name, $description)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("INSERT INTO categories (CategoryName, Description) VALUES (?, ?)");
        $sql->bind_param("ss", $name, $description);
        return $sql->execute();
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("UPDATE categories SET name=? WHERE id=?");
        $sql->bind_param("si", $name, $id);
        return $sql->execute();
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("DELETE FROM categories WHERE id=?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    // Tìm kiếm danh mục theo tên
    public function searchCategories($keyword)
    {
        $connection = Database::getConnection();
        $keyword = "%$keyword%";
        $sql = $connection->prepare("SELECT * FROM categories WHERE name LIKE ?");
        $sql->bind_param("s", $keyword);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm theo danh mục
    public function getProductsByCategory($categoryId)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("SELECT * FROM products WHERE CategoryID = ?");
        $sql->bind_param("i", $categoryId);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm mới nhất
    public function getLatestProducts($limit = 5)
    {
        $connection = Database::getConnection();
        $sql = $connection->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ?");
        $sql->bind_param("i", $limit);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
