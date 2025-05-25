<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
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
            position: relative;
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
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px;
        }

        .row-input > div {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #fff;
            border-radius: 50px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
            color: #666;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff69b4;
            outline: none;
        }

        button[type="submit"] {
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

        button[type="submit"]:hover {
            background: #333;
            transform: scale(1.05);
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

        .error {
            color: #FF0000;
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .row-input {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Nút Home góc trái -->
    <a href="index.php" class="home-link">Home</a>

    <form action="login_file.php" method="POST">
        <h1>Login</h1>
        <div class="row-input">
            <div>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
        </div>
        <button type="submit">Login</button>
        <a href="register.php" class="or-back">or Sign up</a>
    </form>
</body>
</html>