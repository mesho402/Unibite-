<?php
include("db.php");
session_start();

// تأكد إن المستخدم داخل
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=not_logged_in");
    exit();
}

// لو الفورم انرسل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];

    if (!empty($name) && !empty($location) && !empty($phone)) {

        $sql = "INSERT INTO `restaurant`(`name`, `location`, `phone`) 
                VALUES ('$name', '$location', '$phone')";

        $result = $conn->query($sql);

        if ($result) {
            header("Location: admin.php?msg=success");
            exit();
        } else {
            header("Location: admin.php?msg=error");
            exit();
        }

    } else {
        header("Location: admin.php?msg=empty_fields");
        exit();
    }
}
?>

