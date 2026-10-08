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
  <title>UniBite</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Base */
    body {
      font-family: "League Spartan", sans-serif;
      background-color: #f5f5f5;
    }

    /* Navbar */
    nav {
      background-color: #2672C3;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.8rem 2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .logo img {
      height: 45px;
      width: auto;
      object-fit: contain;
      display: block;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    .nav-links li a {
      display: flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: 0.3s;
      padding: 0.5rem 0.8rem;
      border-radius: 8px;
    }

    .nav-links li a img {
      width: 18px;
      height: 18px;
      object-fit: contain;
    }

    .nav-links li a:hover,
    .nav-links li a.active {
      background-color: #064180;
      color: #2D8EF7;
    }

    .menu-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 4px;
    }

    .menu-toggle span {
      background-color: white;
      width: 25px;
      height: 3px;
      border-radius: 3px;
      transition: 0.3s;
    }

    @media (max-width: 1024px) {
      .nav-links {
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        flex-direction: column;
        background-color: #1b5491;
        align-items: center;
        display: none;
        padding: 1rem 0;
      }

      .nav-links.active {
        display: flex;
      }

      .menu-toggle {
        display: flex;
      }
    }

    /* Search Section */
    .home-section {
      padding: 1.5rem 2rem;
    }

    .search-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 2rem;
      margin: 20px 50px;
    }

    .search-bar {
      display: flex;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 40px;
      padding: 4px 8px;
      flex: 1;
      min-width: 250px;
      background: none;
      justify-content: space-between;
    }

    .search-input {
      flex: 1;
      border: none;
      outline: none;
      font-size: 1rem;
      background: none;
      padding-left: 10px;
    }

    .search-icon {
      background-color: #93BE55;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 35px;
      height: 35px;
      margin-left: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .search-icon:hover {
      background-color: #4c632b;
    }

    .search-icon img {
      width: 18px;
      height: 18px;
      filter: brightness(0) invert(1);
    }

    /* Icons */
    .top-icons {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .top-icons .icon {
      background-color: #F5F5F5;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: 0.3s;
    }

    .top-icons .icon:hover {
      transform: scale(1.08);
    }

    .top-icons .icon img {
      width: 55%;
      height: 55%;
      object-fit: contain;
      display: block;
    }


    @media (max-width: 768px) {
      .search-section {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
      }

      .search-bar {
        width: 100%;
      }

      .top-icons {
        justify-content: center;
      }
    }

    /* Categories */
    .categories {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .category {
      flex: 1 1 150px;
      max-width: 150px;
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      transition: 0.3s;
      text-align: center;
    }

    .icon-wrapper {
      width: 80px;
      height: 80px;
      background-color: #93be55;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      transition: 0.3s;
    }

    .icon-wrapper img {
      width: 70%;
      height: 70%;
      object-fit: contain;
      transition: 0.3s;
    }

    .category span {
      margin-top: 0.5rem;
      font-weight: 500;
      color: #4c632b;
    }

    .category:hover img {
      transform: scale(1.1);
    }

    @media (max-width: 768px) {
      .categories {
        justify-content: center !important;
        gap: 1rem;
      }

      .category {
        flex: 0 1 20%;
        max-width: 45%;
      }

      .icon-wrapper {
        width: 70px;
        height: 70px;
      }

      .category span {
        font-size: 0.9rem;
      }
    }

    @media (max-width: 480px) {
      .categories {
        justify-content: center !important;
        align-items: center;
        gap: 1rem;
      }

      .category {
        flex: 0 1 20%;
        max-width: 45%;
      }

      .icon-wrapper {
        width: 65px;
        height: 65px;
      }

      .category span {
        font-size: 0.85rem;
      }
    }

    /* Products */
    .products-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      justify-content: center;
      align-items: center;
      min-height: 200px;
    }

    .product-card {
      background-color: white;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }

    .product-card:hover {
      transform: translateY(-4px);
    }

    .product-card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 0.8rem;
    }

    .product-card h4 {
      color: #4c632b;
      margin-bottom: 0.4rem;
    }

    .product-card .rating {
      display: flex;
      align-items: center;
      margin-bottom: 0.4rem;
    }

    .product-card .rating img {
      width: 16px;
      height: 16px;
      margin-right: 2px;
    }

    .product-card p {
      font-size: 0.9rem;
      color: #555;
    }

    .product-card .price {
      margin-top: 0.5rem;
      font-weight: bold;
      color: #93be55;
    }

    .placeholder {
      grid-column: 1 / -1;
      text-align: center;
      color: #888;
      font-size: 1rem;
      margin: auto;
    }

    @media (max-width: 992px) {
      .products-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .products-container {
        grid-template-columns: 1fr;
      }

      .product-card {
        width: 90%;
        margin: 0 auto;
      }
    }

    /* Greeting */
    .greeting-text {
      margin-top: 15px;
      margin-left: 3.5rem;
      text-align: left;
    }

    .greeting-text h2 {
      color: #000000;
      font-size: 1.8rem;
      font-weight: 600;
    }

    .greeting-text p {
      color: #93BE55;
      font-size: 1rem;
      margin-top: 0.8rem;
      font-weight: 600;
    }

    /* Sections (Best Seller & Recommend) */
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 1rem 0;
      padding-right: 8px;
    }

    .section-header h3 {
      font-size: 1.4rem;
      font-weight: 700;
      color: #4a2c2a;
    }

    .view-all {
      color: #2672c3;
      font-weight: 600;
      text-decoration: none;
      font-size: 0.95rem;
      transition: 0.3s;
    }

    .view-all:hover {
      color: #93be55;
    }

    /* Cards Container */
    .cards {
      display: flex;
      gap: 14px;
      overflow-x: auto;
      padding: 10px 14px;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
    }

    .cards::-webkit-scrollbar {
      display: none;
    }

    /* Individual Card */
    .card {
      position: relative;
      flex: 0 0 300px;
      height: 220px;
      border-radius: 14px;
      overflow: hidden;
      background: #f8f8f8;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      scroll-snap-align: start;
      transition: 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .card .price {
      position: absolute;
      bottom: 8px;
      right: 8px;
      background: #93be55;
      color: white;
      font-weight: 600;
      font-size: 0.9rem;
      padding: 4px 8px;
      border-radius: 10px;
    }

    @media (max-width: 768px) {
      .cards .card {
        flex: 0 0 180px;
        height: 200px;
      }
    }

    @media (max-width: 480px) {
      .cards .card {
        flex: 0 0 160px;
        height: 180px;
      }
    }

    /* Slider */
    .promo-slider {
      position: relative;
      width: 90%;
      max-width: 650px;
      margin: 2rem auto;
      overflow: hidden;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .slides-wrapper {
      display: flex;
      transition: transform 0.5s ease;
    }

    .slide {
      min-width: 100%;
      position: relative;
    }

    .slide img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      display: block;
    }

    .overlay {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 45%;
      background-color: #2672C3;
      color: #F8F8F8;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 15px;
      border-top-left-radius: 20px;
      border-bottom-left-radius: 20px;
    }

    .overlay p {
      font-size: 1rem;
    }

    .overlay h2 {
      font-size: 1.8rem;
      font-weight: bold;
      margin-top: 6px;
    }

    .dots {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      z-index: 10;
      margin: 0;
    }

    .dot {
      width: 10px;
      height: 10px;
      background-color: #ccc;
      border-radius: 50%;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .dot.active {
      background-color: #93BE55;
    }

    @media (max-width: 768px) {
      .slide img {
        height: 220px;
      }
      .overlay {
        width: 60%;
      }
    }

    @media (max-width: 480px) {
      .slide img {
        height: 180px;
      }
      .overlay {
        width: 70%;
      }
    }

    /* Recommend Section */
    .recommend .card {
      min-width: 200px; /* تكبير الكارت */
      height: 220px;    /* تكبير الكارت */
    }

    .recommend .card .info {
      position: absolute;
      top: 8px;
      left: 8px;
      right: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .recommend .rating {
      background: #fff;
      border-radius: 20px;
      padding: 3px 8px;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .recommend .rating img {
      width: 14px;
      height: 14px;
      filter: hue-rotate(200deg);
    }

    .recommend .fav {
      background: #F8F8F8;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .recommend .fav img {
      width: 60%;
      height: 60%;
      object-fit: contain;
    }

    @media (max-width: 768px) {
      .recommend .card {
        min-width: 180px;
        height: 200px;
      }
    }

    @media (max-width: 480px) {
      .recommend .card {
        min-width: 160px;
        height: 180px;
      }
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

    /* ===== Blue Header (Mobile Only) ===== */
    @media (max-width: 768px) {
      body {
        background-color: #2672C3;
      }

      .blue-header {
        background-color: #2672C3;
        padding: 25px 15px 40px;
        position: relative;
        z-index: 1;
      }

      /* 🔍 Search + Icons on same row */
      .blue-header .search-section {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 8px !important;
        width: 100% !important;
        flex-wrap: nowrap !important;
        margin: 0 0 10px 0 !important;
      }

      /* Search Bar */
      .blue-header .search-bar {
        flex: 1 1 auto !important;
        /* min-width: 0 !important; */
        max-width: 68% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        background: #fff !important;
        border-radius: 40px !important;
        padding: 4px 10px !important;
      }

      .blue-header .search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.9rem;
        color: #333;
        background: transparent;
      }

      .blue-header .search-input::placeholder {
        color: #999;
      }

      .blue-header .search-icon {
        background-color: #93BE55;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .blue-header .search-icon img {
        width: 16px;
        height: 16px;
        filter: brightness(0) invert(1);
      }

      /* 🟢 Icons beside search bar */
      .blue-header .top-icons {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 40px !important;
        padding: 6px 10px !important;
        gap: 8px !important;
        flex-shrink: 0 !important;
        width: auto !important;
      }

      .blue-header .icon {
        width: 32px !important;
        height: 32px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 42% !important;
      }

      .blue-header .icon img {
        width: 20px !important;
        height: 20px !important;
      }

      /* 🕊 Greeting Text */
      .greeting-text {
        margin-top: 15px !important;
        margin-left: 7px;
        text-align: left;
      }

      .greeting-text h2 {
        color: #fff;
        font-size: 1.8rem;
        font-weight: 600;
      }

      .greeting-text p {
        color: #93BE55;
        font-size: 1rem;
        margin-top: 5px;
        font-weight: 600;
      }

      /* ⚪ White section below */
      .home-section {
        background: #fff;
        border-top-left-radius: 60px;
        border-top-right-radius: 60px;
        padding: 40px 20px 180px;
        margin-top: -25px;
        z-index: 2;
        position: relative;
      }
    }

    /* ====== Sidebar ====== */
    .sidebar {
      position: fixed;
      top: 0;
      right: -300px;
      width: 280px;
      height: 100%;
      background-color: #93BE55;
      padding: 20px;
      transition: 0.4s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      z-index: 2000;
      box-shadow: -4px 0 10px rgba(0,0,0,0.15);
      border-top-left-radius: 20px;
      border-bottom-left-radius: 20px;
    }

    .sidebar.active {
      right: 0;
    }

    /* ====== Profile Section ====== */
    .profile-section {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 30px;
    }

    .profile-pic {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      /* background: #F8F8F8; */
      padding: 8px;
      object-fit: contain;
    }

    .profile-info h3 {
      color: #F8F8F8;
      font-size: 1rem;
      text-transform: uppercase;
      font-weight: 700;
    }

    .profile-info p {
      color: #2672C3;
      font-size: 0.85rem;
      font-weight: 600;
      margin-top: 2px;
    }

    /* ====== Menu ====== */
    .menu {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #F8F8F8;
      font-weight: 600;
      font-size: 1rem;
      transition: 0.3s;
    }

    .menu-item img {
      width: 32px;
      height: 32px;
      background-color: #F8F8F8;
      padding: 6px;
      border-radius: 30%;
      object-fit: contain;
    }

    .menu-item:hover {
      transform: translateX(-5px);
    }

    /* الخط الفاصل الأبيض */
    .divider {
      height: 1px;
      width: 100%;
      background-color: #F8F8F8;
      opacity: 0.7;
    }

    /* ====== Logout Section ====== */
    .logout {
      margin-top: auto;
    }

    .logout .menu-item img {
      background-color: #F8F8F8;
    }

    /* ====== Profile Toggle Button ====== */
    .icon.profile-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      width: 45px;
      height: 45px;
      background-color: #F8F8F8;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      cursor: pointer;
      z-index: 2100;
      transition: 0.3s;
    }

    .icon.profile-toggle:hover {
      transform: scale(1.1);
    }

    .icon.profile-toggle img {
      width: 60%;
      height: 60%;
      object-fit: contain;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
      .sidebar {
        width: 260px;
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
      }
    }

    .notifications-sidebar {
      position: fixed;
      top: 0;
      right: -100%;
      width: 70%;
      height: 100%;
      background-color: #93BE55;
      box-shadow: -3px 0 8px rgba(0, 0, 0, 0.1);
      transition: right 0.4s ease;
      z-index: 2000;
      display: flex;
      flex-direction: column;
    }

    .notifications-sidebar.open {
      right: 0;
    }

    /* الجزء الأخضر فوق */
    .notifications-header {
      background-color: var(--green);
      padding: 25px 20px 15px;
      border-bottom-left-radius: 50px;
      border-bottom-right-radius: 50px;
      text-align: center;
    }

    .notifications-header .white-line {
      margin-bottom: 0.3rem !important;
      margin-top: 2rem !important;
    }

    .notif-item .notif-box span {
      color: #fff;
      font-size: 15px;
      font-weight: 400;
    }

    .notifications-header .header-center {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .notifications-header .notif-icon {
      width: 28px;
      height: 28px;
      object-fit: contain;
      display: block;
    }

    .notifications-header h2 {
      color: #fff;
      font-size: 20px;
      font-weight: 600;
      margin: 0;
    }

    .notifications-header .white-line {
      margin-top: 10px;
      height: 1px;
      background-color: #fff;
      width: 100%;
    }

    /* المحتوى الأبيض */
    .notifications-content {
      flex: 1;
      padding: 20px;
    }

    .notif-item hr {
      border: none;
      border-bottom: 1px solid #E0E0E0;
    }

    .notif-box {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 0;
    }

    .notif-box img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .notif-box span {
      font-size: 15px;
      color: var(--text);
      font-weight: 500;
    }

    /* === CART SIDEBAR === */
    .cart-sidebar {
      position: fixed;
      top: 0;
      right: -100%;
      width: 300px;
      height: 100vh;
      background-color: #93BE55;
      color: white;
      font-family: "League Spartan", sans-serif;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 20px;
      box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
      transition: right 0.4s ease;
      z-index: 9999;
      overflow-y: auto;
      border-top-left-radius: 50px;
      border-bottom-left-radius: 50px;
    }

    .cart-sidebar.active {
      right: 0;
    }

    .cart-header {
      text-align: center;
    }

    .cart-header .cart-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 20px;
    }

    .cart-header .cart-img {
      background-color: #FFF;
      border-radius: 50%;
      padding: 8px;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .cart-header img {
      width: 24px;
      height: 24px;
    }

    .cart-sidebar hr {
      margin-bottom: 20px;
    }

    .cart-content {
      flex: 1;
    }

    .cart-items {
      text-align: center;
      font-size: 18px;
      margin: 15px 0 25px;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .item-img {
      width: 70px;
      height: 70px;
      border-radius: 15px;
      object-fit: cover;
    }

    .item-info h4 {
      font-size: 16px;
      font-weight: 600;
      color: white;
    }

    .item-info .price {
      color: white;
      opacity: 0.9;
      font-size: 14px;
    }

    .item-meta {
      text-align: right;
      font-size: 12px;
    }

    .quantity {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 6px;
    }

    .quantity button {
      background: none;
      border: 2px solid white;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 16px;
      color: white;
      cursor: pointer;
    }

    .totals {
      margin-top: 20px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      font-size: 16px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .total-row.total {
      font-weight: 700;
      font-size: 18px;
    }

    .checkout-btn {
      width: 100%;
      background: #2672C3;
      color: white;
      font-size: 18px;
      border: none;
      border-radius: 25px;
      padding: 12px;
      margin-top: 20px;
      cursor: pointer;
      font-weight: 400;
    }
  </style>
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
        <div class="icon"><img src="imgs/icons/lock-icon.png" alt="Lock"></div>
        <div class="icon"><img src="imgs/icons/notification-icon.png" alt="Notifications"></div>
        <div class="icon" id="profileIcon">
          <img src="imgs/icons/profile-icon.png" alt="Profile">
        </div>
      </div>
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

    </div>

    <!-- Products Container -->
    <div id="productsContainer" class="products-container" style="display: none;"></div>

    <!-- Bottom Navbar for mobile/tablet -->
    <div class="bottom-nav">
      <a href="index.php" class="active"><img src="imgs/icons/home.png" alt="Home"></a>
      <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
      <a href="favorites.html"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
      <a href="orders.html"><img src="imgs/icons/orders.png" alt="Orders"></a>
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
          <span>We have added a product you might like.</span>
        </div>
        <hr>
      </div>

      <div class="notif-item">
        <div class="notif-box">
          <img src="imgs/icons/notif2.png" alt="Notif Icon">
          <span>One of your favorite is on promotion.</span>
        </div>
        <hr>
      </div>

      <div class="notif-item">
        <div class="notif-box">
          <img src="imgs/icons/notif3.png" alt="Notif Icon">
          <span>Your order has been delivered.</span>
        </div>
        <hr>
      </div>

      <div class="notif-item">
        <div class="notif-box">
          <img src="imgs/icons/notif1.png" alt="Notif Icon">
          <span>The food is almost done.</span>
        </div>
      </div>
    </div>
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
        <div class="total-row">
          <span>Delivery</span><span>0</span>
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

  // جمع أسعار كل المنتجات (نستبعد كلمة "ريال")
  priceEls.forEach(el => {
    const val = parseFloat(el.textContent.replace("ريال", "").trim());
    subtotal += isNaN(val) ? 0 : val;
  });

  const tax = subtotal * 0.15; // نفترض 15% ضريبة
  const delivery = 3.00;
  const total = subtotal + tax + delivery;

  // تحديث القيم في قسم totals
  const totalRows = document.querySelectorAll(".totals .total-row span:last-child");
  if (totalRows.length >= 4) {
    totalRows[0].textContent = subtotal.toFixed(2);
    totalRows[1].textContent = tax.toFixed(2);
    totalRows[2].textContent = delivery.toFixed(2);
    totalRows[3].textContent = total.toFixed(2);
  }
}
</script>
</body>
</html>