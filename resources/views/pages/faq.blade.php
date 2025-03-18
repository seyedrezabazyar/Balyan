@extends('layouts.app')

@section('title', 'سوالات متداول - بالیان')

@push('styles')
    <style>
        /* قسمت هدر صفحه */
        .faq-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .faq-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .faq-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .faq-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        /* بخش جستجو - طراحی جدید و ساده‌تر */
        .faq-search {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-top: -2rem;
            position: relative;
            z-index: 10;
        }

        .faq-search-form {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .faq-search-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 50px;
            padding: 0.9rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .faq-search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .faq-search-icon-btn {
            position: absolute;
            left: 15px;
            background: transparent;
            color: #3b82f6;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            padding: 0;
            margin: 0;
        }

        .faq-search-icon-btn:hover {
            color: #2563eb;
            transform: scale(1.1);
        }

        .faq-search-icon-btn i {
            font-size: 1.3rem;
        }

        /* تب‌های دسته‌بندی */
        .faq-tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin: 2rem 0;
            overflow-x: auto;
            padding-bottom: 1px;
            -webkit-overflow-scrolling: touch;
        }

        .faq-tab {
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .faq-tab:hover {
            color: #3b82f6;
        }

        .faq-tab.active {
            color: #3b82f6;
            border-bottom: 3px solid #3b82f6;
        }

        .faq-tab i {
            margin-left: 0.5rem;
        }

        /* محتوای تب‌ها */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* سوالات متداول - آکاردئون */
        .faq-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--gray-800);
            display: flex;
            align-items: center;
        }

        .section-title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            color: #fff;
            font-size: 1.1rem;
        }

        .faq-accordion {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .faq-question {
            padding: 1.2rem 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s;
        }

        .faq-question:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .faq-question.active {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .question-text {
            flex-grow: 1;
        }

        .question-toggle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            font-size: 1rem;
            margin-right: 1rem;
            transition: all 0.3s;
        }

        .faq-question.active .question-toggle {
            transform: rotate(180deg);
            background-color: #3b82f6;
            color: #fff;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-answer.active {
            padding: 0 1.5rem 1.5rem;
            max-height: 500px;
        }

        .answer-content {
            border-top: 1px solid #e5e7eb;
            padding-top: 1.2rem;
            color: #4b5563;
            line-height: 1.7;
        }

        /* بخش تماس با پشتیبانی */
        .faq-support {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .faq-support i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .faq-support h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .faq-support p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 1.5rem;
        }

        .support-btn {
            background-color: #fff;
            color: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
        }

        .support-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        /* نتایج جستجو */
        .faq-search-results {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-top: 1.5rem;
            display: none;
        }

        .faq-search-results.active {
            display: block;
        }

        .faq-search-results-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-800);
        }

        .faq-search-result-item {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s;
        }

        .faq-search-result-item:last-child {
            border-bottom: none;
        }

        .faq-search-result-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .faq-search-result-category {
            color: #3b82f6;
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
        }

        .faq-search-result-question {
            font-weight: 600;
            color: var(--gray-800);
        }

        .faq-no-results {
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }

        .faq-no-results i {
            font-size: 3rem;
            color: #e5e7eb;
            margin-bottom: 1rem;
            display: block;
        }

        /* طراحی واکنش‌گرا */
        @media (max-width: 768px) {
            .faq-hero-title {
                font-size: 1.8rem;
            }

            .faq-search-input {
                padding: 0.85rem 1rem;
                font-size: 0.95rem;
            }

            .faq-search-icon-btn {
                left: 12px;
            }

            .faq-search-icon-btn i {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه سوالات متداول -->
    <div class="faq-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-question-circle faq-hero-icon"></i>
                <h1 class="faq-hero-title">سوالات متداول</h1>
                <p class="faq-hero-desc">پاسخ سوالات رایج شما درباره خدمات بالیان</p>
            </div>
        </div>
    </div>

    <!-- بخش جستجو -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-search">
                    <form class="faq-search-form" id="faqSearchForm">
                        <input type="text" class="faq-search-input" id="faqSearchInput" placeholder="سوال خود را جستجو کنید...">
                        <button type="submit" class="faq-search-icon-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- نتایج جستجو - به صورت پیش‌فرض مخفی است -->
                <div class="faq-search-results" id="faqSearchResults">
                    <h3 class="faq-search-results-title">نتایج جستجو برای: <span id="faqSearchQuery"></span></h3>
                    <div id="faqSearchResultsList">
                        <!-- نتایج جستجو اینجا قرار می‌گیرند -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- تب‌های دسته‌بندی -->
                <div class="faq-tabs">
                    <div class="faq-tab active" data-tab="all">
                        <i class="fas fa-th-large"></i> همه سوالات
                    </div>
                    <div class="faq-tab" data-tab="purchase">
                        <i class="fas fa-shopping-cart"></i> خرید و فروش
                    </div>
                    <div class="faq-tab" data-tab="account">
                        <i class="fas fa-user"></i> حساب کاربری
                    </div>
                    <div class="faq-tab" data-tab="products">
                        <i class="fas fa-book"></i> محصولات
                    </div>
                    <div class="faq-tab" data-tab="payment">
                        <i class="fas fa-credit-card"></i> پرداخت
                    </div>
                    <div class="faq-tab" data-tab="delivery">
                        <i class="fas fa-truck"></i> ارسال و تحویل
                    </div>
                </div>

                <!-- محتوای تب همه سوالات -->
                <div class="tab-content active" id="all">
                    <!-- بخش خرید و فروش -->
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-shopping-cart"></i></span>
                            سوالات متداول خرید و فروش
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم کتاب‌های دیجیتال را خریداری کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای خرید کتاب‌های دیجیتال در بالیان، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>به حساب کاربری خود وارد شوید (اگر حساب ندارید، ابتدا ثبت‌نام کنید)</li>
                                        <li>کتاب مورد نظر خود را از طریق جستجو یا دسته‌بندی‌ها پیدا کنید</li>
                                        <li>روی دکمه "افزودن به سبد خرید" کلیک کنید</li>
                                        <li>به سبد خرید خود بروید و روی "تکمیل خرید" کلیک کنید</li>
                                        <li>اطلاعات پرداخت را تکمیل کنید و خرید را نهایی کنید</li>
                                        <li>پس از پرداخت موفق، کتاب به کتابخانه دیجیتال شما اضافه می‌شود</li>
                                    </ol>
                                    <p>شما می‌توانید کتاب‌های خریداری شده را از بخش "کتابخانه من" مشاهده و دانلود کنید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا می‌توانم کتاب‌های خریداری شده را در دستگاه‌های مختلف مطالعه کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، شما می‌توانید کتاب‌های خریداری شده را در دستگاه‌های مختلف مطالعه کنید. برای این کار:</p>
                                    <ul>
                                        <li>در دستگاه جدید، به حساب کاربری خود در سایت بالیان وارد شوید</li>
                                        <li>به بخش "کتابخانه من" مراجعه کنید</li>
                                        <li>کتاب مورد نظر را انتخاب و دانلود کنید</li>
                                    </ul>
                                    <p>توجه داشته باشید که برای مطالعه کتاب‌ها در دستگاه‌های مختلف، نیاز به نصب اپلیکیشن مناسب برای فرمت کتاب (مانند PDF یا EPUB) دارید. همچنین، تعداد دستگاه‌های مجاز برای هر کتاب ممکن است محدود باشد.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- بخش حساب کاربری -->
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-user"></i></span>
                            سوالات متداول حساب کاربری
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم رمز عبور خود را تغییر دهم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای تغییر رمز عبور، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>وارد حساب کاربری خود شوید</li>
                                        <li>به بخش "تنظیمات حساب" بروید</li>
                                        <li>گزینه "تغییر رمز عبور" را انتخاب کنید</li>
                                        <li>رمز عبور فعلی و رمز عبور جدید را وارد کنید</li>
                                        <li>روی دکمه "ذخیره تغییرات" کلیک کنید</li>
                                    </ol>
                                    <p>اگر رمز عبور خود را فراموش کرده‌اید، می‌توانید از گزینه "فراموشی رمز عبور" در صفحه ورود استفاده کنید.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- بخش محصولات -->
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-book"></i></span>
                            سوالات متداول محصولات
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا امکان مطالعه بخشی از کتاب قبل از خرید وجود دارد؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، برای اکثر کتاب‌های موجود در بالیان، امکان مشاهده پیش‌نمایش رایگان وجود دارد. این پیش‌نمایش معمولاً شامل فهرست مطالب، مقدمه و بخش‌هایی از فصل اول کتاب است.</p>
                                    <p>برای مشاهده پیش‌نمایش، در صفحه محصول کتاب، روی گزینه "مشاهده پیش‌نمایش" کلیک کنید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">کتاب‌های دیجیتال در چه فرمت‌هایی ارائه می‌شوند؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>کتاب‌های دیجیتال بالیان در فرمت‌های زیر ارائه می‌شوند:</p>
                                    <ul>
                                        <li><strong>PDF:</strong> قابل استفاده در تمامی دستگاه‌ها با حفظ طرح‌بندی اصلی کتاب</li>
                                        <li><strong>EPUB:</strong> فرمت استاندارد کتاب‌های الکترونیکی با قابلیت تنظیم اندازه متن</li>
                                        <li><strong>MOBI:</strong> مناسب برای دستگاه‌های کیندل آمازون</li>
                                    </ul>
                                    <p>در صفحه هر محصول، فرمت‌های در دسترس آن کتاب مشخص شده است.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- بخش پرداخت -->
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-credit-card"></i></span>
                            سوالات متداول پرداخت
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چه روش‌های پرداختی در بالیان پشتیبانی می‌شود؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>در حال حاضر، روش‌های پرداخت زیر در بالیان پشتیبانی می‌شوند:</p>
                                    <ul>
                                        <li>پرداخت آنلاین با تمامی کارت‌های بانکی عضو شتاب</li>
                                        <li>کیف پول بالیان</li>
                                        <li>پرداخت از طریق اپلیکیشن‌های پرداخت (مانند آپ و ۷۲۴)</li>
                                    </ul>
                                    <p>ما در تلاش هستیم تا در آینده روش‌های پرداخت بیشتری را اضافه کنیم.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- بخش ارسال و تحویل -->
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-truck"></i></span>
                            سوالات متداول ارسال و تحویل
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم کتاب‌های چاپی را سفارش دهم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای سفارش کتاب‌های چاپی، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>به حساب کاربری خود وارد شوید</li>
                                        <li>کتاب مورد نظر را انتخاب کنید و نسخه چاپی را به سبد خرید اضافه کنید</li>
                                        <li>به سبد خرید بروید و روی "تکمیل خرید" کلیک کنید</li>
                                        <li>آدرس دقیق پستی و روش ارسال را انتخاب کنید</li>
                                        <li>روش پرداخت را انتخاب کنید و سفارش را نهایی کنید</li>
                                    </ol>
                                    <p>پس از تأیید پرداخت، سفارش شما آماده ارسال خواهد شد و کد رهگیری پستی از طریق پیامک و ایمیل برای شما ارسال می‌شود.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">هزینه ارسال کتاب‌های چاپی چقدر است؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>هزینه ارسال بر اساس وزن سفارش، مقصد و روش ارسال انتخابی متفاوت است:</p>
                                    <ul>
                                        <li><strong>ارسال عادی:</strong> بین ۱۵,۰۰۰ تا ۳۵,۰۰۰ تومان (۳ تا ۷ روز کاری)</li>
                                        <li><strong>ارسال پیشتاز:</strong> بین ۲۵,۰۰۰ تا ۴۵,۰۰۰ تومان (۱ تا ۳ روز کاری)</li>
                                        <li><strong>ارسال فوری:</strong> بین ۴۰,۰۰۰ تا ۶۰,۰۰۰ تومان (۱ روز کاری، فقط در برخی شهرها)</li>
                                    </ul>
                                    <p>برای سفارش‌های بالای ۳۰۰,۰۰۰ تومان، ارسال عادی رایگان است.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- محتوای تب خرید و فروش -->
                <div class="tab-content" id="purchase">
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-shopping-cart"></i></span>
                            سوالات متداول خرید و فروش
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم کتاب‌های دیجیتال را خریداری کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای خرید کتاب‌های دیجیتال در بالیان، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>به حساب کاربری خود وارد شوید (اگر حساب ندارید، ابتدا ثبت‌نام کنید)</li>
                                        <li>کتاب مورد نظر خود را از طریق جستجو یا دسته‌بندی‌ها پیدا کنید</li>
                                        <li>روی دکمه "افزودن به سبد خرید" کلیک کنید</li>
                                        <li>به سبد خرید خود بروید و روی "تکمیل خرید" کلیک کنید</li>
                                        <li>اطلاعات پرداخت را تکمیل کنید و خرید را نهایی کنید</li>
                                        <li>پس از پرداخت موفق، کتاب به کتابخانه دیجیتال شما اضافه می‌شود</li>
                                    </ol>
                                    <p>شما می‌توانید کتاب‌های خریداری شده را از بخش "کتابخانه من" مشاهده و دانلود کنید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا می‌توانم کتاب‌های خریداری شده را در دستگاه‌های مختلف مطالعه کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، شما می‌توانید کتاب‌های خریداری شده را در دستگاه‌های مختلف مطالعه کنید. برای این کار:</p>
                                    <ul>
                                        <li>در دستگاه جدید، به حساب کاربری خود در سایت بالیان وارد شوید</li>
                                        <li>به بخش "کتابخانه من" مراجعه کنید</li>
                                        <li>کتاب مورد نظر را انتخاب و دانلود کنید</li>
                                    </ul>
                                    <p>توجه داشته باشید که برای مطالعه کتاب‌ها در دستگاه‌های مختلف، نیاز به نصب اپلیکیشن مناسب برای فرمت کتاب (مانند PDF یا EPUB) دارید. همچنین، تعداد دستگاه‌های مجاز برای هر کتاب ممکن است محدود باشد.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۳ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا امکان بازگشت وجه برای کتاب‌های دیجیتال وجود دارد؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>با توجه به ماهیت دیجیتال محصولات، امکان بازگشت وجه برای کتاب‌های دیجیتال محدود است. با این حال، در شرایط زیر امکان بازگشت وجه وجود دارد:</p>
                                    <ul>
                                        <li>اگر به دلیل مشکلات فنی قادر به دانلود یا مطالعه کتاب نباشید</li>
                                        <li>اگر محتوای کتاب با توضیحات ارائه شده مطابقت نداشته باشد</li>
                                        <li>اگر خرید به صورت تصادفی و اشتباه انجام شده باشد (حداکثر تا ۲۴ ساعت پس از خرید)</li>
                                    </ul>
                                    <p>برای درخواست بازگشت وجه، لطفاً با پشتیبانی ما از طریق ایمیل <strong>support@balyan.ir</strong> تماس بگیرید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۴ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم کتاب‌های خود را به فروش برسانم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای فروش کتاب در بالیان، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>در سایت به عنوان ناشر یا نویسنده ثبت‌نام کنید</li>
                                        <li>پس از تأیید حساب، به پنل ناشرین دسترسی خواهید داشت</li>
                                        <li>از طریق پنل ناشرین، اطلاعات کتاب، فایل و قیمت پیشنهادی را وارد کنید</li>
                                        <li>پس از بررسی و تأیید توسط تیم بالیان، کتاب شما در سایت منتشر خواهد شد</li>
                                    </ol>
                                    <p>برای اطلاعات بیشتر درباره شرایط انتشار کتاب و درصد کمیسیون، به صفحه <a href="#">"راهنمای ناشرین"</a> مراجعه کنید.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- محتوای تب حساب کاربری -->
                <div class="tab-content" id="account">
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-user"></i></span>
                            سوالات متداول حساب کاربری
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم رمز عبور خود را تغییر دهم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای تغییر رمز عبور، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>وارد حساب کاربری خود شوید</li>
                                        <li>به بخش "تنظیمات حساب" بروید</li>
                                        <li>گزینه "تغییر رمز عبور" را انتخاب کنید</li>
                                        <li>رمز عبور فعلی و رمز عبور جدید را وارد کنید</li>
                                        <li>روی دکمه "ذخیره تغییرات" کلیک کنید</li>
                                    </ol>
                                    <p>اگر رمز عبور خود را فراموش کرده‌اید، می‌توانید از گزینه "فراموشی رمز عبور" در صفحه ورود استفاده کنید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم اطلاعات پروفایل خود را ویرایش کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای ویرایش اطلاعات پروفایل خود، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>وارد حساب کاربری خود شوید</li>
                                        <li>به بخش "پروفایل من" بروید</li>
                                        <li>روی گزینه "ویرایش پروفایل" کلیک کنید</li>
                                        <li>اطلاعات مورد نظر خود را ویرایش کنید</li>
                                        <li>روی دکمه "ذخیره تغییرات" کلیک کنید</li>
                                    </ol>
                                    <p>توجه داشته باشید که برخی از اطلاعات اصلی مانند نام کاربری پس از ثبت‌نام قابل تغییر نیستند.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۳ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم حساب کاربری خود را حذف کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای حذف حساب کاربری، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>وارد حساب کاربری خود شوید</li>
                                        <li>به بخش "تنظیمات حساب" بروید</li>
                                        <li>به پایین صفحه بروید و گزینه "حذف حساب کاربری" را انتخاب کنید</li>
                                        <li>دلیل حذف حساب را انتخاب کنید</li>
                                        <li>رمز عبور خود را برای تأیید وارد کنید</li>
                                        <li>روی دکمه "حذف حساب کاربری" کلیک کنید</li>
                                    </ol>
                                    <p><strong>توجه:</strong> با حذف حساب کاربری، تمام اطلاعات شما از جمله سوابق خرید، کتاب‌های خریداری شده و اعتبار موجود در کیف پول حذف خواهد شد. این عملیات غیرقابل بازگشت است.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- محتوای تب محصولات -->
                <div class="tab-content" id="products">
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-book"></i></span>
                            سوالات متداول محصولات
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا امکان مطالعه بخشی از کتاب قبل از خرید وجود دارد؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، برای اکثر کتاب‌های موجود در بالیان، امکان مشاهده پیش‌نمایش رایگان وجود دارد. این پیش‌نمایش معمولاً شامل فهرست مطالب، مقدمه و بخش‌هایی از فصل اول کتاب است.</p>
                                    <p>برای مشاهده پیش‌نمایش، در صفحه محصول کتاب، روی گزینه "مشاهده پیش‌نمایش" کلیک کنید.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">کتاب‌های دیجیتال در چه فرمت‌هایی ارائه می‌شوند؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>کتاب‌های دیجیتال بالیان در فرمت‌های زیر ارائه می‌شوند:</p>
                                    <ul>
                                        <li><strong>PDF:</strong> قابل استفاده در تمامی دستگاه‌ها با حفظ طرح‌بندی اصلی کتاب</li>
                                        <li><strong>EPUB:</strong> فرمت استاندارد کتاب‌های الکترونیکی با قابلیت تنظیم اندازه متن</li>
                                        <li><strong>MOBI:</strong> مناسب برای دستگاه‌های کیندل آمازون</li>
                                    </ul>
                                    <p>در صفحه هر محصول، فرمت‌های در دسترس آن کتاب مشخص شده است.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۳ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا کتاب‌های دیجیتال دارای قفل DRM هستند؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، بسیاری از کتاب‌های دیجیتال بالیان دارای قفل DRM (مدیریت حقوق دیجیتال) هستند. این قفل برای محافظت از حقوق ناشران و نویسندگان طراحی شده است.</p>
                                    <p>کتاب‌های دارای قفل DRM:</p>
                                    <ul>
                                        <li>فقط در دستگاه‌های مجاز قابل مطالعه هستند</li>
                                        <li>نیاز به احراز هویت کاربر دارند</li>
                                        <li>ممکن است محدودیت‌هایی در تعداد دستگاه‌های مجاز داشته باشند</li>
                                    </ul>
                                    <p>در صفحه هر محصول، وضعیت DRM کتاب مشخص شده است.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- محتوای تب پرداخت -->
                <div class="tab-content" id="payment">
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-credit-card"></i></span>
                            سوالات متداول پرداخت
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چه روش‌های پرداختی در بالیان پشتیبانی می‌شود؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>در حال حاضر، روش‌های پرداخت زیر در بالیان پشتیبانی می‌شوند:</p>
                                    <ul>
                                        <li>پرداخت آنلاین با تمامی کارت‌های بانکی عضو شتاب</li>
                                        <li>کیف پول بالیان</li>
                                        <li>پرداخت از طریق اپلیکیشن‌های پرداخت (مانند آپ و ۷۲۴)</li>
                                    </ul>
                                    <p>ما در تلاش هستیم تا در آینده روش‌های پرداخت بیشتری را اضافه کنیم.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">آیا پرداخت در بالیان امن است؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>بله، تمامی تراکنش‌های مالی در بالیان با استفاده از درگاه‌های پرداخت رسمی و معتبر انجام می‌شود. ما از پروتکل SSL برای رمزگذاری اطلاعات استفاده می‌کنیم و هیچ‌گاه اطلاعات کارت بانکی شما را ذخیره نمی‌کنیم.</p>
                                    <p>همچنین، تمامی پرداخت‌ها مستقیماً از طریق درگاه‌های بانکی انجام می‌شود و اطلاعات حساس شما هرگز در اختیار سایت قرار نمی‌گیرد.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۳ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">کیف پول بالیان چیست و چگونه کار می‌کند؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>کیف پول بالیان یک روش پرداخت داخلی است که به شما امکان می‌دهد اعتبار مالی در حساب خود ذخیره کنید و از آن برای خرید کتاب‌ها استفاده کنید. مزایای کیف پول بالیان عبارتند از:</p>
                                    <ul>
                                        <li>سرعت بالای پرداخت بدون نیاز به ورود اطلاعات بانکی در هر خرید</li>
                                        <li>امکان استفاده از تخفیف‌های ویژه برای شارژ کیف پول</li>
                                        <li>دریافت هدیه و اعتبار رایگان در مناسبت‌های مختلف</li>
                                    </ul>
                                    <p>برای شارژ کیف پول، به بخش "کیف پول" در پنل کاربری خود مراجعه کنید و مبلغ مورد نظر را وارد کنید.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- محتوای تب ارسال و تحویل -->
                <div class="tab-content" id="delivery">
                    <section class="faq-section">
                        <h2 class="section-title">
                            <span class="section-title-icon"><i class="fas fa-truck"></i></span>
                            سوالات متداول ارسال و تحویل
                        </h2>

                        <!-- سوال ۱ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم کتاب‌های چاپی را سفارش دهم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای سفارش کتاب‌های چاپی، مراحل زیر را دنبال کنید:</p>
                                    <ol>
                                        <li>به حساب کاربری خود وارد شوید</li>
                                        <li>کتاب مورد نظر را انتخاب کنید و نسخه چاپی را به سبد خرید اضافه کنید</li>
                                        <li>به سبد خرید بروید و روی "تکمیل خرید" کلیک کنید</li>
                                        <li>آدرس دقیق پستی و روش ارسال را انتخاب کنید</li>
                                        <li>روش پرداخت را انتخاب کنید و سفارش را نهایی کنید</li>
                                    </ol>
                                    <p>پس از تأیید پرداخت، سفارش شما آماده ارسال خواهد شد و کد رهگیری پستی از طریق پیامک و ایمیل برای شما ارسال می‌شود.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۲ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">هزینه ارسال کتاب‌های چاپی چقدر است؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>هزینه ارسال بر اساس وزن سفارش، مقصد و روش ارسال انتخابی متفاوت است:</p>
                                    <ul>
                                        <li><strong>ارسال عادی:</strong> بین ۱۵,۰۰۰ تا ۳۵,۰۰۰ تومان (۳ تا ۷ روز کاری)</li>
                                        <li><strong>ارسال پیشتاز:</strong> بین ۲۵,۰۰۰ تا ۴۵,۰۰۰ تومان (۱ تا ۳ روز کاری)</li>
                                        <li><strong>ارسال فوری:</strong> بین ۴۰,۰۰۰ تا ۶۰,۰۰۰ تومان (۱ روز کاری، فقط در برخی شهرها)</li>
                                    </ul>
                                    <p>برای سفارش‌های بالای ۳۰۰,۰۰۰ تومان، ارسال عادی رایگان است.</p>
                                </div>
                            </div>
                        </div>

                        <!-- سوال ۳ -->
                        <div class="faq-accordion">
                            <div class="faq-question">
                                <div class="question-text">چگونه می‌توانم وضعیت سفارش خود را پیگیری کنم؟</div>
                                <div class="question-toggle"><i class="fas fa-chevron-down"></i></div>
                            </div>
                            <div class="faq-answer">
                                <div class="answer-content">
                                    <p>برای پیگیری وضعیت سفارش خود، دو روش وجود دارد:</p>
                                    <ol>
                                        <li>
                                            <strong>از طریق پنل کاربری:</strong>
                                            <ul>
                                                <li>وارد حساب کاربری خود شوید</li>
                                                <li>به بخش "سفارش‌های من" بروید</li>
                                                <li>سفارش مورد نظر را انتخاب کنید تا جزئیات آن را مشاهده کنید</li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>از طریق کد رهگیری پستی:</strong>
                                            <ul>
                                                <li>به وب‌سایت شرکت پست مراجعه کنید</li>
                                                <li>کد رهگیری پستی که برای شما ارسال شده را وارد کنید</li>
                                                <li>وضعیت دقیق مرسوله خود را مشاهده کنید</li>
                                            </ul>
                                        </li>
                                    </ol>
                                    <p>در صورت بروز هرگونه مشکل یا تأخیر در ارسال، می‌توانید با پشتیبانی ما تماس بگیرید.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- بخش تماس با پشتیبانی -->
                <div class="faq-support">
                    <i class="fas fa-headset"></i>
                    <h3>هنوز سوالی دارید؟</h3>
                    <p>اگر پاسخ سوال خود را در این صفحه پیدا نکردید، می‌توانید با تیم پشتیبانی ما تماس بگیرید. کارشناسان ما آماده پاسخگویی به سوالات شما هستند.</p>
                    <a href="{{ route('contact') }}" class="support-btn">تماس با پشتیبانی</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // کد جاوااسکریپت برای باز و بسته کردن آکاردئون‌ها
            const questions = document.querySelectorAll('.faq-question');

            questions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;

                    // باز یا بسته کردن سوال فعلی
                    question.classList.toggle('active');
                    answer.classList.toggle('active');
                });
            });

            // کد جاوااسکریپت برای تغییر تب‌ها
            const tabs = document.querySelectorAll('.faq-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');

                    // غیرفعال کردن همه تب‌ها
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // فعال کردن تب انتخاب شده
                    tab.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // کد جاوااسکریپت برای جستجو
            const searchForm = document.getElementById('faqSearchForm');
            const searchInput = document.getElementById('faqSearchInput');
            const searchResults = document.getElementById('faqSearchResults');
            const searchQuery = document.getElementById('faqSearchQuery');
            const searchResultsList = document.getElementById('faqSearchResultsList');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const query = searchInput.value.trim().toLowerCase();

                if (query.length < 2) return;

                // نمایش بخش نتایج جستجو
                searchResults.classList.add('active');
                searchQuery.textContent = query;

                // جستجو در سوالات
                const allQuestions = document.querySelectorAll('.faq-question .question-text');
                let results = [];

                allQuestions.forEach(questionEl => {
                    const questionText = questionEl.textContent.toLowerCase();
                    const answerText = questionEl.closest('.faq-accordion').querySelector('.answer-content').textContent.toLowerCase();

                    if (questionText.includes(query) || answerText.includes(query)) {
                        const category = questionEl.closest('.faq-section').querySelector('.section-title').textContent.trim();
                        results.push({
                            question: questionEl.textContent,
                            category: category,
                            element: questionEl.closest('.faq-question')
                        });
                    }
                });

                // نمایش نتایج
                searchResultsList.innerHTML = '';

                if (results.length > 0) {
                    results.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'faq-search-result-item';
                        resultItem.innerHTML = `
                            <div class="faq-search-result-category">${result.category}</div>
                            <div class="faq-search-result-question">${result.question}</div>
                        `;

                        resultItem.addEventListener('click', () => {
                            // مخفی کردن نتایج جستجو
                            searchResults.classList.remove('active');

                            // باز کردن سوال مربوطه
                            const questionElement = result.element;
                            const tabId = questionElement.closest('.tab-content').id;

                            // تغییر به تب مربوطه
                            tabs.forEach(t => t.classList.remove('active'));
                            tabContents.forEach(c => c.classList.remove('active'));

                            document.querySelector(`.faq-tab[data-tab="${tabId}"]`).classList.add('active');
                            document.getElementById(tabId).classList.add('active');

                            // اسکرول به سوال و باز کردن آن
                            questionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            setTimeout(() => {
                                if (!questionElement.classList.contains('active')) {
                                    questionElement.click();
                                }
                            }, 500);
                        });

                        searchResultsList.appendChild(resultItem);
                    });
                } else {
                    searchResultsList.innerHTML = `
                        <div class="faq-no-results">
                            <i class="fas fa-search"></i>
                            <p>متأسفانه نتیجه‌ای برای جستجوی شما یافت نشد.</p>
                            <p>لطفاً با کلمات کلیدی دیگری جستجو کنید یا با پشتیبانی تماس بگیرید.</p>
                        </div>
                    `;
                }
            });
        });
    </script>
@endsection
