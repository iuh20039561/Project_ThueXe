<?php 
$page_title = "Trang chủ";
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Lấy danh sách xe có sẵn
$sql = "SELECT * FROM cars WHERE status = 'available' ORDER BY created_at DESC LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách dịch vụ
$sql_services = "SELECT * FROM services WHERE status = 1 LIMIT 4";
$stmt_services = $conn->prepare($sql_services);
$stmt_services->execute();
$services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1>Thuê Xe Dễ Dàng, Giá Cả Hợp Lý</h1>
                <p>Hơn 100+ dòng xe cao cấp, hiện đại. Thủ tục nhanh chóng, giao xe tận nơi 24/7</p>
                <a href="#cars" class="btn btn-gradient btn-lg">
                    <i class="fas fa-car me-2"></i>Xem xe ngay
                </a>
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="search-box">
                    <form action="search.php" method="GET" id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-bold">Loại xe</label>
                                <select class="form-select" name="brand">
                                    <option value="">Tất cả</option>
                                    <option value="Toyota">Toyota</option>
                                    <option value="Honda">Honda</option>
                                    <option value="Mazda">Mazda</option>
                                    <option value="Ford">Ford</option>
                                    <option value="Hyundai">Hyundai</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-bold">Số chỗ</label>
                                <select class="form-select" name="seats">
                                    <option value="">Tất cả</option>
                                    <option value="4">4 chỗ</option>
                                    <option value="5">5 chỗ</option>
                                    <option value="7">7 chỗ</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-dark fw-bold">Giá thuê/ngày</label>
                                <select class="form-select" name="price">
                                    <option value="">Tất cả</option>
                                    <option value="0-500000">Dưới 500k</option>
                                    <option value="500000-1000000">500k - 1tr</option>
                                    <option value="1000000-2000000">1tr - 2tr</option>
                                    <option value="2000000">Trên 2tr</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label opacity-0">Search</label>
                                <button type="submit" class="btn btn-gradient w-100">
                                    <i class="fas fa-search me-2"></i>Tìm xe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Xe nổi bật -->
<section class="py-5" id="cars">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title d-inline-block">
                <h2>Xe Nổi Bật</h2>
            </div>
            <p class="text-muted">Những dòng xe được yêu thích nhất</p>
        </div>
        
        <div class="row g-4">
            <?php foreach($cars as $car): ?>
            <div class="col-lg-4 col-md-6 fade-in-up">
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
        </div>
        
        <div class="text-center mt-5">
            <a href="search.php" class="btn btn-gradient btn-lg">
                Xem tất cả xe <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Dịch vụ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title d-inline-block">
                <h2>Dịch Vụ Của Chúng Tôi</h2>
            </div>
            <p class="text-muted">Cam kết mang đến trải nghiệm tốt nhất</p>
        </div>
        
        <div class="row g-4">
            <?php 
            $icons = ['fa-shield-alt', 'fa-user-tie', 'fa-shipping-fast', 'fa-baby'];
            $gradients = ['primary', 'secondary', 'success', 'orange'];
            foreach($services as $index => $service): 
            ?>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="card service-card text-center">
                    <div class="service-icon" style="background: var(--gradient-<?php echo $gradients[$index % 4]; ?>);">
                        <i class="fas <?php echo $icons[$index]; ?>"></i>
                    </div>
                    <h5 class="fw-bold mb-3"><?php echo $service['name']; ?></h5>
                    <p class="text-muted"><?php echo $service['description']; ?></p>
                    <p class="fw-bold text-primary"><?php echo number_format($service['price']); ?>đ</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Tại sao chọn chúng tôi -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="section-title">
                    <h2>Tại Sao Chọn Chúng Tôi?</h2>
                </div>
                <p class="text-muted mb-4">CarRental cam kết mang đến dịch vụ cho thuê xe chất lượng cao nhất với nhiều ưu điểm vượt trội</p>
                
                <div class="info-box mb-3">
                    <h5><i class="fas fa-check-circle text-success me-2"></i>Đa dạng dòng xe</h5>
                    <p class="mb-0 text-muted">Hơn 100+ mẫu xe từ các hãng uy tín: Toyota, Honda, Mazda, Ford...</p>
                </div>
                
                <div class="info-box mb-3">
                    <h5><i class="fas fa-check-circle text-success me-2"></i>Giá cả hợp lý</h5>
                    <p class="mb-0 text-muted">Giá thuê cạnh tranh, nhiều ưu đãi cho khách hàng thân thiết</p>
                </div>
                
                <div class="info-box mb-3">
                    <h5><i class="fas fa-check-circle text-success me-2"></i>Thủ tục đơn giản</h5>
                    <p class="mb-0 text-muted">Đặt xe online nhanh chóng, không cần thế chấp phức tạp</p>
                </div>
                
                <div class="info-box">
                    <h5><i class="fas fa-check-circle text-success me-2"></i>Hỗ trợ 24/7</h5>
                    <p class="mb-0 text-muted">Đội ngũ tư vấn nhiệt tình, sẵn sàng hỗ trợ mọi lúc</p>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="assets/images/why-choose-us.jpg" class="img-fluid rounded-3 shadow" alt="Why Choose Us" onerror="this.src='https://via.placeholder.com/600x400?text=Why+Choose+Us'">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title d-inline-block">
                <h2>Khách Hàng Nói Gì</h2>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="text-muted">"Dịch vụ tuyệt vời! Xe sạch sẽ, thủ tục nhanh gọn. Tôi rất hài lòng và sẽ quay lại."</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-gradient text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-primary);">
                                <strong>NV</strong>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Nguyễn Văn A</h6>
                                <small class="text-muted">Khách hàng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="text-muted">"Giá cả hợp lý, xe đẹp, mới. Nhân viên tư vấn nhiệt tình. Highly recommended!"</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-gradient text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-secondary);">
                                <strong>TT</strong>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Trần Thị B</h6>
                                <small class="text-muted">Khách hàng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="text-muted">"Đã thuê xe nhiều lần, lần nào cũng hài lòng. Dịch vụ chuyên nghiệp số 1!"</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-gradient text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-success);">
                                <strong>LH</strong>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Lê Hoàng C</h6>
                                <small class="text-muted">Khách hàng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>