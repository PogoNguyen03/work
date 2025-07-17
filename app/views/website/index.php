<?php include '../app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <?php include '../app/views/layouts/sidebar.php'; ?>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="dashboard-header py-3 border-bottom mb-3 bg-white sticky-top">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h2 mb-0"><?= $pageTitle ?></h1>
                    <div class="d-flex align-items-center gap-2">
                        <?php include __DIR__ . '/../components/language_selector.php'; ?>
                        <span class="badge bg-primary"><?= $_SESSION['user_name'] ?? 'User' ?></span>
                        <span class="badge bg-secondary ms-2"><?= ucfirst($_SESSION['role'] ?? 'user') ?></span>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['translation_message'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-language me-2"></i><?= $_SESSION['translation_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['translation_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Website Management Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="websiteTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="homepage-tab" data-bs-toggle="tab" data-bs-target="#homepage" type="button" role="tab">
                                <i class="fas fa-home me-2"></i>Trang chủ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Giới thiệu
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                <i class="fas fa-envelope me-2"></i>Liên hệ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview" type="button" role="tab">
                                <i class="fas fa-eye me-2"></i>Xem trước
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Cấu hình giao diện
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                                <i class="fas fa-envelope-open-text me-2"></i>Email liên hệ
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="websiteTabsContent">
                        <!-- Homepage Tab -->
                        <div class="tab-pane fade show active" id="homepage" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Chỉnh sửa trang chủ</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($has_chinese_homepage): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Đã có bản dịch tiếng Trung
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Chưa có bản dịch tiếng Trung
                                        </span>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="translateContent('homepage')">
                                        <i class="fas fa-language me-1"></i>Dịch sang tiếng Trung
                                    </button>
                                </div>
                            </div>
                            
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_homepage">
                                <input type="hidden" name="active_tab" id="active_tab_input" value="homepage">
                                
                                <div class="mb-3">
                                    <label for="homepage_title" class="form-label">Tiêu đề trang chủ</label>
                                    <input type="text" class="form-control" id="homepage_title" name="title" 
                                           value="<?= htmlspecialchars($homepage_content['title'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="homepage_description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="homepage_description" name="description" rows="3" required><?= htmlspecialchars($homepage_content['description'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="homepage_content" class="form-label">Nội dung trang chủ</label>
                                    <textarea class="form-control" id="homepage_content" name="content" rows="10" required><?= htmlspecialchars($homepage_content['content'] ?? '') ?></textarea>
                                    <div class="form-text">Sử dụng HTML để định dạng nội dung</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật trang chủ
                                </button>
                            </form>
                        </div>

                        <!-- About Tab -->
                        <div class="tab-pane fade" id="about" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Chỉnh sửa trang giới thiệu</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($has_chinese_about): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Đã có bản dịch tiếng Trung
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Chưa có bản dịch tiếng Trung
                                        </span>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="translateContent('about')">
                                        <i class="fas fa-language me-1"></i>Dịch sang tiếng Trung
                                    </button>
                                </div>
                            </div>
                            
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_about">
                                <input type="hidden" name="active_tab" id="active_tab_input" value="about">
                                
                                <div class="mb-3">
                                    <label for="about_title" class="form-label">Tiêu đề trang giới thiệu</label>
                                    <input type="text" class="form-control" id="about_title" name="title" 
                                           value="<?= htmlspecialchars($about_content['title'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="about_content" class="form-label">Nội dung trang giới thiệu</label>
                                    <textarea class="form-control" id="about_content" name="content" rows="15" required><?= htmlspecialchars($about_content['content'] ?? '') ?></textarea>
                                    <div class="form-text">Sử dụng HTML để định dạng nội dung</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật trang giới thiệu
                                </button>
                            </form>
                        </div>

                        <!-- Contact Tab -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Chỉnh sửa thông tin liên hệ</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($has_chinese_contact): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Đã có bản dịch tiếng Trung
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Chưa có bản dịch tiếng Trung
                                        </span>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="translateContent('contact')">
                                        <i class="fas fa-language me-1"></i>Dịch sang tiếng Trung
                                    </button>
                                </div>
                            </div>
                            
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_contact">
                                <input type="hidden" name="active_tab" id="active_tab_input" value="contact">
                                
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email liên hệ</label>
                                    <input type="email" class="form-control" id="contact_email" name="email" 
                                           value="<?= htmlspecialchars($contact_content['email'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="contact_phone" name="phone" 
                                           value="<?= htmlspecialchars($contact_content['phone'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_address" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" id="contact_address" name="address" rows="3" required><?= htmlspecialchars($contact_content['address'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_hours" class="form-label">Giờ làm việc</label>
                                    <input type="text" class="form-control" id="contact_hours" name="hours" value="<?= htmlspecialchars($contact_content['hours'] ?? '') ?>" placeholder="Thứ 2 - Thứ 6: 8:00 - 18:00, Thứ 7: 8:00 - 12:00">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mạng xã hội</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="facebook" placeholder="Facebook" value="<?= htmlspecialchars($contact_content['facebook'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="twitter" placeholder="Twitter" value="<?= htmlspecialchars($contact_content['twitter'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="linkedin" placeholder="LinkedIn" value="<?= htmlspecialchars($contact_content['linkedin'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="instagram" placeholder="Instagram" value="<?= htmlspecialchars($contact_content['instagram'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_map" class="form-label">Google Maps (iframe hoặc link nhúng)</label>
                                    <textarea class="form-control" id="contact_map" name="map" rows="2" placeholder="Dán mã nhúng Google Maps hoặc link"><?= htmlspecialchars($contact_content['map'] ?? '') ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật thông tin liên hệ
                                </button>
                            </form>
                        </div>

                        <!-- Preview Tab -->
                        <div class="tab-pane fade" id="preview" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Xem trước trang chủ</h5>
                                    <div class="border rounded p-3 bg-light">
                                        <h3><?= htmlspecialchars($homepage_content['title'] ?? '') ?></h3>
                                        <p class="text-muted"><?= htmlspecialchars($homepage_content['description'] ?? '') ?></p>
                                        <div><?= $homepage_content['content'] ?? '' ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Xem trước trang giới thiệu</h5>
                                    <div class="border rounded p-3 bg-light">
                                        <h3><?= htmlspecialchars($about_content['title'] ?? '') ?></h3>
                                        <div><?= $about_content['content'] ?? '' ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Thông tin liên hệ</h5>
                                    <div class="border rounded p-3 bg-light">
                                        <p><strong>Email:</strong> <?= htmlspecialchars($contact_content['email'] ?? '') ?></p>
                                        <p><strong>Điện thoại:</strong> <?= htmlspecialchars($contact_content['phone'] ?? '') ?></p>
                                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($contact_content['address'] ?? '') ?></p>
                                        <p><strong>Giờ làm việc:</strong> <?= htmlspecialchars($contact_content['hours'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <h5>Cấu hình giao diện</h5>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_settings">
                                <input type="hidden" name="active_tab" id="active_tab_input" value="settings">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_name" class="form-label">Tên website</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                                   value="<?= htmlspecialchars($website_settings['site_name'] ?? '') ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="primary_color" class="form-label">Màu chủ đạo</label>
                                            <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" 
                                                   value="<?= $website_settings['primary_color'] ?? '#1e40af' ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="font_family" class="form-label">Font chữ</label>
                                            <select class="form-select" id="font_family" name="font_family">
                                                <option value="Inter" <?= ($website_settings['font_family'] ?? '') === 'Inter' ? 'selected' : '' ?>>Inter</option>
                                                <option value="Roboto" <?= ($website_settings['font_family'] ?? '') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                                                <option value="Open Sans" <?= ($website_settings['font_family'] ?? '') === 'Open Sans' ? 'selected' : '' ?>>Open Sans</option>
                                                <option value="Poppins" <?= ($website_settings['font_family'] ?? '') === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="layout" class="form-label">Layout</label>
                                            <select class="form-select" id="layout" name="layout">
                                                <option value="A" <?= ($website_settings['layout'] ?? '') === 'A' ? 'selected' : '' ?>>Layout A</option>
                                                <option value="B" <?= ($website_settings['layout'] ?? '') === 'B' ? 'selected' : '' ?>>Layout B</option>
                                                <option value="C" <?= ($website_settings['layout'] ?? '') === 'C' ? 'selected' : '' ?>>Layout C</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logo</label>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                            <?php if (!empty($website_settings['logo'])): ?>
                                                <div class="mt-2">
                                                    <img src="/work/public/<?= $website_settings['logo'] ?>" alt="Logo" style="max-height: 50px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="banner" class="form-label">Banner</label>
                                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                                            <?php if (!empty($website_settings['banner'])): ?>
                                                <div class="mt-2">
                                                    <img src="/work/public/<?= $website_settings['banner'] ?>" alt="Banner" style="max-height: 100px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật cấu hình
                                </button>
                            </form>
                        </div>

                        <!-- Email Tab -->
                        <div class="tab-pane fade" id="email" role="tabpanel">
                            <h5>Email liên hệ</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Tên</th>
                                            <th>Email</th>
                                            <th>Chủ đề</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($email_list as $email): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($email['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($email['name']) ?></td>
                                            <td><?= htmlspecialchars($email['email']) ?></td>
                                            <td><?= htmlspecialchars($email['subject']) ?></td>
                                            <td>
                                                <?php if ($email['is_read']): ?>
                                                    <span class="badge bg-success">Đã đọc</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Chưa đọc</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" onclick="viewEmail(<?= $email['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="action" value="mark_read_contact_email">
                                                    <input type="hidden" name="id" value="<?= $email['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <input type="hidden" name="action" value="delete_contact_email">
                                                    <input type="hidden" name="id" value="<?= $email['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Email Detail Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="emailModalBody">
                <!-- Email content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Set active tab from URL hash
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`[data-bs-target="${hash}"]`);
        if (tab) {
            const tabInstance = new bootstrap.Tab(tab);
            tabInstance.show();
        }
    }
});

// Update active tab input when tab changes
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e) {
        const target = e.target.getAttribute('data-bs-target');
        document.getElementById('active_tab_input').value = target.substring(1);
    });
});

// View email detail
function viewEmail(id) {
    fetch(`/work/public/website?email_id=${id}`)
        .then(response => response.text())
        .then(html => {
            // Extract email detail from response (you might need to adjust this)
            document.getElementById('emailModalBody').innerHTML = 'Loading...';
            const modal = new bootstrap.Modal(document.getElementById('emailModal'));
            modal.show();
        });
}

// Translate content manually
function translateContent(page) {
    if (confirm('Bạn có muốn dịch nội dung sang tiếng Trung không?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'translate_content';
        
        const pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = page;
        
        const activeTabInput = document.createElement('input');
        activeTabInput.type = 'hidden';
        activeTabInput.name = 'active_tab';
        activeTabInput.value = page;
        
        form.appendChild(actionInput);
        form.appendChild(pageInput);
        form.appendChild(activeTabInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include '../app/views/layouts/footer.php'; ?> 