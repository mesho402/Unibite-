<?php
include("get_user_info.php");

$sql = "SELECT `id`, `type`, `name`, `price`, `image`, `ingredients`, `status` FROM `menu`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recommendations</title>
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
      color: #93BE55;
      font-size: 16px;
      margin-bottom: 25px;
    }

    /* المنتجات */
    .product-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow: hidden;
      margin-bottom: 20px;
      position: relative;
      padding: 10px;
    }

    .product-top {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
    }

    .product-top img {
      width: 100%;
      height: 150px;
      border-radius: 16px;
      object-fit: cover;
    }

    .cat-icon {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #fff;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .cat-icon img {
      width: 18px;
      height: 18px;
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
    }

    .rating {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background: #FFD8C7;
      border-radius: 20px;
      padding: 2px 6px;
      font-size: 12px;
      font-weight: 600;
      color: #391713;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .rating img {
      width: 10px;
      height: 10px;
    }

    .product-info {
      margin-top: 10px;
    }

    .new-product {
      background: #93BE55;
      color: white;
      border-radius: 8px;
      font-size: 11px;
      padding: 3px 8px;
      display: inline-block;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .product-info h4 {
      font-size: 14px;
      font-weight: 600;
      color: #391713;
      margin: 2px 0;
    }

    .desc {
      font-size: 11px;
      color: #555;
      line-height: 1.4;
      margin-bottom: 6px;
    }

    .product-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .product-actions .price {
      font-size: 14px;
      font-weight: 600;
      color: #93BE55;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .quantity-controls button {
      width: 26px;
      height: 26px;
      border: none;
      border-radius: 50%;
      color: #fff;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
    }

    .quantity-controls .minus {
      background-color: #2672C3;
    }

    .quantity-controls .plus {
      background-color: #93BE55;
    }

    .quantity-controls span {
      font-size: 14px;
      font-weight: 600;
      color: #391713;
      width: 16px;
      text-align: center;
    }

    .cart-blue {
      background-color: #fff;
      border-radius: 10px;
      padding: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .cart-blue img {
      width: 16px;
      height: 16px;
    }

    /* Navbar */
    .bottom-nav {
      display: flex;
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

    .bottom-nav a img {
      width: 24px;
      height: 24px;
    }

  </style>
</head>
<body>

  <!-- الجزء الأزرق -->
  <div class="top-bar">
    <button onclick="history.back()">&lt;</button>
    Recommendations
  </div>

  <!-- الجزء الأبيض -->
  <div class="content">
    <h3>Discover the dishes recommended by us.</h3>

    <div id="recommendContainer"></div>
  </div>

  <!-- Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact"></a>
  </div>

<script>
    let products = [];
</script>
<?php
while ($row = $result->fetch_assoc()) {
?>

<script>
      products.push({
        name: "<?= $row['name'] ?>",
        img: "<?= $row['image'] ?>",
        price: "<?= $row['price'] ?>",
        rating: 5 ,
        desc: "<?= substr($row['ingredients'], 0, 30) . '...' ?>",
        cat: "<?= $row['type'] ?>"
      });
</script>
<?php }?>

  <script>
 

    const catIcons = {
      snacks: "imgs/icons/snacks-green.png",
      meal: "imgs/icons/meals-green.png",
      desserts: "imgs/icons/desserts-green.png",
      drinks: "imgs/icons/drinks-green.png"
    };

    const container = document.getElementById("recommendContainer");
    container.innerHTML = products.map((p, i) => `
      <div class="product-card">
        <div class="product-top">
          <img src="${p.img}" alt="${p.name}">
          <div class="cat-icon"><img src="${catIcons[p.cat]}"></div>
          <div class="fav-icon"><img src="imgs/icons/heart.png"></div>
          <div class="rating"><img src="imgs/icons/star.png"> ${p.rating}</div>
        </div>
        <div class="product-info">
          ${p.new ? `<div class="new-product">New Product</div>` : ''}
          <h4>${p.name}</h4>
          <div class="desc">${p.desc}</div>
          <div class="product-actions">
            <div class="price">${p.price} ريال</div>
            <div class="quantity-controls">
              <button class="minus" onclick="updateQty(event, -1)">−</button>
              <span>1</span>
              <button class="plus" onclick="updateQty(event, 1)">+</button>
              <div class="cart-blue"><img src="imgs/icons/cart-blue.png"></div>
            </div>
          </div>
        </div>
      </div>
    `).join("");

    function updateQty(e, delta) {
      e.stopPropagation();
      const span = e.target.parentElement.querySelector("span");
      let val = parseInt(span.textContent);
      val = Math.max(1, val + delta);
      span.textContent = val;
    }
  </script>

</body>
</html>
