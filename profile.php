<?php
include("get_user_info.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <title>My Profile | UniBite</title>
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

    /* Navbar (Desktop) */
    nav {
      width: 100%;
      background-color: #2672C3;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 40px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    nav .logo img {
      height: 45px;
    }

    .nav-links {
      display: flex;
      gap: 2rem;
      list-style: none;
      align-items: center;
    }

    .nav-links li a {
      text-decoration: none;
      color: var(--text);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.3s;
      font-size: 15px;
    }

    .nav-links li a img {
      width: 20px;
      height: 20px;
      object-fit: contain;
    }

    .nav-links li a.active,
    .nav-links li a:hover {
      color: var(--blue);
    }

    /* Header */
    .header {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 45px 25px 40px;
      color: #F8F8F8;
      background: var(--blue);
    }

    .header h1 {
      font-size: 26px;
      font-weight: 700;
      text-align: center;
      flex: 1;
    }

    .back {
      font-size: 28px;
      font-weight: 600;
      color: #F5F5F5;
      cursor: pointer;
      user-select: none;
    }

    .spacer { width: 28px; }

    /* Panel */
    .panel {
      background: #fff;
      flex: 1;
      border-top-left-radius: 50px;
      border-top-right-radius: 50px;
      padding: 80px 40px 120px;
      display: flex;
      flex-direction: column;
      align-items: center;
      color: var(--text);
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 -4px 8px rgba(0,0,0,0.1);
    }

    /* Profile Icon */
    .profile-pic {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 40px;
    }

    /* Labels */
    label {
      align-self: flex-start;
      font-size: 15px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 6px;
    }

    /* Inputs */
    input {
      width: 100%;
      background: var(--blue);
      border: none;
      border-radius: 14px;
      padding: 12px 16px;
      font-size: 16px;
      font-weight: 500;
      color: var(--text);
      margin-bottom: 20px;
      outline: none;
    }

    input::placeholder {
      color: var(--text);
      opacity: 0.9;
    }

    /* Button */
    .update-btn {
      margin-top: 10px;
      width: 70%;
      background: var(--green);
      color: white;
      border: none;
      border-radius: 25px;
      padding: 12px 0;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      text-transform: capitalize;
      transition: 0.3s;
    }

    .update-btn:hover {
      opacity: 0.9;
    }

    /* Bottom Navbar (Mobile) */
    .bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: var(--green);
      border-top-left-radius: 25px;
      border-top-right-radius: 25px;
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
      object-fit: contain;
      opacity: 0.9;
      transition: transform 0.3s;
    }

    .bottom-nav a.active img {
      transform: scale(1.2);
    }

    /* Responsive behavior */
    @media (max-width: 1024px) {
      nav { display: none; }
      .bottom-nav { display: flex; }
      .panel {
        width: 100%;
        border-radius: 40px 40px 0 0;
        padding-bottom: 130px;
      }
    }

    @media (min-width: 1025px) {
      .bottom-nav { display: none; }
      body {
        align-items: center;
      }
      .panel {
        margin-top: 40px;
        border-radius: 50px;
      }
    }
  </style>
</head>
<body>

  <!-- Desktop Navbar -->
  <nav>
    <div class="logo">
      <img src="imgs/unibite-logo.png" alt="UniBite Logo">
    </div>
    <ul class="nav-links">
      <li><a href="index.php"><img src="imgs/icons/home.png" alt="">Home</a></li>
      <li><a href="#"><img src="imgs/icons/meal.png" alt="">Meal</a></li>
      <li><a href="#"><img src="imgs/icons/favorite.png" alt="">Favorite</a></li>
      <li><a href="#" class="active"><img src="imgs/icons/orders.png" alt="">Orders</a></li>
      <li><a href="#"><img src="imgs/icons/contact.png" alt="">Contact Us</a></li>
    </ul>
  </nav>

  <!-- Header -->
  <div class="header">
    <div class="back" onclick="history.back()">&lt;</div>
    <h1>My Profile</h1>
    <div class="spacer"></div>
  </div>

  <!-- Profile Panel -->
  <div class="panel">
    <form action="update_profile.php" method="POST">
    <img src="imgs/icons/profile3.png" alt="Profile Icon" class="profile-pic">

    <label>Full Name</label>
    <input type="text" name="name" value="<?=$user_name?>">

    <!-- <label>Date of Birth</label>
    <input type="text" placeholder="02 / 5 / 2004"> -->

    <label>Email</label>
    <input type="email" name="email" value="<?=$user_email?>">

    <label>Phone Number</label>
    <input type="text" name="phone" value="<?=$user_phone?>">

    <button type="submit" class="update-btn">Update Profile</button>
    </form>
  </div>

  <!-- Bottom Navbar (Mobile) -->
  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.php"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact Us"></a>
  </div>

</body>
</html>