<?php 
$page_title = "Tìm kiếm xe";
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Lấy tham số tìm kiếm
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$seats = isset($_GET['seats']) ? intval($_GET['seats']) : 0;
$price_range = isset($_GET['price']) ? $_GET['price'] : '';

// Build query
$sql = "SELECT * FROM cars WHERE status = 'available'";
$params = [];

if($brand) {
    $sql .= " AND brand = :brand";
    $params[':brand'] = $brand;
}

if($seats > 0) {
    $sql .= " AND seats = :seats";
    $params[':seats'] = $seats;
}

if($price_range) {
    if($price_range == '2000000') {
        $sql .= " AND price_per_day >= 2000000";
    } else {
        list($min, $max) = explode('-', $price_range);
        $sql .= " AND price_per_day BETWEEN :min_price AND :max_price";
        $params[':min_price'] = $min;
        $params[':max_price'] = $max;
    }
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
foreach($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php'; 
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="page-header mb-4">
            <h2><i class="fas fa-search me-2"></i>Tìm Kiếm Xe</h2>
            <p class="text-muted">Tìm thấy <?php echo count($cars); ?> kết quả</p>
        </div>
        
        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Hãng xe</label>
                            <select class="form-select" name="brand">
                                <option value="">Tất cả</option>
                                <option value="Toyota" <?php echo $brand == 'Toyota' ? 'selected' : ''; ?>>Toyota</option>
                                <option value="Honda" <?php echo $brand == 'Honda' ? 'selected' : ''; ?>>Honda</option>
                                <option value="Mazda" <?php echo $brand == 'Mazda' ? 'selected' : ''; ?>>Mazda</option>
                                <option value="Ford" <?php echo $brand == 'Ford' ? 'selected' : ''; ?>>Ford</option>
                                <option value="Hyundai" <?php echo $brand == 'Hyundai' ? 'selected' : ''; ?>>Hyundai</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Số chỗ ngồi</label>
                            <select class="form-select" name="seats">
                                <option value="">Tất cả</option>
                                <option value="4" <?php echo $seats == 4 ? 'selected' : ''; ?>>4 chỗ</option>
                                <option value="5" <?php echo $seats == 5 ? 'selected' : ''; ?>>5 chỗ</option>
                                <option value="7" <?php echo $seats == 7 ? 'selected' : ''; ?>>7 chỗ</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Giá thuê/ngày</label>
                            <select class="form-select" name="price">
                                <option value="">Tất cả</option>
                                <option value="0-500000" <?php echo $price_range == '0-500000' ? 'selected' : ''; ?>>Dưới 500k</option>
                                <option value="500000-1000000" <?php echo $price_range == '500000-1000000' ? 'selected' : ''; ?>>500k - 1tr</option>
                                <option value="1000000-2000000" <?php echo $price_range == '1000000-2000000' ? 'selected' : ''; ?>>1tr - 2tr</option>
                                <option value="2000000" <?php echo $price_range == '2000000' ? 'selected' : ''; ?>>Trên 2tr</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label opacity-0">Action</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gradient flex-fill">
                                    <i class="fas fa-search me-2"></i>Tìm
                                </button>
                                <a href="search.php" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Results -->
        <div class="row g-4">
            <?php if(count($cars) > 0): ?>
                <?php foreach($cars as $car): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card car-card">
                        <div class="position-relative">
                            <img src="assets/images/cars/<?php echo $car['main_image']; ?>" 
                                 class="card-img-top car-card-img" 
                                 alt="<?php echo $car['name']; ?>"
                                 onerror="this.src='assets/images/cars/default.jpg'">
                            <span class="badge badge-status badge-available">Có sẵn</span>
                        </div>
                        <div class="card-body car-card-body">
                            <h5 class="card-title fw-bold"><?php echo $car['name']; ?></h5>
                            <p class="text-muted mb-3"><?php echo $car['brand'] . ' ' . $car['model'] . ' ' . $car['year']; ?></p>
                            
                            <div class="car-features">
                                <div class="car-feature-item">
                                    <i class="fas fa-users text-primary"></i>
                                    <span><?php echo $car['seats']; ?> chỗ</span>
                                </div>
                                <div class="car-feature-item">
                                    <i class="fas fa-cog text-primary"></i>
                                    <span><?php echo $car['transmission']; ?></span>
                                </div>
                                <div class="car-feature-item">
                                    <i class="fas fa-gas-pump text-primary"></i>
                                    <span><?php echo $car['fuel_type']; ?></span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="car-price">
                                    <?php echo number_format($car['price_per_day']); ?>đ/ngày
                                </span>
                                <a href="car-detail.php?id=<?php echo $car['id']; ?>" class="btn btn-gradient-secondary btn-sm">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-car fa-4x text-muted mb-3"></i>
                            <h5>Không tìm thấy xe phù hợp</h5>
                            <p class="text-muted">Vui lòng thử lại với bộ lọc khác</p>
                            <a href="search.php" class="btn btn-gradient">Xóa bộ lọc</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>