<?php
require_once __DIR__ . '/../../helpers/i18n.php';
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? __('dashboard') ?> - Thiên cơ trí liên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        
        .layout-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .main-area {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
        }
        
        .topbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .content-area {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 60px);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 15px;
            transition: all 0.3s;
            padding: 10px 15px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .module-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .language-dropdown {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-area {
                margin-left: 0;
            }
            
            .topbar {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <!-- Main Area -->
        <div class="main-area">
            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-left">
                    <!-- <h4 class="mb-0"><?= $pageTitle ?? __('dashboard') ?></h4> -->
                </div>
                <div class="topbar-right">
                    <!-- Language Selector -->
                    <?php include __DIR__ . '/../components/language_selector.php'; ?>
                    
                    <!-- User Info -->
                    <div class="user-info">
                        <span class="badge bg-primary"><?= getContentInCurrentLang($_SESSION['user_name'] ?? __('user'), $_SESSION['user_name_zh'] ?? '') ?></span>
                        <span class="badge bg-secondary">
                            <?php
                            $role = $_SESSION['role'] ?? 'user';
                            if ($role === 'admin') {
                                echo __('admin');
                            } elseif ($role === 'quanly') {
                                echo __('manager');
                            } elseif ($role === 'nhomtruong') {
                                echo __('team_leader');
                            } elseif ($role === 'nhanvien') {
                                echo __('employee');
                            } else {
                                echo __('user');
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="content-area">
                <?php include __DIR__ . '/../components/notifications.php'; ?>
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 