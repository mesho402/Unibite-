<?php
session_start();
include("db.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?msg=not_logged_in");
    exit();
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // تحقق إن الحقول مو فاضية
    if (empty($name) || empty($email) || empty($phone)) {
        header("Location: profile.php?msg=empty_fields");
        exit();
    }

    $sql = "UPDATE `user` SET `name` = ?, `email` = ?, `phone` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

    if ($stmt->execute()) {

        header("Location: profile.php?msg=update_success");
    } else {

        header("Location: profile.php?msg=update_failed");
    }

    $stmt->close();
} else {
    header("Location: profile.php?msg=invalid_request");
    exit();
}
?>
