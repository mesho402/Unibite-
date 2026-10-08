<?php
include 'get_user_info.php'; // تأكد إن الملف ده فيه الاتصال بقاعدة البيانات

// استقبل البيانات
$product_id = $_POST['product_id'];
$stars = $_POST['stars'];
$comment = $_POST['comment'];

// تحقق إن البيانات موجودة
if (!$stars || !$comment) {
    die("Missing fields");
}

// أدخل البيانات
$stmt = $conn->prepare("INSERT INTO rating (user_id, product_id, stars, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $user_id, $product_id, $stars, $comment);

if ($stmt->execute()) {
    echo "<script>
        alert('Review submitted successfully!');
        window.location.href='product.php?id=$product_id';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}
