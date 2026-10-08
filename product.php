<?php
include("get_user_info.php");
include("db.php");
if(isset($_GET["id"])){
    $id = $_GET["id"];
    $sql = "SELECT * FROM `menu` WHERE `id` =$id ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    $sql3 = "SELECT * FROM `favourite` WHERE `menu_id` =$id AND `user_id`=$user_id";
    $result3 = $conn->query($sql3);
    
  
    $stars = 5;
    $sql5 = "SELECT `stars` FROM `rating` WHERE `user_id` = $user_id AND `product_id` = $id";
    $result5 = $conn->query($sql5);
    $z = $result5->fetch_assoc();
    if($result5->num_rows > 0){
      $stars = $z["stars"];
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Product</title>

  <style>
    :root{
      --blue:#2672C3;
      --green:#93BE55;
      --dark-brown:#391713;
      --light-divider:#FFD8C7;
      --white:#ffffff;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:"League Spartan",sans-serif;
      background:var(--blue);
      /* direction: rtl; */
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* ===== top blue bar ===== */
    .top-bar{
      background:var(--blue);
      color:var(--white);
      padding:28px 20px 30px;
      position:relative;
      display:flex;
      align-items:flex-start;
      justify-content:center;
    }
    .back-btn{
      position:absolute;
      left:16px;
      top:20px;
      background:none;
      border:none;
      color:var(--white);
      font-size:28px;
      cursor:pointer;
      line-height:1;
    }
    .title-wrap{
      display:flex;
      flex-direction:column;
      align-items: center;
      gap:6px;
    }
    .product-title{
      font-size:20px;
      font-weight:700;
      color:var(--white);
    }
    .rating-row{
      display:flex;
      align-items:center;
      gap:8px;
      margin-top:2px;
    }
    .stars-compact img{
      width:18px;
      height:18px;
      opacity:0.95;
      filter:brightness(0) invert(1); /* أبيض */
    }

    /* favorite heart at far right inside small green circle */
    .fav-wrap{
      position:absolute;
      right:16px;
      top:18px;
      width:40px;
      height:40px;
      border-radius:50%;
      background:var(--green);
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .fav-wrap img{ width:18px; height:18px; filter:brightness(0) invert(1); }

    /* ===== white content ===== */
    .content {
      background: var(--white);
      border-top-left-radius: 30px;
      border-top-right-radius: 30px;
      padding: 80px;
      margin-top: -10px; /* خفيف بس */
      min-height: calc(100vh - 150px);
      color: var(--dark-brown);
      position: relative;
      z-index: 1;
    }

    .center-img{
      display:flex;
      justify-content:center;
      margin-top:-40px;
    }
    .product-img{
      width:180px;
      height:180px;
      border-radius:20px;
      object-fit:cover;
      background:#f2f2f2;
      box-shadow:0 8px 20px rgba(0,0,0,0.08);
      border-radius:20px;
    }

    .price-row{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-top:14px;
      gap:10px;
    }
    .price{
      color:var(--green);
      font-weight:800;
      font-size:20px;
    }

    .qty{
      display:flex;
      align-items:center;
      gap:8px;
      border-radius:18px;
      background:#fff;
      padding:6px;
      box-shadow:0 2px 8px rgba(0,0,0,0.06);
    }
    .qty button{
      width:34px;height:34px;border-radius:50%;border:1px solid #ddd;background:#fff;cursor:pointer;font-size:20px;
    }
    .qty .count{ min-width:26px;text-align:center;font-weight:700; }

    .divider{ height:1px;background:var(--light-divider); margin:12px 0;border-radius:1px; }

    .prod-name{ font-weight:600; color:var(--dark-brown); font-size:14px; margin-bottom:6px; }
    .prod-desc{ font-size:13px; color:var(--dark-brown); opacity:0.9; line-height:1.45; }

    /* Toppings section */
    .section-title{ margin-top:18px; font-weight:700; color:var(--dark-brown); font-size:15px; }
    .toppings{ margin-top:10px; display:flex; flex-direction:column; gap:10px; }

    .topping{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      font-size:14px;
      color:var(--dark-brown);
    }
    .topping .left{
      display:flex;
      align-items:center;
      gap:10px;
    }
    .dots-line{
      width:100px; height:8px; background:var(--light-divider); border-radius:4px; align-self:center;
      margin:0 8px; flex-shrink:0;
    }

    /* custom checkbox round with green stroke and inside dot when checked */
    .check{
      --size:20px;
      width:var(--size); height:var(--size);
      border-radius:50%;
      border:2px solid var(--green);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      background:#fff;
    }
    .check .dot{ width:10px; height:10px; border-radius:50%; background:transparent; transition:background .12s; }
    .check.checked .dot{ background:var(--green); }

    /* Add to cart row */
    .add-row{ display:flex; gap:12px; justify-content: center; align-items:center; margin-top:18px; }
    .add-btn{
      background:var(--green);
      color:#fff;
      border:none;
      padding:12px 18px;
      border-radius:26px;
      font-weight:700;
      cursor:pointer;
      display:inline-flex;
      gap:10px;
      align-items:center;
      box-shadow:0 6px 18px rgba(0,0,0,0.08);
    }
    .add-btn img{ width:20px; height:20px; filter:brightness(0) invert(1); }

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
  <!-- top blue -->
  <div class="top-bar">
    <div class="back-btn" onclick="history.back()">&lt;</div>
    
    <div class="title-wrap">
      <div class="product-title" id="productTitle">Product Name</div>
      <div class="rating-row">
        <div class="stars-compact" id="ratingStars">
          <!-- نجوم تتعمر بالـ JS -->
        </div>
      </div>
    </div>
    
    <div class="fav-wrap">
      <!-- user will replace path if needed -->
       <?php if($result3->num_rows > 0){ ?>
        <a href="remove_fav.php?id=<?=$id?>"><img src="imgs/icons/favoritee.png" alt="fav"></a>
      <?php } else { ?>
        <a href="add_fav.php?id=<?=$id?>"><img src="imgs/icons/heart-white.png" alt="fav"></a>
      <?php } ?>
    </div>
  </div>
  
  <!-- white content -->
  <div class="content">
    <div class="center-img">
      <img id="productImage" class="product-img" src="<?= $row["image"] ?>" alt="product">
    </div>
    
    <div class="price-row">
      <div class="price" id="productPrice"><?= $row["price"] ?> ريال</div>
      
      <div class="qty" aria-hidden="false">
        <button id="minusBtn">−</button>
        <div class="count" id="qtyCount">1</div>
        <button id="plusBtn">+</button>
      </div>
    </div>
    
    <div class="divider"></div>
    
    
    <div class="prod-name" id="prodNameShort">Tortilla Chips With Toppins</div>
    <div class="prod-desc" id="prodDesc"><?= $row["ingredients"] ?></div>
    
    <!-- <div class="section-title">Toppings</div> -->
    <div class="toppings" id="toppingsList">
      <!-- toppings populate from JS -->
    </div>

    <div class="add-row">
      <a href="add_to_cart.php?product_id=<?= $row["id"] ?>">
        <button class="add-btn" id="addToCartBtn">
          <img src="imgs/icons/cart-white.png" alt="cart"> Add to Cart
        </button>
      </a>
    </div>
  </div>

  <!-- Bottom Navbar for mobile/tablet -->
  <div class="bottom-nav">
    <a href="index.php" class="active"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

<script>
(function(){
  // read params
  const params = new URLSearchParams(location.search);
  const name = params.get('name') || '<?= $row["name"] ?>';
  const img = params.get('img') || '<?= $row["image"] ?>';
  const price = params.get('price') || '<?= $row["price"] ?> ريال';
  const desc = params.get('desc') || '<?= $row["ingredients"] ?>';
  const rating = parseInt(params.get('rating')) || <?= $stars ?>;

  // تعبئة البيانات
  document.getElementById('productTitle').textContent = name;
  document.getElementById('productImage').src = img;
  document.getElementById('productPrice').textContent = price;
  document.getElementById('prodNameShort').textContent = name;
  document.getElementById('prodDesc').textContent = desc;

  // عرض النجوم حسب التقييم
  const ratingContainer = document.getElementById('ratingStars');
  for (let i = 1; i <= 5; i++) {
    const star = document.createElement('img');
    star.src = 'imgs/icons/star.png';
    star.style.filter = i <= rating 
      ? 'brightness(0) saturate(100%) invert(72%) sepia(45%) saturate(603%) hue-rotate(45deg) brightness(90%) contrast(95%)' 
      : 'brightness(0) invert(1)'; // أبيض للتقييم الفاضي
    ratingContainer.appendChild(star);
  }



  // تفعيل checkboxes (دوائر)
  document.querySelectorAll('.check').forEach(c => {
    c.addEventListener('click', () => c.classList.toggle('checked'));
  });

  // quantity logic
  const minusBtn = document.getElementById('minusBtn');
  const plusBtn = document.getElementById('plusBtn');
  const qtyCount = document.getElementById('qtyCount');
  let qty = 1;
  plusBtn.addEventListener('click', () => {
    qty++;
    qtyCount.textContent = qty;
  });
  minusBtn.addEventListener('click', () => {
    if (qty > 1) qty--;
    qtyCount.textContent = qty;
  });

  // زر Add to Cart
  document.getElementById('addToCartBtn').addEventListener('click', () => {
    alert(`${qty} × ${name} added to cart ✅`);
  });
})();
</script>

</body>
</html>
