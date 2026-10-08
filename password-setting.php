<?php
include("get_user_info.php");


$message = ""; // لعرض رسالة بعد التنفيذ

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST["current_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];
    $user_id = $user["id"]; // من get_user_info.php

    if ($new !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        // التحقق من كلمة المرور الحالية (بدون تشفير)
        $sql = "SELECT password FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row["password"] === $current) {
            // تحديث كلمة المرور الجديدة (بدون تشفير)
            $update = "UPDATE user SET password = ? WHERE id = ?";
            $stmt2 = $conn->prepare($update);
            $stmt2->bind_param("si", $new, $user_id);

            if ($stmt2->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password.";
            }
        } else {
            $message = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Password Setting | UniBite</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <style>
    :root {
      --green: #93BE55;
      --blue: #2672C3;
      --text: #391713;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "League Spartan", sans-serif;
    }

    body {
      background: var(--blue);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Header */
    .header {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 45px 25px 40px;
      color: #F8F8F8;
      background: var(--blue);
    }

    .header h1 {
      font-size: 26px;
      font-weight: 700;
      text-align: center;
      flex: 1;
    }

    .back {
      font-size: 28px;
      font-weight: 600;
      color: #F5F5F5;
      cursor: pointer;
      user-select: none;
    }

    .spacer {
      width: 28px;
    }

    /* White panel */
    .panel {
      background: #fff;
      flex: 1;
      border-top-left-radius: 50px;
      border-top-right-radius: 50px;
      padding: 60px 40px 120px;
      display: flex;
      flex-direction: column;
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
      color: var(--text);
    }

    label {
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 6px;
      display: block;
    }

    .password-input {
      position: relative;
      margin-bottom: 10px;
    }

    .password-input input {
      width: 100%;
      background: var(--blue);
      border: none;
      border-radius: 14px;
      padding: 12px 40px 12px 14px;
      font-size: 16px;
      color: var(--text);
      outline: none;
    }

    .password-input img {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 22px;
      cursor: pointer;
    }

    .forgot-password {
      text-align: right;
      color: var(--green);
      font-size: 14px;
      margin-bottom: 25px;
      cursor: pointer;
    }

    button {
      margin-top: 10px;
      width: 70%;
      background: var(--green);
      color: white;
      border: none;
      border-radius: 25px;
      padding: 12px 0;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      text-transform: capitalize;
      transition: 0.3s;
      align-self: center;
    }

    button:hover {
      opacity: 0.9;
    }

    /* Bottom Navbar (Mobile) */
    .bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: var(--green);
      border-top-left-radius: 25px;
      border-top-right-radius: 25px;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.2);
      justify-content: space-around;
      align-items: center;
      padding: 8px 0;
      z-index: 1000;
    }

    .bottom-nav a {
      display: flex;
      justify-content: center;
      align-items: center;
      flex: 1;
    }

    .bottom-nav a img {
      width: 24px;
      height: 24px;
      object-fit: contain;
      opacity: 0.9;
      transition: transform 0.3s;
    }

    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
      .panel {
        border-radius: 40px 40px 0 0;
        padding-bottom: 130px;
      }
    }

    @media (min-width: 1025px) {
      body { align-items: center; }
      .panel { margin-top: 40px; border-radius: 50px; }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <div class="back" onclick="history.back()">&lt;</div>
    <h1>Password Setting</h1>
    <div class="spacer"></div>
  </div>
  
  <!-- Content Panel -->
  <div class="panel">
    <form method="post">
      <label>Current Password</label>
      <div class="password-input">
        <input type="password" id="current-password" value="****" name="current_password">
        <img src="imgs/icons/eye-slash.png" alt="Show" onclick="togglePassword('current-password', this)">
      </div>
      <div class="forgot-password">Forgot Password?</div>
      
      <label>New Password</label>
      <div class="password-input">
        <input type="password" id="new-password" value="****" name="new_password">
        <img src="imgs/icons/eye-slash.png" alt="Show" onclick="togglePassword('new-password', this)">
      </div>
      <label>Confirm New Password</label>
      <div class="password-input">
        <input type="password" id="confirm-password" value="****" name="confirm_password">
        <img src="imgs/icons/eye-slash.png" alt="Show" onclick="togglePassword('confirm-password', this)">
      </div>
      
      <button type="submit">Change Password</button>
      <h5><?= $message; ?></h5>
    </form>
  </div>

  <!-- Bottom Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

  <script>
    function togglePassword(id, icon) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        icon.src = "imgs/icons/eye-slash.png";
      } else {
        input.type = "password";
        icon.src = "imgs/icons/eye-slash.png";
      }
    }
  </script>

</body>
</html>