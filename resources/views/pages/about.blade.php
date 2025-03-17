@extends('layouts.app')

@section('title', 'درباره بلیان - کتابخانه دیجیتال')

@push('styles')
    <style>
        /* هدر صفحه */
        .about-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .about-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .about-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .about-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .about-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* بخش‌ها */
        .about-section {
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

        .about-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .info-highlight {
            background-color: rgba(59, 130, 246, 0.05);
            border-right: 4px solid var(--primary);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .values-list {
            padding-right: 1.5rem;
            margin: 0;
        }

        .values-list li {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* بخش پایانی */
        .about-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .about-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .about-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .about-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .about-hero-title {
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
        }

        @media (max-width: 576px) {
            .section-header {
                flex-direction: column;
                text-align: center;
            }

            .section-icon {
                margin: 0 auto 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه درباره ما -->
    <div class="about-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-book-open about-hero-icon"></i>
                <h1 class="about-hero-title">کتابخانه دیجیتال بلیان</h1>
                <p class="about-hero-desc">دروازه‌ای به دنیای نامحدود دانش و خلاقیت</p>
                <div class="about-hero-update">
                    آخرین به‌روزرسانی: {{ date('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- بخش درباره بلیان -->
                <section class="about-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle section-icon"></i>
                        <h2>درباره بلیان</h2>
                    </div>
                    <div class="about-card">
                        <p>در دنیای امروز، فناوری به طور چشمگیری شیوه مطالعه و انتشار کتاب‌ها را متحول کرده است. یکی از مهم‌ترین این تحولات، ظهور نشر الکترونیک است که امکان دسترسی سریع، کاهش هزینه‌ها، حذف کاغذ و گسترش فرهنگ مطالعه را فراهم آورده است. در این راستا، کتابخانه دیجیتال بلیان با هدف ارائه بستری مناسب برای انتشار و معرفی کتاب‌های الکترونیکی، مجلات، مقالات و سایر منابع علمی و فرهنگی تأسیس شده است.</p>
                    </div>
                </section>

                <!-- بخش بلیان چیست؟ -->
                <section class="about-section">
                    <div class="section-header">
                        <i class="fas fa-book section-icon"></i>
                        <h2>بلیان چیست؟</h2>
                    </div>
                    <div class="about-card">
                        <p>بلیان یک کتابخانه دیجیتال است که مجموعه‌ای گسترده از کتاب‌های الکترونیکی، مقالات و مجلات را در اختیار کاربران قرار می‌دهد. هدف ما ارائه بستری قانونی و منطبق با حقوق نشر برای دسترسی آسان به منابع علمی و فرهنگی است. ما تلاش می‌کنیم با همکاری نویسندگان و ناشران معتبر، مجموعه‌ای ارزشمند از منابع دیجیتال را فراهم کنیم.</p>

                        <div class="info-highlight">
                            <p>تمامی محتوای ارائه‌شده در بلیان با رعایت حقوق ناشران و نویسندگان در دسترس قرار می‌گیرد. برخی از کتاب‌ها به صورت رایگان و برخی دیگر با رعایت قوانین نشر و شرایط خاص ناشران عرضه می‌شوند. استفاده از وب‌سایت بلیان رایگان است و کاربران می‌توانند بدون نیاز به ثبت‌نام، به بسیاری از کتاب‌های الکترونیکی دسترسی داشته باشند.</p>
                        </div>

                        <p>ناشران و نویسندگان می‌توانند از طریق وب‌سایت بلیان، آثار خود را به‌صورت الکترونیکی و صوتی منتشر کنند. اطلاعات تماس برای همکاری در بخش "تماس با ما" سایت بلیان در دسترس است.</p>
                    </div>
                </section>

                <!-- بخش ما که هستیم؟ -->
                <section class="about-section">
                    <div class="section-header">
                        <i class="fas fa-users section-icon"></i>
                        <h2>ما که هستیم؟</h2>
                    </div>
                    <div class="about-card">
                        <p>بلیان توسط تیمی از متخصصان در حوزه نشر دیجیتال، فناوری اطلاعات و هوش مصنوعی مدیریت می‌شود. هدف ما ارائه یک پلتفرم هوشمند برای تسهیل دسترسی به منابع علمی و فرهنگی است. ما از فناوری‌های پیشرفته برای جستجو، دسته‌بندی و ارائه محتوا استفاده می‌کنیم و در تلاشیم تا کتاب‌ها و مقالاتی که مجوز انتشار دارند، در دسترس کاربران قرار دهیم.</p>
                    </div>
                </section>

                <!-- بخش رویکرد ما -->
                <section class="about-section">
                    <div class="section-header">
                        <i class="fas fa-bullseye section-icon"></i>
                        <h2>رویکرد ما</h2>
                    </div>
                    <div class="about-card">
                        <ul class="values-list">
                            <li><strong>حفظ حقوق مؤلفان و ناشران:</strong> تمامی محتواهای ارائه‌شده مطابق با قوانین کپی‌رایت و نشر دیجیتال منتشر می‌شوند.</li>
                            <li><strong>دسترسی آسان:</strong> کاربران می‌توانند در هر زمان و مکان به منابع الکترونیکی دسترسی داشته باشند.</li>
                            <li><strong>کیفیت و تنوع:</strong> بلیان مجموعه‌ای متنوع از کتاب‌های علمی، ادبی، تاریخی و فرهنگی را ارائه می‌دهد.</li>
                            <li><strong>همکاری با ناشران و نویسندگان:</strong> بلیان یک پلتفرم حمایتی برای مؤلفان است که آثار خود را به‌صورت الکترونیکی منتشر کنند.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش کتاب‌ها همیشه برای شماست -->
                <section class="about-section">
                    <div class="section-header">
                        <i class="fas fa-book-reader section-icon"></i>
                        <h2>کتاب‌ها همیشه برای شماست</h2>
                    </div>
                    <div class="about-card">
                        <p>با استفاده از بلیان، کاربران می‌توانند کتاب‌های الکترونیکی و صوتی را دریافت کرده و بر روی دستگاه‌های مختلف اجرا کنند. حتی در صورت عدم دسترسی به اینترنت، فایل‌های دانلودشده قابل استفاده خواهند بود. ما بر آنیم که تجربه‌ای لذت‌بخش از مطالعه و شنیدن کتاب را برای همه علاقه‌مندان فراهم کنیم.</p>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="about-footer">
                    <i class="fas fa-book-open"></i>
                    <h3>تعهد ما به گسترش دانش</h3>
                    <p>کتابخانه دیجیتال بلیان متعهد به ارائه خدمات با کیفیت و گسترش فرهنگ مطالعه است. ما به حقوق کاربران و ناشران احترام می‌گذاریم و تلاش می‌کنیم محیطی غنی برای کسب دانش فراهم کنیم.</p>
                </div>

            </div>
        </div>
    </div>
@endsection
