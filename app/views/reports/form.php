<?php
$base_path = realpath(__DIR__ . '/../../..');
require_once $base_path . '/app/helpers/i18n.php';
// View: Form báo cáo
ob_start();
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?> me-2"></i>
                        <?= $isEdit ? __('edit_report') : __('create_report') ?>
                    </h4>
                    <?php if ($isEdit): ?>
                    <button type="button" class="btn btn-outline-info" id="toggle-auto-translate-btn">
                        <i class="fas fa-language me-1"></i> <span id="auto-translate-label"><?= __('auto_translate') ?></span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="/reports/<?= $isEdit ? 'update' : 'store' ?>" data-ajax="true">
                    <input type="hidden" id="auto_translate_enabled" name="auto_translate_enabled" value="1">
                    <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $report['id'] ?>">
                    <?php endif; ?>
                    <?php
                    $lang = $_SESSION['lang'] ?? 'vi';
                    ?>
                    <?php if ($isEdit): ?>
                    <div id="auto-translate-warning" class="alert alert-warning py-2 px-3 mb-3" style="display:none; font-size:0.95em;">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?= __('auto_translate_warning') ?>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <label for="title" class="form-label mb-0 flex-grow-1">
                                <?= __('title') ?> <span class="text-danger">*</span>
                            </label>
                            <?php if ($lang === 'vi'): ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="show-title-zh-btn" tabindex="-1"><?= __('show_chinese_field') ?></button>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="show-title-vi-btn" tabindex="-1"><?= __('show_vietnamese_field') ?></button>
                            <?php endif; ?>
                        </div>
                        <?php if ($lang === 'vi'): ?>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($report['title'] ?? '') ?>" placeholder="<?= __('report_title_placeholder') ?> (<?= __('vietnamese') ?>)" required>
                            <input type="text" class="form-control mt-2" id="title_zh" name="title_zh" value="<?= htmlspecialchars($report['title_zh'] ?? '') ?>" placeholder="<?= __('report_title_placeholder') ?> (<?= __('chinese') ?>)" style="display:none;">
                        <?php else: ?>
                            <input type="text" class="form-control" id="title_zh" name="title_zh" value="<?= htmlspecialchars($report['title_zh'] ?? '') ?>" placeholder="<?= __('report_title_placeholder') ?> (<?= __('chinese') ?>)" required>
                            <input type="text" class="form-control mt-2" id="title" name="title" value="<?= htmlspecialchars($report['title'] ?? '') ?>" placeholder="<?= __('report_title_placeholder') ?> (<?= __('vietnamese') ?>)" style="display:none;">
                        <?php endif; ?>
                        <div class="form-text">
                            <?= __('report_title_hint') ?> (<?= $lang === 'vi' ? __('vietnamese') : __('chinese') ?>)
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <label for="content" class="form-label mb-0 flex-grow-1">
                                <?= __('content') ?> <span class="text-danger">*</span>
                            </label>
                            <?php if ($lang === 'vi'): ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="show-content-zh-btn" tabindex="-1"><?= __('show_chinese_field') ?></button>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="show-content-vi-btn" tabindex="-1"><?= __('show_vietnamese_field') ?></button>
                            <?php endif; ?>
                        </div>
                        <?php if ($lang === 'vi'): ?>
                            <textarea class="form-control" id="content" name="content" rows="8" placeholder="<?= __('report_content_placeholder') ?> (<?= __('vietnamese') ?>)" required><?= htmlspecialchars($report['content'] ?? '') ?></textarea>
                            <textarea class="form-control mt-2" id="content_zh" name="content_zh" rows="8" placeholder="<?= __('report_content_placeholder') ?> (<?= __('chinese') ?>)" style="display:none;"><?= htmlspecialchars($report['content_zh'] ?? '') ?></textarea>
                        <?php else: ?>
                            <textarea class="form-control" id="content_zh" name="content_zh" rows="8" placeholder="<?= __('report_content_placeholder') ?> (<?= __('chinese') ?>)" required><?= htmlspecialchars($report['content_zh'] ?? '') ?></textarea>
                            <textarea class="form-control mt-2" id="content" name="content" rows="8" placeholder="<?= __('report_content_placeholder') ?> (<?= __('vietnamese') ?>)" style="display:none;"><?= htmlspecialchars($report['content'] ?? '') ?></textarea>
                        <?php endif; ?>
                        <div class="form-text">
                            <strong><?= __('report_suggestion') ?>:</strong> <?= __('report_should_include') ?>
                            <ul class="mb-0 mt-1">
                                <li><?= __('report_task_done_today') ?></li>
                                <li><?= __('report_result_progress') ?></li>
                                <li><?= __('report_difficulties') ?></li>
                                <li><?= __('report_plan_tomorrow') ?></li>
                            </ul>
                            (<?= $lang === 'vi' ? __('vietnamese') : __('chinese') ?>)
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="/reports" class="btn btn-secondary">
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
let autoTranslateEnabled = <?= $isEdit ? 'false' : 'true' ?>;
const autoTranslateInput = document.getElementById('auto_translate_enabled');
<?php if ($isEdit): ?>
const toggleBtn = document.getElementById('toggle-auto-translate-btn');
const autoLabel = document.getElementById('auto-translate-label');
const autoWarning = document.getElementById('auto-translate-warning');
if (toggleBtn) {
    toggleBtn.onclick = function() {
        autoTranslateEnabled = !autoTranslateEnabled;
        autoTranslateInput.value = autoTranslateEnabled ? '1' : '0';
        if (autoTranslateEnabled) {
            toggleBtn.classList.add('btn-success');
            toggleBtn.classList.remove('btn-outline-info');
            autoLabel.textContent = '<?= __('auto_translate_on') ?>';
            autoWarning.style.display = '';
        } else {
            toggleBtn.classList.remove('btn-success');
            toggleBtn.classList.add('btn-outline-info');
            autoLabel.textContent = '<?= __('auto_translate') ?>';
            autoWarning.style.display = 'none';
        }
    };
    if (autoTranslateEnabled) autoWarning.style.display = '';
    autoTranslateInput.value = autoTranslateEnabled ? '1' : '0';
}
// Hiện trường còn lại để nhập tay
const showTitleZhBtn = document.getElementById('show-title-zh-btn');
const showTitleViBtn = document.getElementById('show-title-vi-btn');
const titleZhInput = document.getElementById('title_zh');
const titleViInput = document.getElementById('title');
if (showTitleZhBtn && titleZhInput) {
    showTitleZhBtn.onclick = function() {
        titleZhInput.style.display = '';
        titleZhInput.focus();
        showTitleZhBtn.style.display = 'none';
        setupAutoTranslateInput(titleZhInput, titleViInput, 'zh', 'vi');
    };
}
if (showTitleViBtn && titleViInput) {
    showTitleViBtn.onclick = function() {
        titleViInput.style.display = '';
        titleViInput.focus();
        showTitleViBtn.style.display = 'none';
        setupAutoTranslateInput(titleViInput, titleZhInput, 'vi', 'zh');
    };
}
const showContentZhBtn = document.getElementById('show-content-zh-btn');
const showContentViBtn = document.getElementById('show-content-vi-btn');
const contentZhInput = document.getElementById('content_zh');
const contentViInput = document.getElementById('content');
if (showContentZhBtn && contentZhInput) {
    showContentZhBtn.onclick = function() {
        contentZhInput.style.display = '';
        contentZhInput.focus();
        showContentZhBtn.style.display = 'none';
        setupAutoTranslateInput(contentZhInput, contentViInput, 'zh', 'vi');
    };
}
if (showContentViBtn && contentViInput) {
    showContentViBtn.onclick = function() {
        contentViInput.style.display = '';
        contentViInput.focus();
        showContentViBtn.style.display = 'none';
        setupAutoTranslateInput(contentViInput, contentZhInput, 'vi', 'zh');
    };
}
// Hàm lắng nghe input và dịch hai chiều
defaultLang = '<?= $lang ?>';
function setupAutoTranslateInput(mainInput, otherInput, mainLang, otherLang) {
    if (!mainInput || !otherInput) return;
    mainInput.addEventListener('input', function() {
        if (autoTranslateEnabled) {
            const text = mainInput.value.trim();
            if (!text) return;
            fetch('/translate', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ text, target_lang: otherLang })
            })
            .then(response => response.json())
            .then(data => {
                if (autoTranslateEnabled && data.translation) {
                    otherInput.value = data.translation;
                }
            });
        }
    });
}
// Gắn event cho cả 4 trường (nếu đã hiện)
setupAutoTranslateInput(titleViInput, titleZhInput, 'vi', 'zh');
setupAutoTranslateInput(titleZhInput, titleViInput, 'zh', 'vi');
setupAutoTranslateInput(contentViInput, contentZhInput, 'vi', 'zh');
setupAutoTranslateInput(contentZhInput, contentViInput, 'zh', 'vi');
<?php endif; ?>

