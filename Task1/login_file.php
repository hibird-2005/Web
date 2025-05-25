<?php
session_start();
include "db.php";

$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if ($password === $row["password"]) {
        $_SESSION["user"] = $row;
        header("Location: index.php");
    } else {
        echo "Sai mật khẩu";
    }
} else {
    echo "Không tìm thấy tài khoản";
}
?>