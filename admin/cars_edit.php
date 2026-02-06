<?php
$page_title = "Sửa thông tin xe";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin xe
$sql = "SELECT * FROM cars WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $car_id);
$stmt->execute();
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$car) {
    header("Location: cars.php");
    exit();
}

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
    
    $main_image = $car['main_image'];
    
    // Xử lý upload hình ảnh mới
    if(isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['main_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../assets/images/cars/' . $new_filename;
            
            if(move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_path)) {
                // Xóa ảnh cũ
                if($car['main_image'] != 'default.svg' && file_exists('../assets/images/cars/' . $car['main_image'])) {
                    unlink('../assets/images/cars/' . $car['main_image']);
                }
                $main_image = $new_filename;
            }
        }
    }
    
    try {
        $sql = "UPDATE cars SET name = :name, brand = :brand, model = :model, year = :year, 
                seats = :seats, transmission = :transmission, fuel_type = :fuel_type, 
                price_per_day = :price_per_day, description = :description, features = :features, 
                main_image = :main_image, status = :status WHERE id = :id";
        
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
        $stmt->bindParam(':id', $car_id);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật xe thành công!";
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
            <h2><i class="fas fa-edit me-2"></i>Sửa Thông Tin Xe</h2>
            <p class="text-muted mb-0">Cập nhật thông tin xe #<?php echo $car['id']; ?></p>
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
                    <input type="text" class="form-control" name="name" value="<?php echo $car['name']; ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Hãng xe <span class="text-danger">*</span></label>
                    <select class="form-select" name="brand" required>
                        <option value="">Chọn hãng</option>
                        <?php 
                        $brands = ['Toyota', 'Honda', 'Mazda', 'Ford', 'Hyundai', 'Kia', 'Mitsubishi', 'Nissan'];
                        foreach($brands as $b): 
                        ?>
                        <option value="<?php echo $b; ?>" <?php echo $car['brand'] == $b ? 'selected' : ''; ?>><?php echo $b; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Dòng xe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="model" value="<?php echo $car['model']; ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Năm sản xuất <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="year" value="<?php echo $car['year']; ?>" min="2000" max="2025" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Số chỗ <span class="text-danger">*</span></label>
                    <select class="form-select" name="seats" required>
                        <?php 
                        $seats_options = [4, 5, 7, 9, 16];
                        foreach($seats_options as $s): 
                        ?>
                        <option value="<?php echo $s; ?>" <?php echo $car['seats'] == $s ? 'selected' : ''; ?>><?php echo $s; ?> chỗ</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Hộp số <span class="text-danger">*</span></label>
                    <select class="form-select" name="transmission" required>
                        <option value="Tự động" <?php echo $car['transmission'] == 'Tự động' ? 'selected' : ''; ?>>Tự động</option>
                        <option value="Số sàn" <?php echo $car['transmission'] == 'Số sàn' ? 'selected' : ''; ?>>Số sàn</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Nhiên liệu <span class="text-danger">*</span></label>
                    <select class="form-select" name="fuel_type" required>
                        <option value="Xăng" <?php echo $car['fuel_type'] == 'Xăng' ? 'selected' : ''; ?>>Xăng</option>
                        <option value="Dầu" <?php echo $car['fuel_type'] == 'Dầu' ? 'selected' : ''; ?>>Dầu</option>
                        <option value="Hybrid" <?php echo $car['fuel_type'] == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                        <option value="Điện" <?php echo $car['fuel_type'] == 'Điện' ? 'selected' : ''; ?>>Điện</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Giá thuê/ngày (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="price_per_day" value="<?php echo $car['price_per_day']; ?>" min="0" step="1000" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="available" <?php echo $car['status'] == 'available' ? 'selected' : ''; ?>>Có sẵn</option>
                        <option value="rented" <?php echo $car['status'] == 'rented' ? 'selected' : ''; ?>>Đang thuê</option>
                        <option value="maintenance" <?php echo $car['status'] == 'maintenance' ? 'selected' : ''; ?>>Bảo trì</option>
                    </select>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo $car['description']; ?></textarea>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Tính năng (phân cách bởi dấu phẩy)</label>
                    <input type="text" class="form-control" name="features" value="<?php echo $car['features']; ?>">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Hình ảnh chính</label>
                    <input type="file" class="form-control" name="main_image" accept="image/*" onchange="previewImage(this, 'mainPreview')">
                    <img id="mainPreview" src="../assets/images/cars/<?php echo $car['main_image']; ?>" class="mt-2 image-preview">
                </div>
                
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                    <a href="cars.php" class="btn btn-secondary">Hủy</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>