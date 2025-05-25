<?php
include "db.php";
$name = $password = $email = $phone = $gender = $address = "";

if (isset($_GET['error'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['error']) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
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
            min-height: 90vh;
            padding: 20px;
            background: url('images/background-2.jpg') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* thêm để định vị nút Home */
        }

        form {
            background: rgba(255,255,255,0.01);
            backdrop-filter: blur(0.1px);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 45px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            color: #000;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 2.1rem;
            font-weight: 600;
        }

        .row-input {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .row-input > div {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        .row-input select {
            width: 100%;
            padding: 10px;
            border: 1px solid #fff;
            border-radius: 50px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
            color: #666;
            appearance: none;
        }

        .row-input select {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="5" viewBox="0 0 10 5"><path d="M0 0h10L5 5z" fill="black"/></svg>') no-repeat right 10px center;
            background-size: 10px;
            background-color: #fff;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #ff69b4;
            outline: none;
        }

        input[type="submit"] {
            background: #000;
            color: #fff;
            border: none;
            padding: 6px 30px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .or-back {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .or-back:hover {
            color: #555;
        }

        input[type="submit"]:hover {
            background: #333;
            transform: scale(1.05);
        }

        /* thêm nút Home góc trái */
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 14px;
            text-decoration: none;
            color: #000;
            background: rgba(255,255,255,0.7);
            padding: 5px 10px;
            border-radius: 50px;
            transition: background 0.3s ease;
        }

        .home-link:hover {
            background: rgba(255,255,255,1);
        }

        @media (max-width: 600px) {
            .row-input {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- nút Home góc trái -->
<a href="index.php" class="home-link">Home</a>

<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const button = event.target;

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        button.textContent = "Ẩn";
    } else {
        passwordInput.type = "password";
        button.textContent = "Hiện";
    }
}
</script>

<form method="post" action="register_file.php">
    <h1 class="signup-text">Sign up</h1>

    <div class="row-input">
        <div>
            <label for="name">Username:</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
        </div>
        <div>
            <label for="password">Password:</label>
            <div style="position: relative;">
                <input type="password" id="password" name="password" value="<?= $password ?>" style="padding-right: 70px;">
                <button type="button" onclick="togglePassword()" style="
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    padding: 5px 10px;
                    border: none;
                    background: #f5e4e9;
                    border-radius: 15px;
                    cursor: pointer;
                ">Hiện</button>
            </div>
        </div>
    </div>

    <div class="row-input">
        <div>
            <label for="email">E-mail:</label>
            <input type="text" id="email" name="email" value="<?= $email ?>">
        </div>
        <div>
            <label for="phone">Phone number:</label>
            <input type="text" id="phone" name="phone" value="<?= $phone ?>">
        </div>
    </div>

    <div class="row-input">
        <div>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= $address ?>">
        </div>
    </div>

    <div class="row-input">
        <div>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Female" <?= ($gender == "Female") ? "selected" : "" ?>>Female</option>
                <option value="Male" <?= ($gender == "Male") ? "selected" : "" ?>>Male</option>
                <option value="Other" <?= ($gender == "Other") ? "selected" : "" ?>>Other</option>
            </select>
        </div>
    </div>

    <input type="submit" name="submit" value="Sign up">
    <a href="login.php" class="or-back">or Login</a>
</form>

</body>
</html>