<!-- resources/views/partials/footer.blade.php -->
<footer class="footer-modern">
    <div class="footer-background"></div>
    <div class="container footer-content">
        <div class="row">
            <!-- بخش اول - درباره ما (افزایش عرض به 40%) -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="footer-brand">
                    <img src="{{ asset('images/logo-light.png') }}" alt="کتابخانه دیجیتال" height="40" class="mb-3">
                </div>
                <p class="footer-desc">دسترسی به هزاران کتاب الکترونیکی و صوتی در موضوعات مختلف. با ما در دنیای کتاب‌ها سفر کنید و از تجربه مطالعه دیجیتال لذت ببرید.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="تلگرام">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="اینستاگرام">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="توییتر">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="لینکدین">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- بخش‌های دوم و سوم - دسترسی سریع و خدمات ما (کاهش عرض) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="row footer-mobile-flex">
                    <!-- بخش دوم - دسترسی سریع -->
                    <div class="col-lg-6 footer-mobile-half">
                        <h3 class="footer-title">دسترسی سریع</h3>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i>صفحه اصلی</a></li>
                            <li><a href="{{ route('topics') }}"><i class="fas fa-book"></i>دسته‌بندی کتاب‌ها</a></li>
                            <li><a href="#"><i class="fas fa-star"></i>کتاب‌های پرفروش</a></li>
                            <li><a href="#"><i class="fas fa-tags"></i>تخفیف‌ها و پیشنهادها</a></li>
                            <li><a href="{{ route('about') }}"><i class="fas fa-info-circle"></i>درباره ما</a></li>
                        </ul>
                    </div>

                    <!-- بخش سوم - خدمات ما -->
                    <div class="col-lg-6 footer-mobile-half">
                        <h3 class="footer-title">خدمات ما</h3>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-crown"></i>اشتراک ویژه</a></li>
                            <li><a href="#"><i class="fas fa-mobile-alt"></i>اپلیکیشن موبایل</a></li>
                            <li><a href="#"><i class="fas fa-headphones"></i>کتاب‌های صوتی</a></li>
                            <li><a href="{{ route('contact') }}"><i class="fas fa-envelope"></i>تماس با ما</a></li>
                            <li><a href="#"><i class="fas fa-question-circle"></i>سوالات متداول</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- بخش چهارم - مجوزها (کاهش عرض) -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h3 class="footer-title">مجوزهای رسمی</h3>
                <!-- کادرهای مجوز بازطراحی شده -->
                <div class="certificates-container">
                    <div class="certificate-box">
                        <h4 class="certificate-title">نماد اعتماد الکترونیکی</h4>
                        <div class="certificate-placeholder">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <div class="certificate-box">
                        <h4 class="certificate-title">مجوز وزارت ارشاد</h4>
                        <div class="certificate-placeholder">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                    <div class="certificate-box">
                        <h4 class="certificate-title">ساماندهی</h4>
                        <div class="certificate-placeholder">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="certificate-box">
                        <h4 class="certificate-title">نشان ملی</h4>
                        <div class="certificate-placeholder">
                            <i class="fas fa-award"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright">© {{ date('Y') }} کتابخانه دیجیتال | طراحی با <i class="fas fa-heart text-danger"></i> برای دوستداران کتاب</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-bottom-links">
                        <li><a href="#" class="footer-bottom-link">حریم خصوصی</a></li>
                        <li><a href="#" class="footer-bottom-link">قوانین استفاده</a></li>
                        <li><a href="#" class="footer-bottom-link">نقشه سایت</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
