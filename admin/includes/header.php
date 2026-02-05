<?php require_once 'check_login.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin CarRental</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-car me-2"></i>CarRental Admin</h4>
        </div>
        
        <div class="sidebar-menu">
            <a href="index.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Tổng quan</span>
            </a>
            
            <a href="bookings.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Quản lý đơn</span>
            </a>
            
            <a href="cars.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'cars.php' || basename($_SERVER['PHP_SELF']) == 'car-add.php' || basename($_SERVER['PHP_SELF']) == 'car-edit.php' ? 'active' : ''; ?>">
                <i class="fas fa-car"></i>
                <span>Quản lý xe</span>
            </a>
            
            <a href="services.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="fas fa-concierge-bell"></i>
                <span>Quản lý dịch vụ</span>
            </a>
            
            <a href="settings.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Cài đặt</span>
            </a>
            
            <a href="logout.php" class="sidebar-item text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <nav class="topbar">
            <div class="d-flex justify-content-between align-items-center w-100">
                <button class="btn btn-link text-dark" id="sidebarToggle">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                
                <div class="d-flex align-items-center">
                    <span class="me-3">Xin chào, <strong><?php echo $_SESSION['admin_name'] ?? $_SESSION['admin_username']; ?></strong></span>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="content-wrapper">