<?php
include("get_admin_info.php");
$sql = "SELECT * FROM `restaurant`";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Role</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: #2672C3;
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
      padding: 25px 20px 120px; /* مساحة للـ navbar */
      color: #391713;
      min-height: 80vh;
    }

    /* Tabs */
    .admin-tabs {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 20px;
    }

    .admin-tabs button {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      background: #E5E5E5;
      color: #391713;
      transition: 0.3s;
    }

    .admin-tabs button.active {
      background: #2672C3;
      color: #fff;
    }

    /* محتوى التبويبات */
    .tab-content {
      display: none;
      animation: fadeIn 0.3s ease-in-out;
    }

    .tab-content.active {
      display: block;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(10px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* Dashboard */
    .restaurant-list {
      margin-top: 20px;
    }

    .restaurant-item {
      background: #F9F9F9;
      padding: 12px 15px;
      border-radius: 12px;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: opacity 0.4s ease, transform 0.4s ease;
    }

    /* تأثير الإخفاء */
    .restaurant-item.hide {
      opacity: 0;
      transform: translateY(20px);
    }

    .add-btn {
      background: #93BE55;
      color: #fff;
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      display: block;
      margin: 15px auto 0;
    }

    /* Forms */
    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 20px;
    }

    label {
      font-size: 14px;
      font-weight: 600;
      color: #391713;
    }

    input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 14px;
    }

    .save-btn {
      background: #93BE55;
      color: #fff;
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      margin-top: 10px;
      align-self: center;
    }

    .delete-btn {
      background: #C33;
      color: #fff;
      padding: 8px 16px;
      border-radius: 16px;
      border: none;
      font-size: 13px;
      cursor: pointer;
    }

    /* Navbar */
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #93BE55;
      border-top-left-radius: 30px;
      border-top-right-radius: 30px;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.2);
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 10px 0;
      z-index: 1000;
    }

    .bottom-nav a {
      display: flex;
      justify-content: center;
      align-items: center;
      flex: 1;
      transition: transform 0.3s;
    }

    .bottom-nav a img {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }

    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    @media (min-width: 1025px) {
      .bottom-nav { display: none; }
    }
    @media (max-width: 1024px) {
      nav { display: none; }
    }
  </style>
</head>
<body>

  <!-- الجزء الأزرق -->
  <div class="top-bar">
    <button onclick="history.back()">&lt;</button>
    Admin
  </div>

  <!-- الجزء الأبيض -->
  <div class="content">
    <div class="admin-tabs">
      <button class="tab-btn active" data-tab="dashboard">Dashboard</button>
      <button class="tab-btn" data-tab="add">Add Restaurant</button>
    <!--  <button class="tab-btn" data-tab="edit">Edit / Delete</button>-->
    </div>

    <!-- Dashboard -->
    <div id="dashboard" class="tab-content active">
      <h3>Restaurant Dashboard</h3>
      <div class="restaurant-list" id="restaurantList">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="restaurant-item">
            <span><?= $row["name"]?></span>
            <a href="delete_restaurant.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this restaurant?');">
              <button class="delete-btn">Delete</button>
            </a>
          </div>
        <?php }?>
      </div>
      <button class="add-btn" onclick="switchTab('add')">+ Add Restaurant</button>
    </div>
    
    <!-- Add Restaurant -->
    <div id="add" class="tab-content">
      <h3>Add a New Restaurant</h3>
      <form id="addForm" method="post" action="add_restaurant.php">
        <label>Restaurant Name</label>
        <input type="text" placeholder="Enter name" name="name">
        <label>Location</label>
        <input type="text" placeholder="Enter location" name="location">
        <label>Contact Number</label>
        <input type="text" placeholder="Enter contact number" name="phone">
        <button class="save-btn" type="submit">Save</button>
      </form>
    </div>
    
   
  <!-- ✅ Navbar -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>
  
  <script>
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-tab');
        switchTab(target);
      });
    });
    
    function switchTab(tabName) {
      tabButtons.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));
      
      document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
      document.getElementById(tabName).classList.add('active');
    }
    
    // ✅ حذف ناعم بدون Alert
    document.addEventListener('click', e => {
      if (e.target.classList.contains('delete-btn')) {
        const item = e.target.closest('.restaurant-item');
        if (item) {
          item.classList.add('hide');
          setTimeout(() => item.remove(), 400);
        }
      }
    });
    </script>

</body>
</html>