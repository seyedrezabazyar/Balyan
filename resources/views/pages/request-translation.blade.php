@extends('layouts.app')

@section('title', 'درخواست ترجمه کتاب و مقاله - کتابخانه دیجیتال بَلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .translation-request-hero {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); /* رنگ بنفش برای هدر ترجمه */
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .translation-request-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .translation-request-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .translation-request-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .translation-request-highlight {
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
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); /* رنگ بنفش برای آیکون‌ها */
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
            background-color: #f5f3ff;
            border-right: 4px solid #8b5cf6; /* رنگ بنفش برای بوردر */
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
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%238b5cf6' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .intro-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: #8b5cf6; /* رنگ بنفش برای آیکون */
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
            background: linear-gradient(to right, #f8fafc, #f5f3ff);
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1.5rem;
            border-right: 4px solid #8b5cf6; /* رنگ بنفش برای بوردر */
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
            color: #8b5cf6; /* رنگ بنفش برای آیکون */
            position: absolute;
            right: -1.5rem;
        }

        /* بخش پایانی */
        .request-footer {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); /* رنگ بنفش برای فوتر */
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
            line-height: 1.6;
        }

        .request-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
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
            background-color: rgba(139, 92, 246, 0.05); /* رنگ بنفش برای تب فعال */
            border-bottom: 3px solid #8b5cf6; /* رنگ بنفش برای بوردر تب فعال */
            color: #8b5cf6; /* رنگ بنفش برای متن تب فعال */
        }

        .tab-item:hover:not(.active) {
            background-color: rgba(139, 92, 246, 0.03);
            color: #8b5cf6;
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
            background-color: #8b5cf6; /* رنگ بنفش برای خط زیر تب فعال */
            animation: tabIndicator 0.3s ease-out;
        }

        @keyframes tabIndicator {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }

        /* استایل کارت‌های انتخاب نوع ترجمه */
        .translation-type-card {
            border-radius: 12px;
            padding: 2rem;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .translation-type-card::before {
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

        .article-translation {
            background-color: #f5f3ff;
            border: 2px solid #8b5cf6;
        }

        .book-translation {
            background-color: #ede9fe;
            border: 2px solid #7c3aed;
        }

        .translation-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .translation-type-icon {
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

        .article-translation .translation-type-icon {
            background-color: #8b5cf6;
            color: white;
        }

        .book-translation .translation-type-icon {
            background-color: #7c3aed;
            color: white;
        }

        .translation-type-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .translation-type-list {
            padding-right: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            flex-grow: 1;
        }

        .translation-type-list li {
            margin-bottom: 0.7rem;
            position: relative;
        }

        .translation-type-list li:last-child {
            margin-bottom: 0;
        }

        .article-translation .translation-type-list li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #8b5cf6;
            position: absolute;
            right: -1.5rem;
        }

        .book-translation .translation-type-list li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #7c3aed;
            position: absolute;
            right: -1.5rem;
        }

        .translation-type-btn {
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
            background-color: #8b5cf6;
            color: white !important;
        }

        .book-btn {
            background-color: #7c3aed;
            color: white !important;
        }

        .translation-type-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* استایل جدول تعرفه */
        .pricing-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .pricing-table thead th {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            border: none;
        }

        .pricing-table th:first-child {
            text-align: right;
        }

        .pricing-table tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        .pricing-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .pricing-table tbody td {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .pricing-table tbody td:first-child {
            text-align: right;
            font-weight: 500;
        }

        .highlight-price {
            font-weight: 700;
            color: #8b5cf6;
        }

        /* استایل برای کارت‌های مراحل کار */
        .process-card {
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .process-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .process-number {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-left: 1.5rem;
        }

        .process-content h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .process-content p {
            margin-bottom: 0;
            color: var(--gray-600);
        }

        /* استایل برای کارت‌های ویژگی‌ها */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .feature-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: flex-start;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
            border-radius: 10px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-left: 1rem;
        }

        .feature-content h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .feature-content p {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: var(--gray-600);
        }

        /* استایل برای تب‌های نوع ترجمه */
        .translation-type-tabs {
            display: flex;
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 0.5rem;
            margin-bottom: 2rem;
        }

        .translation-type-tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .translation-type-tab.active {
            background-color: #8b5cf6;
            color: white;
        }

        .translation-type-tab:not(.active):hover {
            background-color: rgba(139, 92, 246, 0.1);
        }

        .translation-type-content {
            display: none;
        }

        .translation-type-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .translation-request-hero-title {
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

            .pricing-table {
                font-size: 0.9rem;
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

            .process-card {
                flex-direction: column;
                text-align: center;
            }

            .process-number {
                margin: 0 auto 1rem;
            }

            .feature-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .feature-icon {
                margin: 0 auto 1rem;
            }

            .pricing-table thead th {
                font-size: 0.8rem;
                padding: 0.5rem;
            }

            .pricing-table tbody td {
                font-size: 0.8rem;
                padding: 0.5rem;
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
                box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.3);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(139, 92, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(139, 92, 246, 0);
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه درخواست ترجمه -->
    <div class="translation-request-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-language translation-request-hero-icon"></i>
                <h1 class="translation-request-hero-title">درخواست ترجمه کتاب و مقاله</h1>
                <p class="translation-request-hero-desc">ترجمه تخصصی و با کیفیت متون علمی و تخصصی</p>
                <div class="translation-request-highlight">
                    <i class="fas fa-check-circle me-1"></i>
                    ترجمه تخصصی با کیفیت بالا
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- پیام راهنما -->
                <div class="intro-message request-card mb-4">
                    <div class="intro-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p>خدمات ترجمه تخصصی کتابخانه دیجیتال بَلیان، ترجمه دقیق و با کیفیت کتاب‌ها و مقالات علمی را برای شما فراهم می‌کند. تیم مترجمان متخصص ما، با تسلط بر زبان‌های مختلف و آشنایی با حوزه‌های تخصصی، آماده ارائه خدمات ترجمه به شما هستند. شما می‌توانید با انتخاب نوع ترجمه (کتاب یا مقاله) و ارسال درخواست خود، از خدمات ترجمه ما بهره‌مند شوید.</p>
                    </div>
                </div>

                <!-- بخش انتخاب نوع ترجمه -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-exchange-alt section-icon"></i>
                        <h2>انتخاب نوع ترجمه</h2>
                    </div>
                    <div class="request-card">
                        <p class="text-center mb-4">لطفاً نوع ترجمه مورد نظر خود را انتخاب کنید:</p>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="translation-type-card article-translation" id="article-translation-card">
                                    <div class="translation-type-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h3>ترجمه مقاله</h3>
                                    <ul class="translation-type-list">
                                        <li>ترجمه مقالات علمی و پژوهشی</li>
                                        <li>ترجمه مقالات ژورنال‌های معتبر</li>
                                        <li>ترجمه مقالات کنفرانسی</li>
                                        <li>ترجمه چکیده و مقدمه مقالات</li>
                                    </ul>
                                    <a href="#article-translation-section" class="translation-type-btn article-btn">
                                        انتخاب ترجمه مقاله
                                        <i class="fas fa-long-arrow-alt-left me-2"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="translation-type-card book-translation" id="book-translation-card">
                                    <div class="translation-type-icon">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h3>ترجمه کتاب</h3>
                                    <ul class="translation-type-list">
                                        <li>ترجمه کتاب‌های علمی و تخصصی</li>
                                        <li>ترجمه کتاب‌های دانشگاهی</li>
                                        <li>ترجمه فصل‌های خاص از کتاب</li>
                                        <li>ترجمه کتاب‌های عمومی</li>
                                    </ul>
                                    <a href="#book-translation-section" class="translation-type-btn book-btn">
                                        انتخاب ترجمه کتاب
                                        <i class="fas fa-long-arrow-alt-left me-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش تعرفه ترجمه -->
                <section class="request-section" id="pricing-section">
                    <div class="section-header">
                        <i class="fas fa-tags section-icon"></i>
                        <h2>تعرفه خدمات ترجمه</h2>
                    </div>
                    <div class="request-card">
                        <div class="translation-type-tabs">
                            <div class="translation-type-tab active" data-target="article-pricing">تعرفه ترجمه مقاله</div>
                            <div class="translation-type-tab" data-target="book-pricing">تعرفه ترجمه کتاب</div>
                        </div>

                        <div class="translation-type-content active" id="article-pricing">
                            <div class="table-responsive">
                                <table class="pricing-table">
                                    <thead>
                                    <tr>
                                        <th>نوع ترجمه مقاله</th>
                                        <th>قیمت (هر صفحه)</th>
                                        <th>زمان تحویل (هر 10 صفحه)</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>ترجمه عادی مقاله</td>
                                        <td><span class="highlight-price">35,000 تومان</span></td>
                                        <td>3-4 روز کاری</td>
                                        <td>ترجمه روان و دقیق متن</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه تخصصی مقاله</td>
                                        <td><span class="highlight-price">45,000 تومان</span></td>
                                        <td>3-5 روز کاری</td>
                                        <td>ترجمه توسط مترجم متخصص در حوزه موضوعی</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه فوری مقاله</td>
                                        <td><span class="highlight-price">60,000 تومان</span></td>
                                        <td>1-2 روز کاری</td>
                                        <td>ترجمه در کوتاه‌ترین زمان ممکن</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه چکیده مقاله</td>
                                        <td><span class="highlight-price">40,000 تومان</span></td>
                                        <td>1 روز کاری</td>
                                        <td>ترجمه دقیق و تخصصی چکیده</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="mt-3 small text-muted">* هر صفحه معادل 250 کلمه محاسبه می‌شود.</p>
                        </div>

                        <div class="translation-type-content" id="book-pricing">
                            <div class="table-responsive">
                                <table class="pricing-table">
                                    <thead>
                                    <tr>
                                        <th>نوع ترجمه کتاب</th>
                                        <th>قیمت (هر صفحه)</th>
                                        <th>زمان تحویل (هر 50 صفحه)</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>ترجمه عادی کتاب</td>
                                        <td><span class="highlight-price">30,000 تومان</span></td>
                                        <td>15-20 روز کاری</td>
                                        <td>ترجمه روان و دقیق متن</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه تخصصی کتاب</td>
                                        <td><span class="highlight-price">40,000 تومان</span></td>
                                        <td>20-25 روز کاری</td>
                                        <td>ترجمه توسط مترجم متخصص در حوزه موضوعی</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه فصل خاص از کتاب</td>
                                        <td><span class="highlight-price">45,000 تومان</span></td>
                                        <td>5-7 روز کاری</td>
                                        <td>ترجمه فصل‌های منتخب از کتاب</td>
                                    </tr>
                                    <tr>
                                        <td>ترجمه ویرایش شده کتاب</td>
                                        <td><span class="highlight-price">50,000 تومان</span></td>
                                        <td>25-30 روز کاری</td>
                                        <td>ترجمه با ویرایش ادبی و تخصصی</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="mt-3 small text-muted">* هر صفحه معادل 250 کلمه محاسبه می‌شود.</p>
                            <p class="small text-muted">* برای کتاب‌های حجیم (بیش از 300 صفحه) تخفیف ویژه در نظر گرفته می‌شود.</p>
                        </div>

                        <div class="request-info mt-4">
                            <h3>نکات مهم درباره تعرفه‌ها:</h3>
                            <ul>
                                <li>قیمت‌های فوق برای ترجمه از زبان انگلیسی به فارسی و بالعکس است.</li>
                                <li>برای سایر زبان‌ها (آلمانی، فرانسوی، عربی و...) ممکن است تعرفه متفاوت باشد.</li>
                                <li>هزینه نهایی پس از بررسی متن و تعیین سطح تخصصی بودن آن محاسبه می‌شود.</li>
                                <li>برای سفارش‌های حجیم، تخفیف ویژه در نظر گرفته می‌شود.</li>
                                <li>زمان تحویل بسته به حجم کار و پیچیدگی متن ممکن است متغیر باشد.</li>
                                <li>هزینه ویرایش و صفحه‌آرایی به صورت جداگانه محاسبه می‌شود.</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- بخش ترجمه مقاله -->
                <section class="request-section" id="article-translation-section">
                    <div class="section-header">
                        <i class="fas fa-newspaper section-icon"></i>
                        <h2>ترجمه مقاله</h2>
                    </div>
                    <div class="request-card">
                        <p>خدمات ترجمه مقاله کتابخانه دیجیتال بَلیان، شامل ترجمه انواع مقالات علمی، پژوهشی، کنفرانسی و تخصصی است. مترجمان متخصص ما، با تسلط بر اصطلاحات تخصصی هر حوزه، ترجمه‌ای دقیق و روان از مقالات شما ارائه می‌دهند.</p>

                        <h4 class="mt-4 mb-3">ویژگی‌های ترجمه مقاله:</h4>
                        <div class="feature-grid">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>مترجمان متخصص</h4>
                                    <p>ترجمه توسط متخصصان هر حوزه موضوعی</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-spell-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>ویرایش تخصصی</h4>
                                    <p>بازبینی و ویرایش متن توسط ویراستاران حرفه‌ای</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>کنترل کیفیت</h4>
                                    <p>بررسی چندمرحله‌ای برای اطمینان از کیفیت ترجمه</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>تحویل سریع</h4>
                                    <p>ارائه ترجمه در کوتاه‌ترین زمان ممکن</p>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">حوزه‌های تخصصی ترجمه مقاله:</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>پزشکی و علوم زیستی</li>
                                    <li>مهندسی و فناوری</li>
                                    <li>علوم کامپیوتر</li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>علوم انسانی و اجتماعی</li>
                                    <li>اقتصاد و مدیریت</li>
                                    <li>فیزیک و شیمی</li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>روانشناسی و علوم تربیتی</li>
                                    <li>حقوق و علوم سیاسی</li>
                                    <li>هنر و معماری</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش ترجمه کتاب -->
                <section class="request-section" id="book-translation-section">
                    <div class="section-header">
                        <i class="fas fa-book-reader section-icon"></i>
                        <h2>ترجمه کتاب</h2>
                    </div>
                    <div class="request-card">
                        <p>خدمات ترجمه کتاب کتابخانه دیجیتال بَلیان، شامل ترجمه انواع کتاب‌های علمی، تخصصی، دانشگاهی و عمومی است. تیم ترجمه ما، با بهره‌گیری از مترجمان متخصص و باتجربه، ترجمه‌ای یکدست، روان و دقیق از کتاب‌ها ارائه می‌دهد.</p>

                        <h4 class="mt-4 mb-3">ویژگی‌های ترجمه کتاب:</h4>
                        <div class="feature-grid">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>تیم ترجمه</h4>
                                    <p>ترجمه توسط تیمی از مترجمان متخصص</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>ویرایش ادبی</h4>
                                    <p>ویرایش ادبی و زبانی متن ترجمه شده</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>صفحه‌آرایی</h4>
                                    <p>صفحه‌آرایی حرفه‌ای و آماده‌سازی برای چاپ</p>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>بازبینی نهایی</h4>
                                    <p>بازبینی و کنترل نهایی قبل از تحویل</p>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">انواع کتاب‌های قابل ترجمه:</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>کتاب‌های علمی و دانشگاهی</li>
                                    <li>کتاب‌های تخصصی مهندسی</li>
                                    <li>کتاب‌های پزشکی و سلامت</li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>کتاب‌های مدیریت و کسب‌وکار</li>
                                    <li>کتاب‌های روانشناسی و خودیاری</li>
                                    <li>کتاب‌های فلسفه و علوم انسانی</li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <ul>
                                    <li>کتاب‌های کودک و نوجوان</li>
                                    <li>کتاب‌های ادبی و داستانی</li>
                                    <li>کتاب‌های هنر و معماری</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش روند کار -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-tasks section-icon"></i>
                        <h2>مراحل درخواست و انجام ترجمه</h2>
                    </div>
                    <div class="request-card">
                        <div class="process-card">
                            <div class="process-number">1</div>
                            <div class="process-content">
                                <h4>ارسال درخواست</h4>
                                <p>درخواست خود را از طریق یکی از راه‌های ارتباطی ارسال کنید و فایل متن مورد نظر را ضمیمه نمایید.</p>
                            </div>
                        </div>
                        <div class="process-card">
                            <div class="process-number">2</div>
                            <div class="process-content">
                                <h4>بررسی و برآورد هزینه</h4>
                                <p>کارشناسان ما متن را بررسی کرده و هزینه و زمان تقریبی ترجمه را به شما اعلام می‌کنند.</p>
                            </div>
                        </div>
                        <div class="process-card">
                            <div class="process-number">3</div>
                            <div class="process-content">
                                <h4>پرداخت و شروع ترجمه</h4>
                                <p>پس از توافق و پرداخت پیش‌پرداخت، کار ترجمه آغاز می‌شود.</p>
                            </div>
                        </div>
                        <div class="process-card">
                            <div class="process-number">4</div>
                            <div class="process-content">
                                <h4>ترجمه و ویرایش</h4>
                                <p>متن توسط مترجمان متخصص ترجمه شده و سپس مراحل ویرایش و بازبینی را طی می‌کند.</p>
                            </div>
                        </div>
                        <div class="process-card">
                            <div class="process-number">5</div>
                            <div class="process-content">
                                <h4>تحویل نهایی</h4>
                                <p>پس از اتمام کار و پرداخت مابقی هزینه، فایل نهایی ترجمه به شما تحویل داده می‌شود.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش راه‌های ارتباطی -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-comments section-icon"></i>
                        <h2>درخواست ترجمه از طریق</h2>
                    </div>
                    <div class="request-card">
                        <p>برای درخواست ترجمه کتاب یا مقاله مورد نظر خود، می‌توانید از طریق راه های ارتباطی موجود در این صفحه با ما تماس بگیرید. تیم پشتیبانی ما در اسرع وقت پاسخگوی شما خواهد بود.</p>

                        <div class="request-methods-grid">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <a href="https://api.whatsapp.com/send?phone=0989179070298&text=%D8%AF%D8%B1%D8%AE%D9%88%D8%A7%D8%B3%D8%AA%20%D8%AA%D8%B1%D8%AC%D9%85%D9%87%20%C2%AB%D8%A8%D9%84%DB%8C%D8%A7%D9%86%C2%BB" target="_blank" class="request-method-card whatsapp">
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
                            <h3>اطلاعات مورد نیاز برای درخواست ترجمه:</h3>
                            <ul>
                                <li>نوع ترجمه (کتاب یا مقاله)</li>
                                <li>عنوان و موضوع متن</li>
                                <li>تعداد صفحات یا کلمات</li>
                                <li>زبان مبدأ و مقصد</li>
                                <li>حوزه تخصصی متن</li>
                                <li>مهلت زمانی مورد نظر برای تحویل</li>
                                <li>هرگونه توضیح یا نیازمندی خاص</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- بخش پرسش‌های متداول -->
                <section class="request-section">
                    <div class="section-header">
                        <i class="fas fa-question-circle section-icon"></i>
                        <h2>پرسش‌های متداول</h2>
                    </div>
                    <div class="request-card">
                        <div class="accordion" id="translationFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        هزینه ترجمه چگونه محاسبه می‌شود؟
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#translationFAQ">
                                    <div class="accordion-body">
                                        هزینه ترجمه بر اساس تعداد صفحات یا کلمات متن، نوع متن (عمومی یا تخصصی)، زبان مبدأ و مقصد، و زمان تحویل محاسبه می‌شود. هر صفحه معادل 250 کلمه در نظر گرفته می‌شود. برای دریافت برآورد دقیق هزینه، می‌توانید متن خود را برای ما ارسال کنید.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        چه زبان‌هایی را پشتیبانی می‌کنید؟
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#translationFAQ">
                                    <div class="accordion-body">
                                        ما خدمات ترجمه را در زبان‌های مختلفی ارائه می‌دهیم، از جمله انگلیسی، فارسی، عربی، فرانسوی، آلمانی، اسپانیایی، ایتالیایی، روسی و چینی. برای سایر زبان‌ها، لطفاً با ما تماس بگیرید تا امکان‌سنجی انجام شود.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        زمان تحویل ترجمه چقدر است؟
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#translationFAQ">
                                    <div class="accordion-body">
                                        زمان تحویل بسته به حجم متن، پیچیدگی موضوع و نوع سفارش (عادی یا فوری) متغیر است. به طور میانگین، برای ترجمه مقاله 10 صفحه‌ای حدود 3-4 روز کاری و برای کتاب 50 صفحه‌ای حدود 15-20 روز کاری زمان نیاز است. برای سفارش‌های فوری، با پرداخت هزینه اضافی، زمان تحویل کاهش می‌یابد.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        آیا امکان ترجمه بخشی از کتاب وجود دارد؟
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#translationFAQ">
                                    <div class="accordion-body">
                                        بله، ما امکان ترجمه فصل‌های خاص یا بخش‌هایی از کتاب را فراهم می‌کنیم. شما می‌توانید فصل‌های مورد نظر خود را مشخص کنید و ما ترجمه آن بخش‌ها را انجام می‌دهیم. این گزینه برای دانشجویان و پژوهشگرانی که به بخش خاصی از کتاب نیاز دارند، مناسب است.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        نحوه پرداخت هزینه چگونه است؟
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#translationFAQ">
                                    <div class="accordion-body">
                                        معمولاً 50% از هزینه به عنوان پیش‌پرداخت قبل از شروع کار و 50% باقیمانده پس از اتمام ترجمه و قبل از تحویل نهایی دریافت می‌شود. برای پروژه‌های بزرگ، ممکن است پرداخت به صورت مرحله‌ای انجام شود. روش‌های پرداخت شامل واریز به حساب بانکی، پرداخت آنلاین و کیف پول الکترونیکی است.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="request-footer">
                    <i class="fas fa-language"></i>
                    <h3>ترجمه تخصصی، پلی میان زبان‌ها و فرهنگ‌ها</h3>
                    <p>با تشکر از انتخاب شما برای همکاری با کتابخانه دیجیتالی بلیان، ما متعهد هستیم تا خدمات ترجمه با کیفیت و تخصصی را برای شما فراهم کنیم. هدف ما انتقال دقیق و روان مفاهیم و ایده‌ها از یک زبان به زبان دیگر است تا مرزهای زبانی مانعی برای دسترسی به دانش و اطلاعات نباشد.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.request-card, .request-method-card, .intro-message, .process-card, .feature-card');

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
            const buttons = document.querySelectorAll('.btn, .request-method-card, .translation-type-btn');
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

            // تغییر تب‌های تعرفه
            const translationTypeTabs = document.querySelectorAll('.translation-type-tab');
            translationTypeTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // حذف کلاس active از همه تب‌ها
                    translationTypeTabs.forEach(t => t.classList.remove('active'));
                    // اضافه کردن کلاس active به تب کلیک شده
                    this.classList.add('active');

                    // حذف کلاس active از همه محتواها
                    const contents = document.querySelectorAll('.translation-type-content');
                    contents.forEach(c => c.classList.remove('active'));

                    // نمایش محتوای مرتبط با تب
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('active');
                });
            });

            // اسکرول نرم به بخش‌ها
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
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

            // انیمیشن کارت‌های انتخاب نوع ترجمه
            const articleCard = document.getElementById('article-translation-card');
            const bookCard = document.getElementById('book-translation-card');

            articleCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                bookCard.style.opacity = '0.7';
            });

            articleCard.addEventListener('mouseleave', function() {
                this.style.transform = '';
                bookCard.style.opacity = '';
            });

            bookCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                articleCard.style.opacity = '0.7';
            });

            bookCard.addEventListener('mouseleave', function() {
                this.style.transform = '';
                articleCard.style.opacity = '';
            });
        });
    </script>
@endpush
