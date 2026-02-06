<?php 
$page_title = "Liên hệ";
include 'includes/header.php'; 
?>

<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center hero-content">
                <h1>Liên Hệ Với Chúng Tôi</h1>
                <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-primary);">
                                <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Địa Chỉ</h5>
                        <p class="text-muted">123 Đường ABC, Quận 1,<br>TP. Hồ Chí Minh</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-secondary);">
                                <i class="fas fa-phone fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Điện Thoại</h5>
                        <p class="text-muted mb-2">Hotline: <a href="tel:0123456789">0123 456 789</a></p>
                        <p class="text-muted">Zalo: <a href="https://zalo.me/0123456789" target="_blank">0123 456 789</a></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-success);">
                                <i class="fas fa-envelope fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Email</h5>
                        <p class="text-muted mb-2"><a href="mailto:contact@carrental.com">contact@carrental.com</a></p>
                        <p class="text-muted"><a href="mailto:support@carrental.com">support@carrental.com</a></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Gửi Tin Nhắn</h4>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-gradient w-100">
                                <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4326739819487!2d106.70253857577914!3d10.775983489374252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4b3330bcc9%3A0x5a8e3d5533bb48b3!2sBến Thành Market!5e0!3m2!1sen!2s!4v1704964289542!5m2!1sen!2s" 
                                width="100%" 
                                height="450" 
                                style="border:0; border-radius: 15px;" 
                                allowfullscreen="" 
                                loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>