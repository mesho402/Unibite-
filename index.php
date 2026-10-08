<?php
include("get_user_info.php");
$sql = "SELECT * FROM `menu`";
$result = $conn->query($sql);

// Get all products from database 
$products = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $products[$row['type']][] = array(
      'id' => $row['id'],
      'name' => $row['name'], 
      'price' => $row['price'],
      'image' => $row['image'],
      'ingredients' => $row['ingredients']
    );
  }
}

// Convert products array to JSON for JavaScript use
$productsJson = json_encode($products);


$sql2 = "SELECT menu.name, menu.price, menu.image 
         FROM cart 
         JOIN menu ON cart.menu_id = menu.id 
         WHERE cart.user_id = ?";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();

$result2 = $stmt2->get_result();


$row_count = $result2->num_rows;

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
  <title>UniBite</title>
</head>
<body>
  <nav>
    <div class="logo">
      <img src="imgs/unibite-logo.png" alt="UniBite Logo">
    </div>
    <ul class="nav-links" id="navLinks">
      <li><a href="#" class="active"><img src="imgs/icons/home.png" alt="">Home</a></li>
      <li><a href="meals.php"><img src="imgs/icons/meal.png" alt="">Meal</a></li>
      <li><a href="#"><img src="imgs/icons/favorite.png" alt="">Favorite</a></li>
      <li><a href="orders.php"><img src="imgs/icons/orders.png" alt="">Orders</a></li>
      <li><a href="#"><img src="imgs/icons/contact.png" alt="">Contact Us</a></li>
      <li><a href="logout.php" id="logoutBtn"><img src="imgs/icons/logout.png" alt="">Logout</a></li>
      <!-- <li><a href="logout.php">Logout</a></li> -->
    </ul>
  </nav>

  <!-- ✅ Blue Header for mobile only -->
  <section class="blue-header">
    <div class="search-section">
      <div class="search-bar">
        <input type="text" placeholder="Search..." class="search-input">
        <div class="search-icon">
          <img src="imgs/icons/filters-icon.png" alt="Search" class="filter-icon" style="cursor:pointer;">
        </div>
      </div>

      <div class="top-icons">
        <div class="icon"><img src="imgs/icons/shopping-cart.png" alt="Lock"></div>
        <div class="icon"><img src="imgs/icons/notification-icon.png" alt="Notifications"></div>
        <div class="icon" id="profileIcon">
          <img src="imgs/icons/profile-icon.png" alt="Profile">
        </div>
      </div>
    </div>

    <div class="greeting-text">
      <h2>Good Morning</h2>
      <p>Rise and shine! It's breakfast time</p>
    </div>
  </section>

  <!-- 🏠 Main Home Section -->
  <section id="homeSection" class="home-section" style="display: none;">

   

    <div class="categories">
      <div class="category" data-cat="snacks">
        <div class="icon-wrapper">
          <img src="imgs/categories/snacks.png" alt="Snacks">
        </div>
        <span>Snacks</span>
      </div>
      <div class="category" data-cat="meal">
        <div class="icon-wrapper">
          <img src="imgs/categories/meal.png" alt="Meal">
        </div>
        <span>Meal</span>
      </div>
      <div class="category" data-cat="desserts">
        <div class="icon-wrapper">
          <img src="imgs/categories/desserts.png" alt="Desserts">
        </div>
        <span>Desserts</span>
      </div>
      <div class="category" data-cat="drinks">
        <div class="icon-wrapper">
          <img src="imgs/categories/drinks.png" alt="Drinks">
        </div>
        <span>Drinks</span>
      </div>
    </div>

    <!-- Main Content (Home) -->
    <div id="mainContent">

      <!-- Best Seller -->
      <section class="best-seller">
        <div class="section-header">
          <h3>Best Seller</h3>
          <a href="best-seller.php" class="view-all">View All</a>
        </div>
        <div class="cards">
          <div class="card"><img src="imgs/products/food1.png" alt=""><span class="price">30.0</span></div>
          <div class="card"><img src="imgs/products/food2.png" alt=""><span class="price">20.0</span></div>
          <div class="card"><img src="imgs/products/food3.png" alt=""><span class="price">15.0</span></div>
          <div class="card"><img src="imgs/products/food4.png" alt=""><span class="price">20.0</span></div>
        </div>
      </section>

      <!-- Promo Slider -->
      <section class="promo-slider">
        <div class="slides-wrapper">
          <div class="slide active">
            <img src="imgs/products/pizza.png" alt="Promo 1">
            <div class="overlay">
              <p>Experience our delicious new dish</p>
              <h2>30% OFF</h2>
            </div>
          </div>
          <div class="slide">
            <img src="imgs/products/burgar.png" alt="Promo 2">
            <div class="overlay">
              <p>Try something new today</p>
              <h2>20% OFF</h2>
            </div>
          </div>
          <div class="slide">
            <img src="imgs/products/salad.png" alt="Promo 3">
            <div class="overlay">
              <p>Healthy and Fresh</p>
              <h2>25% OFF</h2>
            </div>
          </div>
        </div>
        <div class="dots">
          <span class="dot active" onclick="setSlide(0)"></span>
          <span class="dot" onclick="setSlide(1)"></span>
          <span class="dot" onclick="setSlide(2)"></span>
        </div>
      </section>

      <!-- Recommend -->
      <section class="recommend" onclick="location.href='recommendations.php'" style="cursor: pointer;">
        <div class="section-header">
          <h3>Recommend</h3>
        </div>
        <div class="cards">
          <div class="card">
            <img src="imgs/products/burgar.png" alt="">
            <div class="info">
              <div class="rating"><img src="imgs/icons/star.png" alt="">5.0</div>
              <div class="fav"><img src="imgs/icons/heart.png" alt=""></div>
            </div>
            <span class="price">30.0</span>
          </div>
          <div class="card">
            <img src="imgs/products/salad.png" alt="">
            <div class="info">
              <div class="rating"><img src="imgs/icons/star.png" alt="">5.0</div>
              <div class="fav"><img src="imgs/icons/heart.png" alt=""></div>
            </div>
            <span class="price">25.0</span>
          </div>
          <div class="card">
            <img src="imgs/products/pasta.png" alt="">
            <div class="info">
              <div class="rating"><img src="imgs/icons/star.png" alt="">5.0</div>
              <div class="fav"><img src="imgs/icons/heart.png" alt=""></div>
            </div>
            <span class="price">18.0</span>
          </div>
          <div class="card">
            <img src="imgs/products/sushi.png" alt="">
            <div class="info">
              <div class="rating"><img src="imgs/icons/star.png" alt="">5.0</div>
              <div class="fav"><img src="imgs/icons/heart.png" alt=""></div>
            </div>
            <span class="price">9.0</span>
          </div>
        </div>
      </section>

    </div>

    <!-- Products Container -->
    <div id="productsContainer" class="products-container" style="display: none;"></div>

    <!-- Bottom Navbar for mobile/tablet -->
    <div class="bottom-nav">
      <a href="index.php" class="active"><img src="imgs/icons/home.png" alt="Home"></a>
      <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
      <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
      <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
      <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
    </div>

  </section>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div>
      <div class="profile-section">
        <img src="imgs/icons/profile2.png" alt="Profile" class="profile-pic">
        <div class="profile-info">
          <h3><?= $user_name;?></h3>
          <p><?= $user_email;?></p>
        </div>
      </div>

      <div class="menu">
        <a href="orders.php" class="menu-item">
          <img src="imgs/icons/myorders.png" alt="Orders">
          <span>My Orders</span>
        </a>
        <div class="divider"></div>

        <a href="profile.php" class="menu-item">
          <img src="imgs/icons/myprofile.png" alt="Profile">
          <span>My Profile</span>
        </a>
        <div class="divider"></div>

        <a href="settings.html" class="menu-item">
          <img src="imgs/icons/settings.png" alt="Settings">
          <span>Settings</span>
        </a>
        <div class="divider"></div>

        <a href="payment.html" class="menu-item">
          <img src="imgs/icons/payment-methods.png" alt="Payment">
          <span>Payment Method</span>
        </a>
        <div class="divider"></div>

        <a href="contact-us.html" class="menu-item">
          <img src="imgs/icons/contactus.png" alt="Contact">
          <span>Contact Us</span>
        </a>
        <div class="divider"></div>

        <a href="address.html" class="menu-item">
          <img src="imgs/icons/address.png" alt="Address">
          <span>Address</span>
        </a>
      </div>
    </div>

    <div class="logout" id="">
      <a href="logout.php" class="menu-item">
        <img src="imgs/icons/logout-icon.png" alt="Logout">
        <span>Logout</span>
      </a>
    </div>
  </div>

  <!-- Notification Sidebar -->
  <div class="sidebar notifications-sidebar" id="notificationsSidebar">
    <div class="notifications-header">
      <div class="header-center">
        <img src="imgs/icons/notification-white.png" alt="Notifications Icon" class="notif-icon">
        <h2>Notifications</h2>
      </div>
      <div class="white-line"></div>
    </div>

    <div class="notifications-content">
      <div class="notif-item">
        <div class="notif-box">
          <img src="imgs/icons/notif1.png" alt="Notif Icon">
          <span><h1>SOON </h1></span>
        </div>
        <hr>
      </div>


  <!-- Cart Sidebar -->
  <div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
      <div class="cart-icon">
        <div class="cart-img">
          <img src="imgs/icons/cart-blue.png" alt="Cart Icon">
        </div>
        <h2>Cart</h2>
      </div>
      <hr>
    </div>

    <div class="cart-content">
      <p class="cart-items">You have <span><?= $row_count ?></span> items in the cart</p>

      <!-- Item 1 -->
      <?php while($raw = $result2->fetch_assoc()){ ?>
      <div class="cart-item">
        <img src="<?= $raw["image"] ?>" alt="<?= $raw["name"] ?>" class="item-img">
        <div class="item-info">
          <h4><?= $raw["name"] ?></h4>
          <p class="price"><?= $raw["price"] ?> ريال</p>
        </div>
        <div class="item-meta">
          <p class="time">29/11/24<br>15:00</p>
          <div class="quantity">
            <button class="minus">−</button>
            <span class="count">1</span>
            <button class="plus">+</button>
          </div>
        </div>
      </div>
      <hr>
      <?php } ?>

      <!-- Totals -->
      <div class="totals">
        <div class="total-row">
          <span>Subtotal</span><span>0</span>
        </div>
        <div class="total-row">
          <span>Tax and Fees</span><span>0</span>
        </div>
       
        <hr>
        <div class="total-row total">
          <span>Total</span><span>0</span>
        </div>
      </div>

      <button class="checkout-btn" onclick="window.location.href='confirm-order.php'">Checkout</button>
    </div>
  </div>

  <script src="js/main.js"></script>
