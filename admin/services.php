<?php
$page_title = "Quản lý dịch vụ";
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Xử lý thêm/sửa/xóa
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if($action == 'add') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $icon = trim($_POST['icon']);
            
            $sql = "INSERT INTO services (name, description, price, icon) VALUES (:name, :description, :price, :icon)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':icon', $icon);
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Thêm dịch vụ thành công!";
            }
        }
        elseif($action == 'edit') {
            $id = intval($_POST['id']);
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $icon = trim($_POST['icon']);
            $status = intval($_POST['status']);
            
            $sql = "UPDATE services SET name = :name, description = :description, price = :price, icon = :icon, status = :status WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            
            if($stmt->execute()) {
                $_SESSION['success'] = "Cập nhật dịch vụ thành công!";
            }
        }
    }
}

// Xóa dịch vụ
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM services WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
        $_SESSION['success'] = "Xóa dịch vụ thành công!";
    }
    header("Location: services.php");
    exit();
}

// Lấy danh sách dịch vụ
$sql = "SELECT * FROM services ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-concierge-bell me-2"></i>Quản Lý Dịch Vụ</h2>
            <p class="text-muted mb-0">Quản lý các dịch vụ đi kèm</p>
        </div>
        <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="fas fa-plus me-2"></i>Thêm dịch vụ
        </button>
    </div>
</div>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Services Table -->
<div class="data-table">
    <div class="table-header">
        <h5 class="mb-0">Danh sách dịch vụ (<?php echo count($services); ?>)</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Icon</th>
                    <th>Tên dịch vụ</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($services as $service): ?>
                <tr>
                    <td><strong>#<?php echo $service['id']; ?></strong></td>
                    <td>
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                             style="width: 40px; height: 40px; background: var(--gradient-primary);">
                            <i class="fas fa-<?php echo $service['icon']; ?> text-white"></i>
                        </div>
                    </td>
                    <td><strong><?php echo $service['name']; ?></strong></td>
                    <td><?php echo $service['description']; ?></td>
                    <td><strong class="text-primary"><?php echo number_format($service['price']); ?>đ</strong></td>
                    <td>
                        <?php if($service['status'] == 1): ?>
                        <span class="badge bg-success">Đang hoạt động</span>
                        <?php else: ?>
                        <span class="badge bg-secondary">Tạm dừng</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-gradient" onclick='editService(<?php echo json_encode($service); ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?delete=<?php echo $service['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc muốn xóa dịch vụ này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Dịch Vụ Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" min="0" step="1000" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="icon" placeholder="shield-alt" required>
                        <small class="text-muted">
                            Xem icons tại: <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gradient">Thêm dịch vụ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa Dịch Vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" id="edit_price" min="0" step="1000" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="icon" id="edit_icon" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status" id="edit_status">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gradient">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editService(service) {
    document.getElementById('edit_id').value = service.id;
    document.getElementById('edit_name').value = service.name;
    document.getElementById('edit_description').value = service.description;
    document.getElementById('edit_price').value = service.price;
    document.getElementById('edit_icon').value = service.icon;
    document.getElementById('edit_status').value = service.status;
    
    const modal = new bootstrap.Modal(document.getElementById('editServiceModal'));
    modal.show();
}
</script>

<?php include 'includes/footer.php'; ?>