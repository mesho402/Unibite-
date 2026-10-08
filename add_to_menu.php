<?php
include("db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=not_logged_in");
    exit();
}

$name = $_POST['name'];
$price = $_POST['price'];
$type = $_POST['type'];
$ingredients = $_POST['ingredients'];

if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    $upload_dir = "images/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_path = $upload_dir . basename($image_name);

    if (move_uploaded_file($image_tmp, $image_path)) {
        $sql = "INSERT INTO `menu` (`type`, `name`, `price`, `image`, `ingredients`) 
                VALUES ('$type', '$name', '$price', '$image_path', '$ingredients')";
        $result = $conn->query($sql);


        if ($result) {
            $menu_stmt = $conn->prepare("SELECT id FROM `menu` WHERE `name` = ?");
            $menu_stmt->bind_param("s", $name);
            $menu_stmt->execute();
            $menu_result = $menu_stmt->get_result();
            $menu_row = $menu_result->fetch_assoc();
            $menu_id = $menu_row ? intval($menu_row['id']) : 0;
            $menu_stmt->close();
            
            $sql3 = "INSERT INTO `sales`(`menu_id`) VALUES ($menu_id)";
            $result3 = $conn->query($sql3);
            header("Location: staff.php?msg=success");
            exit();
        } else {
            header("Location: staff.php?msg=db_error");
            exit();
        }
    } else {
        header("Location: staff.php?msg=upload_failed");
        exit();
    }
} else {
    header("Location: staff.phpl?msg=no_image");
    exit();
}
?>
