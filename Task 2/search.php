<?php
session_start();
require 'db.php'; 
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Search Page</title>
    <style>
                @font-face {
            font-family: 'NeueMachina';
            src: url('fonts/NeueMachina-Light.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('fonts/Poppins-Regular.ttf') format('opentype');
            font-weight: 300;
            font-style: normal;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            font-family: 'Quicksand', sans-serif;
            background-image: linear-gradient(to right top, #d16ba5, #86abe7, #5ffbf1);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 50px 20px;
        }

        a button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #5ffbf1;
            color: #333;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s, background-color 0.3s;
        }

        a button:hover {
            background-color: #86abe7;
            transform: scale(1.05);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        form {
            background: rgba(255,255,255,0.15);
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        input {
            padding: 10px;
            border: none;
            border-radius: 20px;
            width: 200px;
            outline: none;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #5ffbf1;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s, background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #86abe7;
            transform: scale(1.05);
            color: #fff;
        }

        .result {
            background: rgba(255,255,255,0.15);
            padding: 15px 20px;
            border-radius: 15px;
            width: 80%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: 15px;
            text-align: left;
        }

        .result p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<a href="index.php"><button>🏠 Home</button></a>

<h1>🔍 Tìm kiếm người dùng</h1>

<form method="GET">
    <input name="name" placeholder="Nhập tên cần tìm">
    <button type="submit">Tìm kiếm</button>
</form>

<?php
if (isset($_GET['name'])) {
    $search = $_GET['name'];
    $search = "%$search%";

    $stmt = $conn->prepare("SELECT id, name, gender, email, address, phone FROM users WHERE name LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            echo "<div class='result'>";
            echo "<p><strong>ID:</strong> {$user['id']}</p>";
            echo "<p><strong>Tên:</strong> {$user['name']}</p>";
            echo "<p><strong>Giới tính:</strong> {$user['gender']}</p>";
            echo "<p><strong>Email:</strong> {$user['email']}</p>";
            echo "<p><strong>Địa chỉ:</strong> {$user['address']}</p>";
            echo "<p><strong>SĐT:</strong> {$user['phone']}</p>";
            echo "</div>";
        }
    } else {
        echo "<p>😥 Không tìm thấy người dùng nào.</p>";
    }

    $stmt->close();
}
?>

</body>
</html>