<script>
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("plus") || e.target.classList.contains("minus")) {
    const quantityBox = e.target.closest(".quantity");
    const countSpan = quantityBox.querySelector(".count");
    const cartItem = e.target.closest(".cart-item");
    const priceEl = cartItem.querySelector(".price");

    let count = parseInt(countSpan.textContent);

    // حفظ السعر الأصلي لمرة واحدة فقط
    if (!priceEl.dataset.base) {
      priceEl.dataset.base = parseFloat(priceEl.textContent);
    }

    const basePrice = parseFloat(priceEl.dataset.base);

    // تعديل العدد
    if (e.target.classList.contains("plus")) {
      count++;
    } else if (e.target.classList.contains("minus") && count > 1) {
      count--;
    }

    countSpan.textContent = count;

    // تحديث سعر المنتج الحالي
    const totalPrice = basePrice * count;
    priceEl.textContent = totalPrice.toFixed(2) + " ريال";

    // بعد التعديل نحسب الإجمالي الكلي
    updateTotals();
  }
});

// دالة تحسب الإجماليات
function updateTotals() {
  const priceEls = document.querySelectorAll(".cart-item .price");
  let subtotal = 0;

  priceEls.forEach(el => {
    const val = parseFloat(el.textContent.replace("ريال", "").trim());
    subtotal += isNaN(val) ? 0 : val;
  });

  const tax = subtotal * 0.15; // نفترض 15% ضريبة
  const total = subtotal + tax;

  const totalRows = document.querySelectorAll(".totals .total-row span:last-child");
  if (totalRows.length >= 3) {
    totalRows[0].textContent = subtotal.toFixed(2);
    totalRows[1].textContent = tax.toFixed(2);
    totalRows[2].textContent = total.toFixed(2);
  }
}

</script>





</body>
</html>