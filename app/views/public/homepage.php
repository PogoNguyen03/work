<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="display-4 fw-bold mb-4"><?= $homepage_content['title'] ?? 'Thiên Cơ Trí Liên SEO' ?></h1>
                <p class="lead mb-4"><?= $homepage_content['description'] ?? 'Công ty SEO hàng đầu Việt Nam - Tối ưu hóa website, tăng thứ hạng Google' ?></p>
                <div class="d-flex gap-3">
                    <a href="/contact" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i><?= __('homepage_free_consultation') ?>
                    </a>
                    <a href="/about" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i><?= __('homepage_learn_more') ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-chart-line" style="font-size: 300px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3"><?= __('why_choose_us') ?></h2>
                <p class="lead text-muted"><?= __('why_choose_us_desc') ?></p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title"><?= __('seo_onpage') ?></h4>
                        <p class="card-text"><?= __('seo_onpage_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-link fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title"><?= __('seo_offpage') ?></h4>
                        <p class="card-text"><?= __('seo_offpage_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-cogs fa-3x text-warning"></i>
                        </div>
                        <h4 class="card-title"><?= __('technical_seo') ?></h4>
                        <p class="card-text"><?= __('technical_seo_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-pen-fancy fa-3x text-info"></i>
                        </div>
                        <h4 class="card-title"><?= __('content_marketing') ?></h4>
                        <p class="card-text"><?= __('content_marketing_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-danger"></i>
                        </div>
                        <h4 class="card-title"><?= __('local_seo') ?></h4>
                        <p class="card-text"><?= __('local_seo_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-chart-bar fa-3x text-secondary"></i>
                        </div>
                        <h4 class="card-title"><?= __('homepage_detailed_reports') ?></h4>
                        <p class="card-text"><?= __('homepage_reports_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4"><?= __('homepage_brand_elevation') ?></h2>
                <div class="content-area">
                    <?= $homepage_content['content'] ?? '<p>' . __('homepage_default_content') . '</p>' ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-4"><?= __('homepage_success_stats') ?></h3>
                        <div class="row text-center">
                            <div class="col-6 mb-4">
                                <h2 class="fw-bold text-primary" data-target="500">0</h2>
                                <p class="text-muted"><?= __('homepage_successful_projects') ?></p>
                            </div>
                            <div class="col-6 mb-4">
                                <h2 class="fw-bold text-success" data-target="98">0</h2>
                                <p class="text-muted"><?= __('homepage_satisfied_clients') ?></p>
                            </div>
                            <div class="col-6 mb-4">
                                <h2 class="fw-bold text-warning" data-target="50">0</h2>
                                <p class="text-muted"><?= __('homepage_google_top10') ?></p>
                            </div>
                            <div class="col-6 mb-4">
                                <h2 class="fw-bold text-info" data-target="24">0</h2>
                                <p class="text-muted"><?= __('homepage_support_hours') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4"><?= __('homepage_ready_ranking') ?></h2>
        <p class="lead mb-4"><?= __('homepage_help_goals') ?></p>
        <a href="/contact" class="btn btn-light btn-lg">
            <i class="fas fa-phone me-2"></i><?= __('homepage_contact_now') ?>
        </a>
    </div>
</section>

<?php include 'footer.php'; ?> 