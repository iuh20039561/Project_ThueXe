<?php
$page_title = "Thêm xe mới";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $seats = intval($_POST['seats']);
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $price_per_day = floatval($_POST['price_per_day']);
    $description = trim($_POST['description']);
    $features = trim($_POST['features']);
    $status = $_POST['status'];
    
    // Xử lý upload hình ảnh chính
    $main_image = 'default.svg';
    if(isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['main_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/cars/' . $new_filename;
            
            if(move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_path)) {
                $main_image = $new_filename;
            }
        }
    }
    
    try {
        $sql = "INSERT INTO cars (name, brand, model, year, seats, transmission, fuel_type, 
                price_per_day, description, features, main_image, status) 
                VALUES (:name, :brand, :model, :year, :seats, :transmission, :fuel_type, 
                :price_per_day, :description, :features, :main_image, :status)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':seats', $seats);
        $stmt->bindParam(':transmission', $transmission);
        $stmt->bindParam(':fuel_type', $fuel_type);
        $stmt->bindParam(':price_per_day', $price_per_day);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':features', $features);
        $stmt->bindParam(':main_image', $main_image);
        $stmt->bindParam(':status', $status);
        
        if($stmt->execute()) {
            $car_id = $conn->lastInsertId();
            
            // Xử lý upload nhiều hình ảnh
            if(isset($_FILES['images'])) {
                foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if($_FILES['images']['error'][$key] == 0) {
                        $filename = $_FILES['images']['name'][$key];
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if(in_array($ext, $allowed)) {
                            $new_filename = uniqid() . '.' . $ext;
                            $upload_path = '../assets/images/cars/' . $new_filename;
                            
                            if(move_uploaded_file($tmp_name, $upload_path)) {
                                $sql_img = "INSERT INTO car_images (car_id, image_path) VALUES (:car_id, :image_path)";
                                $stmt_img = $conn->prepare($sql_img);
                                $stmt_img->bindParam(':car_id', $car_id);
                                $stmt_img->bindParam(':image_path', $new_filename);
                                $stmt_img->execute();
                            }
                        }
                    }
                }
            }
            
            $_SESSION['success'] = "Thêm xe thành công!";
            header("Location: cars.php");
            exit();
        }
    } catch(PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-plus-circle me-2"></i>Thêm Xe Mới</h2>
            <p class="text-muted mb-0">Thêm xe vào hệ thống</p>
        </div>
        <a href="cars.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<?php if(isset($error)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên xe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Hãng xe <span class="text-danger">*</span></label>
                    <select class="form-select" name="brand" required>
                        <option value="">Chọn hãng</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Honda">Honda</option>
                        <option value="Mazda">Mazda</option>
                        <option value="Ford">Ford</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Kia">Kia</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Nissan">Nissan</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Dòng xe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="model" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Năm sản xuất <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="year" min="2000" max="2025" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Số chỗ <span class="text-danger">*</span></label>
                    <select class="form-select" name="seats" required>
                        <option value="4">4 chỗ</option>
                        <option value="5">5 chỗ</option>
                        <option value="7">7 chỗ</option>
                        <option value="9">9 chỗ</option>
                        <option value="16">16 chỗ</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Hộp số <span class="text-danger">*</span></label>
                    <select class="form-select" name="transmission" required>
                        <option value="Tự động">Tự động</option>
                        <option value="Số sàn">Số sàn</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Nhiên liệu <span class="text-danger">*</span></label>
                    <select class="form-select" name="fuel_type" required>
                        <option value="Xăng">Xăng</option>
                        <option value="Dầu">Dầu</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Điện">Điện</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Giá thuê/ngày (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="price_per_day" min="0" step="1000" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="available">Có sẵn</option>
                        <option value="rented">Đang thuê</option>
                        <option value="maintenance">Bảo trì</option>
                    </select>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Tính năng (phân cách bởi dấu phẩy)</label>
                    <input type="text" class="form-control" name="features" placeholder="GPS,Bluetooth,Camera lùi,Cửa sổ trời">
                    <small class="text-muted">VD: GPS,Bluetooth,Camera lùi,Cửa sổ trời</small>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Hình ảnh chính</label>
                    <input type="file" class="form-control" name="main_image" accept="image/*" onchange="previewImage(this, 'mainPreview')">
                    <img id="mainPreview" class="mt-2 image-preview" style="display: none;">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Hình ảnh khác (có thể chọn nhiều)</label>
                    <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                </div>
                
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Lưu xe
                    </button>
                    <a href="cars.php" class="btn btn-secondary">Hủy</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>