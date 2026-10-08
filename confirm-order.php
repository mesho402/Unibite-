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


$sql2 = "SELECT menu.name, menu.price, menu.id, menu.image 
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirm Order | UniBite</title>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
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

    /* ⚪ Main panel */
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

    .summary-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .summary-header h3 {
      color: var(--text);
      font-weight: 700;
      font-size: 1.3rem;
    }

    .edit-btn {
      background: var(--blue);
      color: var(--green);
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

    /* 🧾 Order items */
    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(57,23,19,0.3);
    }

    .order-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .order-left img {
      width: 65px;
      height: 65px;
      border-radius: 12px;
      object-fit: cover;
    }

    .details {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .details .name {
      font-weight: 600;
      color: var(--text);
    }

    .details .date {
      font-size: 0.85rem;
      color: #777;
    }

    .cancel-small-btn {
      background: var(--blue);
      color: var(--green);
      border: none;
      border-radius: 25px;
      padding: 4px 12px;
      font-size: 0.8rem;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      width: fit-content;
    }

    .cancel-small-btn:hover {
      opacity: 0.9;
    }

    .order-right {
      text-align: right;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 6px;
    }

    .order-right .price {
      color: var(--green);
      font-weight: 600;
      font-size: 1rem;
    }

    .items-label {
      color: var(--blue);
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 2px;
    }

    .quantity {
      justify-content: center !important;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .quantity-control button {
      background: var(--blue);
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      line-height: 1;
    }

    .quantity-control button:hover {
      opacity: 0.9;
    }

    .quantity-control span {
      min-width: 20px;
      text-align: center;
      font-weight: 600;
      color: var(--text);
    }

    .totals {
      margin-top: 1rem;
    }

    .totals .row {
      display: flex;
      justify-content: space-between;
      color: var(--text);
      margin-bottom: 0.4rem;
      font-weight: 500;
    }

    .totals .total {
      margin-top: 20px;
      font-weight: 700;
      color: var(--blue);
      font-size: 1.1rem;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
      gap: 1rem;
    }

    .cancel-btn, .submit-btn {
      flex: 0.35;
      margin: 0 auto;
      padding: 0.8rem 0.5rem;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      font-size: 1rem;
      transition: 0.3s;
    }

    .cancel-btn {
      background-color: var(--blue);
      color: #F8F8F8;
    }

    .submit-btn {
      background-color: var(--green);
      color: #F8F8F8;
    }

    .cancel-btn:hover, .submit-btn:hover {
      opacity: 0.9;
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
      <h1>Confirm Order</h1>
    </div>
  </div>

  <!-- ⚪ Content -->
  <div class="panel">
    <div class="summary-header">
      <h3>Order Summary</h3>
      <button class="edit-btn">Edit</button>
    </div>
    <hr class="divider" />

    <!-- 🧾 Order item 1 -->
    <?php while($raw = $result2->fetch_assoc()){ ?>
    <div class="order-item">
      <div class="order-left">
        <img src="<?= $raw["image"]?>" alt="<?= $raw["name"]?>" />
        <div class="details">
          <p class="name"><?= $raw["name"]?></p>
          <p class="date">17 Oct 2025</p>
          <a href="delete_cart.php?menu_id=<?=$raw["id"]?>"><button class="cancel-small-btn">Cancel Order</button></a>
        </div>
      </div>
      <div class="order-right">
        <p class="price"><?=$raw["price"]?> ريال</p>
        <p class="items-label">Items</p>
        <div class="quantity-control">
          <button class="minus">-</button>
          <span class="quantity">1</span>
          <button class="plus">+</button>
        </div>
      </div>
    </div>
    <?php } ?>

    <!-- 💰 Totals -->
    <div class="totals">
      <div class="row"><span>Subtotal</span><span class="subtotal">0.00 ريال</span></div>
      <div class="row"><span>Tax & Fees</span><span class="tax">5.00 ريال</span></div>
      <hr />
      <div class="row total"><span>Total</span><span class="total-value">0.00 ريال</span></div>
    </div>

    <!-- 🟩 Buttons -->
    <div class="buttons">
      <button class="cancel-btn" id="place-order">Place Order</button>

    </div>
  </div>

  <!-- 🟢 Bottom Nav -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home" /></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal" /></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite" /></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders" /></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact" /></a>
  </div>


<script>
document.addEventListener("DOMContentLoaded", function () {

  // ازرار + و -
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("plus") || e.target.classList.contains("minus")) {
      const orderItem = e.target.closest(".order-item");
      const priceEl = orderItem.querySelector(".price");
      const quantityEl = orderItem.querySelector(".quantity");

      let quantity = parseInt(quantityEl.textContent);

      // خزن السعر الأصلي لمرة واحدة
      if (!priceEl.dataset.base) {
        priceEl.dataset.base = parseFloat(priceEl.textContent);
      }
      const basePrice = parseFloat(priceEl.dataset.base);

      // تعديل العدد
      if (e.target.classList.contains("plus")) {
        quantity++;
      } else if (e.target.classList.contains("minus") && quantity > 1) {
        quantity--;
      }

      quantityEl.textContent = quantity;

      // تحديث سعر المنتج
      const newPrice = basePrice * quantity;
      priceEl.textContent = newPrice.toFixed(2) + " ريال";

      // تحديث الإجماليات
      updateTotals();
    }
  });

  // دالة لحساب الإجماليات
  function updateTotals() {
    const allPrices = document.querySelectorAll(".order-item .price");
    let subtotal = 0;
    allPrices.forEach(p => {
      subtotal += parseFloat(p.textContent.replace("ريال", "").trim()) || 0;
    });

    const tax = subtotal * 0.15; // نفترض 15% ضريبة
    const total = subtotal + tax;

    document.querySelector(".subtotal").textContent = subtotal.toFixed(2) + " ريال";
    document.querySelector(".tax").textContent = tax.toFixed(2) + " ريال";
    document.querySelector(".total-value").textContent = total.toFixed(2) + " ريال";

    return total;
  }

  // حساب مبدئي أول ما الصفحة تفتح
  updateTotals();
  // عند الضغط على Place Order
  document.getElementById("place-order").addEventListener("click", function () {
    const items = [];

    document.querySelectorAll(".order-item").forEach(item => {
      const name = item.querySelector(".name").textContent.trim();
      const price = item.querySelector(".price").textContent.replace("ريال", "").trim();
      const quantity = item.querySelector(".quantity").textContent.trim();

      items.push({ name, price, quantity });
    });

    const total = updateTotals();
    
    // نحول البيانات لـ JSON
    const jsonData = encodeURIComponent(JSON.stringify(items));
    
    // نرسلها لصفحة الدفع
    window.location.href = "payment-order.php?data="+jsonData+"&total="+total.toFixed(2);

  });

});


</script>

</body>
</html>
