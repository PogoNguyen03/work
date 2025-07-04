<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Form báo cáo
ob_start();
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?> me-2"></i>
                    <?= $isEdit ? __('edit_report') : __('create_report') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/work/public/reports/<?= $isEdit ? 'update' : 'store' ?>" data-ajax="true">
                    <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $report['id'] ?>">
                    <?php endif; ?>
                    
                    <!-- Tiếng Việt -->
                    <div class="mb-3">
                        <label for="title" class="form-label"><?= __('title') ?> (<?= __('vietnamese') ?>) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= htmlspecialchars($report['title'] ?? '') ?>" 
                               placeholder="<?= __('report_title_placeholder') ?>" required>
                        <div class="form-text"><?= __('report_title_hint') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label"><?= __('content') ?> (<?= __('vietnamese') ?>) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="8" 
                                  placeholder="<?= __('report_content_placeholder') ?>" required><?= htmlspecialchars($report['content'] ?? '') ?></textarea>
                        <div class="form-text">
                            <strong><?= __('report_suggestion') ?>:</strong> <?= __('report_should_include') ?>
                            <ul class="mb-0 mt-1">
                                <li><?= __('report_task_done_today') ?></li>
                                <li><?= __('report_result_progress') ?></li>
                                <li><?= __('report_difficulties') ?></li>
                                <li><?= __('report_plan_tomorrow') ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Tiếng Trung -->
                    <div class="mb-3">
                        <label for="title_zh" class="form-label"><?= __('title') ?> (<?= __('chinese') ?>)</label>
                        <input type="text" class="form-control" id="title_zh" name="title_zh" 
                               value="<?= htmlspecialchars($report['title_zh'] ?? '') ?>" 
                               placeholder="<?= __('chinese_title_placeholder') ?>">
                        <div class="form-text"><?= __('chinese_title_hint') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content_zh" class="form-label"><?= __('content') ?> (<?= __('chinese') ?>)</label>
                        <textarea class="form-control" id="content_zh" name="content_zh" rows="8" 
                                  placeholder="<?= __('chinese_content_placeholder') ?>"><?= htmlspecialchars($report['content_zh'] ?? '') ?></textarea>
                        <div class="form-text"><?= __('chinese_content_hint') ?></div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/work/public/reports" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i><?= __('back') ?>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-<?= $isEdit ? 'save' : 'plus' ?> me-1"></i>
                            <?= $isEdit ? __('save') : __('create_report') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Auto-resize textarea
document.getElementById('content').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

document.getElementById('content_zh').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Character counter
document.getElementById('content').addEventListener('input', function() {
    const maxLength = 5000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    let counter = document.getElementById('char-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'char-counter';
        counter.className = 'form-text text-end';
        this.parentNode.appendChild(counter);
    }
    counter.textContent = `${currentLength}/${maxLength} ${__('characters')}`;
    if (remaining < 100) {
        counter.className = 'form-text text-end text-warning';
    } else {
        counter.className = 'form-text text-end';
    }
    if (currentLength > maxLength) {
        counter.className = 'form-text text-end text-danger';
    }
});

// Auto-translate button
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const titleZhInput = document.getElementById('title_zh');
    const contentZhInput = document.getElementById('content_zh');
    
    // Thêm nút dịch tự động
    if (titleInput && titleZhInput) {
        const translateTitleBtn = document.createElement('button');
        translateTitleBtn.type = 'button';
        translateTitleBtn.className = 'btn btn-sm btn-outline-info mt-1';
        translateTitleBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        translateTitleBtn.onclick = function() {
            if (titleInput.value.trim()) {
                translateTitleBtn.disabled = true;
                translateTitleBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('translating') ?>...';
                
                // Gọi API dịch
                fetch('/work/public/translate', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        text: titleInput.value,
                        target_lang: 'zh'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        titleZhInput.value = data.translation;
                    }
                    translateTitleBtn.disabled = false;
                    translateTitleBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
                })
                .catch(error => {
                    console.error('Translation error:', error);
                    translateTitleBtn.disabled = false;
                    translateTitleBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
                });
            }
        };
        titleInput.parentNode.appendChild(translateTitleBtn);
    }
    
    if (contentInput && contentZhInput) {
        const translateContentBtn = document.createElement('button');
        translateContentBtn.type = 'button';
        translateContentBtn.className = 'btn btn-sm btn-outline-info mt-1';
        translateContentBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        translateContentBtn.onclick = function() {
            if (contentInput.value.trim()) {
                translateContentBtn.disabled = true;
                translateContentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('translating') ?>...';
                
                // Gọi API dịch
                fetch('/work/public/translate', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        text: contentInput.value,
                        target_lang: 'zh'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        contentZhInput.value = data.translation;
                    }
                    translateContentBtn.disabled = false;
                    translateContentBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
                })
                .catch(error => {
                    console.error('Translation error:', error);
                    translateContentBtn.disabled = false;
                    translateContentBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
                });
            }
        };
        contentInput.parentNode.appendChild(translateContentBtn);
    }
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 