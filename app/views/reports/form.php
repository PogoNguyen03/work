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
                    <!-- Tiêu đề (có thể nhập tiếng Việt hoặc Trung) -->
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <?= __('title') ?> <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= htmlspecialchars($report['title'] ?? $report['title_zh'] ?? '') ?>" 
                                   placeholder="<?= __('report_title_placeholder') ?> (<?= __('vietnamese') ?>/<?= __('chinese') ?>)" required>
                            <button type="button" class="btn btn-outline-info" id="translate-title-btn">
                                <i class="fas fa-language me-1"></i><?= __('auto_translate') ?>
                            </button>
                        </div>
                        <div class="form-text">
                            <?= __('report_title_hint') ?> (<?= __('vietnamese') ?> hoặc <?= __('chinese') ?>)
                        </div>
                        <input type="text" class="form-control mt-2" id="title_translated" readonly style="display:none; background:#f6f6f6;">
                    </div>
                    <!-- Nội dung (có thể nhập tiếng Việt hoặc Trung) -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <?= __('content') ?> <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" id="content" name="content" rows="8" 
                                      placeholder="<?= __('report_content_placeholder') ?> (<?= __('vietnamese') ?>/<?= __('chinese') ?>)" required><?= htmlspecialchars($report['content'] ?? $report['content_zh'] ?? '') ?></textarea>
                            <button type="button" class="btn btn-outline-info" id="translate-content-btn">
                                <i class="fas fa-language me-1"></i><?= __('auto_translate') ?>
                            </button>
                        </div>
                        <div class="form-text">
                            <strong><?= __('report_suggestion') ?>:</strong> <?= __('report_should_include') ?>
                            <ul class="mb-0 mt-1">
                                <li><?= __('report_task_done_today') ?></li>
                                <li><?= __('report_result_progress') ?></li>
                                <li><?= __('report_difficulties') ?></li>
                                <li><?= __('report_plan_tomorrow') ?></li>
                            </ul>
                            (<?= __('vietnamese') ?> hoặc <?= __('chinese') ?>)
                        </div>
                        <textarea class="form-control mt-2" id="content_translated" rows="3" readonly style="display:none; background:#f6f6f6;"></textarea>
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
// Detect language (improved, Unicode-aware)
function detectLang(text) {
    // Nếu có ký tự tiếng Trung thì là zh
    if (/[ 00-	FFF]/.test(text)) {
        return 'zh';
    }
    // Nếu có nhiều dấu tiếng Việt thì là vi
    if (/[ăâđêôơưáàảãạấầẩẫậắằẳẵặéèẻẽẹếềểễệíìỉĩịóòỏõọốồổỗộớờởỡợúùủũụứừửữựýỳỷỹỵ]/i.test(text)) {
        return 'vi';
    }
    // Mặc định là vi
    return 'vi';
}

// Auto-resize textarea
const contentInput = document.getElementById('content');
if (contentInput) {
    contentInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    // Character counter
    contentInput.addEventListener('input', function() {
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
        counter.textContent = `${currentLength}/${maxLength} <?= __('characters') ?>`;
        if (remaining < 100) {
            counter.className = 'form-text text-end text-warning';
        } else {
            counter.className = 'form-text text-end';
        }
        if (currentLength > maxLength) {
            counter.className = 'form-text text-end text-danger';
        }
    });
}

// Translate title
const translateTitleBtn = document.getElementById('translate-title-btn');
const titleInput = document.getElementById('title');
const titleTranslated = document.getElementById('title_translated');
if (translateTitleBtn && titleInput && titleTranslated) {
    translateTitleBtn.onclick = function() {
        const text = titleInput.value.trim();
        if (!text) return;
        const lang = detectLang(text);
        const targetLang = lang === 'vi' ? 'zh' : 'vi';
        translateTitleBtn.disabled = true;
        translateTitleBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('translating') ?>...';
        fetch('/work/public/translate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ text, target_lang: targetLang })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                titleTranslated.value = data.translation;
                titleTranslated.style.display = '';
            } else {
                titleTranslated.value = data.error || 'Translation failed';
                titleTranslated.style.display = '';
            }
            translateTitleBtn.disabled = false;
            translateTitleBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        })
        .catch(error => {
            titleTranslated.value = 'Translation error';
            titleTranslated.style.display = '';
            translateTitleBtn.disabled = false;
            translateTitleBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        });
    };
}
// Translate content
const translateContentBtn = document.getElementById('translate-content-btn');
const contentTranslated = document.getElementById('content_translated');
if (translateContentBtn && contentInput && contentTranslated) {
    translateContentBtn.onclick = function() {
        const text = contentInput.value.trim();
        if (!text) return;
        const lang = detectLang(text);
        const targetLang = lang === 'vi' ? 'zh' : 'vi';
        translateContentBtn.disabled = true;
        translateContentBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?= __('translating') ?>...';
        fetch('/work/public/translate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ text, target_lang: targetLang })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                contentTranslated.value = data.translation;
                contentTranslated.style.display = '';
            } else {
                contentTranslated.value = data.error || 'Translation failed';
                contentTranslated.style.display = '';
            }
            translateContentBtn.disabled = false;
            translateContentBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        })
        .catch(error => {
            contentTranslated.value = 'Translation error';
            contentTranslated.style.display = '';
            translateContentBtn.disabled = false;
            translateContentBtn.innerHTML = '<i class="fas fa-language me-1"></i><?= __('auto_translate') ?>';
        });
    };
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 