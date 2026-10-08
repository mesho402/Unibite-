<?php
include("get_user_info.php");
$sql = "SELECT 
    o.id AS order_id,
    o.user_id,
    o.order_date,
    o.total_price,
    o.order_status,
    o.processed_by,
    od.id AS order_detail_id,
    od.menu_id,
    od.amount,
    od.price AS item_price,
    m.type AS menu_type,
    m.name AS menu_name,
    m.price AS menu_price,
    m.image AS menu_image,
    m.ingredients,
    m.status AS menu_status
FROM orders o
INNER JOIN order_details od ON od.order_id = o.id
INNER JOIN menu m ON m.id = od.menu_id
WHERE o.order_status = 'waiting' AND o.user_id = $user_id
ORDER BY o.order_date DESC;
";

$result = $conn->query($sql);


$sql2 = "SELECT 
    o.id AS order_id,
    o.user_id,
    o.order_date,
    o.total_price,
    o.order_status,
    o.processed_by,
    od.id AS order_detail_id,
    od.menu_id,
    od.amount,
    od.price AS item_price,
    m.type AS menu_type,
    m.name AS menu_name,
    m.price AS menu_price,
    m.image AS menu_image,
    m.ingredients,
    m.status AS menu_status
FROM orders o
INNER JOIN order_details od ON od.order_id = o.id
INNER JOIN menu m ON m.id = od.menu_id
WHERE o.order_status = 'accepted' AND o.user_id = $user_id
ORDER BY o.order_date DESC;
";

$result2 = $conn->query($sql2);

$sql20 = "SELECT 
    o.id AS order_id,
    o.user_id,
    o.order_date,
    o.total_price,
    o.order_status,
    o.processed_by,
    od.id AS order_detail_id,
    od.menu_id,
    od.amount,
    od.price AS item_price,
    m.type AS menu_type,
    m.name AS menu_name,
    m.price AS menu_price,
    m.image AS menu_image,
    m.ingredients,
    m.status AS menu_status
FROM orders o
INNER JOIN order_details od ON od.order_id = o.id
INNER JOIN menu m ON m.id = od.menu_id
WHERE o.order_status = 'compeleted' AND o.user_id = $user_id
ORDER BY o.order_date DESC;
";

$result20 = $conn->query($sql20);


$sql3 = "SELECT 
    o.id AS order_id,
    o.user_id,
    o.order_date,
    o.total_price,
    o.order_status,
    o.processed_by,
    od.id AS order_detail_id,
    od.menu_id,
    od.amount,
    od.price AS item_price,
    m.type AS menu_type,
    m.name AS menu_name,
    m.price AS menu_price,
    m.image AS menu_image,
    m.ingredients,
    m.status AS menu_status
FROM orders o
INNER JOIN order_details od ON od.order_id = o.id
INNER JOIN menu m ON m.id = od.menu_id
WHERE o.order_status = 'canceled' AND o.user_id = $user_id
ORDER BY o.order_date DESC;
";

