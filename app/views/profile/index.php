<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Hồ sơ cá nhân
ob_start();
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user me-2"></i><?= __('profile') ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="avatar-placeholder mb-3">
                            <i class="fas fa-user-circle fa-5x text-muted"></i>
                        </div>
                        <h5><?= htmlspecialchars($user['name']) ?></h5>
                        <!-- <span class="badge bg-primary"><?= __('role') ?>: <?= ucfirst($user['role']) ?></span> -->
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('name') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= htmlspecialchars($user['name']) ?>
                                <?php if (!empty($user['name_zh'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($user['name_zh']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('email') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= htmlspecialchars($user['email']) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('role') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
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
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('department') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= htmlspecialchars($user['department_name'] ?? __('not_assigned')) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('created_at') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><?= __('status') ?>:</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php if ($user['is_verified']): ?>
                                    <span class="badge bg-success"><?= __('verified') ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning"><?= __('not_verified') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="/work/public/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i><?= __('back') ?>
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit me-1"></i><?= __('edit_profile') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('edit_profile') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('name') ?> (<?= __('vietnamese') ?>) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="name_zh" class="form-label"><?= __('name') ?> (<?= __('chinese') ?>)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="name_zh" name="name_zh" 
                                   value="<?= htmlspecialchars($user['name_zh'] ?? '') ?>" 
                                   placeholder="<?= __('chinese_name_placeholder') ?>">
                            <button type="button" class="btn btn-outline-info" id="translateNameBtn">
                                <i class="fas fa-language"></i>
                            </button>
                        </div>
                        <div class="form-text"><?= __('chinese_name_hint') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><?= __('email') ?> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('cancel') ?></button>
                    <button type="submit" class="btn btn-primary"><?= __('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-translate name
document.getElementById('translateNameBtn').addEventListener('click', function() {
    const nameInput = document.getElementById('name');
    const nameZhInput = document.getElementById('name_zh');
    const translateBtn = this;
    
    if (nameInput.value.trim()) {
        translateBtn.disabled = true;
        translateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Gọi API dịch
        fetch('/work/public/translate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                text: nameInput.value,
                target_lang: 'zh'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nameZhInput.value = data.translation;
            }
            translateBtn.disabled = false;
            translateBtn.innerHTML = '<i class="fas fa-language"></i>';
        })
        .catch(error => {
            console.error('Translation error:', error);
            translateBtn.disabled = false;
            translateBtn.innerHTML = '<i class="fas fa-language"></i>';
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 