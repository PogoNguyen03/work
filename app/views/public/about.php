<?php include 'header.php'; ?>

<!-- Page Header -->
<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto hero-content">
                <h1 class="display-4 fw-bold mb-4"><?= $about_content['title'] ?? __('about_page_title') ?></h1>
                <p class="lead"><?= __('about_company_desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="content-area">
                            <?= $about_content['content'] ?? '<p>' . __('about_default_content') . '</p>' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3"><?= __('about_expert_team') ?></h2>
                <p class="lead text-muted"><?= __('about_expert_desc') ?></p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-tie fa-4x text-primary"></i>
                        </div>
                        <h4 class="card-title"><?= __('about_seo_expert') ?></h4>
                        <p class="card-text"><?= __('about_seo_expert_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-code fa-4x text-success"></i>
                        </div>
                        <h4 class="card-title"><?= __('about_developer') ?></h4>
                        <p class="card-text"><?= __('about_developer_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-pen-fancy fa-4x text-warning"></i>
                        </div>
                        <h4 class="card-title"><?= __('about_content_writer') ?></h4>
                        <p class="card-text"><?= __('about_content_writer_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3"><?= __('about_core_values') ?></h2>
                <p class="lead text-muted"><?= __('about_values_desc') ?></p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h5><?= __('about_reliability') ?></h5>
                    <p class="text-muted"><?= __('about_reliability_desc') ?></p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-lightbulb fa-3x text-success"></i>
                    </div>
                    <h5><?= __('about_creativity') ?></h5>
                    <p class="text-muted"><?= __('about_creativity_desc') ?></p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-warning"></i>
                    </div>
                    <h5><?= __('about_collaboration') ?></h5>
                    <p class="text-muted"><?= __('about_collaboration_desc') ?></p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-info"></i>
                    </div>
                    <h5><?= __('about_efficiency') ?></h5>
                    <p class="text-muted"><?= __('about_efficiency_desc') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4"><?= __('about_start_project') ?></h2>
        <p class="lead mb-4"><?= __('about_contact_free') ?></p>
        <a href="/work/public/contact" class="btn btn-light btn-lg">
            <i class="fas fa-phone me-2"></i><?= __('homepage_contact_now') ?>
        </a>
    </div>
</section>

<?php include 'footer.php'; ?> 