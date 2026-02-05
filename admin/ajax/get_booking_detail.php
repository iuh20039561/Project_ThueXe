<?php
session_start();
require_once '../../config/database.php';

if(!isset($_SESSION['admin_logged_in'])) {
    echo '<div class="alert alert-danger">Unauthorized</div>';
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT b.*, c.name as car_name, c.brand, c.model, c.year, c.main_image 
        FROM bookings b 
        LEFT JOIN cars c ON b.car_id = c.id 
        WHERE b.id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$booking) {
    echo '<div class="alert alert-danger">Không tìm thấy đơn đặt xe</div>';
    exit();
}

$status_class = [
    'pending' => 'badge-pending',
    'confirmed' => 'badge-confirmed',
    'cancelled' => 'badge-cancelled',
    'completed' => 'badge-completed'
];
$status_text = [
    'pending' => 'Chờ duyệt',
    'confirmed' => 'Đã duyệt',
    'cancelled' => 'Đã hủy',
    'completed' => 'Hoàn thành'
];
?>

<div class="row">
    <div class="col-md-6">
        <h5 class="mb-3">Thông tin khách hàng</h5>
        <table class="table table-borderless">
            <tr>
                <td class="fw-bold">Họ tên:</td>
                <td><?php echo $booking['customer_name']; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Email:</td>
                <td><?php echo $booking['customer_email']; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Số điện thoại:</td>
                <td><?php echo $booking['customer_phone']; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">CMND/CCCD:</td>
                <td><?php echo $booking['id_number'] ?: 'Chưa cung cấp'; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Địa chỉ:</td>
                <td><?php echo $booking['customer_address']; ?></td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h5 class="mb-3">Thông tin xe</h5>
        <div class="text-center mb-3">
            <img src="../assets/images/cars/<?php echo $booking['main_image']; ?>" 
                 class="img-fluid rounded" 
                 style="max-height: 150px;"
                 onerror="this.src='../assets/images/cars/default.jpg'">
        </div>
        <table class="table table-borderless">
            <tr>
                <td class="fw-bold">Tên xe:</td>
                <td><?php echo $booking['car_name']; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Hãng:</td>
                <td><?php echo $booking['brand'] . ' ' . $booking['model'] . ' ' . $booking['year']; ?></td>
            </tr>
        </table>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <h5 class="mb-3">Thông tin thuê xe</h5>
        <table class="table table-borderless">
            <tr>
                <td class="fw-bold" style="width: 200px;">Ngày nhận xe:</td>
                <td><?php echo date('d/m/Y', strtotime($booking['pickup_date'])); ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Ngày trả xe:</td>
                <td><?php echo date('d/m/Y', strtotime($booking['return_date'])); ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Số ngày thuê:</td>
                <td><?php echo $booking['total_days']; ?> ngày</td>
            </tr>
            <tr>
                <td class="fw-bold">Địa điểm nhận xe:</td>
                <td><?php echo $booking['pickup_location'] ?: 'Tại cửa hàng'; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Ghi chú:</td>
                <td><?php echo $booking['notes'] ?: 'Không có'; ?></td>
            </tr>
            <tr>
                <td class="fw-bold">Tổng tiền:</td>
                <td><h4 class="text-primary mb-0"><?php echo number_format($booking['total_price']); ?>đ</h4></td>
            </tr>
            <tr>
                <td class="fw-bold">Trạng thái:</td>
                <td>
                    <span class="badge-status <?php echo $status_class[$booking['status']]; ?>">
                        <?php echo $status_text[$booking['status']]; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Ngày đặt:</td>
                <td><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></td>
            </tr>
        </table>
    </div>
</div>