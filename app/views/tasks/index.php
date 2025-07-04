<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Quản lý công việc
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-tasks me-2"></i><?= __('tasks') ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="/tasks/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i><?= __('create_task') ?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= __('feature_in_development') ?></h5>
                    <p class="text-muted"><?= __('tasks_module_coming') ?></p>
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                                <h6><?= __('create_task') ?></h6>
                                <small class="text-muted"><?= __('assign_task') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6><?= __('track_progress') ?></h6>
                                <small class="text-muted"><?= __('update_status') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                                <h6><?= __('performance_report') ?></h6>
                                <small class="text-muted"><?= __('statistics_analysis') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-bell fa-2x text-info mb-2"></i>
                                <h6><?= __('notifications') ?></h6>
                                <small class="text-muted"><?= __('deadline_reminder') ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php'; 