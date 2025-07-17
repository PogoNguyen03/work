<?php include 'header.php'; ?>

<!-- Page Header -->
<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto hero-content">
                <h1 class="display-4 fw-bold mb-4"><?= __('contact_page_title') ?></h1>
                <p class="lead"><?= __('contact_page_desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4"><?= __('contact_info_title') ?></h3>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5><?= __('email') ?></h5>
                                <p class="mb-0"><?= $contact_content['email'] ?? 'info@thiencotrilien.com' ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5><?= __('contact_phone_label') ?></h5>
                                <p class="mb-0"><?= $contact_content['phone'] ?? '0909.123.456' ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5><?= __('contact_address_label') ?></h5>
                                <p class="mb-0"><?= $contact_content['address'] ?? 'Hà Nội, Việt Nam' ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5><?= __('contact_hours_label') ?></h5>
                                <p class="mb-0"><?= $contact_content['hours'] ?? 'Thứ 2 - Thứ 6: 8:00 - 18:00<br>Thứ 7: 8:00 - 12:00' ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3"><?= __('contact_follow_us') ?></h5>
                        <div class="social-links">
                            <?php if (!empty($contact_content['facebook'])): ?>
                                <a href="<?= htmlspecialchars($contact_content['facebook']) ?>" class="me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($contact_content['twitter'])): ?>
                                <a href="<?= htmlspecialchars($contact_content['twitter']) ?>" class="me-2" target="_blank"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($contact_content['linkedin'])): ?>
                                <a href="<?= htmlspecialchars($contact_content['linkedin']) ?>" class="me-2" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($contact_content['instagram'])): ?>
                                <a href="<?= htmlspecialchars($contact_content['instagram']) ?>" class="me-2" target="_blank"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4"><?= __('contact_send_message') ?></h3>
                        
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"> <?= $success_message ?> </div>
                        <?php endif; ?>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"> <?= $error_message ?> </div>
                        <?php endif; ?>

                        <form id="contactForm" method="POST" action="#">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label"><?= __('contact_full_name') ?></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label"><?= __('email') ?> *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label"><?= __('contact_phone_field') ?></label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company" class="form-label"><?= __('contact_company') ?></label>
                                    <input type="text" class="form-control" id="company" name="company">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label"><?= __('contact_subject') ?></label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value=""><?= __('contact_select_subject') ?></option>
                                    <option value="seo-consultation"><?= __('contact_seo_consultation') ?></option>
                                    <option value="website-optimization"><?= __('contact_website_optimization') ?></option>
                                    <option value="content-marketing"><?= __('contact_content_marketing') ?></option>
                                    <option value="technical-seo"><?= __('contact_technical_seo') ?></option>
                                    <option value="other"><?= __('contact_other') ?></option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label"><?= __('contact_message_content') ?></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required placeholder="<?= __('contact_message_placeholder') ?>"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        <?= __('contact_privacy_agreement') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i><?= __('contact_send_button') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3"><?= __('contact_location_title') ?></h2>
                <p class="lead text-muted"><?= __('contact_location_desc') ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9">
                            <?php if (!empty($contact_content['map'])): ?>
                                <?= $contact_content['map'] ?>
                            <?php else: ?>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.096484300132!2d105.78159831476825!3d21.02851178599432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab3b4220c2bd%3A0x1c9e359e2a4f618c!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgVGjDtG5nIHRpbiB2aWV0!5e0!3m2!1svi!2s!4v1640995200000!5m2!1svi!2s" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3"><?= __('contact_faq_title') ?></h2>
                <p class="lead text-muted"><?= __('contact_faq_desc') ?></p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                <?= __('contact_faq_question1') ?>
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= __('contact_faq_answer1') ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                <?= __('contact_faq_question2') ?>
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= __('contact_faq_answer2') ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                <?= __('contact_faq_question3') ?>
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= __('contact_faq_answer3') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?> 