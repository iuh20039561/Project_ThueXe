<?php 
$page_title = "Hướng dẫn";
include 'includes/header.php'; 
?>

<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center hero-content">
                <h1>Hướng Dẫn Thuê Xe</h1>
                <p>Quy trình đơn giản, nhanh chóng</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-primary);">
                                <span class="text-white fs-2 fw-bold">1</span>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Chọn Xe</h5>
                        <p class="text-muted">Tìm kiếm và chọn xe phù hợp với nhu cầu của bạn</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-secondary);">
                                <span class="text-white fs-2 fw-bold">2</span>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Đặt Xe</h5>
                        <p class="text-muted">Điền thông tin và gửi yêu cầu đặt xe online</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-success);">
                                <span class="text-white fs-2 fw-bold">3</span>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Xác Nhận</h5>
                        <p class="text-muted">Nhận xác nhận từ chúng tôi qua điện thoại hoặc email</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: var(--gradient-orange);">
                                <span class="text-white fs-2 fw-bold">4</span>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Nhận Xe</h5>
                        <p class="text-muted">Ký hợp đồng và nhận xe tại địa điểm đã hẹn</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title mb-4">
            <h2>Giấy Tờ Cần Thiết</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-id-card me-2 text-primary"></i>Thuê Xe Tự Lái</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>CMND/CCCD hoặc Passport (bản gốc)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bằng lái xe (bản gốc, còn hạn)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Hộ khẩu hoặc giấy tạm trú (bản gốc)</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-user-tie me-2 text-primary"></i>Thuê Xe Có Tài Xế</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>CMND/CCCD hoặc Passport (bản gốc)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thông tin liên hệ chính xác</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-title mb-4">
            <h2>Câu Hỏi Thường Gặp</h2>
        </div>
        
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item border-0 shadow-sm mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Tôi cần đặt xe trước bao lâu?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Bạn nên đặt xe trước ít nhất 1-2 ngày để chúng tôi có thể chuẩn bị tốt nhất. Trong trường hợp khẩn cấp, vui lòng liên hệ hotline để được hỗ trợ nhanh chóng.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 shadow-sm mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Phí thuê xe đã bao gồm những gì?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Phí thuê đã bao gồm: bảo hiểm xe, bảo dưỡng định kỳ, hỗ trợ kỹ thuật 24/7. Chưa bao gồm: nhiên liệu, phí cầu đường, phí gửi xe.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 shadow-sm mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Tôi có thể hủy đặt xe không?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Có thể hủy miễn phí trước 24 giờ. Nếu hủy trong vòng 24 giờ, bạn sẽ mất phí cọc (nếu có).
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 shadow-sm mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        Xe có được giao tận nơi không?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Có, chúng tôi cung cấp dịch vụ giao xe tận nơi với phí phụ thu tùy theo khoảng cách.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>