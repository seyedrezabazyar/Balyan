@extends('layouts.app')

@section('title', 'نحوه باز کردن کتاب‌های الکترونیکی - کتابخانه دیجیتال بَلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .ebook-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            padding: 3rem 0;
            color: #fff;
            text-align: center;
        }

        .ebook-hero-content {
            position: relative;
            z-index: 1;
        }

        .ebook-hero-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .ebook-hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .ebook-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-btn {
            display: inline-block;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .hero-btn-primary {
            background-color: #fff;
            color: #3b82f6;
        }

        .hero-btn-primary:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
        }

        /* بخش توضیحات */
        .ebook-intro {
            padding: 3rem 0;
            background-color: #fff;
        }

        .intro-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .intro-text {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.7;
        }

        /* بخش فرمت‌های کتاب */
        .formats-section {
            padding: 3rem 0;
            background-color: #f8fafc;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.8rem;
        }

        .section-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto;
        }

        .format-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .format-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            color: #3b82f6;
            font-size: 1.5rem;
            border-radius: 12px;
            margin: 0 auto 1rem;
        }

        .format-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .format-desc {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* بخش راهنمای سیستم عامل‌ها */
        .os-section {
            padding: 3rem 0;
            background-color: #fff;
        }

        .os-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .os-tab {
            padding: 0.7rem 1.2rem;
            background-color: #f8fafc;
            color: #64748b;
            border-radius: 50px;
            margin: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .os-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            background-color: #f1f5f9;
        }

        .os-tab.active {
            background-color: #3b82f6;
            color: #fff;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
        }

        .os-tab i {
            margin-left: 0.6rem;
            font-size: 1.2rem;
        }

        .os-content {
            display: none;
        }

        .os-content.active {
            display: block;
        }

        .os-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .os-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .os-card-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
            background-color: #f8fafc;
            border-radius: 12px 12px 0 0;
        }

        .os-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            border-radius: 15px;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
        }

        .android .os-icon {
            background-color: #e6f7e9;
            color: #34a853;
        }

        .ios .os-icon {
            background-color: #f2f2f7;
            color: #000;
        }

        .windows .os-icon {
            background-color: #e9f0ff;
            color: #0078d7;
        }

        .macos .os-icon {
            background-color: #f2f2f7;
            color: #333;
        }

        .linux .os-icon {
            background-color: #ffefde;
            color: #f57c00;
        }

        .ereader .os-icon {
            background-color: #f1efff;
            color: #8b5cf6;
        }

        .os-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .os-subtitle {
            color: #64748b;
            font-size: 0.9rem;
        }

        .os-card-body {
            padding: 1.5rem;
        }

        .reader-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .reader-item {
            display: flex;
            flex-direction: column;
            padding: 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            background-color: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.8rem;
        }

        .reader-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .reader-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .reader-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-left: 1rem;
            color: #3b82f6;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .reader-info {
            flex: 1;
        }

        .reader-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
            color: #1e293b;
        }

        .reader-formats {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .reader-format {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background-color: #edf2f7;
            color: #4a5568;
            border-radius: 30px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .reader-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 40px;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .reader-link:hover {
            background-color: #2563eb;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .reader-link:focus {
            color: #ffffff;
        }

        .reader-link i {
            margin-left: 0.5rem;
        }

        /* بخش CTA */
        .cta-section {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: #fff;
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }

        .cta-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-desc {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background-color: #fff;
            color: #3b82f6;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* پاسخگویی */
        @media (max-width: 767.98px) {
            .os-tabs {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                width: 100%;
                padding: 0 0.5rem;
            }

            .os-tab {
                margin: 0.3rem;
                width: 100%;
                min-width: auto;
            }

            .hero-btn {
                display: block;
                margin: 0.5rem auto;
                max-width: 250px;
            }
        }

        @media (max-width: 575.98px) {
            .os-tabs {
                grid-template-columns: 1fr 1fr;
                gap: 6px;
            }

            .os-tab {
                padding: 0.6rem;
                font-size: 0.9rem;
            }

            .os-tab i {
                font-size: 1rem;
            }

            .reader-formats {
                margin-bottom: 0.8rem;
            }

            .reader-format {
                margin-bottom: 0.4rem;
                font-size: 0.75rem;
                padding: 0.2rem 0.6rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه -->
    <div class="ebook-hero">
        <div class="container">
            <div class="ebook-hero-content">
                <i class="fas fa-book-reader ebook-hero-icon"></i>
                <h1 class="ebook-hero-title">نحوه باز کردن کتاب‌های الکترونیکی</h1>
                <p class="ebook-hero-desc">راهنمای استفاده از نرم‌افزارهای کتابخوان در سیستم‌عامل‌های مختلف</p>
                <a href="#os-guide" class="hero-btn hero-btn-primary">راهنمای نصب نرم‌افزارها</a>
            </div>
        </div>
    </div>

    <!-- بخش توضیحات -->
    <div class="ebook-intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <h2 class="intro-title">کتاب الکترونیکی چیست؟</h2>
                    </div>
                    <p class="intro-text">
                        کتاب الکترونیکی (Ebook) در واقع مخفف Electronic Book است و به همین دلیل آن را کتاب الکترونیکی می‌دانند. به بیان ساده دقیقاً مشابه یک کتاب چاپی رایج است، اما به صورت دیجیتالی عرضه و منتشر می‌شود.</p>
                    <p class="intro-text">
                        کتاب الکترونیکی را می‌توان در کامپیوتر، لپ‌تاپ، تبلت، گوشی موبایل یا سایر دستگاه‌های کامپیوتری نظیر «دستگاه‌های کتابخوان الکترونیک» (e-Book Readers) مطالعه کرد. در ادامه، نرم افزار های مورد نیاز برای باز کردن کتاب های الکترونیکی در درستگاه های مختلف را به شما معرفی خواهیم کرد.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش فرمت‌های کتاب -->
    <div class="formats-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">انواع فرمت‌های کتاب الکترونیکی</h2>
                <p class="section-desc">با فرمت‌های رایج کتاب‌های الکترونیکی آشنا شوید</p>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="far fa-file-pdf"></i>
                        </div>
                        <h3 class="format-title">PDF</h3>
                        <p class="format-desc">رایج‌ترین فرمت کتاب الکترونیکی که در تمام دستگاه‌ها قابل استفاده است</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="format-title">EPUB</h3>
                        <p class="format-desc">فرمت استاندارد کتاب الکترونیکی با قابلیت تنظیم سایز متن</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="fab fa-amazon"></i>
                        </div>
                        <h3 class="format-title">MOBI/AZW</h3>
                        <p class="format-desc">فرمت اختصاصی آمازون برای کتابخوان کیندل</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش راهنمای سیستم عامل‌ها -->
    <div id="os-guide" class="os-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">راهنمای نصب نرم‌افزارهای کتابخوان</h2>
                <p class="section-desc">بر اساس سیستم عامل مورد استفاده خود، نرم‌افزار مناسب را انتخاب کنید</p>
            </div>

            <div class="os-tabs">
                <div class="os-tab active" data-target="android">
                    <i class="fab fa-android"></i>
                    اندروید
                </div>
                <div class="os-tab" data-target="ios">
                    <i class="fab fa-apple"></i>
                    آی‌اواس
                </div>
                <div class="os-tab" data-target="windows">
                    <i class="fab fa-windows"></i>
                    ویندوز
                </div>
                <div class="os-tab" data-target="macos">
                    <i class="fab fa-apple"></i>
                    مک‌او‌اس
                </div>
                <div class="os-tab" data-target="linux">
                    <i class="fab fa-linux"></i>
                    لینوکس
                </div>
                <div class="os-tab" data-target="ereader">
                    <i class="fas fa-tablet-alt"></i>
                    کتابخوان
                </div>
            </div>

            <!-- محتوای اندروید -->
            <div id="android" class="os-content active">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card android">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h3 class="os-title">ReadEra</h3>
                                <p class="os-subtitle">بهترین کتابخوان برای اندروید</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">DOC</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=org.readera" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card android">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-moon"></i>
                                </div>
                                <h3 class="os-title">Moon+ Reader</h3>
                                <p class="os-subtitle">پرطرفدار و قدرتمند</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">TXT</span>
                                            <span class="reader-format">HTML</span>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=com.flyersoft.moonreader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card android">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <h3 class="os-title">Adobe Acrobat Reader</h3>
                                <p class="os-subtitle">متخصص PDF</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">مخصوص فایل‌های PDF</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=com.adobe.reader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- محتوای آی‌اواس -->
            <div id="ios" class="os-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card ios">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3 class="os-title">Apple Books</h3>
                                <p class="os-subtitle">اپلیکیشن پیش‌فرض آیفون و آیپد</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متداول</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                        </div>
                                        <div class="reader-link" style="background-color: #4CAF50;">
                                            <a href="https://apps.apple.com/us/app/apple-books" target="_blank" class="reader-link">
                                                <i class="fas fa-download"></i>
                                                دانلود برنامه
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card ios">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-glasses"></i>
                                </div>
                                <h3 class="os-title">iReader</h3>
                                <p class="os-subtitle">برای مطالعه راحت‌تر</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                        <a href="https://apps.apple.com/us/app/ireader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card ios">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-tablet-alt"></i>
                                </div>
                                <h3 class="os-title">Ebook Reader</h3>
                                <p class="os-subtitle">با امکانات پیشرفته</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">FB2</span>
                                            <span class="reader-format">RTF</span>
                                        </div>
                                        <a href="https://apps.apple.com/us/app/ebook-reader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- محتوای ویندوز -->
            <div id="windows" class="os-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card windows">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="os-title">Calibre</h3>
                                <p class="os-subtitle">مدیریت کتابخانه دیجیتال</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">AZW</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                        <a href="https://www.sarzamindownload.com/tags/%D8%AF%D8%A7%D9%86%D9%84%D9%88%D8%AF_Calibre/" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card windows">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fab fa-amazon"></i>
                                </div>
                                <h3 class="os-title">Kindle for PC</h3>
                                <p class="os-subtitle">رسمی آمازون</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">برای کتاب‌های کیندل</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">AZW</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">PDF</span>
                                        </div>
                                        <a href="https://www.yasdl.com/136301/%D8%AF%D8%A7%D9%86%D9%84%D9%88%D8%AF-kindle-for-pc.html" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card windows">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <h3 class="os-title">Icecream Ebook Reader</h3>
                                <p class="os-subtitle">سبک و سریع</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">نرم‌افزار سبک و سریع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">XPS</span>
                                        </div>
                                        <a href="https://www.p30world.com/20065/%D8%AF%D8%A7%D9%86%D9%84%D9%88%D8%AF-ebook-reader-pro-%D9%86%D8%B1%D9%85-%D8%A7%D9%81%D8%B2%D8%A7%D8%B1-%DA%A9%D8%AA%D8%A7%D8%A8%D8%AE%D9%88%D8%A7%D9%86/" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- محتوای مک‌او‌اس -->
            <div id="macos" class="os-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card macos">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3 class="os-title">Apple Books</h3>
                                <p class="os-subtitle">برنامه پیش‌فرض مک</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">برنامه پیش‌فرض</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">IBA</span>
                                        </div>
                                        <div class="reader-link" style="background-color: #4CAF50;">
                                            <a href="https://apps.apple.com/us/app/apple-books/" target="_blank" class="reader-link">
                                                <i class="fas fa-download"></i>
                                                دانلود برنامه
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card macos">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="os-title">Calibre</h3>
                                <p class="os-subtitle">مدیریت کتابخانه دیجیتال</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">AZW</span>
                                        </div>
                                        <a href="https://www.sarzamindownload.com/tags/%D8%AF%D8%A7%D9%86%D9%84%D9%88%D8%AF_Calibre" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card macos">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <h3 class="os-title">Icecream Ebook Reader</h3>
                                <p class="os-subtitle">سبک و سریع</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">نرم‌افزار سبک و سریع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">XPS</span>
                                        </div>
                                        <a href="https://www.p30world.com/20065/%D8%AF%D8%A7%D9%86%D9%84%D9%88%D8%AF-ebook-reader-pro-%D9%86%D8%B1%D9%85-%D8%A7%D9%81%D8%B2%D8%A7%D8%B1-%DA%A9%D8%AA%D8%A7%D8%A8%D8%AE%D9%88%D8%A7%D9%86/" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- محتوای لینوکس -->
            <div id="linux" class="os-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card linux">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="os-title">Calibre</h3>
                                <p class="os-subtitle">مدیریت کتابخانه دیجیتال</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">AZW</span>
                                        </div>
                                        <a href="https://calibre-ebook.com/download_linux" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card linux">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <h3 class="os-title">Okular</h3>
                                <p class="os-subtitle">چندکاره و انعطاف‌پذیر</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">مشاهده‌گر چندفرمتی</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">DJVU</span>
                                            <span class="reader-format">XPS</span>
                                        </div>
                                        <a href="https://okular.kde.org/download" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card linux">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <h3 class="os-title">FBReader</h3>
                                <p class="os-subtitle">قدرتمند و انعطاف‌پذیر</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">DJVU</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                        <a href="https://fbreader.org/en target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                            دانلود برنامه
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- محتوای کتابخوان -->
            <div id="ereader" class="os-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card ereader">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fab fa-amazon"></i>
                                </div>
                                <h3 class="os-title">Amazon Kindle</h3>
                                <p class="os-subtitle">محبوب‌ترین کتابخوان دنیا</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">AZW</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card ereader">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-tablet-alt"></i>
                                </div>
                                <h3 class="os-title">Kobo eReader</h3>
                                <p class="os-subtitle">با پشتیبانی از EPUB</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-header">
                                            <div class="reader-icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="reader-info">
                                                <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            </div>
                                        </div>
                                        <div class="reader-formats">
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش CTA -->
    <div class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">آماده لذت بردن از کتاب‌های الکترونیکی هستید؟</h2>
                <p class="cta-desc">هزاران کتاب الکترونیکی در انتظار شماست. همین حالا به کتابخانه دیجیتال بَلیان بپیوندید.</p>
                <a href="{{ route('topics') }}" class="cta-btn">
                    <i class="fas fa-book-reader me-2"></i>
                    مشاهده کتاب‌های الکترونیکی
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تب‌های سیستم عامل
            const osTabs = document.querySelectorAll('.os-tab');
            const osContents = document.querySelectorAll('.os-content');

            osTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');

                    osTabs.forEach(t => t.classList.remove('active'));
                    osContents.forEach(c => c.classList.remove('active'));

                    this.classList.add('active');
                    document.getElementById(target).classList.add('active');
                });
            });

            // اسکرول نرم برای لینک‌های داخل صفحه
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
@endpush
