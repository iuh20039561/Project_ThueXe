<?php
$page_title = "Quản lý xe";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Xóa xe
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM cars WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
        $_SESSION['success'] = "Xóa xe thành công!";
    }
    header("Location: cars.php");
    exit();
}

// Lấy danh sách xe
$sql = "SELECT * FROM cars ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-car me-2"></i>Quản Lý Xe</h2>
            <p class="text-muted mb-0">Quản lý toàn bộ xe cho thuê</p>
        </div>
        <a href="car-add.php" class="btn btn-gradient">
            <i class="fas fa-plus me-2"></i>Thêm xe mới
        </a>
    </div>
</div>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Cars Table -->
<div class="data-table">
    <div class="table-header">
        <h5 class="mb-0">Danh sách xe (<?php echo count($cars); ?>)</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Thông tin xe</th>
                    <th>Thông số</th>
                    <th>Giá/Ngày</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($cars) > 0): ?>
                    <?php foreach($cars as $car): ?>
                    <tr>
                        <td><strong>#<?php echo $car['id']; ?></strong></td>
                        <td>
                            <img src="../assets/images/cars/<?php echo $car['main_image']; ?>" 
                                 class="img-thumbnail" 
                                 style="width: 80px; height: 60px; object-fit: cover;"
                                 onerror="this.src='../assets/images/cars/default.svg'">
                        </td>
                        <td>
                            <strong><?php echo $car['name']; ?></strong><br>
                            <small class="text-muted"><?php echo $car['brand'] . ' ' . $car['model'] . ' ' . $car['year']; ?></small>
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-users me-1"></i><?php echo $car['seats']; ?> chỗ<br>
                                <i class="fas fa-cog me-1"></i><?php echo $car['transmission']; ?><br>
                                <i class="fas fa-gas-pump me-1"></i><?php echo $car['fuel_type']; ?>
                            </small>
                        </td>
                        <td><strong class="text-primary"><?php echo number_format($car['price_per_day']); ?>đ</strong></td>
                        <td>
                            <?php
                            $status_class = [
                                'available' => 'badge-available',
                                'rented' => 'badge-rented',
                                'maintenance' => 'badge-maintenance'
                            ];
                            $status_text = [
                                'available' => 'Có sẵn',
                                'rented' => 'Đang thuê',
                                'maintenance' => 'Bảo trì'
                            ];
                            ?>
                            <span class="badge-status <?php echo $status_class[$car['status']]; ?>">
                                <?php echo $status_text[$car['status']]; ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="car-edit.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-gradient">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $car['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bạn có chắc muốn xóa xe này?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có xe nào</p>
                            <a href="car-add.php" class="btn btn-gradient">
                                <i class="fas fa-plus me-2"></i>Thêm xe mới
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>