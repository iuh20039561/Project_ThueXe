<?php 
session_start();
$page_title = "Đặt xe thành công";

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if(!$booking_id) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php'; 
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                                 style="width: 120px; height: 120px; background: var(--gradient-success);">
                                <i class="fas fa-check fa-4x text-white"></i>
                            </div>
                        </div>
                        
                        <h2 class="fw-bold mb-3" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Đặt Xe Thành Công!
                        </h2>
                        
                        <p class="text-muted mb-4">
                            Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.<br>
                            Mã đơn đặt xe của bạn là: <strong class="text-primary">#<?php echo str_pad($booking_id, 6, '0', STR_PAD_LEFT); ?></strong>
                        </p>
                        
                        <div class="alert alert-info text-start">
                            <h5><i class="fas fa-info-circle me-2"></i>Thông tin quan trọng:</h5>
                            <ul class="mb-0">
                                <li>Chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ để xác nhận đơn đặt xe</li>
                                <li>Vui lòng kiểm tra email và điện thoại thường xuyên</li>
                                <li>Chuẩn bị CMND/CCCD và bằng lái xe hợp lệ</li>
                                <li>Đọc kỹ hợp đồng trước khi ký</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="index.php" class="btn btn-gradient btn-lg">
                                <i class="fas fa-home me-2"></i>Về trang chủ
                            </a>
                            <a href="search.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Tìm xe khác
                            </a>
                        </div>
                        
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="mb-3">Cần hỗ trợ?</h5>
                            <p class="text-muted mb-3">Liên hệ với chúng tôi qua:</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="tel:0123456789" class="btn btn-light">
                                    <i class="fas fa-phone me-2"></i>0123 456 789
                                </a>
                                <a href="mailto:contact@carrental.com" class="btn btn-light">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </a>
                                <a href="https://zalo.me/0123456789" class="btn btn-light" target="_blank">
                                    <i class="fas fa-comment-dots me-2"></i>Zalo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>