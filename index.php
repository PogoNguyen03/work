<?php
// Nếu muốn luôn chuyển về login:
header('Location: /work/public/login');
exit;

// Hoặc nếu muốn chuyển về dashboard nếu đã đăng nhập:
// session_start();
// if (isset($_SESSION['user_id'])) {
//     header('Location: /work/public/dashboard');
// } else {
//     header('Location: /work/public/login');
// }
// exit;
?>
