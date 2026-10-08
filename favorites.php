<?php
include("get_user_info.php");

$sql = "SELECT menu.name, menu.price, menu.id, menu.image, menu.type, menu.ingredients 
         FROM `favourite` 
         JOIN menu ON favourite.menu_id = menu.id 
         WHERE favourite.user_id = $user_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Favorites</title>
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
      gap: 25px;
    }

    .product-card {
      position: relative;
      cursor: pointer;
    }

    .product-image {
      position: relative;
    }

    .product-image img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 16px;
      display: block;
    }

    /* أيقونة الكاتيجوري */
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

    /* أيقونة القلب */
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

    /* الاسم والوصف */
    .product-info {
      text-align: center;
      margin-top: 10px;
    }

    .product-info h4 {
      font-size: 14px;
      font-weight: 600;
      margin: 5px 0 4px;
      color: #2672C3;
    }

    .product-info .desc {
      font-size: 11px;
      color: #391713;
      line-height: 1.4;
    }

    /* Bottom Navbar */
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

    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    .bottom-nav a:hover img {
      transform: scale(1.1);
    }

    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
    }
  </style>
</head>
<body>



  <!-- الجزء الأزرق -->
  <div class="top-bar">
    <button onclick="history.back()">&lt;</button>
    Favorites
  </div>

  <!-- الجزء الأبيض -->
  <div class="content">
    <h3>It's time to buy your favorite dish.</h3>

    <div class="products-grid" id="favProducts"></div>
  </div>

  <!-- Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php" class="active"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
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

    const container = document.getElementById("favProducts");
    container.innerHTML = products.map(p => `
      <div class="product-card">
        <div class="product-image">
          <img src="${p.img}" alt="${p.name}">
          <div class="cat-icon">
            <img src="${catIcons[p.cat]}" alt="">
          </div>
          <div class="fav-icon">
            <img src="imgs/icons/heart.png">
          </div>
        </div>
        <div class="product-info">
          <h4>${p.name}</h4>
          <div class="desc">${p.desc}</div>
        </div>
      </div>
    `).join("");
  </script>

</body>
</html>
