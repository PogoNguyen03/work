<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Chi tiết báo cáo
ob_start();
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i><?= __('report_detail') ?>
                    </h4>
                    <!-- <div class="btn-group">
                        <a href="/work/public/reports" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <?php if (canDeleteReport($report['user_id'], $report['department_id'])): ?>
                        <a href="/work/public/reports/edit?id=<?= $report['id'] ?>" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                        <?php endif; ?>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <!-- Report Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary"><?= htmlspecialchars(getContentInCurrentLang($report['title'], $report['title_zh'] ?? null)) ?></h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user me-1"></i>
                            <strong><?= __('reporter') ?>:</strong> <?= htmlspecialchars(getContentInCurrentLang($report['user_name'], $report['user_name_zh'])) ?>
                        </p>
                        <p class="text-muted mb-0">
                            <?php 
                            $roleNames = [
                                'admin' => __('admin'),
                                'quanly' => __('manager'),
                                'nhomtruong' => __('team_leader'),
                                'user' => __('employee')
                            ];
                            $roleClass = [
                                'admin' => 'bg-danger',
                                'quanly' => 'bg-warning',
                                'nhomtruong' => 'bg-info',
                                'user' => 'bg-secondary'
                            ];
                            ?>
                            <i class="fas fa-user-tag me-1"></i>
                            <strong><?= __('role') ?>:</strong> 
                            <span class="badge <?= $roleClass[$report['user_role']] ?? 'bg-secondary' ?>">
                                <?= $roleNames[$report['user_role']] ?? 'N/A' ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i>
                            <strong><?= __('created_at') ?>:</strong> <?= date('d/m/Y H:i', strtotime($report['created_at'])) ?>
                        </p>
                        <?php if ($report['updated_at'] && $report['updated_at'] != $report['created_at']): ?>
                        <p class="text-muted mb-0">
                            <i class="fas fa-edit me-1"></i>
                            <strong><?= __('updated_at') ?>:</strong> <?= date('d/m/Y H:i', strtotime($report['updated_at'])) ?>
                            <span class="badge bg-info ms-1"><?= __('updated') ?></span>
                        </p>
                        <?php endif; ?>
                        <?php if ($report['department_name']): ?>
                        <p class="text-muted mb-0">
                            <i class="fas fa-building me-1"></i>
                            <strong><?= __('department') ?>:</strong> <?= htmlspecialchars($report['department_name']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Report Content -->
                <div class="border-top pt-4">
                    <h6 class="text-muted mb-3"><?= __('content') ?>:</h6>
                    <div class="bg-light p-4 rounded">
                        <pre class="report-content"><?= htmlspecialchars(getContentInCurrentLang($report['content'], $report['content_zh'] ?? null)) ?></pre>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between">
                        <a href="/work/public/reports" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i><?= __('back') ?>
                        </a>
                        <div class="btn-group">
                            <?php if (canEditReportByRole($report['user_id'], $report['user_role'], $report['department_id'])): ?>
                            <a href="/work/public/reports/edit?id=<?= $report['id'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i><?= __('edit') ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (canDeleteReportByRole($report['user_id'], $report['user_role'], $report['department_id'])): ?>
                            <a href="/work/public/reports?delete=<?= $report['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('<?= __('confirm_delete') ?>')">
                                <i class="fas fa-trash me-1"></i><?= __('delete') ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.report-content {
    line-height: 1.6;
    font-family: inherit;
    background: #f8f9fa;
    border-radius: 0.5rem;
    font-size: 1rem;
}
</style>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 