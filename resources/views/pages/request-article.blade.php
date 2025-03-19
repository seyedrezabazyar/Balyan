@extends('layouts.app')

@section('title', 'درخواست مقاله - کتابخانه دیجیتال بَلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .article-request-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); /* رنگ متفاوت برای هدر مقاله */
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .article-request-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .article-request-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .article-request-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .article-request-highlight {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* بخش‌ها */
        .request-section {
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
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); /* رنگ متفاوت برای آیکون‌ها */
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

        .request-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* پیام راهنما */
        .intro-message {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background-color: #f0f9ff;
            border-right: 4px solid #0ea5e9; /* رنگ متفاوت برای بوردر */
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
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .intro-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: #0ea5e9; /* رنگ متفاوت برای آیکون */
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
        .request-methods-grid {
            margin-top: 1.5rem;
        }

        .request-method-card {
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

        .request-method-card::before {
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

        .request-method-card:hover::before {
            transform: translateX(0);
        }

        .request-method-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .request-method-card:hover .method-content h3,
        .request-method-card:hover .method-content p {
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

        .request-method-card:hover .method-icon {
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

        .request-method-card:hover .method-arrow {
            transform: translateX(5px);
            opacity: 1;
        }

        /* بخش اطلاعات درخواست */
        .request-info {
            background: linear-gradient(to right, #f8fafc, #f0f9ff);
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1.5rem;
            border-right: 4px solid #0ea5e9; /* رنگ متفاوت برای بوردر */
        }

        .request-info h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-800);
        }

        .request-info ul {
            padding-right: 1.5rem;
            margin-bottom: 0;
        }

        .request-info li {
            margin-bottom: 0.7rem;
            position: relative;
        }

        .request-info li:last-child {
            margin-bottom: 0;
        }

        .request-info li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #0ea5e9; /* رنگ متفاوت برای آیکون */
            position: absolute;
            right: -1.5rem;
        }

        /* بخش پایانی */
        .request-footer {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); /* رنگ متفاوت برای فوتر */
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .request-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .request-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .request-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .article-request-hero-title {
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

            .request-method-card {
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

            .intro-message {
                flex-direction: column;
                text-align: center;
            }

            .intro-icon {
                margin: 0 auto 1rem;
            }
        }

        /* استایل برای تب‌های سوئیچ بین خدمات */
        .service-tabs {
            margin-top: -25px;
            z-index: 10;
        }

        .tabs-container {
            display: flex;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .tab-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            text-align: center;
            color: var(--gray-700);
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-item:first-child {
            border-radius: 0 15px 15px 0;
        }

        .tab-item:last-child {
            border-radius: 15px 0 0 15px;
        }

        .tab-item.active {
            background-color: rgba(14, 165, 233, 0.05); /* رنگ متفاوت برای تب فعال */
            border-bottom: 3px solid #0ea5e9; /* رنگ متفاوت برای بوردر تب فعال */
            color: #0ea5e9; /* رنگ متفاوت برای متن تب فعال */
        }

        .tab-item:hover:not(.active) {
            background-color: rgba(14, 165, 233, 0.03);
            color: #0ea5e9;
        }

        .tab-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .tab-text {
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Animation for active tab indicator */
        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #0ea5e9; /* رنگ متفاوت برای خط زیر تب فعال */
            animation: tabIndicator 0.3s ease-out;
        }

        @keyframes tabIndicator {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }

        /* استایل کارت‌های مقایسه‌ای */
        .compare-service-card {
            border-radius: 12px;
            padding: 2rem;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .compare-service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23f0f9ff' fill-opacity='0.3' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.2;
            z-index: 0;
        }

        .article-service {
            background-color: #e0f2fe;
            border: 2px solid #0ea5e9;
        }

        .book-service {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
        }

        .compare-service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .compare-service-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 1;
        }

        .article-service .compare-service-icon {
            background-color: #0ea5e9;
            color: white;
        }

        .book-service .compare-service-icon {
            background-color: #3b82f6;
            color: white;
        }

        .compare-service-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .compare-service-list {
            padding-right: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            flex-grow: 1;
        }

        .compare-service-list li {
            margin-bottom: 0.7rem;
            position: relative;
        }

        .compare-service-list li:last-child {
            margin-bottom: 0;
        }

        .article-service .compare-service-list li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #0ea5e9;
            position: absolute;
            right: -1.5rem;
        }

        .book-service .compare-service-list li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #3b82f6;
            position: absolute;
            right: -1.5rem;
        }

        .compare-service-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            align-self: center;
            position: relative;
            z-index: 1;
        }

        .article-btn {
            background-color: #0ea5e9;
            color: white !important;
        }

        .book-btn {
            background-color: #3b82f6;
            color: white !important;
        }

        .compare-service-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* مدیا کوئری برای تب‌ها */
        @media (max-width: 576px) {
            .tab-icon {
                font-size: 1.2rem;
                margin-bottom: 0.3rem;
            }

            .tab-text {
                font-size: 0.9rem;
            }

            .tab-item {
                padding: 1rem 0.5rem;
            }

            .compare-service-card {
                padding: 1.5rem;
            }

            .compare-service-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .compare-service-card h3 {
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }
        }

        /* افکت انیمیشنی */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.3);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(14, 165, 233, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه درخواست مقاله -->
    <div class="article-request-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-newspaper article-request-hero-icon"></i>
                <h1 class="article-request-hero-title">درخواست مقاله</h1>
                <p class="article-request-hero-desc">دسترسی به مقالات علمی و تخصصی مورد نیاز شما</p>
                <div class="article-request-highlight">
                    <i class="fas fa-check-circle me-1"></i>
                    تحویل سریع
                </div>
            </div>
        </div>
    </div>

    <!-- تب‌های سوئیچ بین خدمات -->
    <div class="container mt-n4 mb-4 position-relative">
        <div class="service-tabs">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="tabs-container">
                        <a href="/request-article" class="tab-item active">
                            <i class="fas fa-newspaper tab-icon"></i>
                            <span class="tab-text">درخواست مقاله</span>
                        </a>
                        <a href="/request-book" class="tab-item">
                            <i class="fas fa-book-reader tab-icon"></i>
                            <span class="tab-text">درخواست کتاب</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- پیام راهنما -->
                <div class="intro-message request-card mb-4">
                    <div class="intro-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p>اگر به دنبال مقاله علمی یا تخصصی هستید که دسترسی به آن برای شما دشوار است، نگران نباشید! ما می‌توانیم آن مقاله را برای شما تهیه کنیم. کتابخانه دیجیتال بَلیان، دسترسی به مقالات معتبر از ژورنال‌های علمی سراسر دنیا را برای شما فراهم می‌کند.</p>
                    </div>
                </div>

                <!-- بخش روند کار -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-tasks section-icon"></i>
                        <h2>روند درخواست و دریافت مقاله</h2>
                    </div>
                    <div class="request-card">
                        <div class="row text-center">
                            <div class="col-md-4 mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 text-primary" style="font-size: 2.5rem;">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                    <h4 class="h5 mb-2">ارسال درخواست</h4>
                                    <p class="small text-muted">درخواست خود را از طریق یکی از راه‌های ارتباطی ارسال کنید</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 text-primary" style="font-size: 2.5rem;">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h4 class="h5 mb-2">جستجو و تهیه</h4>
                                    <p class="small text-muted">ما مقاله مورد نظر را جستجو و تهیه می‌کنیم</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 text-primary" style="font-size: 2.5rem;">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <h4 class="h5 mb-2">دریافت مقاله</h4>
                                    <p class="small text-muted">پس از پرداخت هزینه، فایل PDF مقاله را دریافت کنید</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش انواع مقالات -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-file-alt section-icon"></i>
                        <h2>انواع مقالات قابل درخواست</h2>
                    </div>
                    <div class="request-card">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h4 class="h5 mb-3 text-primary"><i class="fas fa-graduation-cap me-2"></i>مقالات دانشگاهی</h4>
                                    <p class="small mb-0">مقالات پژوهشی از ژورنال‌های معتبر داخلی و خارجی در تمامی رشته‌های تحصیلی</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h4 class="h5 mb-3 text-primary"><i class="fas fa-flask me-2"></i>مقالات علمی</h4>
                                    <p class="small mb-0">مقالات تخصصی و علمی از پایگاه‌های داده معتبر مانند ScienceDirect، Springer و IEEE</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h4 class="h5 mb-3 text-primary"><i class="fas fa-book-open me-2"></i>مقالات مروری</h4>
                                    <p class="small mb-0">مقالات مروری جامع که خلاصه‌ای از تحقیقات انجام‌شده در یک زمینه خاص را ارائه می‌دهند</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h4 class="h5 mb-3 text-primary"><i class="fas fa-clipboard me-2"></i>مقالات کنفرانسی</h4>
                                    <p class="small mb-0">مقالات ارائه شده در کنفرانس‌های ملی و بین‌المللی</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش راه‌های ارتباطی -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-comments section-icon"></i>
                        <h2>درخواست مقاله از طریق</h2>
                    </div>
                    <div class="request-card">
                        <p>برای درخواست مقاله مورد نظر خود، می‌توانید از طریق راه های ارتباطی موجود در این صفحه با ما تماس بگیرید. تیم پشتیبانی ما در اسرع وقت پاسخگوی شما خواهد بود.</p>

                        <div class="request-methods-grid">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <a href="https://api.whatsapp.com/send?phone=0989179070298&text=%D8%AF%D8%B1%D8%AE%D9%88%D8%A7%D8%B3%D8%AA%20%D9%85%D9%82%D8%A7%D9%84%D9%87%20%D8%B9%D9%84%D9%85%DB%8C%20%C2%AB%D8%A8%D9%84%DB%8C%D8%A7%D9%86%C2%BB" target="_blank" class="request-method-card whatsapp">
                                        <div class="method-icon">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>ارسال پیام در واتس‌اپ</h3>
                                            <p>پاسخگویی سریع در ساعات کاری</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="https://t.me/seyedrezabazyar" target="_blank" class="request-method-card telegram">
                                        <div class="method-icon">
                                            <i class="fab fa-telegram-plane"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>ارسال پیام در تلگرام</h3>
                                            <p>پشتیبانی ۲۴ ساعته</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="mailto:balyan.ir@gmail.com" class="request-method-card email">
                                        <div class="method-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>ارسال ایمیل</h3>
                                            <p>پاسخگویی در کمتر از ۲۴ ساعت</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <a href="sms:+989179070298" class="request-method-card sms">
                                        <div class="method-icon">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div class="method-content">
                                            <h3>ارسال پیامک</h3>
                                            <p>پاسخگویی در اسرع وقت</p>
                                        </div>
                                        <div class="method-arrow">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="request-info">
                            <h3>اطلاعات مورد نیاز برای درخواست مقاله:</h3>
                            <ul>
                                <li>عنوان دقیق مقاله</li>
                                <li>نام نویسندگان مقاله</li>
                                <li>نام ژورنال یا مجله علمی</li>
                                <li>شماره DOI مقاله (در صورت امکان)</li>
                                <li>لینک مقاله در سایت ناشر یا ژورنال (در صورت وجود)</li>
                                <li>سال انتشار مقاله</li>
                                <li>هرگونه اطلاعات دیگری که ممکن است به ما در یافتن مقاله کمک کند</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- بخش مقایسه خدمات -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-exchange-alt section-icon"></i>
                        <h2>کدام خدمت مناسب شماست؟</h2>
                    </div>
                    <div class="request-card">
                        <p class="text-center mb-4">کتابخانه دیجیتال بَلیان دو خدمت متفاوت برای دسترسی به منابع علمی ارائه می‌دهد:</p>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="compare-service-card article-service">
                                    <div class="compare-service-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h3>درخواست مقاله</h3>
                                    <ul class="compare-service-list">
                                        <li>مقالات علمی و پژوهشی</li>
                                        <li>مقالات ژورنال‌های معتبر</li>
                                        <li>مقالات کنفرانسی</li>
                                        <li>مناسب برای پژوهش‌های علمی و دانشگاهی</li>
                                    </ul>
                                    <a href="/request-article" class="compare-service-btn article-btn">
                                        درخواست مقاله
                                        <i class="fas fa-long-arrow-alt-left me-2"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="compare-service-card book-service">
                                    <div class="compare-service-icon">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h3>درخواست کتاب</h3>
                                    <ul class="compare-service-list">
                                        <li>کتاب‌های الکترونیکی</li>
                                        <li>کتاب‌های علمی و تخصصی</li>
                                        <li>کتاب‌های عمومی و داستانی</li>
                                        <li>مناسب برای مطالعه عمیق‌تر موضوعات</li>
                                    </ul>
                                    <a href="/request-book" class="compare-service-btn book-btn">
                                        درخواست کتاب
                                        <i class="fas fa-long-arrow-alt-left me-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="request-footer">
                    <i class="fas fa-newspaper"></i>
                    <h3>دسترسی به دانش، حق همه است</h3>
                    <p>با تشکر از انتخاب شما برای همکاری با کتابخانه دیجیتالی بلیان، ما متعهد هستیم تا دسترسی به مقالات علمی و تخصصی را برای همه پژوهشگران، دانشجویان و علاقه‌مندان فراهم کنیم. ما برای ارتقای سطح علمی جامعه تلاش می‌کنیم و امیدواریم با ارائه این خدمات، گامی مؤثر در مسیر پیشرفت علمی کشور برداریم.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.request-card, .request-method-card, .intro-message');

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
            const buttons = document.querySelectorAll('.btn, .request-method-card');
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

            // پالس آیکون پیام راهنما
            const introIcon = document.querySelector('.intro-icon');
            if (introIcon) {
                introIcon.style.animation = 'pulse 2s infinite';
            }
        });
    </script>
@endpush
