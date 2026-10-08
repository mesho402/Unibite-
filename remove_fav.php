<?php 
include("get_user_info.php");

if(isset($_GET["id"])){
    $id = $_GET["id"];
    $sql = "DELETE FROM `favourite` WHERE `menu_id` = $id AND `user_id` = $user_id ";
    $result = $conn->query($sql);
    if($result){
        echo '<script>window.history.back();</script>';
    }

}