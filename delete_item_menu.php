<?php
include("db.php");
session_start();

// تأكد إن المستخدم داخل
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=not_logged_in");
    exit();
}

// تأكد إن فيه id جاي في الرابط
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // كود الحذف
    $sql = "DELETE FROM `menu` WHERE `id` = '$id'";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: staff.php?msg=deleted");
        exit();
    } else {
        header("Location: staff.php?msg=error");
        exit();
    }

} else {
    header("Location: staff.php?msg=no_id");
    exit();
}
?>