function detectLang(text) {
    if (/[ - ]/.test(text) && /[ăâđêôơưáàảãạấầẩẫậắằẳẵặéèẻẽẹếềểễệíìỉĩịóòỏõọốồổỗộớờởỡợúùủũụứừửữựýỳỷỹỵ]/i.test(text)) {
        return 'vi';
    }
    if (/[ - ]/.test(text) && /[\u4e00-\u9fff]/.test(text)) {
        return 'zh';
    }
    return 'vi';
}

<?php if ($lang === 'vi'): ?>
const titleInput = document.getElementById('title');
const contentInput = document.getElementById('content');
<?php else: ?>
const titleInput = document.getElementById('title_zh');
const contentInput = document.getElementById('content_zh');
<?php endif; ?>

if (titleInput) {
    titleInput.addEventListener('input', function() {
        if (autoTranslateEnabled) {
        const text = titleInput.value.trim();
        if (!text) return;
            const targetLang = '<?= $lang === 'vi' ? 'zh' : 'vi' ?>';
            fetch('/translate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ text, target_lang: targetLang })
        })
        .then(response => response.json())
        .then(data => {
                if (autoTranslateEnabled) {
                    if (targetLang === 'zh') {
                        let zhInput = document.getElementById('title_zh');
                        if (zhInput) zhInput.value = data.translation || '';
            } else {
                        let viInput = document.getElementById('title');
                        if (viInput) viInput.value = data.translation || '';
                    }
                }
        });
        }
    });
}
if (contentInput) {
    contentInput.addEventListener('input', function() {
        if (autoTranslateEnabled) {
        const text = contentInput.value.trim();
        if (!text) return;
            const targetLang = '<?= $lang === 'vi' ? 'zh' : 'vi' ?>';
            fetch('/translate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ text, target_lang: targetLang })
        })
        .then(response => response.json())
        .then(data => {
                if (autoTranslateEnabled) {
                    if (targetLang === 'zh') {
                        let zhInput = document.getElementById('content_zh');
                        if (zhInput) zhInput.value = data.translation || '';
            } else {
                        let viInput = document.getElementById('content');
                        if (viInput) viInput.value = data.translation || '';
                    }
                }
        });
        }
    });
}
</script>
<?php
$content = ob_get_clean();
include $base_path . '/app/views/layouts/main.php'; 