<?php
include("db.php");
include("get_user_info.php");

// Ensure no output before header redirects
if (!isset($_GET["product_id"])) {
    header("Location: product.php?msg=add_failed");
    exit;
}

$product_id = (int)$_GET["product_id"];
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
    // Not logged in — redirect to login
    header("Location: login.html");
    exit;
}

// Check if the item is already in the user's cart
$exists = false;
$checkSql = "SELECT 1 FROM `cart` WHERE `user_id` = ? AND `menu_id` = ? LIMIT 1";
if ($stmt = $conn->prepare($checkSql)) {
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
} else {
    // DB prepare error — fail safely
    header("Location: product.php?id=" . urlencode($product_id) . "&msg=add_failed");
    exit;
}

if ($exists) {
    // Item already in cart
    header("Location: product.php?id=" . urlencode($product_id) . "&msg=already_in_cart");
    exit;
}

// Insert into cart
$insertSql = "INSERT INTO `cart` (`user_id`, `menu_id`) VALUES (?, ?)";
if ($stmt = $conn->prepare($insertSql)) {
    $stmt->bind_param("ii", $user_id, $product_id);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        header("Location: product.php?id=" . urlencode($product_id) . "&msg=add_success");
        exit;
    } else {
        header("Location: product.php?id=" . urlencode($product_id) . "&msg=add_failed");
        exit;
    }
} else {
    header("Location: product.php?id=" . urlencode($product_id) . "&msg=add_failed");
    exit;
}
?>