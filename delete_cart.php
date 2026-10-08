<?php
include("get_user_info.php");

if (isset($_GET['menu_id'])) {
    $menu_id = $_GET['menu_id'];

    $sql = "DELETE FROM `cart` WHERE `user_id` = ? AND `menu_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $menu_id);
    $stmt->execute();
    if ($stmt) {
        header("Location: confirm-order.php?msg=item_deleted");
        exit();
    }
    else {
        header("Location: confirm-order.php?msg=deletion_failed");
        exit();
    }
}
else{
    header("Location: confirm-order.php?msg=invalid_request");
    exit();
}

?>