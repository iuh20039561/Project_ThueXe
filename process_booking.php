<?php
session_start();
require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Lấy dữ liệu từ form
    $car_id = intval($_POST['car_id']);
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $customer_phone = trim($_POST['customer_phone']);
    $customer_address = trim($_POST['customer_address']);
    $id_number = trim($_POST['id_number']);
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];
    $pickup_location = trim($_POST['pickup_location']);
    $notes = trim($_POST['notes']);
    $price_per_day = floatval($_POST['price_per_day']);
    
    // Tính số ngày và tổng tiền
    $pickup = new DateTime($pickup_date);
    $return = new DateTime($return_date);
    $interval = $pickup->diff($return);
    $total_days = $interval->days;
    $total_price = $total_days * $price_per_day;
    
    // Validate
    if($total_days <= 0) {
        $_SESSION['error'] = "Ngày trả xe phải sau ngày nhận xe!";
        header("Location: car-detail.php?id=" . $car_id);
        exit();
    }
    
    try {
        // Insert booking
        $sql = "INSERT INTO bookings (car_id, customer_name, customer_email, customer_phone, 
                customer_address, id_number, pickup_date, return_date, pickup_location, 
                notes, total_days, total_price, status) 
                VALUES (:car_id, :customer_name, :customer_email, :customer_phone, 
                :customer_address, :id_number, :pickup_date, :return_date, :pickup_location, 
                :notes, :total_days, :total_price, 'pending')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':car_id', $car_id);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);
        $stmt->bindParam(':customer_phone', $customer_phone);
        $stmt->bindParam(':customer_address', $customer_address);
        $stmt->bindParam(':id_number', $id_number);
        $stmt->bindParam(':pickup_date', $pickup_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':pickup_location', $pickup_location);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':total_days', $total_days);
        $stmt->bindParam(':total_price', $total_price);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Đặt xe thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.";
            header("Location: booking-success.php?booking_id=" . $conn->lastInsertId());
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại!";
            header("Location: car-detail.php?id=" . $car_id);
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        header("Location: car-detail.php?id=" . $car_id);
    }
} else {
    header("Location: index.php");
}
exit();
?>