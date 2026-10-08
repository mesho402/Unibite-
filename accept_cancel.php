<?php 
include("get_user_info.php");
if(isset($_GET["accept"])) {
    $order_id = $_GET["accept"];
    $sql = "UPDATE `orders` SET `order_status` = 'accepted' , `processed_by`=$user_id WHERE `id` = $order_id";
    $conn->query($sql);


    $items = explode(",", $_GET["item"]);
    
    $sql1 = "INSERT INTO `sales` (`menu_id`, `count`) 
             VALUES (?, 1)
             ON DUPLICATE KEY UPDATE `count` = `count` + 1";
    $stmt = $conn->prepare($sql1);
    
    foreach ($items as $id) {
        $id = intval($id);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: staff.php?msg=order_accepted");
    exit();

}
else if(isset($_GET["cancel"])) {
    $order_id = $_GET["cancel"];
    $sql = "UPDATE `orders` SET `order_status` = 'canceled' , `processed_by`=$user_id WHERE `id` = $order_id";
    $conn->query($sql);
    header("Location: staff.php?msg=order_rejected");
    exit();
}
else if(isset($_GET["compeleted"])) {

    $order_id = $_GET["compeleted"];
    $sql = "UPDATE `orders` SET `order_status` = 'compeleted' , `processed_by`=$user_id WHERE `id` = $order_id";
    $conn->query($sql);
    header("Location: staff.php?msg=order_compeleted");
    exit();
}


