<?php 
$page_title = "Dịch vụ";
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM services WHERE status = 1 ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php'; 
?>

<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center hero-content">
                <h1>Dịch Vụ Của Chúng Tôi</h1>
                <p>Đa dạng dịch vụ để phục vụ mọi nhu cầu của bạn</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach($services as $service): ?>
            <div class="col-lg-6">
                <div class="card service-card">
                    <div class="card-body p-4">
                        <div class="d-flex">
                            <div class="service-icon me-4" style="flex-shrink: 0;">
                                <i class="fas fa-<?php echo $service['icon'] ?? 'star'; ?>"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-3"><?php echo $service['name']; ?></h4>
                                <p class="text-muted mb-3"><?php echo $service['description']; ?></p>
                                <h5 class="text-primary fw-bold"><?php echo number_format($service['price']); ?>đ</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>