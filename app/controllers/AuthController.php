<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        handleLogin();
        break;
}

function handleLogin() {
    global $conn;
    
    // If already logged in, redirect to dashboard
    if (isset($_SESSION['user_id'])) {
        header('Location: /work/public/dashboard');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        if (empty($email) || empty($password)) {
            $error = __('please_fill_all_fields');
        } else {
            $stmt = $conn->prepare('SELECT id, name, email, password, role, department_id, name_zh, is_verified FROM users WHERE email = ? AND is_verified = 1');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_name_zh'] = $user['name_zh'] ?? '';
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['department_id'] = $user['department_id'];
                $_SESSION['is_verified'] = $user['is_verified'];
                
                header('Location: /work/public/dashboard');
                exit;
            } else {
                $error = __('invalid_credentials');
            }
        }
    }
    
    // Show login form
    ?>
    <!DOCTYPE html>
    <html lang="<?= getCurrentLang() ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __('login') ?> - <?= __('app_name') ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-card {
                background: white;
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                overflow: hidden;
                max-width: 400px;
                width: 100%;
            }
            .login-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2rem;
                text-align: center;
            }
            .login-body {
                padding: 2rem;
            }
            .form-control {
                border-radius: 10px;
                border: 2px solid #e9ecef;
                padding: 12px 15px;
                transition: all 0.3s;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-login {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 10px;
                padding: 12px;
                font-weight: 600;
                transition: all 0.3s;
            }
            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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

        <div class="login-card">
            <div class="login-header">
                <h3><i class="fas fa-tachometer-alt me-2"></i><?= __('app_name') ?></h3>
                <p class="mb-0"><?= __('login_to_system') ?></p>
            </div>
            <div class="login-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/work/public/login">
                    <div class="mb-3">
                        <label for="email" class="form-label"><?= __('email') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label"><?= __('password') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i><?= __('login') ?>
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <?= __('no_account') ?> <a href="/work/public/register" class="text-decoration-none"><?= __('register_now') ?></a>
                    </small>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}

function handleLogout() {
    // Xóa toàn bộ biến session
    $_SESSION = [];
    // Xóa cookie session nếu có
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /work/public/login');
    exit;
}
?> 