<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/i18n.php';
$role = getUserRole();
$sidebarTitles = [
    'dashboard' => __('dashboard'),
    'reports' => __('reports'),
    'users' => __('users'),
    'tasks' => __('tasks'),
    'notifications' => __('notifications'),
    'profile' => __('profile'),
];
$sidebarTitle = $sidebarTitles[$currentPage] ?? __('dashboard');
?>
<nav class="sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 mt-3">
            <h4 class="text-white"><?= __('app_name') ?></h4>
            <small class="text-white-50"><?= $sidebarTitle ?></small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="/work/public/dashboard">
                    <i class="fas fa-home me-2"></i> <?= __('dashboard') ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $currentPage === 'reports' ? 'active' : '' ?>" href="/work/public/reports">
                    <i class="fas fa-file-alt me-2"></i> <?= __('reports') ?>
                </a>
            </li>
            <?php if ($role === 'admin'|| $role === 'quanly' || $role === 'nhomtruong'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $currentPage === 'users' ? 'active' : '' ?>" href="/work/public/users">
                    <i class="fas fa-users me-2"></i> <?= __('users') ?>
                </a>
            </li>
            <?php endif; ?>
            <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $currentPage === 'tasks' ? 'active' : '' ?>" href="/work/public/tasks">
                    <i class="fas fa-tasks me-2"></i> <?= __('tasks') ?>
                </a>
            </li>
            <?php endif; ?>
            <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $currentPage === 'notifications' ? 'active' : '' ?>" href="/work/public/notifications">
                    <i class="fas fa-bell me-2"></i> <?= __('notifications') ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <hr class="text-white-50 mx-3">
        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a class="nav-link text-white" href="/work/public/profile">
                    <i class="fas fa-user me-2"></i> <?= __('profile') ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/work/public/logout">
                    <i class="fas fa-sign-out-alt me-2"></i> <?= __('logout') ?>
                </a>
            </li>
        </ul>
    </div>
</nav> 