<?php
require_once 'database.php'; // Kết nối database
require_once 'config.php'; // Cấu hình database

class Category_Database extends Database
{
    // Lấy tất cả danh mục
    public function getAllCategories()
    {
        $sql = self::$connection->prepare("SELECT * FROM categories");
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM categories WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return isset($result[0]) ? $result[0] : null;
    }

    // Thêm danh mục mới
    public function addCategory($name)
    {
        $sql = self::$connection->prepare("INSERT INTO categories (name) VALUES (?)");
        $sql->bind_param("s", $name);
        return $sql->execute();
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name)
    {
        $sql = self::$connection->prepare("UPDATE categories SET name=? WHERE id=?");
        $sql->bind_param("si", $name, $id);
        return $sql->execute();
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        $sql = self::$connection->prepare("DELETE FROM categories WHERE id=?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    // Tìm kiếm danh mục theo tên
    public function searchCategories($keyword)
    {
        $keyword = "%$keyword%";
        $sql = self::$connection->prepare("SELECT * FROM categories WHERE name LIKE ?");
        $sql->bind_param("s", $keyword);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm theo danh mục
    public function getProductsByCategory($categoryId)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE category_id = ?");
        $sql->bind_param("i", $categoryId);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm mới nhất
    public function getLatestProducts($limit = 5)
    {
        $sql = self::$connection->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ?");
        $sql->bind_param("i", $limit);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
