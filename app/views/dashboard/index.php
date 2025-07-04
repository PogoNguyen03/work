<?php
require_once __DIR__ . '/../../helpers/i18n.php';
require_once __DIR__ . '/../../helpers/translate.php';
// View: Dashboard
ob_start();
?>
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card module-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <?= __('total_reports') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $reports_count ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($role === 'admin'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card module-card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <?= __('total_users') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $users_count ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card module-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            <?= __('role') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            $roleNames = [
                                'admin' => __('admin'),
                                'quanly' => __('manager'),
                                'nhomtruong' => __('team_leader'),
                                'nhanvien' => __('employee'),
                                'user' => __('employee')
                            ];
                            echo $roleNames[$role] ?? __('not_available');
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card module-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            <?= __('today') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= date('d/m/Y') ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Module Cards -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card module-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-alt me-2"></i>
                    <?= __('reports') ?>
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3"><?= __('dashboard_reports_desc') ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary"><?= $reports_count ?> <?= __('reports') ?></span>
                    <a href="/work/public/reports" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>
                        <?= __('view') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php if ($role === 'admin'): ?>
    <div class="col-lg-6 mb-4">
        <div class="card module-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-users me-2"></i>
                    <?= __('users') ?>
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3"><?= __('dashboard_users_desc') ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-success"><?= $users_count ?> <?= __('users') ?></span>
                    <a href="/work/public/users" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>
                        <?= __('view') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($role === 'admin' || $role === 'quanly'): ?>
    <div class="col-lg-6 mb-4">
        <div class="card module-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-tasks me-2"></i>
                    <?= __('tasks') ?>
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3"><?= __('dashboard_tasks_desc') ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-info"><?= __('coming_soon') ?></span>
                    <a href="/work/public/tasks" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>
                        <?= __('view') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
    <div class="col-lg-6 mb-4">
        <div class="card module-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-bell me-2"></i>
                    <?= __('notifications') ?>
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3"><?= __('dashboard_notifications_desc') ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-warning"><?= __('coming_soon') ?></span>
                    <a href="/work/public/notifications" class="btn btn-warning btn-sm">
                        <i class="fas fa-arrow-right me-1"></i>
                        <?= __('view') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- Recent Reports -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock me-2"></i>
                    <?= __('recent_reports') ?>
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($recent_reports)): ?>
                    <p class="text-muted"><?= __('no_reports') ?></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= __('reports') ?></th>
                                    <th><?= __('reporter') ?></th>
                                    <th><?= __('role') ?></th>
                                    <?php if ($role === 'admin'): ?>
                                    <th><?= __('department') ?></th>
                                    <?php endif; ?>
                                    <th><?= __('created_at') ?></th>
                                    <th><?= __('actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_reports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars(getContentInCurrentLang($report['title'], $report['title_zh'] ?? null)) ?></td>
                                    <td><?= htmlspecialchars($report['user_name']) ?></td>
                                    <td>
                                        <?php 
                                        $roleNames = [
                                            'admin' => __('admin'),
                                            'quanly' => __('manager'),
                                            'nhomtruong' => __('team_leader'),
                                            'nhanvien' => __('employee'),
                                            'user' => __('employee')
                                        ];
                                        $roleClass = [
                                            'admin' => 'bg-danger',
                                            'quanly' => 'bg-warning',
                                            'nhomtruong' => 'bg-info',
                                            'nhanvien' => 'bg-secondary',
                                            'user' => 'bg-secondary'
                                        ];
                                        ?>
                                        <span class="badge <?= $roleClass[$report['user_role']] ?? 'bg-secondary' ?>">
                                            <?= $roleNames[$report['user_role']] ?? __('not_available') ?>
                                        </span>
                                    </td>
                                    <?php if ($role === 'admin'): ?>
                                    <td><?= htmlspecialchars($report['department_name'] ?? __('not_available')) ?></td>
                                    <?php endif; ?>
                                    <td><?= date('d/m/Y H:i', strtotime($report['created_at'])) ?></td>
                                    <td>
                                        <a href="/work/public/reports/view?id=<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 