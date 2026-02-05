<?php
$page_title = "Tổng quan";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Thống kê tổng quan
$sql_total_bookings = "SELECT COUNT(*) as total FROM bookings";
$stmt = $conn->prepare($sql_total_bookings);
$stmt->execute();
$total_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql_total_cars = "SELECT COUNT(*) as total FROM cars";
$stmt = $conn->prepare($sql_total_cars);
$stmt->execute();
$total_cars = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql_pending_bookings = "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'";
$stmt = $conn->prepare($sql_pending_bookings);
$stmt->execute();
$pending_bookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$sql_total_revenue = "SELECT SUM(total_price) as total FROM bookings WHERE status = 'completed'";
$stmt = $conn->prepare($sql_total_revenue);
$stmt->execute();
$total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Đơn đặt xe gần đây
$sql_recent = "SELECT b.*, c.name as car_name FROM bookings b 
               LEFT JOIN cars c ON b.car_id = c.id 
               ORDER BY b.created_at DESC LIMIT 10";
$stmt = $conn->prepare($sql_recent);
$stmt->execute();
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="page-header">
    <h2><i class="fas fa-home me-2"></i>Tổng Quan</h2>
    <p class="text-muted">Thống kê và dữ liệu tổng quan hệ thống</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="stats-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-label">Tổng đơn đặt</div>
                        <div class="stats-value"><?php echo $total_bookings; ?></div>
                    </div>
                    <div class="stats-icon primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="stats-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-label">Tổng số xe</div>
                        <div class="stats-value"><?php echo $total_cars; ?></div>
                    </div>
                    <div class="stats-icon success">
                        <i class="fas fa-car"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="stats-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-label">Đơn chờ duyệt</div>
                        <div class="stats-value"><?php echo $pending_bookings; ?></div>
                    </div>
                    <div class="stats-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="stats-card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-label">Doanh thu</div>
                        <div class="stats-value"><?php echo number_format($total_revenue/1000000, 1); ?>M</div>
                    </div>
                    <div class="stats-icon danger">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-container">
            <h5 class="mb-3">Doanh thu theo tháng</h5>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="chart-container">
            <h5 class="mb-3">Trạng thái đơn</h5>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="data-table">
    <div class="table-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Đơn đặt xe gần đây</h5>
        <a href="bookings.php" class="btn btn-gradient btn-sm">Xem tất cả</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Xe</th>
                    <th>Ngày thuê</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_bookings as $booking): ?>
                <tr>
                    <td>#<?php echo $booking['id']; ?></td>
                    <td>
                        <strong><?php echo $booking['customer_name']; ?></strong><br>
                        <small class="text-muted"><?php echo $booking['customer_phone']; ?></small>
                    </td>
                    <td><?php echo $booking['car_name']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($booking['pickup_date'])); ?></td>
                    <td><strong><?php echo number_format($booking['total_price']); ?>đ</strong></td>
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
                    <td>
                        <a href="booking-detail.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-gradient">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
            label: 'Doanh thu (triệu đồng)',
            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Chờ duyệt', 'Đã duyệt', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [<?php echo $pending_bookings; ?>, 15, 25, 5],
            backgroundColor: [
                '#ffc107',
                '#28a745',
                '#17a2b8',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>