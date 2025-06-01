<?php
session_start();
include "db.php";

$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result === false) {
    echo "SQL error: " . $conn->error;
} elseif ($result->num_rows >= 1) {
    echo "<h2 style='color: green; text-align: center;'>Đăng nhập thành công!</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p style='text-align: center;'>" . $row["email"] . " - " . $row["password"] . "</p>";
    }
    $_SESSION["user"] = $row;
    // Không chuyển hướng tự động, thay bằng liên kết
    echo "<p style='text-align: center;'><a href='index.php'>Tiếp tục đến trang chính</a></p>";
} else {
    echo "<h2 style='color: red; text-align: center;'>Sai tài khoản hoặc mật khẩu</h2>";
    echo "<p style='text-align: center;'><a href='login.php'>Quay lại</a></p>";
}
?>