<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Đăng ký tài khoản
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLang() ?>">
<head>
    <meta charset="UTF-8">
    <title><?= __('register') ?> - <?= __('app_name') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .register-card {
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            background: rgba(255,255,255,0.97);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .register-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .register-header img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
            box-shadow: 0 2px 8px rgba(67,206,162,0.15);
        }
        .form-control:focus {
            border-color: #185a9d;
            box-shadow: 0 0 0 0.2rem rgba(24, 90, 157, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2fa07a 0%, #14487a 100%);
        }
        .btn-link {
            color: #185a9d;
        }
        .btn-link:hover {
            color: #43cea2;
        }
        .language-selector {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .language-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .language-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        @media (max-width: 576px) {
            .register-card { padding: 1.2rem 0.5rem; }
        }
    </style>
</head>
<body>
    <!-- Language Selector -->
    <div class="language-selector">
        <div class="dropdown">
            <button class="btn language-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-globe me-1"></i><?= __('language') ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/work/public/language?lang=vi"><?= __('vietnamese') ?></a></li>
                <li><a class="dropdown-item" href="/work/public/language?lang=zh"><?= __('chinese') ?></a></li>
            </ul>
        </div>
    </div>

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-7 col-lg-6 col-xl-5">
            <div class="register-card">
                <div class="register-header">
                    <!-- <img src="/work/public/assets/pic/logo-vi.png" alt="Logo" style="width:120px;height:auto;border-radius:0;box-shadow:none;background:#fff;padding:8px;"> -->
                    <h3 class="fw-bold mb-0"><i class="fa-solid fa-user-plus me-2"></i><?= __('register_account') ?></h3>
                </div>
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-info animate__animated animate__fadeInDown"><?php echo $message; ?></div>
                    <?php endif; ?>
                <form method="post" autocomplete="off">
                        <div class="mb-3">
                        <label for="name" class="form-label fw-semibold"><i class="fa-solid fa-user me-1"></i><?= __('name') ?></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                        <label for="email" class="form-label fw-semibold"><i class="fa-solid fa-envelope me-1"></i><?= __('email') ?></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                        <label for="department_id" class="form-label fw-semibold"><i class="fa-solid fa-building me-1"></i><?= __('department') ?></label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">-- <?= __('select_department') ?> --</option>
                            <?php if ($departments) { while ($d = $departments->fetch_assoc()): ?>
                                <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['name']); ?></option>
                            <?php endwhile; } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold"><i class="fa-solid fa-lock me-1"></i><?= __('password') ?></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                        <label for="confirm" class="form-label fw-semibold"><i class="fa-solid fa-key me-1"></i><?= __('confirm_password') ?></label>
                            <input type="password" class="form-control" id="confirm" name="confirm" required>
                        </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-2"><i class="fa-solid fa-user-plus me-1"></i><?= __('register') ?></button>
                    <a href="/work/public/login" class="btn btn-link w-100"><?= __('have_account') ?> <?= __('login') ?></a>
                    </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html> 