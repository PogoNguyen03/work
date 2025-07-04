<?php
$currentPage = 'reports';
// View: Danh sách báo cáo
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-file-alt me-2"></i><?= __('report_list') ?></h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="/work/public/reports/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i><?= __('create_report') ?>
        </a>
        <a href="<?= exportExcelUrl() ?>" class="btn btn-success ms-2">
            <i class="fa-solid fa-file-excel me-1"></i><?= __('export_excel') ?>
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/work/public/reports" class="row g-3">
            <?php if (isset($users) && ($role === 'admin' || $role === 'quanly' || $role === 'nhomtruong')): ?>
            <div class="col-md-4">
                <label for="user_id" class="form-label"><?= __('reporter') ?></label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">-- <?= __('all') ?> --</option>
                    <?php $users->data_seek(0); while ($u = $users->fetch_assoc()): ?>
                        <option value="<?= $u['id'] ?>" <?= (!empty($_GET['user_id']) && $_GET['user_id'] == $u['id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php endif; ?>
            <?php if (isset($departments) && $role === 'admin'): ?>
            <div class="col-md-4">
                <label for="department_id" class="form-label"><?= __('department') ?></label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">-- <?= __('all') ?> --</option>
                    <?php $departments->data_seek(0); while ($d = $departments->fetch_assoc()): ?>
                        <option value="<?= $d['id'] ?>" <?= (!empty($_GET['department_id']) && $_GET['department_id'] == $d['id']) ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
                <label for="from_date" class="form-label"><?= __('from_date') ?></label>
                <input type="date" class="form-control" id="from_date" name="from_date" 
                       value="<?= $_GET['from_date'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label"><?= __('to_date') ?></label>
                <input type="date" class="form-control" id="to_date" name="to_date" 
                       value="<?= $_GET['to_date'] ?? '' ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i><?= __('filter') ?>
                </button>
                <a href="/work/public/reports" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i><?= __('clear_filter') ?>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-body">
        <?php if (empty($reports)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted"><?= __('no_reports') ?></h5>
                <p class="text-muted"><?= __('create_first_report') ?></p>
                <a href="/work/public/reports/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i><?= __('create_report') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
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
                        <?php foreach ($reports as $report): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars(getContentInCurrentLang($report['title'], $report['title_zh'])) ?></div>
                                <small class="text-muted">
                                    <?= mb_substr(htmlspecialchars(getContentInCurrentLang($report['content'], $report['content_zh'])), 0, 100) ?>...
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= htmlspecialchars(getContentInCurrentLang($report['name'], $report['name_zh'])) ?></span>
                            </td>
                            <td>
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
                                <span class="badge <?= $roleClass[$report['user_role']] ?? 'bg-secondary' ?>">
                                    <?= $roleNames[$report['user_role']] ?? 'N/A' ?>
                                </span>
                            </td>
                            <?php if ($role === 'admin'): ?>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($report['department_name'] ?? 'N/A') ?></span>
                            </td>
                            <?php endif; ?>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($report['created_at'])) ?>
                                    <?php if (shouldShowUpdatedBadge($report['id'], $report['updated_at'], $report['created_at'])): ?>
                                    <br><span class="badge bg-info badge-sm">
                                        <?= date('d/m/Y H:i', strtotime($report['updated_at'])) ?> <?= __('updated') ?>
                                    </span>
                                    <?php endif; ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/work/public/reports/view?id=<?= $report['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="<?= __('view') ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if (canEditReportByRole($report['user_id'], $report['user_role'], $report['department_id'] ?? null)): ?>
                                    <a href="/work/public/reports/edit?id=<?= $report['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="<?= __('edit') ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (canDeleteReportByRole($report['user_id'], $report['user_role'], $report['department_id'] ?? null)): ?>
                                    <a href="/work/public/reports?delete=<?= $report['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="<?= __('delete') ?>"
                                       onclick="return confirm('<?= __('confirm_delete') ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&from_date=<?= $_GET['from_date'] ?? '' ?>&to_date=<?= $_GET['to_date'] ?? '' ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&from_date=<?= $_GET['from_date'] ?? '' ?>&to_date=<?= $_GET['to_date'] ?? '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&from_date=<?= $_GET['from_date'] ?? '' ?>&to_date=<?= $_GET['to_date'] ?? '' ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

function exportExcelUrl() {
    $params = $_GET;
    $params['action'] = 'export';
    return '/work/public/reports?' . http_build_query($params);
}
?> 