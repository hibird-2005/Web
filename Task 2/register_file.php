<?php
include "db.php";

$name = $_POST['name'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'];

// Kiểm tra dữ liệu rỗng
if (empty($name) || empty($password) || empty($email) || empty($phone) || empty($gender) || empty($address)) {
    header("Location: register.php?error=Vui+lòng+điền+đầy+đủ+thông+tin");
    exit();
}

// Kiểm tra email hợp lệ
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=Email+không+hợp+lệ");
    exit();
}

// Kiểm tra số điện thoại hợp lệ
if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
    header("Location: register.php?error=Số+điện+thoại+không+hợp+lệ");
    exit();
}

// Kiểm tra độ dài mật khẩu
if (strlen($password) < 6) {
    header("Location: register.php?error=Mật+khẩu+phải+có+ít+nhất+6+ký+tự");
    exit();
}

// Kiểm tra trùng email
$check_email = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($check_email);
if ($result->num_rows > 0) {
    header("Location: register.php?error=Email+đã+được+sử+dụng");
    exit();
}

// INSERT dữ liệu
$sql = "INSERT INTO users (name, gender, email, address, phone, password) 
        VALUES ('$name', '$gender', '$email', '$address', '$phone', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Đăng ký thành công!');
        window.location.href = 'index.php';
    </script>";
} else {
    $error_msg = addslashes($conn->error);
    echo "<script>
        alert('Lỗi đăng ký: $error_msg');
        window.location.href = 'index.php';
    </script>";
}
?>