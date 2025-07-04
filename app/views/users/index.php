<?php
require_once __DIR__ . '/../../helpers/i18n.php';
$currentPage = 'users';
// View: Danh sách người dùng
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-users me-2"></i><?= __('users') ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
        <a href="/work/public/users/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i><?= __('add_user') ?>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"> <?= $_SESSION['success']; unset($_SESSION['success']); ?> </div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"> <?= $_SESSION['error']; unset($_SESSION['error']); ?> </div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted"><?= __('no_users') ?></h5>
                <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
                <a href="/work/public/users/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i><?= __('add_first_user') ?>
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><?= __('name') ?></th>
                            <th><?= __('email') ?></th>
                            <th><?= __('role') ?></th>
                            <th><?= __('department') ?></th>
                            <th><?= __('created_at') ?></th>
                            <th><?= __('status') ?></th>
                            <th><?= __('actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars(getContentInCurrentLang($user['name'], $user['name_zh'])) ?></div>
                            </td>
                            <td><span class="text-muted"><?= htmlspecialchars($user['email']) ?></span></td>
                            <td>
                                <?php
                                $roleClass = 'bg-secondary';
                                $roleLabel = '';
                                if ($user['role'] === 'admin') {
                                    $roleClass = 'bg-danger';
                                    $roleLabel = __('admin');
                                } elseif ($user['role'] === 'quanly') {
                                    $roleClass = 'bg-warning';
                                    $roleLabel = __('manager');
                                } elseif ($user['role'] === 'nhomtruong') {
                                    $roleClass = 'bg-info';
                                    $roleLabel = __('team_leader');
                                } else {
                                    $roleClass = 'bg-primary';
                                    $roleLabel = __('employee');
                                }
                                ?>
                                <span class="badge <?= $roleClass ?>"><?= $roleLabel ?></span>
                            </td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($user['department_name'] ?? __('not_assigned')) ?></span></td>
                            <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></small></td>
                            <td>
                                <?php if ($user['is_verified']): ?>
                                    <span class="badge bg-success"><?= __('verified') ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><?= __('not_verified') ?></span>
                                    <?php if ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong'): ?>
                                    <a href="/work/public/users/verify?id=<?= $user['id'] ?>" class="btn btn-sm btn-success ms-2"><?= __('verify') ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/work/public/users/edit?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-warning" title="<?= __('edit') ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 