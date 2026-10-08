<?php
include("get_user_info.php"); // تأكد من ملف الاتصال بقاعدة البيانات


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Best Seller</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #2672C3;
    }

    /* الجزء الأزرق */
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px 80px;
      color: white;
      font-size: 20px;
      font-weight: 600;
      position: relative;
    }

    .top-bar button {
      position: absolute;
      left: 20px;
      background: none;
      border: none;
      color: white;
      font-size: 26px;
      cursor: pointer;
    }

    /* الجزء الأبيض */
    .content {
      background: #fff;
      border-top-left-radius: 30px;
      border-top-right-radius: 30px;
      margin-top: -40px;
      padding: 25px 20px 110px;
      color: #391713;
    }

    .content h3 {
      text-align: center;
      color: #2672C3;
      font-size: 16px;
      margin-bottom: 25px;
    }

    /* المنتجات */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 18px;
    }

    .product-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow: hidden;
      cursor: pointer;
      position: relative;
    }

    .product-image {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      display: block;
      border-radius: 16px;
    }

    .cat-icon {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #fff;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cat-icon img {
      width: 16px;
      height: 16px;
      object-fit: contain;
    }

    .fav-icon {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #fff;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .fav-icon img {
      width: 16px;
      height: 16px;
      object-fit: contain;
    }

    .price-tag {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: #93BE55;
      color: #fff;
      font-size: 12px;
      border-radius: 20px;
      padding: 3px 10px;
      font-weight: 600;
    }

    .product-info {
      padding: 10px 8px 14px;
    }

    .product-info h4 {
      font-size: 14px;
      font-weight: 600;
      margin: 0;
      color: #391713;
    }

    .rating {
      display: flex;
      align-items: center;
      gap: 5px;
      margin: 4px 0;
    }

    .rating img {
      width: 14px;
      height: 14px;
    }

    .rating span {
      font-size: 12px;
      color: #391713;
      font-weight: 500;
    }

    .desc {
      font-size: 11px;
      color: #555;
      line-height: 1.4;
    }

    .cart-icon {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background: #2672C3;
      border-radius: 50%;
      width: 26px;
      height: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
    }

    .cart-icon img {
      width: 14px;
      height: 14px;
      filter: brightness(0) invert(1);
    }

    /* Bottom Navbar for mobile/tablet */
    .bottom-nav {
      display: none; /* hidden by default */
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #93BE55;
      border-top-left-radius: 30px;
      border-top-right-radius: 30px;
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
      padding: 6px 0;
    }

    .bottom-nav a img {
      width: 24px;
      height: 24px;
      object-fit: contain;
      /* خلي اللون أصلي أبيض */
      filter: none;
      transition: transform 0.3s;
    }

    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    .bottom-nav a:hover img {
      transform: scale(1.1);
    }

    /* Show bottom nav only on tablet/mobile */
    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
      nav { display: none; } /* يخفي الـ top navbar تمامًا */
    }

    @media (min-width: 1025px) {
      .bottom-nav { display: none; } /* يتأكد انه مخفي على الكمبيوتر */
    }
  </style>
</head>
<body>

  <!-- الجزء الأزرق -->
  <div class="top-bar">
    <button onclick="history.back()">&lt;</button>
    Best Seller
  </div>

  <!-- الجزء الأبيض -->
  <div class="content">
    <h3>Discover our most popular dishes!</h3>

    <div class="products-grid" id="bestProducts">
      <?php
      include("db.php");

      // استعلام لجلب المنتجات مع عدد المبيعات وترتيب حسب الأكثر مبيعًا
      $sql = "
      SELECT 
          m.id,
          m.type,
          m.name,
          m.price,
          m.image,
          m.ingredients,
          m.status,
          IFNULL(s.count, 0) AS total_sales
      FROM menu m
      LEFT JOIN sales s ON m.id = s.menu_id
      ORDER BY total_sales DESC
      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // تحديد أيقونة التصنيف
              $catIcon = "";
              switch($row['type']){
                  case "snacks": $catIcon = "imgs/icons/snacks-green.png"; break;
                  case "meal": $catIcon = "imgs/icons/meals-green.png"; break;
                  case "desserts": $catIcon = "imgs/icons/desserts-green.png"; break;
                  case "drinks": $catIcon = "imgs/icons/drinks-green.png"; break;
              }
      ?>
              <div class="product-card" onclick="location.href='product.php?id=<?= $row['id'] ?>'">
                <div class="product-image">
                  <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                  <div class="cat-icon">
                    <img src="<?= $catIcon ?>" alt="">
                  </div>
                  <div class="fav-icon">
                    <img src="imgs/icons/heart.png">
                  </div>
                  <span class="price-tag"><?= $row['price'] ?></span>
                  <div class="cart-icon">
                    <img src="imgs/icons/cart.png">
                  </div>
                </div>
                <div class="product-info">
                  <h4><?= $row['name'] ?></h4>
                  <div class="rating">
                    <img src="imgs/icons/star.png" alt="star">
                    <span>5.0</span> <!-- يمكن تعديلها لو فيه rating حقيقي -->
                  </div>
                  <div class="desc"><?= $row['ingredients'] ?></div>
                </div>
              </div>
      <?php
          }
      } else {
          echo "<p>No best sellers yet.</p>";
      }
      ?>
    </div>
  </div>

  <!-- Navbar -->
  <div class="bottom-nav">
    <a href="index.php" class="active"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

</body>

</html>