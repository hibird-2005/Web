<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin người dùng</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 50px 20px;
            font-family: 'Quicksand', sans-serif;
            background-image: linear-gradient(135deg, #D16BA5, #86ABE7, #5FFBF1);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        a button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #5FFBF1;
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
            background-color: #D16BA5;
            color: #fff;
            transform: scale(1.1);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 30px;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .info-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            width: 80%;
            max-width: 500px;
            backdrop-filter: blur(10px);
        }

        .info-box p {
            margin: 10px 0;
            font-size: 18px;
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 15px;
            border-radius: 15px;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
            color: #fff;
        }

        .info-label {
            font-weight: 600;
            color: #D16BA5;
        }

        .info-value {
            color: #5FFBF1;
        }
    </style>
</head>
<body>

<a href="index.php"><button>🏠 Home</button></a>

<h1>✨ Thông tin của bạn ✨</h1>

<div class="info-box">
    <p><span class="info-label">Tên:</span> <span class="info-value"><?php echo $user["name"]; ?></span></p>
    <p><span class="info-label">Giới tính:</span> <span class="info-value"><?php echo $user["gender"]; ?></span></p>
    <p><span class="info-label">Email:</span> <span class="info-value"><?php echo $user["email"]; ?></span></p>
    <p><span class="info-label">Địa chỉ:</span> <span class="info-value"><?php echo $user["address"]; ?></span></p>
    <p><span class="info-label">SĐT:</span> <span class="info-value"><?php echo $user["phone"]; ?></span></p>
</div>

</body>
</html>
