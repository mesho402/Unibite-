<?php
include("get_staff_info.php");
$sql = "SELECT * FROM `menu`";
$result = $conn->query($sql);
$sql100 ="SELECT `id`, `user_id`, `order_date`, `total_price` 
  FROM `orders`
  WHERE order_status = 'waiting'
  ORDER BY order_date DESC;
";
$result100 = $conn->query($sql100);


$sql200 ="SELECT `id`, `user_id`, `order_date`, `total_price` 
  FROM `orders`
  WHERE order_status = 'accepted'
  ORDER BY order_date DESC;
";
$result200 = $conn->query($sql200);


$sql300 ="SELECT `id`, `user_id`, `order_date`, `total_price` 
  FROM `orders`
  WHERE order_status = 'waiting'
  ORDER BY order_date DESC;
";
$result300 = $conn->query($sql300);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Role | UniBite</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: "League Spartan", sans-serif;
      background-color: #F8F8F8;
      margin: 0;
      padding: 0;
    }

    /* Header */
    .staff-header {
      background-color: #2672C3;
      color: #fff;
      padding: 2.8rem 1.5rem 4rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .staff-header h2 {
      font-size: 1.6rem;
      font-weight: 700;
    }
    .staff-header .back-btn {
      background: none;
      border: none;
      color: #fff;
      font-size: 1.8rem;
      cursor: pointer;
    }

    /* Container */
    .staff-container {
      background: #fff;
      border-top-left-radius: 35px;
      border-top-right-radius: 35px;
      margin-top: -2.5rem;
      padding: 2rem 1rem 4rem;
      box-shadow: 0 -4px 8px rgba(0,0,0,0.08);
      min-height: 80vh;
    }

    /* Tabs */
    .staff-tabs {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }
    .staff-tabs button {
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      font-size: 1rem;
      color: #fff;
      background-color: #2672C3;
      cursor: pointer;
      transition: 0.3s;
    }
    .staff-tabs button.active {
      background-color: #93BE55;
    }

    /* Section Content */
    .tab-content {
      display: none;
      animation: fadeIn 0.4s ease;
    }
    .tab-content.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Dashboard */
    .dashboard {
      text-align: center;
      color: #391713;
    }

    .dashboard h3 {
      margin-bottom: 1rem;
    }

    /* Menu Management */
    .menu-item {
      background: #f9f9f9;
      border-radius: 14px;
      padding: 1rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .menu-item img {
      width: 60px;
      height: 60px;
      border-radius: 10px;
      object-fit: cover;
    }

    .menu-item-info {
      flex: 1;
      margin-left: 1rem;
    }

    .menu-item-actions button {
      border: none;
      border-radius: 15px;
      padding: 5px 12px;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
    }

    .edit-btn { background: #2672C3; }
    .delete-btn { background: #E63946; }

    .add-form {
      display: flex;
      flex-direction: column;
      gap: 0.7rem;
      margin-top: 1rem;
    }

    .add-form input, .add-form textarea {
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .add-form button {
      align-self: flex-start;
      background: #93BE55;
      color: #fff;
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      cursor: pointer;
    }

    /* Orders Page */
    .order-card {
      background: #fff;
      border-radius: 14px;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .order-card .order-info {
      color: #391713;
    }

    .order-actions button {
      border: none;
      border-radius: 20px;
      padding: 6px 14px;
      font-size: 0.9rem;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
    }

    .accept-btn { background: #93BE55; }
    .reject-btn { background: #E63946; }
    .progress-btn { background: #2672C3; }

    /* Navbar Bottom */
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
      opacity: 0.9;
    }

    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
      nav { display: none; }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <div class="logo">
      <img src="imgs/unibite-logo.png" alt="UniBite Logo">
    </div>
    <ul class="nav-links">
      <li><a href="index.php"><img src="imgs/icons/home.png" alt="">Home</a></li>
      <li><a href="meals.php"><img src="imgs/icons/meal.png" alt="">Meal</a></li>
      <li><a href="favorites.php"><img src="imgs/icons/favorite.png" alt="">Favorite</a></li>
      <li><a href="orders.php"><img src="imgs/icons/orders.png" alt="">Orders</a></li>
      <li><a href="contact-us.html"><img src="imgs/icons/contact.png" alt="">Contact Us</a></li>
    </ul>
  </nav>

  <!-- Header -->
  <section class="staff-header">
    <button class="back-btn" onclick="history.back()">&lt;</button>
    <h2>Staff</h2>
    <div style="width:24px;"></div>
  </section>

  <!-- Content -->
  <section class="staff-container">
    <div class="staff-tabs">
      <button class="tab-btn active" data-target="dashboard">Dashboard</button>
      <button class="tab-btn" data-target="menu">Menu Management</button>
      <button class="tab-btn" data-target="orders">Orders Page</button>
    </div>

    <!-- Dashboard -->
    <div class="tab-content dashboard active" id="dashboard">
      <h3>إدارة الأصناف / الطلبات الجديدة</h3>
      <p>يمكنك إدارة قائمة الأصناف ومتابعة الطلبات الجديدة من هنا.</p>
      <?php while($raw = $result->fetch_assoc()){ ?>
        <div class="menu-item">
          <img src="<?= $raw['image']; ?>" alt="">
          <div class="menu-item-info">
            <strong style="color:#391713"><?= $raw['name']; ?></strong>
            <p style="color:#391713"><?= $raw['price']; ?> ريال</p>
            <p style="color:#391713"><?= $raw['type']; ?></p>
          </div>
          <div class="menu-item-actions">
            <a href="delete_item_menu.php?id=<?=$raw['id']?>"><button class="delete-btn">حذف</button></a>
          </div>
        </div>
      <?php } ?>

    </div>

    <!-- Menu Management -->
    <div class="tab-content" id="menu">
      

      <form class="add-form" method="post" action="add_to_menu.php" enctype="multipart/form-data">
        <!-- <label>اسم الصنف</label> -->
        <input type="text" name="name" placeholder="اسم الصنف" required>
        <!-- <label>السعر</label> -->
        <input type="number" name="price" step="0.01" placeholder="السعر" required>
        <label>النوع</label>
          <select name="type" required>
          <option value="">اختر النوع</option>
          <option value="snacks">Snacks</option>
          <option value="meal">Meal</option>
          <option value="desserts">Desserts</option>
          <option value="drinks">Drinks</option>
        </select>
        <!-- <label>المكونات</label> -->
        <textarea name="ingredients" placeholder="المكونات أو الوصف" required></textarea>
        <!-- <label>الصورة</label> -->
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">إضافة صنف</button>
      </form>

    </div>

    <!-- Orders Page -->
    <div class="tab-content" id="orders">
      <h3>الطلبات الجديدة</h3>
      <?php while($row = $result100->fetch_assoc()){
        $idm = $row['id'];
        $sql101 = "SELECT 
          od.amount,
          m.id AS menu_id,
          m.name AS menu_name
        FROM order_details od
        INNER JOIN menu m ON od.menu_id = m.id
        WHERE od.order_id = $idm ;
        ";
        $result101 = $conn->query($sql101); 
        $arr = [];
      ?>
      <div class="order-card">
        <div class="order-info">
          <strong>طلب <?= $row["id"] ?></strong>
          <?php while($x = $result101->fetch_assoc()){
            $arr[] = $x["menu_id"]; ?>
          <p><?= $x["menu_name"] ?> x <?= $x["amount"] ?></p>
          <?php } ?>
        </div>
        <?php
        $menu_ids_str = implode(",", $arr); 
        ?>
        <div class="order-actions">
          <a href="accept_cancel.php?accept=<?= $row["id"] ?>&item=<?= urlencode($menu_ids_str) ?>"><button class="accept-btn">قبول</button></a>
          <a href="accept_cancel.php?cancel=<?= $row["id"] ?>"><button class="reject-btn"> رفض</button></a>
          <!-- <button class="reject-btn">رفض</button> -->
        </div>
      </div>
      <?php } ?>
      
      <?php while($row = $result200->fetch_assoc()){
        $idm = $row['id'];
        $sql101 = "SELECT 
          od.amount,
          m.name AS menu_name
        FROM order_details od
        INNER JOIN menu m ON od.menu_id = m.id
        WHERE od.order_id = $idm ;
        ";
        $result101 = $conn->query($sql101); 
      ?>
      <div class="order-card">
        <div class="order-info">
          <strong>طلب <?= $row["id"] ?></strong>
          <?php while($x = $result101->fetch_assoc()){ ?>
          <p><?= $x["menu_name"] ?> x <?= $x["amount"] ?></p>
          <?php } ?>
        </div>
        <div class="order-actions">
          <a href="accept_cancel.php?compeleted=<?= $row["id"] ?>"><button class="progress-btn">قيد التنفيذ → جاهز</button></a>
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
    <a href="orders.php"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

  <script>
    const staffBtns = document.querySelectorAll('.tab-btn');
    const staffTabs = document.querySelectorAll('.tab-content');

    staffBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        staffBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        staffTabs.forEach(tab => tab.classList.remove('active'));
        document.getElementById(btn.dataset.target).classList.add('active');
      });
    });
  </script>

</body>
</html>