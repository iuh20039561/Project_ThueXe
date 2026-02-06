<?php
$page_title = "Cài đặt";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';

// Xử lý đổi mật khẩu
if(isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Kiểm tra mật khẩu mới
    if($new_password !== $confirm_password) {
        $error = "Mật khẩu mới không khớp!";
    } elseif(strlen($new_password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự!";
    } else {
        // Lấy thông tin admin
        $sql = "SELECT * FROM admins WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['admin_id']);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Kiểm tra mật khẩu hiện tại
        if(password_verify($current_password, $admin['password'])) {
            // Cập nhật mật khẩu mới
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET password = :password WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id', $_SESSION['admin_id']);
            
            if($stmt->execute()) {
                $success = "Đổi mật khẩu thành công!";
            } else {
                $error = "Có lỗi xảy ra!";
            }
        } else {
            $error = "Mật khẩu hiện tại không đúng!";
        }
    }
}

// Xử lý cập nhật thông tin
if(isset($_POST['update_info'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    
    $sql = "UPDATE admins SET full_name = :full_name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $_SESSION['admin_id']);
    
    if($stmt->execute()) {
        $_SESSION['admin_name'] = $full_name;
        $success = "Cập nhật thông tin thành công!";
    } else {
        $error = "Có lỗi xảy ra!";
    }
}

// Xử lý cập nhật cài đặt website
if(isset($_POST['update_settings'])) {
    $settings = [
        'site_name' => trim($_POST['site_name']),
        'site_email' => trim($_POST['site_email']),
        'site_phone' => trim($_POST['site_phone']),
        'site_address' => trim($_POST['site_address']),
        'facebook_url' => trim($_POST['facebook_url']),
        'zalo_phone' => trim($_POST['zalo_phone'])
    ];
    
    foreach($settings as $key => $value) {
        $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
    }
    
    $success = "Cập nhật cài đặt thành công!";
}

// Lấy thông tin admin
$sql = "SELECT * FROM admins WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $_SESSION['admin_id']);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy cài đặt website
$sql = "SELECT * FROM settings";
$stmt = $conn->prepare($sql);
$stmt->execute();
$settings_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings = [];
foreach($settings_data as $s) {
    $settings[$s['setting_key']] = $s['setting_value'];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2><i class="fas fa-cog me-2"></i>Cài Đặt</h2>
    <p class="text-muted mb-0">Quản lý thông tin và cài đặt hệ thống</p>
</div>

<?php if($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Thông tin tài khoản -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông Tin Tài Khoản</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" value="<?php echo $admin['username']; ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo $admin['full_name']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $admin['email']; ?>">
                    </div>
                    
                    <button type="submit" name="update_info" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Cập nhật thông tin
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Đổi mật khẩu -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Đổi Mật Khẩu</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" required minlength="6">
                        <small class="text-muted">Tối thiểu 6 ký tự</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="6">
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-gradient">
                        <i class="fas fa-lock me-2"></i>Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Cài đặt website -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Cài Đặt Website</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên website</label>
                            <input type="text" class="form-control" name="site_name" value="<?php echo $settings['site_name'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email liên hệ</label>
                            <input type="email" class="form-control" name="site_email" value="<?php echo $settings['site_email'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="site_phone" value="<?php echo $settings['site_phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Số Zalo</label>
                            <input type="text" class="form-control" name="zalo_phone" value="<?php echo $settings['zalo_phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="site_address" value="<?php echo $settings['site_address'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" class="form-control" name="facebook_url" value="<?php echo $settings['facebook_url'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" name="update_settings" class="btn btn-gradient">
                                <i class="fas fa-save me-2"></i>Cập nhật cài đặt
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Thông tin hệ thống -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Hệ Thống</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Phiên bản PHP:</td>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Database:</td>
                                <td>MySQL <?php echo $conn->query('SELECT VERSION()')->fetchColumn(); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Server:</td>
                                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Upload max size:</td>
                                <td><?php echo ini_get('upload_max_filesize'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Post max size:</td>
                                <td><?php echo ini_get('post_max_size'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Memory limit:</td>
                                <td><?php echo ini_get('memory_limit'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>