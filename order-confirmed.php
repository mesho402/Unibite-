<?php
session_start();

if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_SESSION['order_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmed | UniBite</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    :root {
      --green: #93BE55;
      --blue: #2672C3;
      --text: #391713;
    }

    body {
      background-color: #fff;
      font-family: "League Spartan", sans-serif;
      color: var(--text);
      text-align: center;
      margin: 0;
      padding: 0;
    }

    .confirmed-header {
      color: #fff;
      padding: 1.2rem 1.5rem;
      border-bottom-left-radius: 35px;
      border-bottom-right-radius: 35px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .confirmed-header .back-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: black;
      font-size: 1.8rem;
    }

    .confirmed-body {
      padding: 3rem 1rem 6rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .confirmed-body img {
      width: 150px;
      margin-bottom: 1.5rem;
    }

    .confirmed-body h2 {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.5rem;
    }

    .confirmed-body p {
      font-size: 0.95rem;
      color: var(--text);
      margin-bottom: 1.5rem;
    }

    .pickup-time {
      margin: 2rem 0 1rem;
      font-weight: 600;
      color: var(--text);
      font-size: 1rem;
    }

    .track-order {
      margin-top: 0.5rem;
    }

    .track-order a {
      color: var(--green);
      font-weight: 700;
      text-decoration: none;
      font-size: 1.05rem;
      transition: 0.3s;
    }

    .track-order a:hover {
      opacity: 0.8;
    }

    .footer-text {
      font-size: 0.85rem;
      color: var(--text);
      margin: 3rem 1rem 2rem;
      line-height: 1.4;
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
      nav { display: none; }
    }
  </style>
</head>
<body>

  <!-- 🔵 Header -->
  <section class="confirmed-header">
    <button class="back-btn" onclick="history.back()">&lt;</button>
    <div style="width: 24px;"></div>
  </section>

  <!-- ⚪ Body -->
  <section class="confirmed-body">
    <img src="imgs/icons/confirmed-icon.png" alt="Order Confirmed">
    <h2>Order Confirmed</h2>
    <p>Your order has been placed successfully</p>
    <h3> Order Number: <?php echo $order_id; ?> </h3>
    <div class="pickup-time" id="pickup-time">Pickup at --:--</div>

    <div class="track-order">
      <a href="pickup-time.php">Track my order</a>
    </div>
  </section>

  <!-- 🩶 Footer -->
  <p class="footer-text">
    If you have any questions, please reach out directly to our customer support
  </p>

  <!-- 🟢 Bottom Nav -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact"></a>
  </div>


<script>

  setTimeout(function() {
    window.location.href = "pickup-time.php";
  }, 3000);

  const pickupDiv = document.getElementById("pickup-time");

  // الحصول على الوقت الحالي
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();

  // تنسيق الوقت بصيغة AM/PM
  const ampm = hours >= 12 ? "PM" : "AM";
  hours = hours % 12;
  hours = hours ? hours : 12; // إذا كان 0 يصبح 12
  minutes = minutes < 10 ? "0" + minutes : minutes;

  pickupDiv.textContent = `Pickup at ${hours}:${minutes} ${ampm}`;
</script>
<?php unset($_SESSION['order_id']); ?>

</body>
</html>