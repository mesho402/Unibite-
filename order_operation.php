<?php
include("get_user_info.php");
session_start();

$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
$data  = isset($_GET['order']) ? urldecode($_GET['order']) : '[]';

$items = json_decode($data, true);

if (!$items || $total <= 3) {
    header("Location: index.php?msg=invalid_order");
    exit();
}

// ==================
// إدخال الطلب
// ==================
$sql = "INSERT INTO `orders`(`user_id`, `total_price`) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();

/* 🔴 هنا أهم سطر */
$order_id = $conn->insert_id;

$stmt->close();

// ==================
// إدخال عناصر الطلب
// ==================
$item_stmt = $conn->prepare(
    "INSERT INTO `order_details`(`order_id`, `menu_id`, `amount`, `price`) 
     VALUES (?, ?, ?, ?)"
);

foreach ($items as $item) {

    $name = $item['name'];

    $menu_stmt = $conn->prepare("SELECT id FROM `menu` WHERE `name` = ?");
    $menu_stmt->bind_param("s", $name);
    $menu_stmt->execute();
    $menu_result = $menu_stmt->get_result();
    $menu_row = $menu_result->fetch_assoc();
    $menu_id = $menu_row ? intval($menu_row['id']) : 0;
    $menu_stmt->close();

    $quantity = intval($item['quantity']);
    $price    = floatval($item['price']);

    $item_stmt->bind_param("iiid", $order_id, $menu_id, $quantity, $price);
    $item_stmt->execute();
}

$item_stmt->close();

/* ✨ نخزن رقم الطلب للكستمر */
$_SESSION['order_id'] = $order_id;

/* نوديه لصفحة التأكيد */
header("Location: order-confirmed.php");
exit();
?>
