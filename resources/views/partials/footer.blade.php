<!-- resources/views/partials/footer.blade.php -->
<!-- نوار نقل قول -->
<div class="quote-ribbon">
    <div class="container">
        <div class="quote-compact">
            <div class="quote-icon" aria-hidden="true">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="quote-content">
                <blockquote class="quote-text">
                    <span class="quote-highlight">
                        مبلغی که بابت خرید کتاب می‌پردازیم به مراتب پایین‌تر از هزینه‌هایی است که در آینده بابت نخواندن آن خواهیم پرداخت.
                    </span>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<footer class="footer-modern">
    <div class="footer-background" aria-hidden="true"></div>
    <div class="container footer-content">
        <div class="row">
            <!-- بخش اول - درباره ما -->
            <div class="col-lg-5 col-md-12 mb-4">
                <h2 class="footer-title">بَلیان</h2>
                <div class="footer-desc">
                    <p>
                        کتابخانه دیجیتال بلیان، سامانه‌ای برای معرفی و دسترسی به کتاب‌ها و مقالات است. ما تلاش می‌کنیم منابع مفید و معتبر را به شما معرفی کنیم و در صورت امکان، لینک دانلود آنها را نیز ارائه دهیم.
                    </p>
                    <p>
                        تمامی محتوای این سایت مطابق با قوانین جمهوری اسلامی ایران منتشر می‌شود. نویسندگان و ناشران می‌توانند از طریق بخش تماس با ما، درخواست حذف اثر خود را ثبت نمایند.
                        <a href="{{ route('dmca') }}" class="dmca-link">[DMCA Policy]</a>
                    </p>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="تلگرام">
                        <i class="fab fa-telegram-plane" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="اینستاگرام">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="توییتر">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="لینکدین">
                        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12">
                <div class="row footer-links-row">
                    <!-- بخش دوم - خدمات کاربران و دسترسی سریع در یک ردیف -->
                    <div class="col-lg-8 col-md-8 mb-4">
                        <div class="row">
                            <!-- خدمات کاربران -->
                            <div class="col-sm-6 footer-links-column">
                                <h3 class="footer-title">خدمات کاربران</h3>
                                <nav aria-label="خدمات کاربران">
                                    <ul class="footer-links">
                                        <li><a href="#"><i class="fas fa-file-pdf" aria-hidden="true"></i>باز کردن فایل کتاب</a></li>
                                        <li><a href="#"><i class="fas fa-shipping-fast" aria-hidden="true"></i>پیگیری سفارش</a></li>
                                        <li><a href="#"><i class="fas fa-undo" aria-hidden="true"></i>بازگردانی محصول</a></li>
                                        <li><a href="{{ route('book.request') }}"><i class="fas fa-book-medical" aria-hidden="true"></i>درخواست کتاب</a></li>
                                        <li><a href="{{ route('article.request') }}"><i class="fas fa-newspaper" aria-hidden="true"></i>درخواست مقاله</a></li>
                                        <li><a href="#"><i class="fas fa-language" aria-hidden="true"></i>درخواست ترجمه</a></li>
                                    </ul>
                                </nav>
                            </div>

                            <!-- دسترسی سریع -->
                            <div class="col-sm-6 footer-links-column">
                                <h3 class="footer-title">دسترسی سریع</h3>
                                <nav aria-label="دسترسی سریع">
                                    <ul class="footer-links">
                                        <li><a href="{{ route('topics') }}"><i class="fas fa-book" aria-hidden="true"></i>دسته‌بندی کتاب‌ها</a></li>
                                        <li><a href="{{ route('terms') }}"><i class="fas fa-gavel" aria-hidden="true"></i>قوانین و مقررات</a></li>
                                        <li><a href="#"><i class="fas fa-crown" aria-hidden="true"></i>اشتراک ویژه</a></li>
                                        <li><a href="#"><i class="fas fa-tags" aria-hidden="true"></i>تخفیف‌ها</a></li>
                                        <li><a href="{{ route('about') }}"><i class="fas fa-info-circle" aria-hidden="true"></i>درباره ما</a></li>
                                        <li><a href="{{ route('contact') }}"><i class="fas fa-envelope" aria-hidden="true"></i>تماس با ما</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- بخش چهارم - مجوزها -->
                    <div class="col-lg-4 col-md-4 mb-4">
                        <h3 class="footer-title">مجوزهای رسمی</h3>
                        <div class="certificates-container">
                            <div class="certificate-box">
                                <h4 class="certificate-title">نماد اعتماد</h4>
                                <div class="certificate-placeholder">
                                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="certificate-box">
                                <h4 class="certificate-title">مجوز ارشاد</h4>
                                <div class="certificate-placeholder">
                                    <i class="fas fa-certificate" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="certificate-box">
                                <h4 class="certificate-title">ساماندهی</h4>
                                <div class="certificate-placeholder">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="certificate-box">
                                <h4 class="certificate-title">نشان ملی</h4>
                                <div class="certificate-placeholder">
                                    <i class="fas fa-award" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-1 order-2 text-md-start text-center mt-3 mt-md-0">
                    <p class="copyright">© {{ date('Y') }} کتابخانه دیجیتال بلیان | طراحی با <i class="fas fa-heart text-danger" aria-hidden="true"></i> برای دوستداران کتاب</p>
                </div>

                <div class="col-md-6 order-md-2 order-1 mb-md-0">
                    <nav aria-label="لینک‌های قانونی">
                        <ul class="footer-bottom-links">
                            <li><a href="{{ route('privacy') }}" class="footer-bottom-link">حریم خصوصی</a></li>
                            <li><a href="{{ route('terms') }}" class="footer-bottom-link">قوانین استفاده</a></li>
                            <li><a href="{{ route('faq') }}" class="footer-bottom-link">سوالات متداول</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // اضافه کردن افکت تایپ به متن
            const quoteText = document.querySelector('.quote-text');

            // نمایش متن با تاخیر کوتاه برای جلب توجه
            setTimeout(() => {
                if (quoteText) {
                    quoteText.style.opacity = '1';
                }
            }, 300);
        });
    </script>
@endpush
