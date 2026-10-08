<?php
include("db.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = 'customer';

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($password)) {


        $check = "SELECT * FROM `user` WHERE `email` = '$email'";
        $result = $conn->query($check);

        if ($result && $result->num_rows > 0) {
            header("Location: register.html?msg=email_exists");
            exit();
        }


        $sql = "INSERT INTO `user` (`name`, `phone`, `email`, `password`, `role`) 
                VALUES ('$name', '$phone', '$email', '$password', '$role')";
        $auth = $conn->query($sql);
        
        if ($auth) {

            $user_id = $conn->insert_id;


            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            header("Location: index.php?msg=success");
            exit();
        } else {
            header("Location: register.html?msg=error");
            exit();
        }

    } else {
        header("Location: register.html?msg=empty_fields");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>
