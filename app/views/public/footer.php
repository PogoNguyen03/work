    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-globe me-2"></i><?= __('footer_company_name') ?></h5>
                    <p class="text-muted" style="color: white!important;"><?= __('footer_company_desc') ?></p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5><?= __('footer_services') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><?= __('footer_seo_onpage') ?></a></li>
                        <li><a href="#"><?= __('footer_seo_offpage') ?></a></li>
                        <li><a href="#"><?= __('footer_technical_seo') ?></a></li>
                        <li><a href="#"><?= __('footer_content_marketing') ?></a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5><?= __('footer_contact') ?></h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i><?= $contact_content['email'] ?? 'info@thiencotrilien.com' ?></li>
                        <li><i class="fas fa-phone me-2"></i><?= $contact_content['phone'] ?? '0909.123.456' ?></li>
                        <li><i class="fas fa-map-marker-alt me-2"></i><?= $contact_content['address'] ?? 'Hà Nội, Việt Nam' ?></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5><?= __('footer_support') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><?= __('footer_faq') ?></a></li>
                        <li><a href="#"><?= __('footer_guide') ?></a></li>
                        <li><a href="#"><?= __('footer_pricing') ?></a></li>
                        <li><a href="#"><?= __('footer_free_consultation') ?></a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0"><?= __('footer_copyright') ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3" style="color: white!important;"><?= __('footer_privacy_policy') ?></a>
                    <a href="#" class="text-muted" style="color: white!important;"><?= __('footer_terms') ?></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(30, 64, 175, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'linear-gradient(135deg, #1e40af 0%, #3b82f6 100%)';
                navbar.style.backdropFilter = 'none';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            }
            
            updateCounter();
        }

        // Intersection Observer for counter animation
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    animateCounter(counter, target);
                    observer.unobserve(counter);
                }
            });
        }, observerOptions);

        // Observe counter elements
        document.querySelectorAll('[data-target]').forEach(counter => {
            observer.observe(counter);
        });
    </script>
</body>
</html> 