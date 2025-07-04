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
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        .module-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .language-selector {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Language Selector -->
    <div class="language-selector">
        <?php include __DIR__ . '/../components/language_selector.php'; ?>
    </div>
    
    <!-- <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="dashboard-header py-3 border-bottom mb-3 bg-white sticky-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h2 mb-0"><?= $pageTitle ?? __('dashboard') ?></h1>
                        <div>
                            <span class="badge bg-primary"><?= $_SESSION['user_name'] ?? 'User' ?></span>
                            <span class="badge bg-secondary ms-2"><?= ucfirst($_SESSION['role'] ?? 'user') ?></span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div> -->
</body>
</html> 