$result3 = $conn->query($sql3);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <title>My Orders | UniBite</title>
  <style>
    body {
      font-family: "League Spartan", sans-serif;
      background-color: #F8F8F8;
      margin: 0;
      padding: 0;
    }

    /* Header */
    .orders-header {
      background-color: #2672C3;
      color: #F8F8F8;
      padding: 2.8rem 1.5rem 4.5rem !important;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
    }
    .orders-header h2 { font-size: 1.6rem; font-weight: 700; }
    .orders-header .back-btn {
      background: none;
      border: none;
      color: #F8F8F8;
      font-size: 1.8rem;
      cursor: pointer;
    }

    /* Tabs */
    .order-tabs {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin: 1.5rem auto;
      flex-wrap: wrap;
    }
    .order-tabs button {
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      cursor: pointer;
      font-size: 1rem;
      color: #fff;
      background-color: #2672C3;
      transition: 0.3s;
    }
    .order-tabs button.active {
      background-color: #93BE55;
    }

    .tab-divider {
      width: 90%;
      height: 1px;
      background-color: #ccc;
      margin: 0 auto 1rem;
    }

    /* Container */
    .orders-container {
      background-color: #F8F8F8;
      padding: 2rem 1rem 4rem;
      border-top-left-radius: 35px;
      border-top-right-radius: 35px;
      position: relative;
      margin-top: -2.5rem; /* overlap خفيف زي index.php */
      box-shadow: 0 -4px 8px rgba(0,0,0,0.08);
    }

    /* Completed Orders */
    .completed-orders {
      display: none;
      flex-direction: column;
      gap: 1rem;
    }
    .completed-orders.active {
      display: flex;
    }

    .order-complete-card {
      background: #fff;
      border-radius: 14px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      border-bottom: 2px solid #391713;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .order-complete-card img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
      object-fit: cover;
    }

    .order-complete-info {
      flex: 1;
    }

    .order-complete-info h4 {
      color: #391713;
      margin-bottom: 3px;
    }

    .order-complete-info p {
      color: #391713;
      font-size: 0.9rem;
      margin-bottom: 5px;
    }

    .delivered {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.9rem;
      color: #93BE55;
      font-weight: 600;
    }

    .delivered img {
      width: 10px !important;
      height: 10px !important;
      object-fit: contain;
    }

    .order-actions {
      display: flex;
      gap: 8px;
      margin-top: 0.5rem;
    }

    .order-actions button {
      border: none;
      border-radius: 20px;
      padding: 6px 14px;
      font-size: 0.85rem;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
      transition: 0.3s;
    }
    .order-actions .review {
      background-color: #93BE55;
    }
    .order-actions .again {
      background-color: #2672C3;
    }

    .order-details {
      text-align: right;
      color: #93BE55;
      font-weight: 700;
      font-size: 1.05rem;
    }

    .order-details span {
      display: block;
      font-size: 0.85rem;
      color: #391713;
      font-weight: 500;
    }

    /* Active Orders */
    .active-orders {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .active-orders.hidden { display: none; }

    .order-card {
      background: #fff;
      border-radius: 14px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      border-bottom: 2px solid #391713;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .order-card img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
      object-fit: cover;
    }

    .order-info {
      flex: 1;
    }

    .order-info h4 {
      color: #391713;
      margin-bottom: 3px;
    }

    .order-info p {
      color: #391713;
      font-size: 0.9rem;
    }

    .order-info .order-actions {
      margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
      .orders-header {
        padding: 2rem 1rem;
      }
      .order-tabs button {
        font-size: 0.9rem;
        padding: 6px 14px;
      }
      .order-card img, .order-complete-card img {
        width: 70px;
        height: 70px;
      }
    }

    /* Bottom Navbar same as index */
    .bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #93BE55;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
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
      filter: none;
      opacity: 0.9;
      transition: transform 0.3s;
    }
    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    /* Responsive behavior */
    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
      nav { display: none; }
    }
    @media (min-width: 1025px) {
      .bottom-nav { display: none; }
    }

      /* Cancelled Orders */
  .order-cancelled-card {
    background: #fff;
    border-radius: 14px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 2px solid #391713;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .order-cancelled-card img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
  }

  .order-cancelled-info {
    flex: 1;
  }

  .order-cancelled-info h4 {
    color: #391713;
    margin-bottom: 3px;
  }

  .order-cancelled-info p {
    color: #391713;
    font-size: 0.9rem;
    margin-bottom: 5px;
  }

  .cancelled-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    color: #E63946;
    font-weight: 600;
  }

  .cancelled-status img {
    width: 10px;
    height: 10px;
    object-fit: contain;
  }
  </style>
