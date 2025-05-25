<?php
$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

//charset UTF-8mb4 để hỗ trợ đầy đủ tiếng Việt
$conn->set_charset("utf8mb4");

?>