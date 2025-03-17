@extends('layouts.app')

@section('title', 'قوانین و مقررات - کتابخانه دیجیتال بلیان')

@section('content')
    <!-- هدر صفحه -->
    <div class="terms-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-gavel terms-hero-icon"></i>
                <h1 class="terms-hero-title">قوانین و مقررات</h1>
                <p class="terms-hero-desc">لطفاً قبل از استفاده از خدمات ما، این قوانین را مطالعه کنید</p>
                <div class="terms-hero-update">
                    آخرین به‌روزرسانی: {{ date('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>

    <!-- منوی ناوبری -->
    <div class="terms-nav">
        <div class="container">
            <div class="terms-nav-items">
                <a href="#intro" class="terms-nav-link"><i class="fas fa-info-circle"></i> مقدمه</a>
                <a href="#users" class="terms-nav-link"><i class="fas fa-user"></i> کاربران عضو</a>
                <a href="#conditions" class="terms-nav-link"><i class="fas fa-clipboard-check"></i> شرایط استفاده</a>
                <a href="#books" class="terms-nav-link"><i class="fas fa-book"></i> کتاب‌های منتشر شده</a>
                <a href="#content" class="terms-nav-link"><i class="fas fa-file-alt"></i> محتوای آثار</a>
                <a href="#comments" class="terms-nav-link"><i class="fas fa-comments"></i> نظرات</a>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- بخش مقدمه -->
                <section id="intro" class="terms-section">

                    <div class="section-header">
                        <i class="fas fa-info-circle section-icon"></i>
                        <h2>مقدمه</h2>
                    </div>
                    <div class="terms-card">
                        <p>برای ثبت نام و استفاده از خدمات بلیان رعایت قوانین زیر الزامی است. بلیان این حق را برای خود محفوظ می‌دارد که هر زمان که لازم بود قوانین زیر را تغییر دهد یا به آن اضافه کند.</p>

                        <div class="alert-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>هر زمان شما خلاف قوانین عمل کنید بلیان می‌تواند عضویت شما را برای مدتی یا برای همیشه تعلیق کند و دسترسی شما را به کتاب‌ها محدود کند.</p>
                        </div>
                    </div>
                </section>

                <!-- بخش کاربران عضو -->
                <section id="users" class="terms-section">
                    <div class="section-header">
                        <i class="fas fa-user section-icon"></i>
                        <h2>کاربران عضو</h2>
                    </div>
                    <div class="terms-card">
                        <ul class="terms-list">
                            <li>همه می‌توانند از خدمات بلیان استفاده کنند. برای استفاده از خدمات مانند خرید کتاب، می‌توانید به دو روش «مهمان» و «عضو سایت» خرید خود را انجام دهید.</li>
                            <li>وارد کردن اطلاعات صحیح در هنگام ثبت نام الزامی است.</li>
                            <li>هر گونه فعالیتی که از طریق حساب کاربری صورت بگیرد به عهده کاربر است.</li>
                            <li>تمام اطلاعات که شما برای عضو شدن در اختیار بلیان قرار می‌دهید بر طبق سیاست حریم شخصی، نزد سرویس محفوظ می‌ماند.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش شرایط استفاده -->
                <section id="conditions" class="terms-section">
                    <div class="section-header">
                        <i class="fas fa-clipboard-check section-icon"></i>
                        <h2>شرایط استفاده از خدمات</h2>
                    </div>
                    <div class="terms-card">
                        <ul class="terms-list">
                            <li>هر گونه استفاده غیر قانونی از خدمات بلیان ممنوع است.</li>
                            <li>استفاده از آثار عرضه شده در بلیان تنها برای کاربرانی که آن را خریداری کرده‌اند مجاز است.</li>
                            <li>کاربران باید قبل از خرید، اطلاعات کتاب را با دقت بخوانند. زیرا در صورت خرید اشتباه کتاب، بازگرداندن وجه به حساب شما امکان پذیر نیست.</li>
                            <li>پشتیبانی و خدمات پس از فروش فقط از طریق راه‌های ذکر شده در قسمت "تماس با ما" انجام می‌شود.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش کتاب‌های منتشر شده -->
                <section id="books" class="terms-section">
                    <div class="section-header">
                        <i class="fas fa-book section-icon"></i>
                        <h2>کتاب‌های منتشر شده</h2>
                    </div>
                    <div class="terms-card">
                        <ul class="terms-list">
                            <li>بلیان تلاش میکند که فقط کتاب هایی را منتشر کند که انتشار آن‌ها در ایران منع قانونی نداشته باشند و قوانین جمهوری اسلامی ایران را نقض نکنند.</li>
                            <li>در صورتی که نویسنده یا ناشر کتابی تمایل نداشته باشد که آثار آنها در بلیان منتشر شود، میتوانند در صفحه «تماس با ما»،‌ درخواست خود را ثبت نمایند تا از انتشار آثار آن‌ها در بلیان جلوگیری شود.</li>
                            <li>در صورتی که آثار شما در سایت بلیان معرفی شده باشد یا فایل کتاب در اختیار کاربران قرار گرفته باشد،‌ از طریق «درخواست حذف کتاب» اطلاعات را ارسال نمایید تا از سایت حذف شوند.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش محتویات آثار -->
                <section id="content" class="terms-section">
                    <div class="section-header">
                        <i class="fas fa-file-alt section-icon"></i>
                        <h2>محتویات آثار منتشر شده</h2>
                    </div>
                    <div class="terms-card">
                        <ul class="terms-list">
                            <li>مسئولیت بلیان در مورد محتوای آثار منتشر شده هم‌سطح کتابخانه‌های عمومی و کتابفروشی‌ها است.</li>
                            <li>کتاب‌هایی که بر روی بلیان قرار می‌گیرد، مسئولیت محتوایش به پای نویسنده و ناشر آن است. بلیان به هیچ وجه اطلاعات داده شده و قضاوت‌های انجام شده از سوی پدیدآورنده‌ی محتوا یا کاربر را تایید یا تکذیب نمی‌کند و در قبال آن هم مسئولیتی ندارد.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش نظرات -->
                <section id="comments" class="terms-section">
                    <div class="section-header">
                        <i class="fas fa-comments section-icon"></i>
                        <h2>نظرات</h2>
                    </div>
                    <div class="terms-card">
                        <p>نظراتی که شامل موارد زیر باشند، منتشر نخواهند شد:</p>
                        <div class="prohibited-items">
                            <div class="prohibited-item"><i class="fas fa-ban"></i> تبلیغات به هر شکلی</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> شماره تلفن، ایمیل و اطلاعات تماس</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> نام کاربری افراد در سایت‌های مختلف</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> الفاظ رکیک</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> تبلیغ به نفع یا علیه نویسندگان و ناشران</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> هر نوع محتوای متناقض عرف جامعه</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> نقض قوانین جمهوری اسلامی ایران</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> محتوای سیاسی و توهین به قومیت‌ها</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> توهین به مقدسات، اقوام یا اشخاص</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> هرگونه لینک به سایت‌های دیگر</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> درخواست جمع‌آوری رأی و لایک</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> درخواست و دعوت به چت و گفتگو</div>
                            <div class="prohibited-item"><i class="fas fa-ban"></i> نظرات غیر‌مرتبط با کتاب</div>
                        </div>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="terms-footer">
                    <i class="fas fa-shield-alt"></i>
                    <h3>تعهد ما به رعایت قوانین</h3>
                    <p>کتابخانه دیجیتال بلیان متعهد به ارائه خدمات با کیفیت و مطابق با قوانین و مقررات است. ما به حقوق کاربران و ناشران احترام می‌گذاریم و تلاش می‌کنیم محیطی امن و قانونمند برای همه فراهم کنیم.</p>
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
        /* استایل‌های صفحه قوانین و مقررات */
        :root {
            --primary: #3b82f6;
            --primary-light: rgba(59, 130, 246, 0.1);
            --primary-dark: #2563eb;
            --danger: #ef4444;
            --gray-800: #1f2937;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --light: #f9fafb;
        }

        /* هدر صفحه */
        .terms-hero {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
            /*margin-bottom: 1rem;*/
        }

        .terms-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .terms-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .terms-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .terms-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* منوی ناوبری */
        .terms-nav {
            background-color: #fff;
            position: sticky;
            top: 70px;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .terms-nav-items {
            display: flex;
            gap: 0.5rem;
            padding: 0.7rem 0;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .terms-nav-items::-webkit-scrollbar {
            display: none;
        }

        .terms-nav-link {
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

        .terms-nav-link i {
            margin-left: 0.4rem;
            color: var(--primary);
        }

        .terms-nav-link:hover, .terms-nav-link.active {
            background-color: var(--primary);
            color: #fff;
        }

        .terms-nav-link:hover i, .terms-nav-link.active i {
            color: #fff;
        }

        /* بخش‌ها */
        .terms-section {
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

        .terms-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        /* هشدار */
        .alert-box {
            background-color: rgba(239, 68, 68, 0.08);
            border-right: 4px solid var(--danger);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            display: flex;
            align-items: flex-start;
        }

        .alert-box i {
            font-size: 1.2rem;
            color: var(--danger);
            margin-left: 0.8rem;
        }

        /* لیست قوانین */
        .terms-list {
            padding-right: 1.5rem;
            margin: 0;
        }

        .terms-list li {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* موارد ممنوع */
        .prohibited-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .prohibited-item {
            background-color: var(--light);
            border-radius: 8px;
            padding: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .prohibited-item i {
            color: var(--danger);
        }

        /* بخش پایانی */
        .terms-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .terms-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .terms-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .terms-footer p {
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
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
            .terms-hero-title {
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

            .prohibited-items {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {

            .terms-nav {
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

            .alert-box {
                flex-direction: column;
                text-align: center;
            }

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
            const navLinks = document.querySelectorAll('.terms-nav-link');

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
            const sections = document.querySelectorAll('.terms-section');

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
