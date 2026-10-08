<?php
include("db.php"); // تأكد إن ملف الاتصال موجود ومظبوط
$message = "";
$color = "#ffcccc";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $message = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";
    } else {
        // تحقق من وجود الإيميل
        $stmt = $conn->prepare("SELECT id , password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // موجود فعلاً
            $message = "Password reset link sent successfully!";
            $color = "#ccffcc";
            $raw = $result->fetch_assoc();

            // هنا ممكن ترسل كود فعلي بالبريد (مثلاً reset link)
            // أو تنتقل لصفحة إعادة تعيين
            header("refresh:2; url=login.html?password=$raw[password]");
        } else {
            $message = "Email not found in our records.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <title>Forget Password</title>
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
      height: 100vh;
      width: 100%;
      display: flex;
      flex-direction: column;
      background: var(--blue);
      overflow: hidden;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 70px 25px 20px;
      color: white;
      position: relative;
    }

    .header h1 {
      font-size: 26px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-align: center;
      flex: 1;
    }

    .back {
      font-size: 28px;
      font-weight: 600;
      cursor: pointer;
      user-select: none;
      line-height: 1;
    }

    .spacer {
      width: 28px;
    }

    .panel {
      background: #fff;
      flex: 1;
      border-top-left-radius: 60px;
      border-top-right-radius: 60px;
      margin-top: 40px;
      padding: 80px 40px 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      color: var(--text);
    }

    h2 {
      font-size: 22px;
      margin-bottom: 25px;
      color: var(--text);
    }

    label {
      align-self: flex-start;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 6px;
    }

    input {
      width: 100%;
      background: var(--blue);
      border: none;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 15px;
      color: var(--text);
      margin-bottom: 25px;
      outline: none;
    }

    input::placeholder {
      color: var(--text);
      opacity: 0.8;
    }

    .reset-btn {
      width: 100%;
      background: var(--green);
      color: white;
      border: none;
      border-radius: 30px;
      padding: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-text {
      margin-top: 30px;
      text-align: center;
      font-size: 13px;
      color: var(--text);
    }

    .login-text a {
      color: var(--green);
      text-decoration: none;
      font-weight: 600;
    }

    .msg {
      width: 100%;
      background: #ffcccc;
      color: #391713;
      font-size: 14px;
      text-align: center;
      padding: 8px 0;
      border-radius: 8px;
      margin-bottom: 12px;
      font-weight: 600;
      animation: fadeIn 0.3s ease;
      display: none;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="back" onclick="history.back()">&lt;</div>
    <h1>Forget Password</h1>
    <div class="spacer"></div>
  </div>

  <div class="panel">
    <?php if (!empty($message)): ?>
      <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
    <label>Email Address</label>
    <input type="email" id="email" placeholder="example@example.com" name="email">

    <div class="msg"></div>

    <button type="submit" class="reset-btn" id="resetBtn">Reset Password</button>
    </form>
    <div class="login-text">
      Remembered your password? <a href="login.html">Log In</a>
    </div>
  </div>

 
</body>
</html>