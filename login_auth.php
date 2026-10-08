<?php
include("db.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {


        $sql = "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();


            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];


            if ($user['role'] == 'admin') {
                header("Location: admin.php");
                exit();
            }
            elseif ($user['role'] == 'customer') {
                header("Location: index.php");
                exit();
            }
            else {
                header("Location: staff.php");
                exit();
            }

        } else {
            // بيانات خاطئة
            header("Location: login.html?msg=invalid_credentials");
            exit();
        }

    } else {
        header("Location: login.html?msg=empty_fields");
        exit();
    }

} else {
    header("Location: login.html");
    exit();
}
?>
