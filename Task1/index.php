<?php
session_start();
#Dùng để lưu phiên đăng nhập cho các trang
?>

<!DOCTYPE html>
<html>
<head>
  <title>Trang chủ</title>
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
      background: linear-gradient(to right, #d16ba5, #c777b9, #ba83ca, #aa8fd8, #9a9ae1, #8aa7ea, #79b3f4, #69bff8, #52cffe, #41dfff, #46eefa, #5ffbf1);
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #fff;
    }
    .container {
      text-align: center;
      padding: 80px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
    }
    h1 {
      font-size: 2.5em;
      margin-bottom: 10px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }
    .greeting {
      font-size: 1.5em;
      margin-bottom: 20px;
      color: #41dfff;
    }
    .nav {
      margin: 20px 0;
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    .nav a {
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      font-size: 1.1em;
      transition: all 0.3s ease;
    }
    .nav a:hover {
      background: #41dfff;
      color: #fff;
      text-decoration: none;
      transform: scale(1.1);
    }
    .fun-section {
      margin-top: 20px;
      font-size: 1.2em;
      color: #5ffbf1;
    }
    .fun-image {
      margin-top: 20px;
      max-width: 200px;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php if (isset($_SESSION["user"])): ?>
      <p class="greeting">Xin chào <?php echo $_SESSION["user"]["name"]; ?>!</p>
      <h1>Chào mừng đến với trang web của Tuyến</h1>
      <div class="nav">
        <a href="search.php">Tìm kiếm</a>
        <a href="profile.php">Xem thông tin cá nhân</a>
        <a href="logout.php">Đăng xuất</a>
      </div>
    <?php else: ?>
      <p class="greeting">Xin chào Khách!</p>
      <h1>Chào mừng đến với trang web của Tuyến</h1>
      <div class="nav">
        <a href="login.php">Đăng nhập</a>
        <a href="register.php">Đăng ký</a>
      </div>
    <?php endif; ?>

    <div class="fun-section">
      🎉 Hôm nay đẹp trời ghê! 🌌<br>
      🚀 Chúc bạn có một ngày đầy năng lượng! 😎
    </div>
    <img src="https://media.tenor.com/7OlHCow1QGgAAAAM/cy-chengyi.gif" alt="Fun Space GIF" class="fun-image">
</iframe>
  </div>
</body>
</html>
