<?php include '../app/views/layouts/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <?php include '../app/views/layouts/sidebar.php'; ?>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="dashboard-header py-3 border-bottom mb-3 bg-white sticky-top">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h2 mb-0">Quản lý email liên hệ</h1>
                </div>
            </div>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <div class="card mb-4">
                <div class="card-header"><b>Danh sách liên hệ</b></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Chủ đề</th>
                                    <th>Ngày gửi</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($email_list as $email): ?>
                                <tr<?= $email['is_read'] ? '' : ' class="table-warning"' ?>>
                                    <td><?= htmlspecialchars($email['name']) ?></td>
                                    <td><?= htmlspecialchars($email['email']) ?></td>
                                    <td><?= htmlspecialchars($email['subject']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($email['created_at'])) ?></td>
                                    <td><?= $email['is_read'] ? '<span class="badge bg-success">Đã đọc</span>' : '<span class="badge bg-secondary">Chưa đọc</span>' ?></td>
                                    <td>
                                        <a href="/work/public/email?id=<?= $email['id'] ?>" class="btn btn-sm btn-info">Xem</a>
                                        <form method="POST" action="" style="display:inline-block">
                                            <input type="hidden" name="id" value="<?= $email['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa email này?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php if ($email_detail): ?>
            <div class="card">
                <div class="card-header"><b>Chi tiết liên hệ</b></div>
                <div class="card-body">
                    <p><b>Họ tên:</b> <?= htmlspecialchars($email_detail['name']) ?></p>
                    <p><b>Email:</b> <?= htmlspecialchars($email_detail['email']) ?></p>
                    <p><b>Số điện thoại:</b> <?= htmlspecialchars($email_detail['phone']) ?></p>
                    <p><b>Công ty:</b> <?= htmlspecialchars($email_detail['company']) ?></p>
                    <p><b>Chủ đề:</b> <?= htmlspecialchars($email_detail['subject']) ?></p>
                    <p><b>Nội dung:</b><br><?= nl2br(htmlspecialchars($email_detail['message'])) ?></p>
                    <p><b>Ngày gửi:</b> <?= date('d/m/Y H:i', strtotime($email_detail['created_at'])) ?></p>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="id" value="<?= $email_detail['id'] ?>">
                        <input type="hidden" name="action" value="mark_read">
                        <?php if (!$email_detail['is_read']): ?>
                        <button type="submit" class="btn btn-success">Đánh dấu đã đọc</button>
                        <?php endif; ?>
                    </form>
                    <a href="/work/public/email" class="btn btn-secondary">Quay lại danh sách</a>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include '../app/views/layouts/footer.php'; ?> 