<?php 
include("get_user_info.php");

$sql = "SELECT `order_status` 
        FROM `orders`
        WHERE `user_id` = $user_id
        ORDER BY `order_date` DESC
        LIMIT 1";

$result = $conn->query($sql);

$mark = 0;

if ($row = $result->fetch_assoc()) {
    if ($row["order_status"] == "waiting") {
        $mark = 1;
    } 
    else if ($row["order_status"] == "accepted") {
        $mark = 2;
    } 
    else if ($row["order_status"] == "completed") {
        $mark = 3;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pickup Time | UniBite</title>
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
      background-color: var(--blue);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* 🔵 Header */
    .header {
      width: 100%;
      background: var(--blue);
      color: #fff;
      padding: 45px 25px 30px;
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .back {
      position: absolute;
      left: 0;
      font-size: 28px;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
    }

    .header h1 {
      font-size: 26px;
      font-weight: 700;
    }

    /* ⚪ Panel */
    .panel {
      background: #fff;
      flex: 1;
      border-top-left-radius: 50px;
      border-top-right-radius: 50px;
      padding: 40px 30px 120px;
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 -4px 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      color: var(--text);
    }

    .pickup-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 1rem;
      margin-bottom: 0.5rem;
      color: var(--text);
    }

    .pickup-summary .time {
      color: var(--green);
      font-weight: 600;
      font-size: 1.05rem;
    }

    hr {
      border: none;
      border-top: 1px solid var(--text);
      margin: 1rem 0 2rem;
    }

    /* 🟤 Steps */
    .pickup-steps {
      display: flex;
      flex-direction: column;
      gap: 2.5rem;
      margin-bottom: 3rem;
      position: relative;
      padding-left: 20px;
    }

    .pickup-steps::before {
      content: "";
      position: absolute;
      left: 7px;
      top: 8px;
      bottom: 0;
      border-left: 2px dashed var(--text);
      opacity: 0.7;
    }

    .pickup-step {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      font-size: 0.95rem;
      color: var(--text);
      position: relative;
    }

    .pickup-step::before {
      content: "";
      position: absolute;
      left: -17.5px;
      top: 3px;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: var(--text);
    }

    .pickup-step span {
      color: var(--green);
      font-weight: 600;
      font-size: 0.95rem;
    }

    /* 🟦 Return Home */
    .return-home {
      display: flex;
      justify-content: flex-start;
    }

    .return-home a {
      background-color: var(--blue);
      color: #fff;
      padding: 10px 20px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .return-home a:hover {
      opacity: 0.9;
    }

    /* 🟢 Bottom Nav */
    .bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: var(--green);
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.2);
      justify-content: space-around;
      align-items: center;
      padding: 8px 0;
      z-index: 1000;
    }

    .bottom-nav a img {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }

    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
    }
  </style>
</head>
<body>

  <!-- 🔵 Header -->
  <div class="header">
    <div class="header-inner">
      <div class="back" onclick="history.back()">&lt;</div>
      <h1>Pickup Time</h1>
    </div>
  </div>

  <!-- ⚪ Content Panel -->
  <div class="panel">
    <div class="pickup-summary">
      <p>Estimated <strong>Pickup</strong></p>
      <p class="time">25 mins</p>
    </div>

    <hr>

    <div class="pickup-steps">
      <div class="pickup-step">
        <p>Your order is waiting</p>
        <?php if ($mark == 1) { ?>
        🟢
        <?php } ?>
        <span>10 min</span>
      </div>
      <div class="pickup-step">
        <p>Your order has been accepted</p>
        <?php if ($mark == 2) { ?>
          🟢
        <?php } ?>
        <span>2 min</span>
      </div>
      <div class="pickup-step">
        <p>The restaurant is preparing your order</p>
        <?php if ($mark == 3) { ?>
        🟢
        <?php } ?>
        <span>5 min</span>
      </div>
      <div class="pickup-step">
        <p>Order is ready for pickup</p>
        <?php if ($mark == 3) { ?>
        🟢
        <?php } ?>
        <span>8 min</span>
      </div>
    </div>

    <div class="return-home">
      <a href="index.php">Return Home</a>
    </div>
  </div>

  <!-- 🟢 Bottom Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact"></a>
  </div>

</body>
</html>