</head>
<body>

  <!-- Navbar (Desktop only) -->
  <nav>
    <div class="logo">
      <img src="imgs/unibite-logo.png" alt="UniBite Logo">
    </div>
    <ul class="nav-links">
      <li><a href="index.php"><img src="imgs/icons/home.png" alt="">Home</a></li>
      <li><a href="#"><img src="imgs/icons/meal.png" alt="">Meal</a></li>
      <li><a href="#"><img src="imgs/icons/favorite.png" alt="">Favorite</a></li>
      <li><a href="#" class="active"><img src="imgs/icons/orders.png" alt="">Orders</a></li>
      <li><a href="#"><img src="imgs/icons/contact.png" alt="">Contact Us</a></li>
    </ul>
  </nav>

  <section class="orders-header">
    <button class="back-btn" onclick="history.back()">&lt;</button>
    <h2>My Orders</h2>
    <div style="width:24px;"></div>
  </section>

  <!-- Orders Content -->
  <section class="orders-container">
    <div class="order-tabs">
      <button class="tab-btn active" data-target="active-orders">Active</button>
      <button class="tab-btn" data-target="completed-orders">Completed</button>
      <button class="tab-btn" data-target="cancelled-orders">Cancelled</button>
    </div>
    <div class="tab-divider"></div>
      <!-- Active Orders -->
      <div class="active-orders" id="active-orders">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <?php $order_detail_id = $row["order_detail_id"]; ?>
        <div class="order-card">
          <img src="<?=$row["menu_image"]?>" alt="">
          <div class="order-info">
            <h4><?=$row["menu_name"]?></h4>
            <p><?=$row["order_date"]?></p>
            <div class="order-actions">
              <button class="review" onclick="location.href='cancel-order.php?order_detail_id=<?=$order_detail_id?>'">Cancel Order</button>
              <button class="again" onclick="location.href='pickup-time.php'">Track Order</button>
            </div>
          </div>
          <div class="order-details">
            <?=$row["menu_price"]?> ريال
            <span><?=$row["amount"]?> items</span>
          </div>
        </div>
        <hr class="tab-divider">
        <?php } ?>
      </div>

      <!-- Completed Orders -->
      <div class="completed-orders" id="completed-orders">
        <?php while ($row = $result2->fetch_assoc()) { ?>
        <div class="order-complete-card">
          <img src="<?=$row["menu_image"]?>" alt="Chicken Curry">
          <div class="order-complete-info">
            <h4><?=$row["menu_name"]?></h4>
            <p><?=$row["order_date"]?></p>
            <div class="delivered">
              <img src="imgs/icons/check.png" alt="✓">
              Order accepted
            </div>
            <div class="order-actions">
              <button class="review" onclick="location.href='leave-review.php?id=<?=$row['menu_id']?>'">Leave a review</button>
              <button class="again" onclick="location.href='product.php?id=<?=$row['menu_id']?>'">Order Again</button>
            </div>
          </div>
          <div class="order-details">
            <?=$row["menu_price"]?> ريال
            <span><?=$row["amount"]?> items</span>
          </div>
        </div>
        <hr class="tab-divider">
        <?php } ?>
        <?php while ($row = $result20->fetch_assoc()) { ?>
        <div class="order-complete-card">
          <img src="<?=$row["menu_image"]?>" alt="Chicken Curry">
          <div class="order-complete-info">
            <h4><?=$row["menu_name"]?></h4>
            <p><?=$row["order_date"]?></p>
            <div class="delivered">
              <img src="imgs/icons/check.png" alt="✓">
              Order delivered
            </div>
            <div class="order-actions">
              <button class="review" onclick="location.href='leave-review.php?id=<?=$row['menu_id']?>'">Leave a review</button>
              <button class="again" onclick="location.href='product.php?id=<?=$row['menu_id']?>'">Order Again</button>
            </div>
          </div>
          <div class="order-details">
            <?=$row["menu_price"]?> ريال
            <span><?=$row["amount"]?> items</span>
          </div>
        </div>
        <hr class="tab-divider">
        <?php } ?>
      </div>

        <!-- Cancelled (Placeholder) -->
      <div class="cancelled-orders" id="cancelled-orders" style="display:none; flex-direction: column; gap: 1rem;">
        <?php while ($row = $result3->fetch_assoc()) { ?>
          <div class="order-cancelled-card">
            <img src="<?=$row["menu_image"]?>" alt="Burger Meal">
            <div class="order-cancelled-info">
              <h4><?=$row["menu_name"]?></h4>
              <p><?=$row["order_date"]?></p>
              <div class="cancelled-status">
                <img src="imgs/icons/cancel.png" alt="✗" width="12">
                Order Cancelled
              </div>
              <div class="order-actions">
                <button class="again" onclick="location.href='product.php?id=<?=$row['menu_id']?>'">Order Again</button>
              </div>
            </div>
            <div class="order-details">
              <?=$row["menu_price"]?> ريال
              <span><?=$row["amount"]?> item</span>
            </div>
          </div>
        <?php } ?>
      </div>

  </section>

  <!-- Bottom Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

  <script>
    const buttons = document.querySelectorAll(".tab-btn");
    const activeSection = document.getElementById("active-orders");
    const completedSection = document.getElementById("completed-orders");
    const cancelledSection = document.getElementById("cancelled-orders");

    buttons.forEach(btn => {
      btn.addEventListener("click", () => {
        buttons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        activeSection.style.display = "none";
        completedSection.style.display = "none";
        cancelledSection.style.display = "none";

        document.getElementById(btn.dataset.target).style.display = "flex";
      });
    });
  </script>

</body>
</html>