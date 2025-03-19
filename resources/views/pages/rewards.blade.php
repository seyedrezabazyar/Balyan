@extends('layouts.app')

@section('title', 'باشگاه مشتریان کتابخانه دیجیتال بَلیان - سیستم تخفیف هوشمند')

@push('styles')
    <style>
        /* استایل‌های کلی صفحه */
        :root {
            --primary-color: #f59e0b; /* تغییر رنگ اصلی به نارنجی طلایی برای مفهوم جایزه و تخفیف */
            --primary-dark: #d97706;
            --primary-light: #fef3c7;
            --secondary-color: #10b981;
            --secondary-dark: #059669;
            --secondary-light: #d1fae5;
            --bronze: #cd7f32;
            --silver: #c0c0c0;
            --gold: #ffd700;
            --platinum: #e5e4e2;
            --diamond: #b9f2ff;
            --master: #ff4500;
        }

        .rewards-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .rewards-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .rewards-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
            color: #fff;
        }

        .rewards-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .rewards-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .rewards-highlight {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            backdrop-filter: blur(5px);
        }

        /* بخش‌های اصلی */
        .rewards-section {
            margin-bottom: 3rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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

        .rewards-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .rewards-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* کارت معرفی سیستم تخفیف */
        .intro-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .intro-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .intro-icon {
            width: 90px;
            height: 90px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .intro-icon i {
            font-size: 2.8rem;
        }

        .intro-icon::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border: 2px dashed rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .intro-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .intro-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .intro-desc {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .intro-highlight {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 1.5rem 0;
        }

        .register-button {
            display: inline-block;
            background-color: white;
            color: var(--primary-dark) !important;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }

        .register-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* سیستم سطح‌بندی */
        .levels-table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        .levels-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .levels-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
        }

        .levels-table td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .levels-table tr:last-child td {
            border-bottom: none;
        }

        .levels-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .levels-table tr:hover {
            background-color: #f5f5f5;
        }

        .discount-badge {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
        }

        /* کارت‌های سطح */
        .levels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .level-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .level-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .level-header {
            padding: 1.5rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .level-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
        }

        .level-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .level-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            position: relative;
            z-index: 1;
        }

        .level-discount {
            font-size: 2rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .level-body {
            padding: 1.5rem;
            background-color: white;
        }

        .level-requirement {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-700);
        }

        .level-features {
            padding-right: 1.5rem;
            margin-bottom: 0;
        }

        .level-features li {
            margin-bottom: 0.7rem;
            position: relative;
        }

        .level-features li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: var(--primary-color);
            position: absolute;
            right: -1.5rem;
        }

        /* رنگ‌بندی سطوح */
        .bronze-level .level-header {
            background: linear-gradient(135deg, #cd7f32 0%, #a05a2c 100%);
        }

        .silver-level .level-header {
            background: linear-gradient(135deg, #c0c0c0 0%, #a0a0a0 100%);
        }

        .gold-level .level-header {
            background: linear-gradient(135deg, #ffd700 0%, #daa520 100%);
        }

        .platinum-level .level-header {
            background: linear-gradient(135deg, #e5e4e2 0%, #b8b8b8 100%);
        }

        .diamond-level .level-header {
            background: linear-gradient(135deg, #b9f2ff 0%, #89cff0 100%);
        }

        .master-level .level-header {
            background: linear-gradient(135deg, #ff4500 0%, #ff8c00 100%);
        }

        /* بخش نکات و قوانین */
        .rules-list {
            padding-right: 1.5rem;
        }

        .rules-list li {
            margin-bottom: 1rem;
            position: relative;
        }

        .rules-list li::before {
            content: "\f071";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: var(--primary-color);
            position: absolute;
            right: -1.5rem;
        }

        /* بخش پایانی */
        .rewards-footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .rewards-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .rewards-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .rewards-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-button {
            display: inline-block;
            background-color: white;
            color: var(--primary-dark) !important;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* افکت‌های انیمیشنی */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        /* استایل‌های جدید برای کارت‌های دسترسی */
        .access-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .access-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .access-header {
            padding: 1.5rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .access-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.05);
        }

        .access-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .access-discount {
            font-size: 1.6rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.15);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            margin-top: 0.5rem;
        }

        .access-body {
            padding: 1.5rem;
            background-color: white;
        }

        .access-requirement {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-700);
            text-align: center;
            padding: 0.5rem;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .access-features {
            padding-right: 1.5rem;
            margin-bottom: 0;
        }

        .access-features li {
            margin-bottom: 0.8rem;
            position: relative;
        }

        .access-features li::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: -1.5rem;
        }

        /* رنگ‌بندی جدید */
        .special-access .access-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .special-access .access-features li::before {
            color: #4f46e5;
        }

        .rare-access .access-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .rare-access .access-features li::before {
            color: #7c3aed;
        }

        .legendary-access .access-header {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
            position: relative;
        }

        .legendary-access .access-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .legendary-access .access-features li::before {
            color: #be185d;
        }

        /* افکت‌های ویژه */
        .legendary-access .access-icon {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.1);
            animation: pulse-legendary 2s infinite;
        }

        @keyframes pulse-legendary {
            0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .rewards-hero-title {
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

            .intro-title {
                font-size: 1.5rem;
            }

            .intro-icon {
                width: 70px;
                height: 70px;
            }

            .intro-icon i {
                font-size: 2.2rem;
            }

            .intro-icon::after {
                width: 80px;
                height: 80px;
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

            .levels-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .rewards-footer h3 {
                font-size: 1.3rem;
                line-height: 1.6;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه تخفیف -->
    <div class="rewards-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-award rewards-hero-icon"></i>
                <h1 class="rewards-hero-title">باشگاه مشتریان کتابخانه دیجیتال بَلیان</h1>
                <p class="rewards-hero-desc">هر چقدر بیشتر بخوانید، بیشتر پاداش می‌گیرید! با سیستم تخفیف هوشمند ما، به ازای هر خرید، درصد تخفیف شما برای خریدهای بعدی افزایش می‌یابد.</p>
                <div class="rewards-highlight">
                    <i class="fas fa-star me-1"></i>
                    تا ۵۰٪ تخفیف در خریدهای بعدی
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- کارت معرفی سیستم تخفیف -->
                <section class="rewards-section">
                    <div class="intro-card">
                        <div class="intro-content">
                            <div class="intro-icon floating">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <h2 class="intro-title">به باشگاه مشتریان کتابخانه دیجیتال بَلیان خوش آمدید</h2>
                            <p class="intro-desc">سیستم تخفیف هوشمند ما به شما این امکان را می‌دهد که با هر خرید، از تخفیف بیشتری در خریدهای بعدی بهره‌مند شوید. هر چه بیشتر بخوانید، بیشتر پس‌انداز می‌کنید!</p>

                            <div class="intro-highlight">
                                ثبت‌نام کنید و با اولین خرید، ۵٪ تخفیف دریافت کنید!
                            </div>

                            <a href="/register" class="register-button pulse">
                                <i class="fas fa-user-plus me-2"></i>
                                ثبت‌نام و شروع
                            </a>
                        </div>
                    </div>
                </section>

                <!-- بخش سیستم سطح‌بندی -->
                <section class="rewards-section">
                    <div class="section-header">
                        <i class="fas fa-layer-group section-icon"></i>
                        <h2>سیستم سطح‌بندی و تخفیف</h2>
                    </div>
                    <div class="rewards-card">
                        <p class="text-center mb-4">با هر خرید کتاب، سطح شما در باشگاه مشتریان ارتقا می‌یابد و درصد تخفیف بیشتری برای خریدهای بعدی دریافت می‌کنید. سطح شما بر اساس تعداد کتاب‌هایی که در ۳۶۵ روز گذشته خریداری کرده‌اید، محاسبه می‌شود.</p>

                        <div class="levels-table-container">
                            <table class="levels-table">
                                <thead>
                                <tr>
                                    <th>سطح</th>
                                    <th>تعداد کتاب در ۳۶۵ روز</th>
                                    <th>درصد تخفیف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>کتاب‌دوست تازه‌کار</td>
                                    <td>۱ کتاب</td>
                                    <td><span class="discount-badge">۵٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌خوان مشتاق</td>
                                    <td>۳ کتاب</td>
                                    <td><span class="discount-badge">۷٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌خوان حرفه‌ای</td>
                                    <td>۵ کتاب</td>
                                    <td><span class="discount-badge">۱۰٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌باز نخبه</td>
                                    <td>۱۰ کتاب</td>
                                    <td><span class="discount-badge">۱۵٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌خوار</td>
                                    <td>۲۰ کتاب</td>
                                    <td><span class="discount-badge">۲۰٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌خوان افسانه‌ای</td>
                                    <td>۵۰ کتاب</td>
                                    <td><span class="discount-badge">۲۵٪</span></td>
                                </tr>
                                <tr>
                                    <td>استاد کتاب</td>
                                    <td>۱۰۰ کتاب</td>
                                    <td><span class="discount-badge">۳۰٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌شناس برتر</td>
                                    <td>۲۰۰ کتاب</td>
                                    <td><span class="discount-badge">۳۵٪</span></td>
                                </tr>
                                <tr>
                                    <td>کتاب‌دوست ممتاز</td>
                                    <td>۵۰۰ کتاب</td>
                                    <td><span class="discount-badge">۴۰٪</span></td>
                                </tr>
                                <tr>
                                    <td>پادشاه کتاب</td>
                                    <td>۱۰۰۰ کتاب</td>
                                    <td><span class="discount-badge">۵۰٪</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                با ثبت‌نام و اولین خرید، به سطح «کتاب‌دوست تازه‌کار» می‌رسید و از ۵٪ تخفیف در خرید بعدی بهره‌مند می‌شوید.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- بخش سطوح برتر با دسترسی‌های ویژه -->
                <section class="rewards-section">
                    <div class="section-header">
                        <i class="fas fa-unlock-alt section-icon"></i>
                        <h2>خرید بیشتر، کتاب‌های نایاب بیشتر!</h2>
                    </div>
                    <div class="rewards-card">
                        <p class="text-center mb-4">
                            هر قدمی که در مسیر دانش برمی‌دارید، دریچه‌ای تازه به سوی گنجینه‌های مخفی جهان کتاب‌ها باز می‌شود.
                            با افزایش تعداد خریدهای خود، به منابعی دسترسی پیدا می‌کنید که برای عموم کاربران قفل هستند.
                            این امتیازات دائمی هستند و بر اساس مجموع کل خریدهای شما در تمام زمان‌ها محاسبه می‌شوند!
                        </p>

                        <div class="levels-grid">
                            <div class="access-card special-access">
                                <div class="access-header">
                                    <div class="access-icon">
                                        <i class="fas fa-unlock"></i>
                                    </div>
                                    <h3 class="access-name">محفل کتاب‌های خاص</h3>
                                    <div class="access-discount">۱۰۰+ کتاب</div>
                                </div>
                                <div class="access-body">
                                    <div class="access-requirement">سطح: کاوشگر دانش</div>
                                    <ul class="access-features">
                                        <li>امکان تبدیل فایل ها به صورت رایگان</li>
                                        <li>امکان خرید مقالات تخصصی ویژه</li>
                                        <li>امکان خرید کتاب‌های خاص</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="access-card rare-access">
                                <div class="access-header">
                                    <div class="access-icon">
                                        <i class="fas fa-book-medical"></i>
                                    </div>
                                    <h3 class="access-name">تالار منابع کمیاب</h3>
                                    <div class="access-discount">۵۰۰+ کتاب</div>
                                </div>
                                <div class="access-body">
                                    <div class="access-requirement">سطح: استاد دانایی</div>
                                    <ul class="access-features">
                                        <li>امکان تبدیل فایل ها به صورت رایگان</li>
                                        <li>امکان خرید مقالات تخصصی ویژه</li>
                                        <li>امکان خرید کتاب‌های خاص</li>
                                        <li>امکان خرید کتاب‌های کمیاب</li>
                                        <li>پشتیبانی اختصاصی، از صبح تا شب</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="access-card legendary-access">
                                <div class="access-header">
                                    <div class="access-icon">
                                        <i class="fas fa-book-dead"></i>
                                    </div>
                                    <h3 class="access-name">قلمرو نایاب‌ها</h3>
                                    <div class="access-discount">۱۰۰۰+ کتاب</div>
                                </div>
                                <div class="access-body">
                                    <div class="access-requirement">سطح: افسانه‌ی کتاب‌ها</div>
                                    <ul class="access-features">
                                        <li>امکان تبدیل فایل ها به صورت رایگان</li>
                                        <li>امکان خرید مقالات تخصصی ویژه</li>
                                        <li>امکان خرید کتاب‌های خاص</li>
                                        <li>امکان خرید کتاب‌های کمیاب</li>
                                        <li>امکان خرید کتاب‌های نایاب</li>
                                        <li>پشتیبانی ۲۴ ساعته، همیشه در دسترس</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/special-access" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i>
                                ببین چقدر تا سطح بعدی فاصله داری!
                            </a>
                        </div>
                    </div>
                </section>

                <!-- بخش نکات و قوانین -->
                <section class="rewards-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle section-icon"></i>
                        <h2>نکات و قوانین سیستم تخفیف</h2>
                    </div>
                    <div class="rewards-card">
                        <ul class="rules-list">
                            <li>سطح شما بر اساس تعداد کتاب‌هایی که در ۳۶۵ روز گذشته خریداری کرده‌اید، محاسبه می‌شود.</li>
                            <li>اگر برای مدت طولانی خریدی انجام ندهید، سطح شما کاهش می‌یابد و به تبع آن درصد تخفیف شما نیز کمتر می‌شود.</li>
                            <li>تخفیف‌های سیستم سطح‌بندی با تخفیف‌های فصلی و جشنواره‌ای قابل جمع نیست و همواره بیشترین تخفیف اعمال می‌شود.</li>
                            <li>برای حفظ سطح فعلی خود، حداقل هر ۳ ماه یک خرید انجام دهید.</li>
                            <li>کتابخانه دیجیتال بَلیان حق تغییر در قوانین و شرایط سیستم تخفیف را برای خود محفوظ می‌دارد.</li>
                        </ul>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="rewards-footer">
                    <i class="fas fa-gift"></i>
                    <h3>همین امروز ثبت‌نام کنید و از مزایای باشگاه مشتریان بهره‌مند شوید</h3>
                    <p>با عضویت در باشگاه مشتریان کتابخانه دیجیتال بَلیان، هر چه بیشتر بخوانید، بیشتر پس‌انداز می‌کنید. مبلغی که بابت خرید کتاب می‌پردازیم به مراتب پایین‌تر از هزینه‌هایی است که در آینده بابت نخواندن آن خواهیم پرداخت.</p>
                    <div>
                        <a href="/register" class="cta-button pulse me-2">
                            <i class="fas fa-user-plus me-2"></i>
                            ثبت‌نام
                        </a>
                        <a href="/books" class="cta-button">
                            <i class="fas fa-shopping-cart me-2"></i>
                            مشاهده کتاب‌ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.rewards-card, .intro-card, .level-card, .access-card');

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

            // افکت کلیک روی کارت‌های سطح و دسترسی
            const cards = document.querySelectorAll('.level-card, .access-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.add('pulse');
                    setTimeout(() => {
                        this.classList.remove('pulse');
                    }, 2000);
                });
            });
        });
    </script>
@endpush
