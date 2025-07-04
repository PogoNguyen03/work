<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Form thêm/sửa người dùng
ob_start();
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-<?= $isEdit ? 'edit' : 'plus' ?> me-2"></i>
                    <?= $isEdit ? __('edit_user') : __('add_user') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/work/public/users/<?= $isEdit ? 'update' : 'store' ?>">
                    <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <?php endif; ?>
                    
                    <!-- Tên tiếng Việt -->
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('name') ?> (<?= __('vietnamese') ?>) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                    </div>
                    
                    <!-- Tên tiếng Trung -->
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
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required <?= $isEdit ? 'readonly' : '' ?>>
                    </div>
                    <?php if (!$isEdit): ?>
                    <div class="mb-3">
                        <label for="password" class="form-label"><?= __('password') ?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="department_id" class="form-label"><?= __('department') ?></label>
                        <select class="form-select" id="department_id" name="department_id">
                            <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>" <?= (isset($user['department_id']) && $user['department_id'] == $dept['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label"><?= __('role') ?></label>
                        <select class="form-select" id="role" name="role">
                            <option value="user" <?= (isset($user['role']) && $user['role'] == 'user') ? 'selected' : '' ?>><?= __('employee') ?></option>
                            <option value="nhomtruong" <?= (isset($user['role']) && $user['role'] == 'nhomtruong') ? 'selected' : '' ?>><?= __('team_leader') ?></option>
                            <option value="quanly" <?= (isset($user['role']) && $user['role'] == 'quanly') ? 'selected' : '' ?>><?= __('manager') ?></option>
                            <?php if ($role === 'admin'): ?>
                            <option value="admin" <?= (isset($user['role']) && $user['role'] == 'admin') ? 'selected' : '' ?>><?= __('admin') ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1" <?= (!isset($user['is_verified']) || $user['is_verified']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_verified"><?= __('verified_account') ?></label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="/work/public/users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i><?= __('back') ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-<?= $isEdit ? 'save' : 'plus' ?> me-1"></i>
                            <?= $isEdit ? __('save') : __('add_user') ?>
                        </button>
                    </div>
                </form>
            </div>
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