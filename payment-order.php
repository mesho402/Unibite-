<?php
include("get_user_info.php");
$items = [];

if (isset($_GET['data']) && $_GET['total'] > 3 && !empty($_GET['data'])) {
  $items = json_decode($_GET['data'], true);
  $total = $_GET['total'];
}else{
  // Redirect to previous page or show an error
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Order | UniBite</title>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
  <style>
    :root {
      --green: #93BE55;
      --blue: #2672C3;
      --text: #391713;
      --white: #F8F8F8;
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
      font-size: 28px;
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
      color: var(--text);
    }

    /* 🧾 Sections */
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .section-header h3 {
      color: var(--text);
      font-weight: 700;
      font-size: 1.3rem;
    }

    .edit-btn {
      background: var(--blue);
      color: var(--white);
      border: none;
      border-radius: 25px;
      padding: 6px 16px;
      font-weight: 600;
      cursor: pointer;
      font-size: 0.9rem;
      transition: 0.3s;
    }

    .edit-btn:hover {
      opacity: 0.9;
    }

    hr.divider {
      border: none;
      border-top: 1px solid var(--text);
      margin: 0.8rem 0 1rem;
      opacity: 0.6;
    }

    /* 🧾 Order Summary */
    .order-summary {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 1.2rem;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: var(--text);
      font-weight: 500;
    }

    .order-item .name {
      font-size: 1rem;
      font-weight: 500;
    }

    .order-item .items {
      color: var(--green);
      font-weight: 600;
      font-size: 0.95rem;
    }

    .order-item .price {
      font-weight: 600;
      color: var(--text);
      font-size: 1rem;
    }

    /* 💳 Payment Method */
    .payment-method {
      margin-top: 1.5rem;
    }

    .method {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1rem;
    }

    .method img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .method span {
      font-size: 1rem;
      color: var(--text);
    }

    /* ⏰ Pickup Time */
    .pickup {
      margin-top: 1.5rem;
      color: var(--text);
    }

    .pickup .row {
      display: flex;
      justify-content: space-between;
      margin-top: 1rem;
      margin-bottom: 0.4rem;
    }

    .pickup .time {
      color: var(--green);
      font-weight: 600;
    }

    /* 🟦 Confirm Button */
    .confirm-btn {
      background: var(--blue);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 0.9rem 1rem;
      width: 100%;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 2rem;
    }

    .confirm-btn:hover {
      opacity: 0.9;
    }

    /* 🟩 Bottom Nav */
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 10px 0;
      border-top: 1px solid #ddd;
      z-index: 100;
    }

    .bottom-nav a img {
      width: 28px;
      height: 28px;
    }

    @media (max-width: 768px) {
      nav { display: none; }
      body { overflow-x: hidden; }
    }
  </style>
</head>
<body>

  <!-- 🔵 Header -->
  <div class="header">
    <div class="header-inner">
      <div class="back" onclick="history.back()">&lt;</div>
      <h1>Payment</h1>
    </div>
  </div>

  <!-- ⚪ Content -->
  <div class="panel">
    <!-- 🧾 Order Summary -->
    <div class="section-header">
      <h3>Order Summary</h3>
      <button class="edit-btn">Edit</button>
    </div>
    <div class="order-summary">
    <?php
    if (!empty($items)) { 
      foreach ($items as $item) { ?>        
          <div class="order-item">
            <div>
              <p class="name"><?=$item['name'];?></p>
              <p class="items"><?=$item['quantity'];?> items</p>
            </div>
            <p class="price"><?=$item['price'];?> ريال</p>
          </div>
    <?php }}else {
      echo "<p class='name'> no itemes</p>";
    }
    ?>
    </div>

    <hr class="divider" />

    <!-- 💳 Payment Method -->
    <div class="section-header">
      <h3>total price</h3>
      <button class="edit-btn"><?= $total; ?></button>
    </div>

    <div class="payment-method">
      <div class="method">
        <img src="imgs/icons/cash-payment.png" alt="Cash Icon">
        <span>CASH</span>
      </div>
    </div>

    <hr class="divider" />

    <!-- ⏰ Pickup Time -->
    <div class="pickup">
      <h3>Pickup Time</h3>
      <div class="row">
        <span>Estimated Pickup</span>
        <span class="time">25 mins</span>
      </div>
    </div>

    <hr class="divider" />
    <?php
      $total = isset($_GET['total']) ? htmlspecialchars($_GET['total']) : 0;
      $data = isset($_GET['data']) ? urlencode($_GET['data']) : '';
    ?>
    <!-- 🟦 Confirm -->
   
    <button class="confirm-btn" onclick="window.location.href='order_operation.php?order=<?= $data ?>&total=<?= $total ?>&user_id=<?=$user_id?>'"> Confirm Order </button>
  </div>

  <!-- 🟢 Bottom Nav -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home" /></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal" /></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite" /></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders" /></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact" /></a>
  </div>

</body>
</html>