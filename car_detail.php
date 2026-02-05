<?php 
$page_title = "Chi tiết xe";
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Lấy ID xe từ URL
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin xe
$sql = "SELECT * FROM cars WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $car_id);
$stmt->execute();
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$car) {
    header("Location: index.php");
    exit();
}

// Lấy hình ảnh của xe
$sql_images = "SELECT * FROM car_images WHERE car_id = :car_id ORDER BY is_main DESC";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bindParam(':car_id', $car_id);
$stmt_images->execute();
$car_images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

// Parse features
$features = explode(',', $car['features']);

include 'includes/header.php'; 
?>

<section class="py-5 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="search.php">Danh sách xe</a></li>
                <li class="breadcrumb-item active"><?php echo $car['name']; ?></li>
            </ol>
        </nav>
        
        <div class="row">
            <!-- Gallery -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <!-- Main Image -->
                        <img src="assets/images/cars/<?php echo $car['main_image']; ?>" 
                             class="car-detail-img w-100" 
                             id="mainImage"
                             alt="<?php echo $car['name']; ?>"
                             onerror="this.src='assets/images/cars/default.jpg'">
                        
                        <!-- Thumbnails -->
                        <?php if(count($car_images) > 0): ?>
                        <div class="row g-2 mt-3">
                            <?php foreach($car_images as $image): ?>
                            <div class="col-3">
                                <img src="assets/images/cars/<?php echo $image['image_path']; ?>" 
                                     class="car-gallery-thumb w-100 <?php echo $image['is_main'] ? 'active' : ''; ?>"
                                     onclick="changeMainImage(this.src)"
                                     alt="Car Image"
                                     onerror="this.src='assets/images/cars/default.jpg'">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Mô tả chi tiết</h4>
                        <p class="text-muted"><?php echo nl2br($car['description']); ?></p>
                        
                        <h5 class="fw-bold mt-4 mb-3">Tính năng nổi bật</h5>
                        <div class="row">
                            <?php foreach($features as $feature): ?>
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i><?php echo trim($feature); ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header text-white text-center py-3" style="background: var(--gradient-primary); border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0"><?php echo $car['name']; ?></h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Car Info -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-car me-2"></i>Hãng xe:</span>
                                <strong><?php echo $car['brand']; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-cog me-2"></i>Dòng xe:</span>
                                <strong><?php echo $car['model']; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-calendar me-2"></i>Năm sản xuất:</span>
                                <strong><?php echo $car['year']; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-users me-2"></i>Số chỗ:</span>
                                <strong><?php echo $car['seats']; ?> chỗ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-cog me-2"></i>Hộp số:</span>
                                <strong><?php echo $car['transmission']; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"><i class="fas fa-gas-pump me-2"></i>Nhiên liệu:</span>
                                <strong><?php echo $car['fuel_type']; ?></strong>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Price -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold" style="color: #667eea;">
                                <?php echo number_format($car['price_per_day']); ?>đ
                            </h3>
                            <span class="text-muted">/ ngày</span>
                        </div>
                        
                        <!-- Status -->
                        <?php if($car['status'] == 'available'): ?>
                        <div class="alert alert-gradient-success text-center mb-3">
                            <i class="fas fa-check-circle me-2"></i>Xe đang có sẵn
                        </div>
                        
                        <!-- Booking Button -->
                        <button class="btn btn-gradient w-100 mb-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="fas fa-calendar-check me-2"></i>Đặt thuê xe
                        </button>
                        <?php else: ?>
                        <div class="alert alert-gradient-danger text-center mb-3">
                            <i class="fas fa-times-circle me-2"></i>Xe đang được thuê
                        </div>
                        <button class="btn btn-secondary w-100 mb-2" disabled>
                            Xe không có sẵn
                        </button>
                        <?php endif; ?>
                        
                        <a href="tel:0123456789" class="btn btn-outline-primary w-100">
                            <i class="fas fa-phone me-2"></i>Gọi tư vấn: 0123 456 789
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đặt thuê xe: <?php echo $car['name']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm" action="process-booking.php" method="POST">
                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                    <input type="hidden" name="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="customer_phone" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="customer_email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">CMND/CCCD</label>
                            <input type="text" class="form-control" name="id_number">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="customer_address" rows="2" required></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Ngày nhận xe <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="pickup_date" id="pickupDate" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Ngày trả xe <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="return_date" id="returnDate" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Địa điểm nhận xe</label>
                            <input type="text" class="form-control" name="pickup_location" placeholder="VD: 123 Đường ABC, Quận 1">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Yêu cầu đặc biệt..."></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Số ngày thuê:</strong> <span id="totalDays">0</span> ngày<br>
                                <strong>Tổng tiền dự kiến:</strong> <span id="totalPrice">0</span>đ
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu đặt xe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Change main image
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.car-gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.target.classList.add('active');
}

// Calculate total price
const pricePerDay = <?php echo $car['price_per_day']; ?>;
const pickupDateInput = document.getElementById('pickupDate');
const returnDateInput = document.getElementById('returnDate');

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
pickupDateInput.min = today;
returnDateInput.min = today;

function calculateTotal() {
    const pickupDate = new Date(pickupDateInput.value);
    const returnDate = new Date(returnDateInput.value);
    
    if(pickupDate && returnDate && returnDate > pickupDate) {
        const diffTime = Math.abs(returnDate - pickupDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const totalPrice = diffDays * pricePerDay;
        
        document.getElementById('totalDays').textContent = diffDays;
        document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('vi-VN');
    }
}

pickupDateInput.addEventListener('change', function() {
    returnDateInput.min = this.value;
    calculateTotal();
});

returnDateInput.addEventListener('change', calculateTotal);

// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const pickupDate = new Date(pickupDateInput.value);
    const returnDate = new Date(returnDateInput.value);
    
    if(returnDate <= pickupDate) {
        e.preventDefault();
        alert('Ngày trả xe phải sau ngày nhận xe!');
        return false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>