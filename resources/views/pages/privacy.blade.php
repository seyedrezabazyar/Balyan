@extends('layouts.app')

@section('title', 'حریم خصوصی - کتابخانه دیجیتال بلیان')

@section('content')
    <!-- هدر صفحه -->
    <div class="privacy-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-user-shield privacy-hero-icon"></i>
                <h1 class="privacy-hero-title">سیاست حریم خصوصی</h1>
                <p class="privacy-hero-desc">ما متعهد به حفظ اطلاعات شخصی و حریم خصوصی شما هستیم</p>
                <div class="privacy-hero-update">
                    آخرین به‌روزرسانی: {{ date('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>

    <!-- منوی ناوبری -->
    <div class="privacy-nav">
        <div class="container">
            <div class="privacy-nav-items">
                <a href="#collection" class="privacy-nav-link"><i class="fas fa-database"></i> اطلاعات جمع‌آوری شده</a>
                <a href="#usage" class="privacy-nav-link"><i class="fas fa-chart-bar"></i> استفاده از اطلاعات</a>
                <a href="#sharing" class="privacy-nav-link"><i class="fas fa-share-alt"></i> اشتراک‌گذاری اطلاعات</a>
                <a href="#cookies" class="privacy-nav-link"><i class="fas fa-cookie"></i> کوکی‌ها</a>
                <a href="#security" class="privacy-nav-link"><i class="fas fa-lock"></i> امنیت اطلاعات</a>
                <a href="#rights" class="privacy-nav-link"><i class="fas fa-user-cog"></i> حقوق کاربران</a>
                <a href="#changes" class="privacy-nav-link"><i class="fas fa-sync"></i> تغییرات خط‌مشی</a>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- مقدمه -->
                <div class="section-header">
                    <i class="fas fa-info-circle section-icon"></i>
                    <h2>مقدمه</h2>
                </div>
                <div class="privacy-card mb-5">
                    <p>در کتابخانه دیجیتال بلیان، ما متعهد به حفظ حریم خصوصی کاربران خود هستیم. این سیاست حریم خصوصی نحوه جمع‌آوری، استفاده، افشا، و محافظت از اطلاعات شخصی شما را توضیح می‌دهد. با استفاده از وب‌سایت و خدمات ما، شما با شرایط این سیاست حریم خصوصی موافقت می‌کنید.</p>

                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>لطفاً این سیاست حریم خصوصی را با دقت مطالعه کنید تا درک کاملی از نحوه مدیریت اطلاعات شخصی‌تان توسط ما داشته باشید.</p>
                    </div>
                </div>

                <!-- بخش اطلاعات جمع‌آوری شده -->
                <section id="collection" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-database section-icon"></i>
                        <h2>اطلاعات جمع‌آوری شده</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما ممکن است انواع مختلفی از اطلاعات را از کاربران جمع‌آوری کنیم، از جمله:</p>
                        <ul class="privacy-list">
                            <li><strong>اطلاعات شخصی:</strong> نام، آدرس ایمیل، شماره تلفن، و اطلاعات تماس که هنگام ثبت‌نام یا خرید ارائه می‌دهید.</li>
                            <li><strong>اطلاعات پروفایل:</strong> نام کاربری، رمز عبور (به صورت رمزنگاری شده)، تصویر پروفایل، و تنظیمات حساب.</li>
                            <li><strong>اطلاعات تراکنش:</strong> جزئیات پرداخت، سوابق خرید، و اطلاعات کارت اعتباری/بانکی (ما اطلاعات کامل کارت‌های بانکی را ذخیره نمی‌کنیم).</li>
                            <li><strong>اطلاعات استفاده:</strong> تاریخچه مرور، کتاب‌های مشاهده شده، علاقه‌مندی‌ها، و تعامل با محتوا.</li>
                            <li><strong>اطلاعات دستگاه:</strong> نوع دستگاه، سیستم عامل، مرورگر، آدرس IP، و داده‌های مشابه برای بهبود خدمات.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش استفاده از اطلاعات -->
                <section id="usage" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar section-icon"></i>
                        <h2>استفاده از اطلاعات</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما از اطلاعات جمع‌آوری شده برای اهداف زیر استفاده می‌کنیم:</p>
                        <ul class="privacy-list">
                            <li>ارائه، بهبود و شخصی‌سازی خدمات و تجربه کاربری شما</li>
                            <li>پردازش تراکنش‌ها و ارسال اطلاعیه‌های مربوط به خرید‌ها</li>
                            <li>ارسال اطلاعیه‌های مهم در مورد حساب کاربری یا تغییرات در خدمات</li>
                            <li>ارسال خبرنامه، پیشنهادات، و محتوای بازاریابی (در صورت انتخاب شما)</li>
                            <li>تحلیل و بهبود عملکرد وب‌سایت و خدمات</li>
                            <li>شناسایی و پیشگیری از تقلب و سوء استفاده</li>
                            <li>پاسخ به درخواست‌ها و پشتیبانی کاربران</li>
                            <li>رعایت تعهدات قانونی</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش اشتراک‌گذاری اطلاعات -->
                <section id="sharing" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-share-alt section-icon"></i>
                        <h2>اشتراک‌گذاری اطلاعات</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما اطلاعات شخصی شما را با اشخاص ثالث به اشتراک نمی‌گذاریم، مگر در موارد زیر:</p>
                        <ul class="privacy-list">
                            <li><strong>ارائه‌دهندگان خدمات:</strong> ما با شرکای تجاری و ارائه‌دهندگان خدمات که برای ارائه خدمات ما ضروری هستند، همکاری می‌کنیم (مانند خدمات پرداخت، میزبانی وب، و پشتیبانی).</li>
                            <li><strong>الزامات قانونی:</strong> اطلاعات ممکن است در پاسخ به درخواست‌های قانونی مانند احضاریه دادگاه یا دستور قضایی افشا شود.</li>
                            <li><strong>حفاظت از حقوق:</strong> اگر به طور منطقی باور داشته باشیم که افشای اطلاعات برای حفاظت از حقوق، اموال، یا امنیت ما، کاربران ما، یا دیگران ضروری است.</li>
                            <li><strong>ادغام یا خرید:</strong> در صورت ادغام، خرید، یا فروش کل یا بخشی از کسب‌وکار ما، اطلاعات کاربران ممکن است بخشی از دارایی‌های منتقل شده باشد.</li>
                        </ul>
                        <div class="alert-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>ما هرگز اطلاعات شخصی شما را بدون اطلاع قبلی به صورت تجاری به اشخاص ثالث نمی‌فروشیم یا اجاره نمی‌دهیم.</p>
                        </div>
                    </div>
                </section>

                <!-- بخش کوکی‌ها -->
                <section id="cookies" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-cookie section-icon"></i>
                        <h2>کوکی‌ها و فناوری‌های ردیابی</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما از کوکی‌ها و فناوری‌های مشابه برای بهبود تجربه کاربری، تحلیل روند‌ها، و مدیریت وب‌سایت استفاده می‌کنیم. این فناوری‌ها اطلاعاتی درباره فعالیت‌های آنلاین شما جمع‌آوری می‌کنند.</p>

                        <h3 class="mt-4">انواع کوکی‌های مورد استفاده:</h3>
                        <ul class="privacy-list">
                            <li><strong>کوکی‌های ضروری:</strong> برای عملکرد اصلی وب‌سایت ضروری هستند.</li>
                            <li><strong>کوکی‌های عملکردی:</strong> برای به خاطر سپردن ترجیحات و بهبود تجربه کاربری استفاده می‌شوند.</li>
                            <li><strong>کوکی‌های تحلیلی:</strong> به ما کمک می‌کنند تا نحوه استفاده کاربران از وب‌سایت را درک کنیم.</li>
                            <li><strong>کوکی‌های تبلیغاتی:</strong> برای ارائه تبلیغات هدفمند استفاده می‌شوند.</li>
                        </ul>

                        <p class="mt-3">شما می‌توانید کوکی‌ها را از طریق تنظیمات مرورگر خود مدیریت کنید. با این حال، غیرفعال کردن برخی کوکی‌ها ممکن است بر تجربه کاربری شما تأثیر بگذارد.</p>
                    </div>
                </section>

                <!-- بخش امنیت اطلاعات -->
                <section id="security" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-lock section-icon"></i>
                        <h2>امنیت اطلاعات</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما اقدامات امنیتی مناسبی را برای محافظت از اطلاعات شخصی شما در برابر دسترسی غیرمجاز، تغییر، افشا یا تخریب اتخاذ می‌کنیم. این اقدامات شامل موارد زیر است:</p>

                        <div class="security-measures">
                            <div class="security-item">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <h3>رمزنگاری داده‌ها</h3>
                                    <p>استفاده از پروتکل SSL برای انتقال امن داده‌ها و رمزنگاری اطلاعات حساس.</p>
                                </div>
                            </div>

                            <div class="security-item">
                                <i class="fas fa-user-lock"></i>
                                <div>
                                    <h3>کنترل دسترسی</h3>
                                    <p>محدود کردن دسترسی به اطلاعات شخصی به کارکنان مجاز که نیاز به دانستن آن دارند.</p>
                                </div>
                            </div>

                            <div class="security-item">
                                <i class="fas fa-shield-virus"></i>
                                <div>
                                    <h3>سیستم‌های امنیتی</h3>
                                    <p>استفاده از فایروال‌ها، سیستم‌های تشخیص نفوذ و سایر اقدامات امنیتی.</p>
                                </div>
                            </div>

                            <div class="security-item">
                                <i class="fas fa-sync-alt"></i>
                                <div>
                                    <h3>بررسی‌های منظم</h3>
                                    <p>انجام ارزیابی‌های امنیتی منظم و بروزرسانی سیستم‌های امنیتی.</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mt-4">
                            <i class="fas fa-info-circle"></i>
                            <p>با وجود تلاش‌های ما، هیچ روش انتقال یا ذخیره‌سازی الکترونیکی 100٪ امن نیست. ما نمی‌توانیم امنیت کامل را تضمین کنیم، اما تمام تلاش خود را برای محافظت از اطلاعات شما انجام می‌دهیم.</p>
                        </div>
                    </div>
                </section>

                <!-- بخش حقوق کاربران -->
                <section id="rights" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-user-cog section-icon"></i>
                        <h2>حقوق کاربران</h2>
                    </div>
                    <div class="privacy-card">
                        <p>شما نسبت به اطلاعات شخصی خود دارای حقوق زیر هستید:</p>

                        <div class="user-rights">
                            <div class="right-item">
                                <i class="fas fa-eye"></i>
                                <span>حق دسترسی به اطلاعات شخصی خود</span>
                            </div>
                            <div class="right-item">
                                <i class="fas fa-edit"></i>
                                <span>حق اصلاح اطلاعات ناقص یا نادرست</span>
                            </div>
                            <div class="right-item">
                                <i class="fas fa-trash-alt"></i>
                                <span>حق درخواست حذف اطلاعات شخصی</span>
                            </div>
                            <div class="right-item">
                                <i class="fas fa-ban"></i>
                                <span>حق محدود کردن پردازش اطلاعات شخصی</span>
                            </div>
                            <div class="right-item">
                                <i class="fas fa-download"></i>
                                <span>حق دریافت اطلاعات شخصی در قالب قابل استفاده</span>
                            </div>
                            <div class="right-item">
                                <i class="fas fa-envelope"></i>
                                <span>حق لغو اشتراک ایمیل‌های بازاریابی</span>
                            </div>
                        </div>

                        <p class="mt-4">برای اعمال هر یک از این حقوق، لطفاً از طریق اطلاعات تماس ارائه شده در انتهای این سیاست با ما تماس بگیرید. ما به درخواست‌های شما در زمان مناسب و مطابق با قوانین مربوطه پاسخ خواهیم داد.</p>
                    </div>
                </section>

                <!-- بخش تغییرات خط‌مشی -->
                <section id="changes" class="privacy-section">
                    <div class="section-header">
                        <i class="fas fa-sync section-icon"></i>
                        <h2>تغییرات در سیاست حریم خصوصی</h2>
                    </div>
                    <div class="privacy-card">
                        <p>ما ممکن است این سیاست حریم خصوصی را به‌روزرسانی کنیم. هرگونه تغییر در این سیاست از طریق انتشار نسخه به‌روزرسانی شده روی این صفحه اطلاع‌رسانی می‌شود. تاریخ آخرین به‌روزرسانی در بالای این صفحه نشان داده می‌شود.</p>

                        <p>توصیه می‌کنیم به طور منظم این صفحه را بررسی کنید تا از تغییرات احتمالی مطلع شوید. تغییرات در این سیاست پس از انتشار در این صفحه اعمال می‌شود. استفاده مداوم از خدمات ما پس از انتشار تغییرات، به معنای پذیرش این تغییرات از سوی شماست.</p>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="privacy-footer">
                    <i class="fas fa-fingerprint"></i>
                    <h3>تعهد ما به حفظ حریم خصوصی شما</h3>
                    <p>کتابخانه دیجیتال بلیان متعهد به حفظ حریم خصوصی و امنیت اطلاعات شخصی کاربران است. ما به اعتماد شما ارزش می‌دهیم و تمام تلاش خود را برای حفاظت از اطلاعات شما انجام می‌دهیم.</p>
                </div>

                <!-- دکمه بازگشت به بالا -->
                <div id="backToTop">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* استایل‌های صفحه حریم خصوصی */
        :root {
            --primary: #3b82f6;
            --primary-light: rgba(59, 130, 246, 0.1);
            --primary-dark: #2563eb;
            --danger: #ef4444;
            --success: #10b981;
            --info: #0ea5e9;
            --warning: #f59e0b;
            --gray-800: #1f2937;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --light: #f9fafb;
        }

        /* هدر صفحه */
        .privacy-hero {
            background: linear-gradient(135deg, #0f766e 0%, #0e7490 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
            /*margin-bottom: 1rem; !* این خط باید تغییر کند *!*/
        }

        .privacy-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .privacy-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .privacy-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .privacy-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* منوی ناوبری */
        .privacy-nav {
            background-color: #fff;
            position: sticky;
            top: 70px;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .privacy-nav-items {
            display: flex;
            gap: 0.5rem;
            padding: 0.7rem 0;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .privacy-nav-items::-webkit-scrollbar {
            display: none;
        }

        .privacy-nav-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background-color: var(--light);
            color: var(--gray-700);
            font-size: 0.9rem;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .privacy-nav-link i {
            margin-left: 0.4rem;
            color: var(--info);
        }

        .privacy-nav-link:hover, .privacy-nav-link.active {
            background-color: var(--info);
            color: #fff;
        }

        .privacy-nav-link:hover i, .privacy-nav-link.active i {
            color: #fff;
        }

        /* بخش‌ها */
        .privacy-section {
            margin-bottom: 3rem;
            scroll-margin-top: 110px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .section-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .privacy-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .privacy-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: var(--gray-800);
        }

        /* لیست‌ها */
        .privacy-list {
            padding-right: 1.5rem;
            margin: 1rem 0;
        }

        .privacy-list li {
            margin-bottom: 0.8rem;
            line-height: 1.6;
        }

        /* باکس‌های اطلاعات */
        .info-box {
            background-color: rgba(14, 165, 233, 0.1);
            border-right: 4px solid var(--info);
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            display: flex;
            align-items: flex-start;
        }

        .info-box i {
            font-size: 1.2rem;
            color: var(--info);
            margin-left: 0.8rem;
        }

        .alert-box {
            background-color: rgba(239, 68, 68, 0.08);
            border-right: 4px solid var(--danger);
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            display: flex;
            align-items: flex-start;
        }

        .alert-box i {
            font-size: 1.2rem;
            color: var(--danger);
            margin-left: 0.8rem;
        }

        /* اقدامات امنیتی */
        .security-measures {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.2rem;
            margin-top: 1.5rem;
        }

        .security-item {
            display: flex;
            align-items: flex-start;
            background-color: var(--light);
            border-radius: 10px;
            padding: 1.2rem;
            gap: 0.8rem;
            border: 1px solid var(--gray-200);
        }

        .security-item i {
            font-size: 1.5rem;
            color: var(--info);
        }

        .security-item h3 {
            font-size: 1.1rem;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        .security-item p {
            font-size: 0.95rem;
            margin: 0;
            color: var(--gray-600);
        }

        /* حقوق کاربران */
        .user-rights {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .right-item {
            display: flex;
            align-items: center;
            background-color: var(--light);
            border-radius: 8px;
            padding: 1rem;
            gap: 0.8rem;
        }

        .right-item i {
            color: var(--info);
            font-size: 1.2rem;
        }

        /* اطلاعات تماس */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-top: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            background-color: var(--light);
            border-radius: 10px;
            padding: 1.2rem;
            gap: 0.8rem;
            border: 1px solid var(--gray-200);
        }

        .contact-item i {
            font-size: 1.5rem;
            color: var(--info);
        }

        .contact-item h3 {
            font-size: 1.1rem;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        .contact-item p {
            font-size: 0.95rem;
            margin: 0;
            color: var(--gray-600);
        }

        /* بخش پایانی */
        .privacy-footer {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .privacy-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .privacy-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .privacy-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* دکمه بازگشت به بالا */
        #backToTop {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 99;
        }

        #backToTop.visible {
            opacity: 1;
            visibility: visible;
        }

        #backToTop:hover {
            transform: translateY(-3px);
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .privacy-hero-title {
                font-size: 1.8rem;
            }

            .section-icon {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }

            .section-header h2 {
                font-size: 1.3rem;
            }

            .security-measures,
            .user-rights,
            .contact-info {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {

            .privacy-nav {
                margin-bottom: 0;
                top: 60px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
            }

            .section-icon {
                margin: 0 auto 0.8rem;
            }

            .info-box,
            .alert-box {
                flex-direction: column;
                text-align: center;
            }

            .info-box i,
            .alert-box i {
                margin: 0 auto 0.5rem;
            }

            #backToTop {
                width: 40px;
                height: 40px;
                bottom: 20px;
                left: 20px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // دکمه بازگشت به بالا
            const backToTop = document.getElementById('backToTop');

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // اسکرول نرم برای منوی ناوبری
            const navLinks = document.querySelectorAll('.privacy-nav-link');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // هایلایت کردن آیتم فعال در منو
            const sections = document.querySelectorAll('.privacy-section');

            window.addEventListener('scroll', function() {
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 120;
                    const sectionHeight = section.clientHeight;

                    if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
@endpush
