<?php
include("db.php");
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: getstarted.html?msg=not_logged_in");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT `id`, `name`, `phone`, `email`, `role` FROM `user` WHERE `id` = '$user_id' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $user_id = $user["id"];
    $user_name = $user["name"];
    $user_email = $user["email"];
    $user_phone = $user["phone"];
    $user_role = $user["role"];

} else {

    header("Location: login.html?msg=user_not_found");
    exit();
}
?>
