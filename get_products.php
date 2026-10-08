<?php
include("../db.php");

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

    $products[$row['type']][] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'price' => $row['price'],
      'img' => $row['image'],
      'desc' => $row['ingredients'], // نرجعها باسم desc عشان تمشي مع js
      'rating' => 5 
    ];
  }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
?>
