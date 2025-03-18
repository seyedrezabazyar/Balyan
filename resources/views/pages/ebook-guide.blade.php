@extends('layouts.app')

@section('title', 'نحوه باز کردن کتاب‌های الکترونیکی - کتابخانه دیجیتال بَلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .ebook-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            padding: 5rem 0 4rem;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .ebook-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .ebook-hero-content {
            position: relative;
            z-index: 1;
        }

        .ebook-hero-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .ebook-hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .ebook-hero-desc {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .ebook-hero-buttons {
            margin-top: 2rem;
        }

        .hero-btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero-btn-primary {
            background-color: #fff;
            color: #3b82f6;
        }

        .hero-btn-primary:hover {
            background-color: #f8fafc;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .hero-btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            backdrop-filter: blur(5px);
        }

        .hero-btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* بخش توضیحات */
        .ebook-intro {
            padding: 5rem 0;
            background-color: #fff;
        }

        .intro-icon {
            font-size: 2.5rem;
            color: #3b82f6;
            margin-bottom: 1.5rem;
        }

        .intro-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .intro-text {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* کارت‌های سیستم عامل */
        .os-section {
            padding: 4rem 0;
            background-color: #f8fafc;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .section-desc {
            font-size: 1.1rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto;
        }

        .os-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .os-tab {
            padding: 0.8rem 1.5rem;
            background-color: #fff;
            color: #64748b;
            border-radius: 50px;
            margin: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .os-tab:hover {
            background-color: #f1f5f9;
            transform: translateY(-3px);
        }

        .os-tab.active {
            background-color: #3b82f6;
            color: #fff;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }

        .os-tab i {
            margin-left: 0.7rem;
            font-size: 1.2rem;
        }

        .os-content {
            display: none;
        }

        .os-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .os-card {
            background-color: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            height: 100%;
        }

        .os-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .os-card-header {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .os-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            border-radius: 20px;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
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
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .os-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .os-card-body {
            padding: 2rem;
        }

        .reader-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .reader-item {
            display: flex;
            align-items: center;
            padding: 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .reader-item:last-child {
            border-bottom: none;
        }

        .reader-item:hover {
            background-color: #f8fafc;
        }

        .reader-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background-color: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: 1rem;
            color: #3b82f6;
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

        .reader-desc {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .reader-formats {
            display: flex;
            flex-wrap: wrap;
        }

        .reader-format {
            display: inline-block;
            padding: 0.2rem 0.7rem;
            background-color: #f1f5f9;
            color: #64748b;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .reader-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #f1f5f9;
            color: #3b82f6;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .reader-link:hover {
            background-color: #3b82f6;
            color: #fff;
            transform: translateY(-3px);
        }

        /* بخش فرمت‌های کتاب */
        .formats-section {
            padding: 5rem 0;
            background-color: #fff;
        }

        .format-card {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .format-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            opacity: 0.05;
            z-index: 0;
        }

        .format-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .format-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: #3b82f6;
            font-size: 2rem;
            border-radius: 15px;
            margin-bottom: 1.2rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .format-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
            color: #1e293b;
            position: relative;
            z-index: 1;
        }

        .format-desc {
            color: #64748b;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        /* بخش راهنمای تصویری */
        .guide-section {
            padding: 5rem 0;
            background-color: #f8fafc;
        }

        .guide-card {
            background-color: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .guide-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid #f1f5f9;
        }

        .guide-content {
            padding: 1.5rem;
        }

        .guide-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .guide-steps {
            list-style-type: none;
            padding: 0;
            margin: 0;
            counter-reset: step-counter;
        }

        .guide-step {
            display: flex;
            margin-bottom: 1rem;
            position: relative;
            padding-right: 2.5rem;
        }

        .guide-step:last-child {
            margin-bottom: 0;
        }

        .guide-step::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            right: 0;
            top: 0;
            width: 25px;
            height: 25px;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .guide-step-text {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.7;
        }

        /* بخش سوالات متداول */
        .faq-section {
            padding: 5rem 0;
            background-color: #fff;
        }

        .faq-accordion {
            margin-bottom: 1.5rem;
        }

        .faq-header {
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-header:hover {
            background-color: #f1f5f9;
        }

        .faq-header.active {
            background-color: #3b82f6;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }

        .faq-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .faq-icon {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .faq-header.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-body {
            background-color: #fff;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .faq-body.active {
            padding: 1.5rem;
            max-height: 500px;
        }

        .faq-content {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.7;
        }

        /* بخش CTA */
        .cta-section {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: #fff;
            padding: 5rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cta-desc {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background-color: #fff;
            color: #3b82f6;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .cta-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* پاسخگویی */
        @media (max-width: 991.98px) {
            .ebook-hero {
                padding: 4rem 0 3rem;
            }

            .ebook-hero-title {
                font-size: 2.2rem;
            }

            .ebook-hero-desc {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .os-tabs {
                overflow-x: auto;
                padding-bottom: 1rem;
                margin-bottom: 2rem;
                justify-content: flex-start;
            }

            .os-tab {
                flex: 0 0 auto;
            }

            .cta-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 767.98px) {
            .ebook-hero {
                padding: 3.5rem 0 2.5rem;
            }

            .ebook-hero-title {
                font-size: 1.8rem;
            }

            .ebook-hero-desc {
                font-size: 1rem;
            }

            .ebook-hero-icon {
                font-size: 3rem;
            }

            .intro-title {
                font-size: 1.8rem;
            }

            .intro-text {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .os-card-header {
                padding: 1.5rem;
            }

            .os-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .os-title {
                font-size: 1.3rem;
            }

            .os-card-body {
                padding: 1.5rem;
            }

            .reader-item {
                padding: 1rem;
            }

            .reader-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .reader-title {
                font-size: 1rem;
            }

            .cta-title {
                font-size: 1.8rem;
            }

            .cta-desc {
                font-size: 1rem;
            }

            .cta-btn {
                padding: 0.8rem 2rem;
                font-size: 1rem;
            }

            .hero-btn {
                display: block;
                margin: 0.5rem auto;
                max-width: 300px;
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
                <p class="ebook-hero-desc">راهنمای جامع استفاده از انواع کتابخوان‌ها در سیستم‌عامل‌های مختلف</p>
                <div class="ebook-hero-buttons">
                    <a href="#os-guide" class="hero-btn hero-btn-primary">راهنمای نصب نرم‌افزارها</a>
                    <a href="#faq-section" class="hero-btn hero-btn-secondary">سوالات متداول</a>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش توضیحات -->
    <div class="ebook-intro">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center mb-4 mb-lg-0">
                    <img src="/images/ebook-devices.svg" alt="کتاب الکترونیکی روی دستگاه‌های مختلف" class="img-fluid" style="max-height: 350px;">
                </div>
                <div class="col-lg-6">
                    <div class="text-center text-lg-right">
                        <i class="fas fa-info-circle intro-icon"></i>
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

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <h3 class="format-title">DOC/DOCX</h3>
                        <p class="format-desc">فرمت مایکروسافت ورد برای اسناد متنی</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="format-title">HTML</h3>
                        <p class="format-desc">فرمت وب که در مرورگرها قابل مشاهده است</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="format-card">
                        <div class="format-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="format-title">TXT</h3>
                        <p class="format-desc">فرمت متنی ساده که در همه دستگاه‌ها قابل استفاده است</p>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تبدیل فرمت، مدیریت کتابخانه، ویرایش متادیتا، همگام‌سازی با انواع کتابخوان‌ها</p>
                                        </div>
                                    </li>
                                    <div class="reader-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="reader-info">
                                        <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                        <p class="reader-desc">کتاب‌های PDF، EPUB، MOBI، DOC و TXT را باز می‌کند</p>
                                        <div class="reader-formats">
                                            <span class="reader-format">PDF</span>
                                            <span class="reader-format">EPUB</span>
                                            <span class="reader-format">MOBI</span>
                                            <span class="reader-format">DOC</span>
                                            <span class="reader-format">TXT</span>
                                        </div>
                                    </div>
                                    <a href="https://play.google.com/store/apps/details?id=org.readera" target="_blank" class="reader-link">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">رابط کاربری ساده، رایگان، بدون تبلیغات، حالت شب، تنظیم اندازه متن</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            <p class="reader-desc">دارای قابلیت‌های پیشرفته برای مطالعه کتاب‌های EPUB، PDF و MOBI</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">TXT</span>
                                                <span class="reader-format">HTML</span>
                                            </div>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=com.flyersoft.moonreader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تم‌های سفارشی، نشانه‌گذاری، یادداشت‌برداری، ترجمه آنلاین متن</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card android">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-tablet-alt"></i>
                                </div>
                                <h3 class="os-title">eReader Prestigio</h3>
                                <p class="os-subtitle">با رابط کاربری زیبا</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های اصلی</h4>
                                            <p class="reader-desc">با قابلیت سازماندهی کتابخانه و مرور آنلاین کتاب‌ها</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">FB2</span>
                                            </div>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=com.prestigio.ereader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">افکت ورق زدن طبیعی، تغییر رنگ‌ها و پس‌زمینه، جستجوی متن</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card android">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fab fa-adobe"></i>
                                </div>
                                <h3 class="os-title">Adobe Acrobat Reader</h3>
                                <p class="os-subtitle">متخصص PDF</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">مخصوص فایل‌های PDF</h4>
                                            <p class="reader-desc">بهترین برنامه برای خواندن اسناد و کتاب‌های PDF</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                            </div>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=com.adobe.reader" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">افزودن یادداشت، برجسته کردن متن، امضای اسناد، ذخیره در فضای ابری</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متداول</h4>
                                            <p class="reader-desc">اپلیکیشن پیش‌فرض اپل برای مطالعه کتاب‌های الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                            </div>
                                        </div>
                                        <div class="reader-link">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">یکپارچه با سیستم عامل، تنظیم فونت و رنگ، حالت شب، همگام‌سازی با iCloud</p>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            <p class="reader-desc">با پشتیبانی از انواع فرمت‌های متنی و کتاب الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">TXT</span>
                                            </div>
                                        </div>
                                        <a href="https://apps.apple.com/us/app/ireader-pdf-epub-mobi-reader/id1262898771" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">ساختار کتابخانه‌ای، امکان مرتب‌سازی، جستجو، حمایت از زبان فارسی</p>
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
                                    <i class="fas fa-tablet-alt"></i>
                                </div>
                                <h3 class="os-title">Ebook Reader</h3>
                                <p class="os-subtitle">با امکانات پیشرفته</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            <p class="reader-desc">برای مطالعه انواع کتاب‌های الکترونیکی با امکانات پیشرفته</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">FB2</span>
                                                <span class="reader-format">RTF</span>
                                            </div>
                                        </div>
                                        <a href="https://apps.apple.com/us/app/ebook-reader-pdf-reader/id1188822370" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تنظیمات نمایش متن، حالت شب، پشتیبانی از زبان فارسی، قابلیت همگام‌سازی</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            <p class="reader-desc">پرطرفدارترین نرم‌افزار مدیریت کتاب‌های الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">و ده‌ها فرمت دیگر</span>
                                            </div>
                                        </div>
                                        <a href="https://calibre-ebook.com/download" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            <p class="reader-desc">کتاب‌های PDF، EPUB، MOBI، DOC و TXT را باز می‌کند</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">DOC</span>
                                                <span class="reader-format">TXT</span>
                                            </div>
                                        </div>
                                        <a href="https://play.google.com/store/apps/details?id=org.readera" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    ```

                                    ادامه کد را به این شکل باید باشد:

                                    ```html
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تبدیل فرمت، مدیریت کتابخانه، ویرایش متادیتا، همگام‌سازی با انواع کتابخوان‌ها</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">برای کتاب‌های کیندل</h4>
                                            <p class="reader-desc">نرم‌افزار رسمی آمازون برای مطالعه کتاب‌های الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">PDF</span>
                                            </div>
                                        </div>
                                        <a href="https://www.amazon.com/kindle-dbs/fd/kcp" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">همگام‌سازی با حساب آمازون، نشانه‌گذاری، یادداشت‌برداری، تنظیمات مطالعه</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card windows">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-ice-cream"></i>
                                </div>
                                <h3 class="os-title">Icecream Ebook Reader</h3>
                                <p class="os-subtitle">رابط کاربری زیبا</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های اصلی</h4>
                                            <p class="reader-desc">با رابط کاربری زیبا و امکانات مناسب مطالعه</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">FB2</span>
                                            </div>
                                        </div>
                                        <a href="https://icecreamapps.com/Ebook-Reader/" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">حالت تمام صفحه، جستجو، نشانه‌گذاری، یادداشت‌برداری، مدیریت کتابخانه</p>
                                        </div>
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
                                <h3 class="os-title">Sumatra PDF</h3>
                                <p class="os-subtitle">سبک و سریع</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">نرم‌افزار سبک و سریع</h4>
                                            <p class="reader-desc">مناسب برای کامپیوترهای با منابع محدود</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">XPS</span>
                                            </div>
                                        </div>
                                        <a href="https://www.sumatrapdfreader.org/download-free-pdf-viewer" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">اجرای سریع، مصرف کم منابع، قابلیت حمل، رابط کاربری ساده</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">برنامه پیش‌فرض</h4>
                                            <p class="reader-desc">برنامه پیش‌فرض اپل برای مطالعه کتاب‌های الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">IBA</span>
                                            </div>
                                        </div>
                                        <div class="reader-link">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">رابط کاربری زیبا، همگام‌سازی با iCloud، قابلیت یادداشت‌برداری و نشانه‌گذاری</p>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            <p class="reader-desc">نرم‌افزار قدرتمند برای مدیریت کتابخانه دیجیتالی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">و ده‌ها فرمت دیگر</span>
                                            </div>
                                        </div>
                                        <a href="https://calibre-ebook.com/download" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تبدیل فرمت، مدیریت کتابخانه، ویرایش متادیتا، همگام‌سازی با انواع کتابخوان‌ها</p>
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
                                    <i class="fab fa-amazon"></i>
                                </div>
                                <h3 class="os-title">Kindle for Mac</h3>
                                <p class="os-subtitle">رسمی آمازون</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">برای کتاب‌های کیندل</h4>
                                            <p class="reader-desc">نرم‌افزار رسمی آمازون برای مک</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">PDF</span>
                                            </div>
                                        </div>
                                        <a href="https://www.amazon.com/kindle-dbs/fd/kcp" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">همگام‌سازی با حساب آمازون، نشانه‌گذاری، یادداشت‌برداری، تنظیمات مطالعه</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از تمام فرمت‌ها</h4>
                                            <p class="reader-desc">بهترین نرم‌افزار مدیریت کتاب الکترونیکی برای لینوکس</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">و ده‌ها فرمت دیگر</span>
                                            </div>
                                        </div>
                                        <a href="https://calibre-ebook.com/download_linux" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">تبدیل فرمت، مدیریت کتابخانه، ویرایش متادیتا، همگام‌سازی با انواع کتابخوان‌ها</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="os-card linux">
                            <div class="os-card-header">
                                <div class="os-icon">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <h3 class="os-title">FBReader</h3>
                                <p class="os-subtitle">مخصوص لینوکس</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متداول</h4>
                                            <p class="reader-desc">نرم‌افزار متن‌باز برای خواندن کتاب‌های الکترونیکی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">FB2</span>
                                                <span class="reader-format">TXT</span>
                                            </div>
                                        </div>
                                        <a href="https://fbreader.org/content/fbreader-beta-linux-desktop" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">قابلیت تنظیم فونت و رنگ، جستجو، نشانه‌گذاری، متن‌باز و رایگان</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">مشاهده‌گر چندفرمتی</h4>
                                            <p class="reader-desc">نرم‌افزار متن‌باز و قدرتمند برای لینوکس</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">DJVU</span>
                                                <span class="reader-format">XPS</span>
                                            </div>
                                        </div>
                                        <a href="https://okular.kde.org/download" target="_blank" class="reader-link">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">افزودن یادداشت، برجسته کردن متن، نشانه‌گذاری، فرم‌های تعاملی، پشتیبانی از زبان فارسی</p>
                                        </div>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های</h4>
                                            <p class="reader-desc">سازگار با فرمت‌های اختصاصی آمازون و فرمت‌های اصلی</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">AZW</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">TXT</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">صفحه نمایش E-Ink، نور پس‌زمینه، عمر باتری طولانی، اکوسیستم آمازون</p>
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
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از فرمت‌های متنوع</h4>
                                            <p class="reader-desc">سازگار با فرمت‌های باز و استاندارد</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">TXT</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">صفحه نمایش E-Ink با وضوح بالا، ضد آب، نور آبی ComfortLight، سازگاری با کتابخانه‌های عمومی</p>
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
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h3 class="os-title">PocketBook</h3>
                                <p class="os-subtitle">انعطاف‌پذیر با فرمت‌های متنوع</p>
                            </div>
                            <div class="os-card-body">
                                <ul class="reader-list">
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">پشتیبانی از بیشترین فرمت‌ها</h4>
                                            <p class="reader-desc">سازگار با بیش از 20 فرمت مختلف</p>
                                            <div class="reader-formats">
                                                <span class="reader-format">EPUB</span>
                                                <span class="reader-format">PDF</span>
                                                <span class="reader-format">FB2</span>
                                                <span class="reader-format">MOBI</span>
                                                <span class="reader-format">DJVU</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reader-item">
                                        <div class="reader-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="reader-info">
                                            <h4 class="reader-title">ویژگی‌های اصلی</h4>
                                            <p class="reader-desc">صفحه نمایش E-Ink، تبدیل متن به گفتار، قابلیت اتصال به وای‌فای، پشتیبانی از زبان فارسی</p>
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

    <!-- بخش راهنمای تصویری -->
    <div class="guide-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">راهنمای تصویری باز کردن کتاب‌های الکترونیکی</h2>
                <p class="section-desc">آموزش گام به گام نحوه باز کردن و مدیریت کتاب‌های الکترونیکی در دستگاه‌های مختلف</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="guide-card">
                        <img src="/images/guides/android-guide.jpg" alt="راهنمای اندروید" class="guide-img">
                        <div class="guide-content">
                            <h3 class="guide-title">نحوه باز کردن کتاب در اندروید</h3>
                            <ul class="guide-steps">
                                <li class="guide-step">
                                    <p class="guide-step-text">برنامه ReadEra یا Moon+ Reader را از Google Play نصب کنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">فایل کتاب را در حافظه دستگاه خود ذخیره کنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">برنامه را باز کنید و به بخش کتابخانه بروید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">روی «اسکن فایل‌ها» یا «اضافه کردن کتاب» بزنید تا برنامه کتاب‌های موجود را پیدا کند.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">روی کتاب مورد نظر بزنید تا باز شود.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="guide-card">
                        <img src="/images/guides/ios-guide.jpg" alt="راهنمای آی‌اواس" class="guide-img">
                        <div class="guide-content">
                            <h3 class="guide-title">نحوه باز کردن کتاب در آیفون و آیپد</h3>
                            <ul class="guide-steps">
                                <li class="guide-step">
                                    <p class="guide-step-text">کتاب را از سایت بلیان دانلود کنید و روی دکمه «باز کردن با» بزنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">گزینه «کپی به Books» یا برنامه کتابخوان دلخواه خود را انتخاب کنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">برنامه Books به صورت خودکار باز می‌شود و کتاب به کتابخانه اضافه می‌شود.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">روی جلد کتاب بزنید تا کتاب باز شود.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="guide-card">
                        <img src="/images/guides/windows-guide.jpg" alt="راهنمای ویندوز" class="guide-img">
                        <div class="guide-content">
                            <h3 class="guide-title">نحوه باز کردن کتاب در ویندوز</h3>
                            <ul class="guide-steps">
                                <li class="guide-step">
                                    <p class="guide-step-text">نرم‌افزار Calibre را از سایت رسمی دانلود و نصب کنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">نرم‌افزار را اجرا کنید و روی دکمه «افزودن کتاب» کلیک کنید.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">فایل کتاب را انتخاب کنید تا به کتابخانه اضافه شود.</p>
                                </li>
                                <li class="guide-step">
                                    <p class="guide-step-text">روی کتاب دابل کلیک کنید یا روی دکمه «خواندن» کلیک کنید تا کتاب باز شود.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بخش سوالات متداول -->
    <div id="faq-section" class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">سوالات متداول</h2>
                <p class="section-desc">پاسخ به سوالات رایج درباره کتاب‌های الکترونیکی و نحوه استفاده از آن‌ها</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="faq-accordion">
                        <div class="faq-header active">
                            <h3 class="faq-title">چرا فایل کتاب روی دستگاه من باز نمی‌شود؟</h3>
                            <div class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="faq-body active">
                            <div class="faq-content">
                                <p>اگر پس از دانلود کتاب، فایل کتاب روی دستگاه شما باز نمی‌شود، ممکن است به دلایل زیر باشد:</p>
                                <ul>
                                    <li>نرم‌افزار کتابخوان متناسب با فرمت کتاب را نصب نکرده‌اید.</li>
                                    <li>فایل کتاب آسیب دیده یا ناقص دانلود شده است.</li>
                                    <li>نسخه نرم‌افزار کتابخوان شما قدیمی است و نیاز به بروزرسانی دارد.</li>
                                    <li>فرمت کتاب با دستگاه شما سازگار نیست و نیاز به تبدیل فرمت دارد.</li>
                                </ul>
                                <p>توصیه می‌کنیم ابتدا یک نرم‌افزار کتابخوان مناسب با فرمت کتاب خود نصب کنید و سپس دوباره امتحان کنید.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-accordion">
                        <div class="faq-header">
                            <h3 class="faq-title">فرمت کتاب موردنظر من موجود نیست. چه کار کنم؟</h3>
                            <div class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="faq-body">
                            <div class="faq-content">
                                <p>در صورتی که کتابی را از سایت خریداری کرده‌اید و به فرمت‌های دیگری مانند PDF و EPUB یا سایر فرمت‌ها نیاز دارید، می‌توانید:</p>
                                <ul>
                                    <li>به پشتیبانی سایت درخواست بفرستید؛ در صورتی که سایر فرمت‌های آن کتاب موجود باشد، برای شما ارسال خواهد شد.</li>
                                    <li>از نرم‌افزار Calibre برای تبدیل فرمت کتاب به فرمت دلخواه خود استفاده کنید.</li>
                                    <li>از سرویس‌های آنلاین تبدیل فرمت مانند <a href="https://www.zamzar.com" target="_blank">Zamzar</a> یا <a href="https://www.convertfiles.com" target="_blank">ConvertFiles</a> استفاده کنید.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-accordion">
                        <div class="faq-header">
                            <h3 class="faq-title">چگونه می‌توانم کتاب‌های الکترونیکی را به کتابخوان کیندل منتقل کنم؟</h3>
                            <div class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="faq-body">
                            <div class="faq-content">
                                <p>برای انتقال کتاب‌های الکترونیکی به کتابخوان کیندل، چند روش وجود دارد:</p>
                                <ol>
                                    <li><strong>انتقال با کابل USB:</strong> کتابخوان کیندل را با کابل به کامپیوتر متصل کنید. سپس فایل‌های کتاب را به پوشه Documents در کیندل کپی کنید.</li>
                                    <li><strong>ارسال از طریق ایمیل:</strong> هر کیندل یک آدرس ایمیل اختصاصی دارد (مانند yourname@kindle.com). می‌توانید کتاب را به این آدرس ایمیل کنید تا به صورت خودکار به کیندل شما ارسال شود.</li>
                                    <li><strong>استفاده از نرم‌افزار Calibre:</strong> می‌توانید با نرم‌افزار Calibre کتاب‌ها را مدیریت کرده و به کیندل خود منتقل کنید.</li>
                                </ol>
                                <p>توجه داشته باشید که کیندل به صورت پیش‌فرض از فرمت‌های AZW، MOBI، PDF و TXT پشتیبانی می‌کند. برای سایر فرمت‌ها مانند EPUB، باید ابتدا آن‌ها را به یکی از فرمت‌های سازگار تبدیل کنید.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-accordion">
                        <div class="faq-header">
                            <h3 class="faq-title">آیا می‌توانم کتاب‌های الکترونیکی را بین چند دستگاه همگام‌سازی کنم؟</h3>
                            <div class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="faq-body">
                            <div class="faq-content">
                                <p>بله، بیشتر نرم‌افزارهای کتابخوان امکان همگام‌سازی بین چند دستگاه را فراهم می‌کنند:</p>
                                <ul>
                                    <li><strong>Apple Books:</strong> با استفاده از iCloud، کتاب‌ها و پیشرفت مطالعه شما بین دستگاه‌های اپل همگام‌سازی می‌شود.</li>
                                    <li><strong>Kindle:</strong> آمازون به صورت خودکار کتاب‌ها و آخرین صفحه خوانده شده را بین همه دستگاه‌های شما همگام‌سازی می‌کند.</li>
                                    <li><strong>Google Play Books:</strong> کتاب‌ها و نشانک‌ها بین همه دستگاه‌هایی که با حساب گوگل شما وارد شده‌اند، همگام‌سازی می‌شوند.</li>
                                    <li><strong>Calibre Companion:</strong> با استفاده از این برنامه می‌توانید کتابخانه Calibre خود را با دستگاه‌های موبایل همگام‌سازی کنید.</li>
                                </ul>
                                <p>برای استفاده از این قابلیت، باید در همه دستگاه‌ها با همان حساب کاربری وارد شوید و اتصال اینترنت داشته باشید.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-accordion">
                        <div class="faq-header">
                            <h3 class="faq-title">چگونه می‌توانم فونت، اندازه متن یا حالت نمایش کتاب الکترونیکی را تغییر دهم؟</h3>
                            <div class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="faq-body">
                            <div class="faq-content">
                                <p>در اکثر نرم‌افزارهای کتابخوان، می‌توانید تنظیمات نمایش متن را به شرح زیر تغییر دهید:</p>
                                <ol>
                                    <li>هنگام مطالعه، روی صفحه ضربه بزنید تا منوی تنظیمات ظاهر شود.</li>
                                    <li>دنبال نماد تنظیمات یا آیکون  «Aa» یا «فونت» بگردید.</li>
                                    <li>در منوی تنظیمات می‌توانید موارد زیر را تغییر دهید:
                                        <ul>
                                            <li>نوع فونت</li>
                                            <li>اندازه متن</li>
                                            <li>فاصله خطوط</li>
                                            <li>حاشیه‌ها</li>
                                            <li>رنگ پس‌زمینه (حالت روز/شب)</li>
                                            <li>روشنایی صفحه</li>
                                        </ul>
                                    </li>
                                </ol>
                                <p>توجه داشته باشید که برخی از این تنظیمات ممکن است در فایل‌های PDF در دسترس نباشند، زیرا PDF فرمتی ثابت است و امکان تغییر فونت و اندازه متن در آن محدود است.</p>
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
                <p class="cta-desc">هزاران کتاب الکترونیکی در انتظار شماست. همین حالا به کتابخانه دیجیتال بَلیان بپیوندید و به دنیای بی‌کران کتاب‌های الکترونیکی وارد شوید.</p>
                <a href="{{ route('home') }}" class="cta-btn">
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

            // سوالات متداول
            const faqHeaders = document.querySelectorAll('.faq-header');

            faqHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const body = this.nextElementSibling;
                    const isActive = this.classList.contains('active');

                    // باز/بسته کردن انتخاب شده
                    if (isActive) {
                        this.classList.remove('active');
                        body.classList.remove('active');
                    } else {
                        this.classList.add('active');
                        body.classList.add('active');
                    }
                });
            });

            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.format-card, .os-card, .guide-card, .faq-accordion');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        }, 100 * Array.from(animateItems).indexOf(entry.target) % 5);
                    }
                });
            }, { threshold: 0.1 });

            animateItems.forEach(item => {
                item.style.opacity = 0;
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(item);
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
