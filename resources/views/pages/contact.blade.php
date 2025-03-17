@extends('layouts.app')

@section('title', 'تماس با ما - کتابخانه دیجیتال')

@push('styles')
    <style>
        /* هدر صفحه */
        .contact-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .contact-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .contact-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .contact-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .contact-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* بخش‌ها */
        .contact-section {
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

        .contact-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* راه‌های ارتباطی */
        /* استایل پیام راهنما */
        .intro-message {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background-color: #f0f9ff;
            border-right: 4px solid #3b82f6;
            position: relative;
            overflow: hidden;
        }

        .intro-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%233b82f6' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .intro-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-left: 1rem;
            position: relative;
            z-index: 1;
        }

        .intro-message p {
            margin-bottom: 0;
            line-height: 1.7;
            color: var(--gray-700);
            position: relative;
            z-index: 1;
        }

        /* راه‌های ارتباطی با طراحی جدید */
        .contact-methods-grid {
            margin-top: 1.5rem;
        }

        .contact-method-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-decoration: none !important; /* مهم: برای جلوگیری از تغییر استایل لینک‌ها */
            color: #fff !important; /* مهم: همیشه رنگ متن سفید باشد */
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .contact-method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 0;
        }

        .contact-method-card:hover::before {
            transform: translateX(0);
        }

        .contact-method-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .contact-method-card:hover .method-content h3,
        .contact-method-card:hover .method-content p {
            color: #fff !important; /* مهم: رنگ متن در حالت hover همیشه سفید باشد */
        }

        .whatsapp {
            background-color: #25D366;
        }

        .telegram {
            background-color: #0088cc;
        }

        .email {
            background-color: #EA4335;
        }

        .sms {
            background-color: #FF9800; /* تغییر به نارنجی */
        }

        .method-icon {
            width: 60px;
            height: 60px;
            min-width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-left: 1rem;
            font-size: 1.8rem;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            flex-shrink: 0;
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .contact-method-card:hover .method-icon {
            transform: scale(1.1);
        }

        .method-content {
            flex: 1;
            z-index: 1;
        }

        .method-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #fff !important; /* مهم: همیشه رنگ متن سفید باشد */
        }

        .method-content p {
            font-size: 0.9rem;
            margin-bottom: 0;
            opacity: 0.9;
            color: #fff !important; /* مهم: همیشه رنگ متن سفید باشد */
        }

        .method-arrow {
            width: 30px;
            height: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
            transform: translateX(0);
            transition: transform 0.3s ease;
            opacity: 0.7;
            z-index: 1;
        }

        .contact-method-card:hover .method-arrow {
            transform: translateX(5px);
            opacity: 1;
        }

        /* اطلاعات تماس */
        .contact-info-list {
            margin-top: 1.5rem;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            padding: 1.5rem;
            background-color: #f8fafc;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.03);
            height: 100%;
        }

        .contact-info-item:hover {
            background-color: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transform: translateY(-5px);
        }

        .location-item {
            background: linear-gradient(to right, #f8fafc, #f0f9ff);
        }

        .info-icon {
            width: 55px;
            height: 55px;
            min-width: 55px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            margin-left: 1rem;
            flex-shrink: 0;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.2);
        }

        .contact-info-item:hover .info-icon {
            transform: scale(1.1);
        }

        .location-item .info-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }

        .info-content {
            flex: 1;
        }

        .info-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .info-content p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        .info-content .working-hours {
            font-size: 0.85rem;
            color: var(--gray-500);
            display: inline-block;
            padding: 0.3rem 0.7rem;
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 20px;
            margin-top: 0.5rem;
        }

        /* نقشه */
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 1.5rem;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .map-info {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
            max-width: 300px;
        }

        .map-info-icon {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-left: 1rem;
        }

        .map-info-content h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .map-info-content p {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        /* سوالات متداول */
        .faq-accordions {
            margin-top: 1.5rem;
        }

        .accordion-item {
            margin-bottom: 1rem;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        }

        .accordion-button {
            padding: 1.25rem;
            font-weight: 600;
            background-color: #f8fafc;
            color: var(--gray-800);
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(59, 130, 246, 0.05);
            color: #3b82f6;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-body {
            padding: 1.25rem;
            line-height: 1.7;
            background-color: white;
        }

        /* بخش پایانی */
        .contact-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .contact-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .contact-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .contact-hero-title {
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

            .contact-info-list {
                grid-template-columns: 1fr;
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

            .contact-method-card {
                flex-direction: column;
                text-align: center;
                padding: 2rem 1.5rem;
            }

            .method-icon {
                margin: 0 auto 1rem;
            }

            .method-arrow {
                display: none;
            }

            .contact-info-item {
                flex-direction: column;
                text-align: center;
            }

            .info-icon {
                margin: 0 auto 1rem;
            }

            .map-info {
                position: static;
                margin: 0 auto 1.5rem;
                max-width: 100%;
            }

            .intro-message {
                flex-direction: column;
                text-align: center;
            }

            .intro-icon {
                margin: 0 auto 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه تماس با ما -->
    <div class="contact-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-headset contact-hero-icon"></i>
                <h1 class="contact-hero-title">تماس با بَلیان</h1>
                <p class="contact-hero-desc">ما همیشه آماده پاسخگویی به شما هستیم</p>
                <div class="contact-hero-update">
                    پشتیبانی ۲۴/۷
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- پیام راهنما -->
                <div class="intro-message contact-card mb-4">
                    <div class="intro-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <p>از طریق راه‌های ارتباطی موجود در این صفحه، می‌توانید با پشتیبان بَلیان ارتباط برقرار کنید. لطفا قبل از ارسال پیام، صفحه سوالات متداول را مطالعه نمایید، اگر پاسخ سوال خود را نیافتید، به پشتیبانی سایت پیام بفرستید.</p>
                </div>

                <!-- بخش سوالات متداول -->
                <section class="contact-section">
                    <div class="section-header">
                        <i class="fas fa-question-circle section-icon"></i>
                        <h2>سوالات متداول</h2>
                    </div>
                    <div class="contact-card">
                        <p>در این بخش، پاسخ سوالات متداول درباره نحوه ارتباط با ما و خدمات پشتیبانی را می‌توانید مشاهده کنید.</p>

                        <div class="faq-accordions">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                            کتاب‌ها بلافاصله پس از پرداخت قابل دانلود هستند؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>بله. بلافاصله پس از پرداخت لینک دانلود فایل برای شما فعال شده و یک نسخه دیگر از لینک دانلود به ایمیل شما ارسال می‌شود.</p>
                                            <p>در صورتی که که قبل از خرید، در سایت ثبت نام کرده باشید و به عنوان کاربر سایت خرید را انجام داده باشید، علاوه بر ایمیل دریافت شده که حاوی لینک دانلود کتاب است، لینک دانلود در «پنل کاربری» قسمت «دانلود ها» هم قابل دسترسی خواهد بود و می‌توانید تا 100 روز پس از خرید، لینک دانلود کتاب را از پشتیبانی دریافت نمایید.</p>
                                            <p>در صورتی که در هنگام خرید، در سایت ثبت‌نام نکرده باشید، علاوه بر ایمیل دریافت شده که حاوی لینک دانلود کتاب است، می‌توانید تا 30 روز پس از خرید، میتوانید از طریق واتس‌اپ، لینک دانلود کتاب را از پشتیبانی دریافت نمایید.</p>
                                            <p>به دلیل اینکه سرور های سایت بلیان در ایران قرار دارند، اگر فایل کتاب روی سرور های بلیان قرار داشته باشد، سرعت دانلود شما 4 برابر حالت عادی خواهد بود، همچنین هزینه دانلود از سایت بلیان نیم‌بها خواهد بود.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                            کتاب روی دستگاه شما باز نمی‌شود؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            در صورتی که پس از دانلود کتاب، فایل کتاب روی دستگاه شما باز نمی‌شود، باید نرم افزار کتابخوان را روی دستگاه خود نصب نمایید. جهت دانلود نرم افزار کتابخوان، به صفحه «نحوه باز کردن کتاب‌های الکترونیکی در سیستم‌عامل‌های مختلف» مراجعه نمایید.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                            فرمت کتاب موردنظر موجود نیست؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            در صورتی که کتابی را از سایت خریداری کرده‌اید و به فرمت های دیگری مانند PDF و EPUB یا سایر فرمت‌ها نیاز دارید، به پشتیبانی درخواست بفرستید؛ در صورتی که سایر فرمت های آن کتاب موجود باشد، برای شما ارسال خواهد شد.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('faq') }}" class="btn btn-outline-primary">
                                <i class="fas fa-question-circle me-2"></i>
                                مشاهده همه سوالات متداول
                            </a>
                        </div>
                    </div>
                </section>

                <!-- بخش راه‌های ارتباطی -->
                <section class="contact-section">
                    <div class="section-header">
                        <i class="fas fa-comments section-icon"></i>
                        <h2>راه‌های ارتباطی</h2>
                    </div>
                    <div class="contact-card">
                        <p>برای ارتباط با پشتیبانی بلیان، می‌توانید از یکی از روش‌های زیر استفاده کنید. تیم پشتیبانی ما در اسرع وقت پاسخگوی شما خواهد بود.</p>

                        <div class="contact-methods-grid">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <a href="https://api.whatsapp.com/send?phone=0989179070298&text=%D8%AF%D8%B1%D8%AE%D9%88%D8%A7%D8%B3%D8%AA%20%D9%BE%D8%B4%D8%AA%DB%8C%D8%A8%D8%A7%D9%86%DB%8C%20%C2%AB%D8%A8%D9%84%DB%8C%D8%A7%D9%86%C2%BB" target="_blank" class="contact-method-card whatsapp">
                                        <div class="method-icon">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>واتس‌اپ</h3>
                                            <p>پاسخگویی سریع در ساعات کاری</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="https://t.me/seyedrezabazyar" target="_blank" class="contact-method-card telegram">
                                        <div class="method-icon">
                                            <i class="fab fa-telegram-plane"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>تلگرام</h3>
                                            <p>پشتیبانی ۲۴ ساعته</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="mailto:balyan.ir@gmail.com" class="contact-method-card email">
                                        <div class="method-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>ایمیل</h3>
                                            <p>پاسخگویی در کمتر از ۲۴ ساعت</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="sms:+989179070298" class="contact-method-card sms">
                                        <div class="method-icon">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>پیامک</h3>
                                            <p>پاسخگویی در اسرع وقت</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش اطلاعات تماس -->
                <section class="contact-section">
                    <div class="section-header">
                        <i class="fas fa-phone-alt section-icon"></i>
                        <h2>اطلاعات تماس</h2>
                    </div>
                    <div class="contact-card">
                        <p>شما می‌توانید از طریق اطلاعات زیر، با دفتر مرکزی کتابخانه دیجیتال بلیان تماس بگیرید یا به صورت حضوری مراجعه نمایید.</p>

                        <div class="contact-info-list">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="contact-info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <h3>شماره تماس دفتر</h3>
                                            <p>۰۷۱-۴۲۳۶۳۱۱۱</p>
                                            <span class="working-hours">پاسخگویی در ساعات کاری</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="contact-info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <h3>شماره همراه پشتیبانی</h3>
                                            <p>۰۹۱۷-۹۰۷-۰۲۹۸</p>
                                            <span class="working-hours">پاسخگویی ۲۴ ساعته</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="contact-info-item location-item">
                                        <div class="info-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <h3>آدرس دفتر مرکزی</h3>
                                            <p>استان فارس، شهرستان کازرون، بلیان، کوچه شهید رجبی، پلاک: ۱</p>
                                            <span class="working-hours">کد پستی: ۷۳۱۷۱۶۶۱۸۷</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش نقشه -->
                <section class="contact-section">
                    <div class="section-header">
                        <i class="fas fa-map-marked-alt section-icon"></i>
                        <h2>موقعیت دفتر مرکزی</h2>
                    </div>
                    <div class="contact-card">
                        <p>دفتر مرکزی ما در مرکز شهر تهران واقع شده و دسترسی به آن از طریق مترو و اتوبوس امکان‌پذیر است. برای مشاهده مسیریابی دقیق، می‌توانید از نقشه زیر استفاده کنید.</p>

                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13635.17747319432!2d51.6418126!3d29.6191439!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fb3de3f4ee3ceff%3A0xa70b7a97d4b88ac9!2sBeliyan%2C%20Fars%20Province%2C%20Iran!5e0!3m2!1sen!2s!4v1710676698662!5m2!1sen!2s" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                            <div class="map-info">
                                <div class="map-info-icon">
                                    <i class="fas fa-map-pin"></i>
                                </div>
                                <div class="map-info-content">
                                    <h4>دفتر مرکزی بَلیان</h4>
                                    <p>استان فارس، شهرستان کازرون، بلیان</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="contact-footer">
                    <i class="fas fa-headset"></i>
                    <h3>تعهد ما به پاسخگویی سریع</h3>
                    <p>در کتابخانه دیجیتال بلیان، ما متعهد هستیم به تمامی پیام‌ها و تماس‌های شما در کمترین زمان ممکن پاسخ دهیم. رضایت شما، افتخار ماست.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.contact-info-item, .contact-method-card, .accordion-item, .intro-message');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        }, 100 * Array.from(animateItems).indexOf(entry.target));
                    }
                });
            }, { threshold: 0.1 });

            animateItems.forEach(item => {
                item.style.opacity = 0;
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(item);
            });

            // افکت موج (ripple) برای دکمه‌ها و کارت‌های ارتباطی
            const buttons = document.querySelectorAll('.btn, .contact-method-card');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.zIndex = '0';

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // افکت انیمیشن برای دکمه‌های پیام
            const styleSheet = document.createElement('style');
            styleSheet.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }

                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.3);
                    }
                    70% {
                        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
                    }
                }
            `;
            document.head.appendChild(styleSheet);

            // پالس آیکون پیام راهنما
            const introIcon = document.querySelector('.intro-icon');
            if (introIcon) {
                introIcon.style.animation = 'pulse 2s infinite';
            }
        });
    </script>
@endpush
