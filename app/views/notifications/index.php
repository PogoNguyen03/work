<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// View: Thông báo
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-bell me-2"></i><?= __('notifications') ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="/notifications/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i><?= __('create_notification') ?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= __('feature_in_development') ?></h5>
                    <p class="text-muted"><?= __('notifications_module_coming') ?></p>
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                <h6><?= __('system_notification') ?></h6>
                                <small class="text-muted"><?= __('admin_notification') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                                <h6><?= __('deadline_reminder') ?></h6>
                                <small class="text-muted"><?= __('task_notification') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h6><?= __('group_notification') ?></h6>
                                <small class="text-muted"><?= __('department_notification') ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-cog fa-2x text-info mb-2"></i>
                                <h6><?= __('notification_settings') ?></h6>
                                <small class="text-muted"><?= __('customize_settings') ?></small>
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