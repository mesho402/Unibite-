<?php
include("get_user_info.php");

if (isset($_GET['order_detail_id'])) {
    $order_detail_id = $_GET['order_detail_id'];
}
elseif (isset($_POST['order_detail_id'])) {
    $order_detail_id = intval($_POST['order_detail_id']);

    // 1. نجيب رقم الطلب (order_id) اللي تابع له الـ order_detail
    $sql_get_order_id = "SELECT order_id FROM order_details WHERE id = $order_detail_id";
    $result_order = $conn->query($sql_get_order_id);

    if ($result_order && $result_order->num_rows > 0) {
        $row_order = $result_order->fetch_assoc();
        $order_id = $row_order['order_id'];

        // 2. نحذف الـ order_detail نفسه
        $sql_delete_detail = "DELETE FROM order_details WHERE id = $order_detail_id";
        $conn->query($sql_delete_detail);

        // 3. نتحقق هل باقي تفاصيل لنفس الطلب
        $sql_check = "SELECT COUNT(*) AS total FROM order_details WHERE order_id = $order_id";
        $result_check = $conn->query($sql_check);
        $row_check = $result_check->fetch_assoc();

        // 4. لو مافي تفاصيل ثانيه نحذف الطلب نفسه
        if ($row_check['total'] == 0) {
            $sql_delete_order = "DELETE FROM orders WHERE id = $order_id";
            $conn->query($sql_delete_order);
        }
    }

    header("Location: index.php");
    exit();
}
else {
    // لو مافي order_detail_id أصلاً
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
  <title>Cancel Order | UniBite</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: "League Spartan", sans-serif;
    }
    /* Header */
    .cancel-header {
      background-color: #2672C3;
      color: #fff;
      padding: 3rem 1.5rem;
      border-bottom-left-radius: 35px;
      border-bottom-right-radius: 35px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .cancel-header h2 {
      font-size: 1.6rem;
      font-weight: 700;
    }
    .cancel-header .back-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: white;
      font-size: 1.8rem;
    }

    /* Container */
    .cancel-container {
      background-color: #fff;
      padding: 1.5rem;
      margin-top: -10px;
      border-top-left-radius: 40px;
      border-top-right-radius: 40px;
      min-height: 100vh;
      color: #391713;
      font-family: "League Spartan", sans-serif;
    }

    .cancel-container p {
      font-size: 0.95rem;
      margin-bottom: 1rem;
    }

    .reason-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.6rem 0;
      border-bottom: 1px solid #391713;
    }
    .reason-item:last-child {
      border-bottom: none;
    }

    .reason-item label {
      flex: 1;
      cursor: pointer;
    }

    /* Checkbox style */
    .reason-item input[type="checkbox"] {
      appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid #93BE55;
      border-radius: 50%;
      position: relative;
      cursor: pointer;
    }
    .reason-item input[type="checkbox"]:checked::after {
      content: "";
      position: absolute;
      top: 4px;
      left: 4px;
      width: 8px;
      height: 8px;
      background-color: #93BE55;
      border-radius: 50%;
    }

    /* Textbox */
    textarea {
      width: 100%;
      height: 100px;
      background-color: #2672C3;
      border: none;
      border-radius: 12px;
      color: white;
      padding: 10px;
      font-size: 0.95rem;
      margin-top: 1rem;
      resize: none;
    }
    textarea::placeholder {
      color: white;
      opacity: 0.8;
    }

    /* Submit button */
    .submit-btn {
      background-color: #93BE55;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
      font-size: 1rem;
      font-weight: 600;
      display: block;
      margin: 1.5rem auto 2rem;
      cursor: not-allowed;
      opacity: 0.6;
      transition: 0.3s;
    }
    .submit-btn.active {
      cursor: pointer;
      opacity: 1;
    }

    /* Bottom nav same as before */
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
    .bottom-nav a img {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }
    @media (max-width: 1024px) {
      .bottom-nav { display: flex; }
      nav { display: none; }
    }
  </style>
</head>
<body>

  <section class="cancel-header">
    <button class="back-btn" onclick="history.back()">&lt;</button>
    <h2>Cancel Order</h2>
    <div style="width: 24px;"></div>
  </section>

  <section class="cancel-container">
    <p>We’re sorry to hear you’d like to cancel your order.<br>Please select a reason below:</p>
    <form action="cancel-order.php" method="post">
    <div class="reason-item"><label>The order took too long</label><input type="checkbox"></div>
    <div class="reason-item"><label>I changed my mind</label><input type="checkbox"></div>
    <div class="reason-item"><label>Ordered by mistake</label><input type="checkbox"></div>
    <div class="reason-item"><label>Payment issue</label><input type="checkbox"></div>
    <div class="reason-item"><label>I won’t be on campus anymore</label><input type="checkbox"></div>
    <div class="reason-item"><label>Others</label><input type="checkbox" id="other-check"></div>

    <textarea id="other-text" placeholder="Others reason..." disabled></textarea>
    <input type="hidden" name="order_detail_id" value="<?= $order_detail_id; ?>">
    <button class="submit-btn" id="submitBtn" type="submit">Submit</button>
    </form>
  </section>

  <div class="bottom-nav">
    <a href="index.php"><img src="imgs/icons/home.png" alt="Home"></a>
    <a href="meals.php"><img src="imgs/icons/meal.png" alt="Meal"></a>
    <a href="favorites.html"><img src="imgs/icons/favorite.png" alt="Favorite"></a>
    <a href="orders.php" class="active"><img src="imgs/icons/orders.png" alt="Orders"></a>
    <a href="contact-us.html"><img src="imgs/icons/contact.png" alt="Contact"></a>
  </div>

  <script>
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const textarea = document.getElementById('other-text');
    const submitBtn = document.getElementById('submitBtn');
    const otherCheck = document.getElementById('other-check');

    checkboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        if (otherCheck.checked) textarea.disabled = false;
        else textarea.disabled = true;

        if ([...checkboxes].some(c => c.checked) || textarea.value.trim() !== '') {
          submitBtn.classList.add('active');
          submitBtn.disabled = false;
          submitBtn.style.cursor = 'pointer';
          submitBtn.onclick = () => window.location.href = 'cancelled.html';
        } else {
          submitBtn.classList.remove('active');
          submitBtn.disabled = true;
          submitBtn.style.cursor = 'not-allowed';
        }
      });
    });

    textarea.addEventListener('input', () => {
      if (textarea.value.trim() !== '' || [...checkboxes].some(c => c.checked)) {
        submitBtn.classList.add('active');
        submitBtn.onclick = () => window.location.href = 'cancelled.html';
      } else {
        submitBtn.classList.remove('active');
      }
    });

    submitBtn.addEventListener("click", function(e) {
    e.preventDefault();
    window.location.href = 'cancelled.html';
});
  </script>

  

</body>
</html>