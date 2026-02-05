<?php
$page_title = "Quản lý đơn";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Lọc theo trạng thái
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT b.*, c.name as car_name, c.brand, c.model FROM bookings b 
        LEFT JOIN cars c ON b.car_id = c.id";

if($status_filter) {
    $sql .= " WHERE b.status = :status";
}

$sql .= " ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
if($status_filter) {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-calendar-check me-2"></i>Quản Lý Đơn Đặt Xe</h2>
            <p class="text-muted mb-0">Quản lý tất cả đơn đặt xe</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Lọc theo trạng thái</label>
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Chờ duyệt</option>
                    <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Đã duyệt</option>
                    <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                    <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <a href="bookings.php" class="btn btn-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

<!-- Bookings Table -->
<div class="data-table">
    <div class="table-header">
        <h5 class="mb-0">Danh sách đơn đặt xe (<?php echo count($bookings); ?>)</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Xe</th>
                    <th>Ngày thuê</th>
                    <th>Số ngày</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($bookings) > 0): ?>
                    <?php foreach($bookings as $booking): ?>
                    <tr>
                        <td><strong>#<?php echo $booking['id']; ?></strong></td>
                        <td>
                            <strong><?php echo $booking['customer_name']; ?></strong><br>
                            <small class="text-muted">
                                <i class="fas fa-phone me-1"></i><?php echo $booking['customer_phone']; ?>
                            </small><br>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i><?php echo $booking['customer_email']; ?>
                            </small>
                        </td>
                        <td>
                            <strong><?php echo $booking['car_name']; ?></strong><br>
                            <small class="text-muted"><?php echo $booking['brand'] . ' ' . $booking['model']; ?></small>
                        </td>
                        <td>
                            <strong><?php echo date('d/m/Y', strtotime($booking['pickup_date'])); ?></strong> đến<br>
                            <strong><?php echo date('d/m/Y', strtotime($booking['return_date'])); ?></strong>
                        </td>
                        <td><?php echo $booking['total_days']; ?> ngày</td>
                        <td><strong class="text-primary"><?php echo number_format($booking['total_price']); ?>đ</strong></td>
                        <td>
                            <?php
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
                            <span class="badge-status <?php echo $status_class[$booking['status']]; ?>">
                                <?php echo $status_text[$booking['status']]; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-gradient" onclick="viewBooking(<?php echo $booking['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if($booking['status'] == 'pending'): ?>
                                <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="updateStatus(<?php echo $booking['id']; ?>, 'cancelled')">
                                    <i class="fas fa-times"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đơn đặt xe nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn đặt xe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewBooking(id) {
    const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
    modal.show();
    
    fetch('ajax/get-booking-detail.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById('bookingDetailContent').innerHTML = data;
        });
}

function updateStatus(id, status) {
    const statusText = {
        'confirmed': 'xác nhận',
        'cancelled': 'hủy'
    };
    
    if(confirm(`Bạn có chắc muốn ${statusText[status]} đơn này?`)) {
        fetch('ajax/update-booking-status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Cập nhật thành công!');
                location.reload();
            } else {
                alert('Có lỗi xảy ra!');
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>