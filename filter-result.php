<?php
include("db.php"); // الاتصال بقاعدة البيانات

// التحقق من الاتصال بقاعدة البيانات
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . (isset($conn) ? $conn->connect_error : "Connection variable not set"));
}

// استقبال القيم
$main = $_GET['main'] ?? '';
$priceIndex = intval($_GET['price'] ?? 0);

// بناء شروط SQL ديناميكية
$conditions = [];

if (!empty($main)) {
    $conditions[] = "type = '" . $conn->real_escape_string($main) . "'";
}


// السعر التقريبي بناءً على القيمة المختارة
switch ($priceIndex) {
    case 0: $conditions[] = "price <= 10"; break;
    case 1: $conditions[] = "price <= 50"; break;
    case 2: $conditions[] = "price <= 100"; break;
    case 3: $conditions[] = "price < 7000"; break;
}

// بناء الاستعلام النهائي
$sql = "SELECT id, name, type, price, image FROM menu";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filtered Results</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #2672C3;
    }

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

    .product-image img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 16px;
      display: block;
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

    .desc {
      font-size: 11px;
      color: #555;
      line-height: 1.4;
    }

    .bottom-nav {
      display: none;
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
      filter: none;
      transition: transform 0.3s;
    }

    .bottom-nav a.active img { transform: scale(1.2); }

    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
    }
  </style>
</head>
<body>

  <div class="top-bar">
    <button onclick="history.back()">&lt;</button>
    Filtered Results
  </div>

  <div class="content">
    <h3>Your Filtered Dishes</h3>

    <div class="products-grid">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
      ?>
          <div class="product-card" onclick="location.href='product.php?id=<?= $row['id'] ?>'">
            <div class="product-image">
              <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
              <span class="price-tag"><?= htmlspecialchars($row['price']) ?> ريال</span>
            </div>
            <div class="product-info">
              <h4><?= htmlspecialchars($row['name']) ?></h4>
              <div class="desc"><?= htmlspecialchars($row['type']) ?></div>
            </div>
          </div>
      <?php
          }
      } else {
          echo "<p style='text-align:center;'>No results found for your filters.</p>";
      }
      ?>
    </div>
  </div>

  <div class="bottom-nav">
    <a href="index.php" class="active"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

</body>
</html>
