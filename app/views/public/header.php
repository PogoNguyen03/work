<?php 
// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = realpath(__DIR__ . '/../../..');
require_once $base_path . '/app/helpers/i18n.php'; 
require_once $base_path . '/app/helpers/ip.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Thiên Cơ Trí Liên SEO' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Công ty SEO hàng đầu Việt Nam - Tối ưu hóa website, tăng thứ hạng Google' ?>">
    <meta name="keywords" content="SEO, tối ưu hóa website, Google ranking, digital marketing, Việt Nam">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $pageTitle ?? 'Thiên Cơ Trí Liên SEO' ?>">
    <meta property="og:description" content="<?= $pageDescription ?? 'Công ty SEO hàng đầu Việt Nam' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <?php $font = $website_settings['font_family'] ?? 'Inter'; ?>
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($font) ?>:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: <?= $website_settings['primary_color'] ?? '#1e40af' ?>;
            --secondary-color: #059669;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: '<?= $font ?>', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            background: var(--primary-color) !important;
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,100 1000,0 1000,100"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }
        
        .section {
            padding: 80px 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--accent-color);
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .section {
                padding: 50px 0;
            }
        }
        .navbar-brand img {
            height: 40px;
            max-width: 160px;
            object-fit: contain;
            vertical-align: middle;
            background: transparent;
            filter: drop-shadow(0 2px 8px rgba(30,64,175,0.10));
            border-radius: 6px;
            padding: 2px 8px;
        }
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background: #d97706;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <?php if (!empty($website_settings['logo'])): ?>
                    <img src="/public/<?= $website_settings['logo'] ?>" alt="Logo">
                <?php endif; ?>
                <span class="ms-2 fw-bold" style="font-size:1.25rem;vertical-align:middle;">
                    <?= htmlspecialchars($website_settings['site_name'] ?? 'Thiên Cơ Trí Liên') ?>
                </span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe"></i> <?= getCurrentLang() === 'vi' ? 'VN' : '中文' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item" href="/language?lang=vi">Tiếng Việt</a></li>
                            <li><a class="dropdown-item" href="/language?lang=zh">中文</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'homepage' ? 'active' : '' ?>" href="/">
                            <i class="fas fa-home me-1"></i><?= __('homepage') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'about' ? 'active' : '' ?>" href="/about">
                            <i class="fas fa-info-circle me-1"></i><?= __('about') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'contact' ? 'active' : '' ?>" href="/contact">
                            <i class="fas fa-envelope me-1"></i><?= __('contact') ?>
                        </a>
                    </li>
                    <?php
                    // Chỉ hiển thị nút đăng nhập nếu IP thuộc mạng nội bộ 192.168.5.0/24
                    ?>
                    <?php if (is_internal_ip($user_ip)): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm ms-2" href="/login">
                            <i class="fas fa-sign-in-alt me-1"></i><?= __('login') ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main> 