<?php 
include("get_user_info.php");

if(isset($_GET["id"])){
    $id = $_GET["id"];
    $sql = "INSERT INTO `favourite`(`user_id`, `menu_id`) VALUES ($user_id,$id)";
    $result = $conn->query($sql);
    if($result){
        echo '<script>window.history.back();</script>';
    }

}