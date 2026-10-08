<?php
include 'get_user_info.php';

if(isset($_GET["id"])) {
    $menu_id = intval($_GET["id"]);
    
    $sql = "SELECT * FROM `menu` WHERE `id` = $menu_id";
    $result = $conn->query($sql);
    $menu = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);
  
    $sql_delete = "DELETE FROM `rating` WHERE user_id = $user_id AND product_id = $menu_id";
    $result_delete = $conn->query($sql_delete);


    $sql_insert = "INSERT INTO rating (user_id, product_id, stars, comment, created_at)
                   VALUES ($user_id, $menu_id, $rating, '$comment', NOW())";

    if ($conn->query($sql_insert)) {
        echo "<script>alert('✅ Review submitted successfully!'); window.location.href='product.php?id=$menu_id';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to submit review');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Leave a Review</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* ===== Header Section ===== */
    .top-section {
      background-color: #2672C3;
      height: 120px;
    }

    header {
      background: transparent;
      color: #F5F5F5;
      padding: 2rem 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    header .back-arrow {
      position: absolute;
      left: 1rem;
      font-size: 1.5rem;
      cursor: pointer;
      color: #F5F5F5;
      text-decoration: none;
    }

    header h2 {
      font-size: 1.3rem;
      font-weight: 600;
    }

    .back-btn{
      position:absolute;
      left:16px;
      top:50%;
      transform:translateY(-50%);
      background:none;
      border:none;
      color:var(--white);
      font-size:26px;
      width:44px;
      height:44px;
      display:flex;
      align-items:center;
      justify-content:center;
      border-radius:50%;
      cursor:pointer;
    }

    /* ===== Content Section ===== */
    .content {
      background-color: #ffffff;
      border-top-left-radius: 35px;
      border-top-right-radius: 35px;
      margin-top: -40px;
      padding: 2rem 1.5rem;
      text-align: center;
    }

    .content img {
      width: 160px;
      height: 160px;
      border-radius: 20px;
      object-fit: cover;
      margin-bottom: 1.2rem;
    }

    .dish-name {
      color: #391713;
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0.7rem;
    }

    .sub-text {
      color: #391713;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }

    /* ===== Stars ===== */
    .stars {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-bottom: 1.8rem;
    }

    .star {
      font-size: 2rem;
      color: #ccc;
      cursor: pointer;
      transition: color 0.3s;
    }

    .star.active {
      color: #FFD700;
    }

    /* ===== Comment Label ===== */
    .comment-label {
      color: #391713;
      font-size: 1rem;
      font-weight: 500;
      margin-bottom: 0.7rem;
    }

    /* ===== Textarea Style ===== */
    .review-box {
      background-color: #2672C3;
      color: #F8F8F8;
      width: 100%;
      border: none;
      border-radius: 15px;
      padding: 1rem;
      font-size: 0.95rem;
      outline: none;
      resize: none;
      height: 120px;
      margin-bottom: 2rem;
    }

    .review-box::placeholder {
      color: #F8F8F8;
    }

    /* ===== Buttons ===== */
    .buttons {
      display: flex;
      justify-content: center;
      gap: 1rem;
      padding-bottom: 2rem;
    }

    .btn {
      border: none;
      border-radius: 70px;
      padding: 0.6rem 2.5rem;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      color: white;
      transition: 0.3s;
    }

    .btn-cancel {
      background-color: #2672C3;
    }

    .btn-submit {
      background-color: #93BE55;
    }

    .btn:hover {
      opacity: 0.9;
    }

    /* ===== Responsive ===== */
    @media (max-width: 480px) {
      .content img {
        width: 130px;
        height: 130px;
      }
      .dish-name {
        font-size: 1.1rem;
      }
      .star {
        font-size: 1.7rem;
      }
    }

    /* ===== Toast ===== */
    .toast {
      position: fixed;
      bottom: 25px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #e7f8e2;
      color: #2c662d;
      padding: 0.9rem 1.3rem;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      font-size: 0.95rem;
      display: none;
      animation: fadeInOut 3s ease forwards;
    }

    @keyframes fadeInOut {
      0% { opacity: 0; transform: translate(-50%, 20px); }
      10%, 90% { opacity: 1; transform: translate(-50%, 0); }
      100% { opacity: 0; transform: translate(-50%, 20px); }
    }
  </style>
</head>
<body>

  <div class="top-section">
    <header>
      <button class="back-btn" aria-label="رجوع" onclick="history.back()">&lt;</button>
      <h2>Leave a Review</h2>
    </header>
  </div>

  <div class="content">
    <img src="<?= $menu['image'] ?>" alt="<?= $menu['name'] ?>" />
    <h3 class="dish-name"><?= htmlspecialchars($menu['name']) ?></h3>
    <p class="sub-text">We'd love to know what you think of your dish.</p>
    <form method="POST" id="ratingForm">
      <div class="stars" id="stars">
        <input type="hidden" name="rating" id="ratingValue" value="0">
        <span class="star">&#9733;</span>
        <span class="star">&#9733;</span>
        <span class="star">&#9733;</span>
        <span class="star">&#9733;</span>
        <span class="star">&#9733;</span>
      </div>

      <p class="comment-label">Leave us your comment!</p>
      <textarea class="review-box" name="comment" placeholder="Write Review..."></textarea>

      <div class="buttons">
        <button type="button" class="btn btn-cancel" onclick="history.back()">Cancel</button>
        <button type="submit" class="btn btn-submit">Submit</button>
      </div>
    </form>
  </div>

  <div class="toast" id="toast">✅ Review submitted successfully!</div>

  <script>
    const stars = document.querySelectorAll(".star");
    const toast = document.getElementById("toast");
    const starsInput = document.getElementById("ratingValue");
    const form = document.getElementById("ratingForm");

    stars.forEach((star, index) => {
      star.addEventListener("click", () => {
        stars.forEach((s, i) => s.classList.toggle("active", i <= index));
        starsInput.value = index + 1; // احفظ عدد النجوم المختارة
      });
    });

    form.addEventListener("submit", (e) => {
      if (!starsInput.value) {
        e.preventDefault();
        alert("Please select a star rating before submitting.");
      }
    });
  </script>
</body>

</html>