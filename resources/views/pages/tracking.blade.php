@extends('layouts.app')

@section('title', 'پیگیری سفارش - کتابخانه دیجیتال بَلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .order-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .order-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .order-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .order-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .order-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* بخش‌ها */
        .order-section {
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

        .order-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

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

        /* فرم پیگیری سفارش */
        .tracking-form {
            background: linear-gradient(to right, #f8fafc, #f0f9ff);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .form-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            font-size: 0.95rem;
            color: var(--gray-600);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        /* استایل منوی بازشونده */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper .form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-left: 2.5rem;
        }

        .select-arrow {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            pointer-events: none;
            transition: all 0.3s;
        }

        .select-wrapper:hover .select-arrow {
            color: #2563eb;
        }

        .select-wrapper .form-control:focus + .select-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        /* لیبل‌های فرم */
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
        }

        /* قرمز برای فیلدهای اجباری */
        .text-danger {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23ef4444' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 1rem center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .form-control.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .btn-tracking {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-tracking:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        }

        .btn-tracking i {
            margin-left: 0.5rem;
        }

        /* راه‌های ارتباطی */
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

        /* وضعیت سفارش */
        .order-status-section {
            margin-top: 3rem;
            display: none;
        }

        .status-timeline {
            margin-top: 2rem;
            position: relative;
            padding-right: 30px;
        }

        .status-line {
            position: absolute;
            top: 0;
            right: 15px;
            width: 2px;
            height: 100%;
            background-color: #e2e8f0;
            transform: translateX(50%);
        }

        .status-item {
            position: relative;
            padding-bottom: 2rem;
            padding-right: 2rem;
        }

        .status-item:last-child {
            padding-bottom: 0;
        }

        .status-marker {
            position: absolute;
            width: 30px;
            height: 30px;
            top: 0;
            right: 0;
            transform: translateX(50%);
            background-color: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .status-completed .status-marker {
            background-color: #10b981;
            border-color: #10b981;
            color: white;
        }

        .status-active .status-marker {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .status-info {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .status-completed .status-info {
            border-right: 3px solid #10b981;
        }

        .status-active .status-info {
            border-right: 3px solid #3b82f6;
        }

        .status-waiting .status-info {
            border-right: 3px solid #e2e8f0;
            opacity: 0.7;
        }

        .status-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .status-time {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }

        .status-description {
            font-size: 0.95rem;
            color: var(--gray-700);
            margin-bottom: 0;
        }

        /* بخش پایانی */
        .order-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .order-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .order-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .order-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .order-hero-title {
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

            .intro-message {
                flex-direction: column;
                text-align: center;
            }

            .intro-icon {
                margin: 0 auto 1rem;
            }

            .status-timeline {
                padding-right: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- هدر صفحه پیگیری سفارش -->
    <div class="order-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-box-open order-hero-icon"></i>
                <h1 class="order-hero-title">پیگیری سفارش</h1>
                <p class="order-hero-desc">وضعیت سفارشات خود را در کتابخانه دیجیتال بَلیان پیگیری کنید</p>
                <div class="order-hero-update">
                    پاسخگویی سریع به درخواست‌ها
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- پیام راهنما -->
                <div class="intro-message order-card mb-4">
                    <div class="intro-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <p>پیگیری سفارشات فروشگاه بَلیان در این صفحه می‌توانید سفارشات خود را در سایت بلیان پیگیری نمایید. لطفا قبل از ثبت درخواست پیگیری، توضیحات زیر را مطالعه نمایید.</p>
                </div>

                <!-- بخش فرم پیگیری سفارش -->
                <section class="order-section">
                    <div class="section-header">
                        <i class="fas fa-search section-icon"></i>
                        <h2>جستجوی سفارش</h2>
                    </div>
                    <div class="order-card">
                        <div class="tracking-form">
                            <div class="form-header">
                                <h3>پیگیری وضعیت سفارش</h3>
                                <p>برای پیگیری سفارش خود، لطفاً شماره سفارش و نوع اطلاعات خریدار را وارد کنید</p>
                            </div>
                            <form id="orderTrackingForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="order_number">شماره سفارش <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="order_number" placeholder="مثال: BLN-12345" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="contact_type">نوع اطلاعات خریدار <span class="text-danger">*</span></label>
                                            <div class="select-wrapper">
                                                <select class="form-control mb-2" id="contact_type">
                                                    <option value="email">پیگیری با ایمیل</option>
                                                    <option value="phone">پیگیری با شماره موبایل</option>
                                                </select>
                                                <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group" id="email_field">
                                            <input type="email" class="form-control" id="email" placeholder="ایمیلی که هنگام خرید وارد کردید">
                                        </div>
                                        <div class="form-group" id="phone_field" style="display: none;">
                                            <input type="tel" class="form-control" id="phone" placeholder="شماره موبایل (مثال: 09123456789)" pattern="09[0-9]{9}" maxlength="11" inputmode="numeric" dir="ltr">
                                            <small class="text-muted">با فرمت 09XXXXXXXXX وارد کنید</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn-tracking">
                                        <i class="fas fa-search"></i>
                                        جستجوی سفارش
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- بخش وضعیت سفارش (نمایش پس از جستجو) -->
                <section class="order-status-section" id="orderStatusSection">
                    <div class="section-header">
                        <i class="fas fa-clipboard-list section-icon"></i>
                        <h2>وضعیت سفارش</h2>
                    </div>
                    <div class="order-card">
                        <div class="order-info mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h4 class="mb-2">شماره سفارش: <span class="text-primary">BLN-12345</span></h4>
                                    <p class="text-muted mb-1">تاریخ ثبت: ۱۴۰۴/۰۱/۲۵</p>
                                </div>
                                <div class="col-md-6 mb-3 text-md-end">
                                    <h4 class="mb-2">وضعیت: <span class="badge bg-success">تکمیل شده</span></h4>
                                    <p class="text-muted mb-1">آخرین بروزرسانی: دیروز ۱۴:۳۰</p>
                                </div>
                            </div>
                        </div>

                        <div class="status-timeline">
                            <div class="status-line"></div>

                            <div class="status-item status-completed">
                                <div class="status-marker"><i class="fas fa-check"></i></div>
                                <div class="status-info">
                                    <h4 class="status-title">ثبت سفارش</h4>
                                    <div class="status-time">۱۴۰۴/۰۱/۲۵ - ساعت ۱۰:۱۵</div>
                                    <p class="status-description">سفارش شما با موفقیت ثبت شد و در انتظار پرداخت قرار گرفت.</p>
                                </div>
                            </div>

                            <div class="status-item status-completed">
                                <div class="status-marker"><i class="fas fa-check"></i></div>
                                <div class="status-info">
                                    <h4 class="status-title">پرداخت موفق</h4>
                                    <div class="status-time">۱۴۰۴/۰۱/۲۵ - ساعت ۱۰:۱۸</div>
                                    <p class="status-description">پرداخت سفارش شما با موفقیت انجام شد و سفارش شما آماده پردازش است.</p>
                                </div>
                            </div>

                            <div class="status-item status-completed">
                                <div class="status-marker"><i class="fas fa-check"></i></div>
                                <div class="status-info">
                                    <h4 class="status-title">پردازش سفارش</h4>
                                    <div class="status-time">۱۴۰۴/۰۱/۲۵ - ساعت ۱۰:۲۰</div>
                                    <p class="status-description">سفارش شما در حال پردازش است و لینک‌های دانلود آماده‌سازی می‌شوند.</p>
                                </div>
                            </div>

                            <div class="status-item status-active">
                                <div class="status-marker"><i class="fas fa-check"></i></div>
                                <div class="status-info">
                                    <h4 class="status-title">آماده تحویل</h4>
                                    <div class="status-time">۱۴۰۴/۰۱/۲۵ - ساعت ۱۰:۲۵</div>
                                    <p class="status-description">لینک‌های دانلود محصولات شما آماده شده و به ایمیل شما ارسال شده است. همچنین می‌توانید از طریق پنل کاربری به محصولات خود دسترسی داشته باشید.</p>
                                </div>
                            </div>

                            <div class="status-item status-waiting">
                                <div class="status-marker"><i class="fas fa-clock"></i></div>
                                <div class="status-info">
                                    <h4 class="status-title">دریافت محصول</h4>
                                    <div class="status-time">در انتظار تایید</div>
                                    <p class="status-description">لطفاً پس از دانلود و استفاده از محصول، رضایت خود را در صفحه محصول ثبت نمایید.</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>
                                دسترسی به لینک‌های دانلود
                            </a>
                            <a href="#" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-print me-2"></i>
                                چاپ رسید
                            </a>
                        </div>
                    </div>
                </section>

                <!-- بخش سوالات متداول -->
                <section class="order-section">
                    <div class="section-header">
                        <i class="fas fa-question-circle section-icon"></i>
                        <h2>سوالات متداول</h2>
                    </div>
                    <div class="order-card">
                        <p>در این بخش، پاسخ سوالات متداول درباره مشکلات احتمالی سفارشات را می‌توانید مشاهده کنید.</p>

                        <div class="faq-accordions">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                            کتاب روی دستگاه شما باز نمی‌شود؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>در صورتی که پس از دانلود کتاب، فایل کتاب روی دستگاه شما باز نمی‌شود، باید نرم افزار کتابخوان را روی دستگاه خود نصب نمایید. جهت دانلود نرم افزار کتابخوان، به صفحه «نحوه باز کردن کتاب‌های الکترونیکی در سیستم‌عامل‌های مختلف» مراجعه نمایید.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                            فرمت کتاب موردنظر موجود نیست؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>در صورتی که کتابی را از سایت خریداری کرده‌اید و به فرمت های دیگری مانند PDF و EPUB یا سایر فرمت‌ها نیاز دارید، به پشتیبانی درخواست بفرستید؛ در صورتی که سایر فرمت های آن کتاب موجود باشد، برای شما ارسال خواهد شد.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                            لینک دانلود منقضی شده است؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>محصولات مجازی و دانلودی سایت بلیان تا 100 روز پس از پرداخت، به تعداد 50 بار قابل دانلود هستند. پس از آن باید دوباره محصول را خریداری نمایید. برای برخی از محصولات ممکن است بتوانیم کد تخفیف برای خرید مجدد آن محصول به شما ارائه دهیم. پس از طریق راه‌های ارتباطی موجود در همین صفحه، اطلاعات محصول خریداری شده همراه با درخواست خود برای کد تخفیف را بنویسید. پس از بررسی توسط کارشناسان سایت بلیان، در صورتی که محصول قابلیت ثبت کد تخفیف برای خرید مجدد را داشته باشد، کد تخفیف برای شما ارسال خواهد شد.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading4">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                            لینک دانلود کار نمیکند؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>با توجه به اینکه کلیه محصولات دانلودی بر روی سرور های قدرتمند بلیان قرار گرفته اند، به مدت 100 روز لینک دانلود برای شما فعال خواهند بود. اگر با این مشکل مواجه شدید، چند دقیقه بعد دوباره اقدام به دانلود نمایید. اگر همچنان مشکل پابرجا بود، اطلاعات سفارش را به پشتیانی سایت بلیان بفرستید تا لینک دانلود جدید برای شما ایجاد شود.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading5">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                                            فایل اشتباهی دانلود می‌شود؟
                                        </button>
                                    </h2>
                                    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>در هنگام خرید محصولات دانلودی، ممکن است برخی از محصولات دارای چند لینک دانلود باشند، مانند کتاب های چند جلدی. در صورتی که باز هم در هنگام دریافت کتاب این مشکل وجود دارد، اطلاعات را ارسال نمایید تا لینک دانلود برای شما اصلاح شود.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading6">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                                            درخواست کد تخفیف برای محصول آپدیت شده
                                        </button>
                                    </h2>
                                    <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>برخی از محصولات مجازی سایت بلیان ممکن است پس از مدتی آپدیت شوند و محتوای جدید، جایگزین محتوای قبلی شوند. در این صورت، جهت دریافت محصول جدید باید دوباره آن محصول را خریداری نمایید. در صورتی که قبلا آن محصول را خریداری کرده‌اید و میخواهید آپدیت جدید محصول را هم دریافت نمایید، اطلاعات سفارش قبلی خود را همراه با درخواست تخفیف، به پشتیبانی بفرستید. پس از بررسی توسط کارشناسان سایت بلیان، در صورت امکان، کد تخفیف برای شما ارسال خواهد شد.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading7">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                                            رویه بازگرداندن محصول
                                        </button>
                                    </h2>
                                    <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>برای فایل‌های مجازی (دانلودی)، فقط در صورتی که اطلاعات محصول با محتوای دانلود شده تفاوت داشته باشند، این امکان وجود خواهد داشت. جهت کسب اطلاعات بیشتر، صفحه «رویه بازگردانی محصول» را مطالعه نمایید.</p>
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
                <section class="order-section">
                    <div class="section-header">
                        <i class="fas fa-comments section-icon"></i>
                        <h2>راه‌های ارتباطی</h2>
                    </div>
                    <div class="order-card">
                        <p>در صورتی که نیاز به پیگیری سفارش دارید یا سوالی درباره سفارش خود دارید، می‌توانید از یکی از روش‌های زیر با ما تماس بگیرید.</p>

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

                <!-- بخش پایانی -->
                <div class="order-footer">
                    <i class="fas fa-headset"></i>
                    <h3>پشتیبانی سریع و مطمئن</h3>
                    <p>در کتابخانه دیجیتال بلیان، ما به پیگیری سریع و دقیق سفارشات متعهد هستیم. هدف ما خدمت‌رسانی شایسته به مشتریان و ارائه کتاب‌های با کیفیت در اسرع وقت است.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // پردازش شماره موبایل
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    // تبدیل اعداد فارسی به انگلیسی
                    let value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));

                    // حذف همه کاراکترهای غیر عددی
                    value = value.replace(/\D/g, '');

                    // بررسی و اصلاح شماره موبایل
                    if (value.startsWith('98')) {
                        // اگر با 98 شروع شود، آن را به 0 تبدیل می‌کنیم
                        value = '0' + value.substring(2);
                    } else if (!value.startsWith('0') && value.length > 0) {
                        // اگر با 0 شروع نشود، 0 به اول آن اضافه می‌کنیم
                        value = '0' + value;
                    }

                    // محدود کردن به حداکثر 11 رقم
                    if (value.length > 11) {
                        value = value.substring(0, 11);
                    }

                    // قرار دادن مقدار اصلاح شده در فیلد
                    this.value = value;
                });

                // تایید فرمت شماره موبایل هنگام blur
                phoneInput.addEventListener('blur', function() {
                    const value = this.value;
                    if (value && (value.length !== 11 || !value.startsWith('09'))) {
                        this.classList.add('is-invalid');
                    } else if (value) {
                        this.classList.remove('is-invalid');
                    }
                });
            }

            // تغییر حالت فرم بین ایمیل و شماره تلفن
            const contactTypeSelect = document.getElementById('contact_type');
            const emailField = document.getElementById('email_field');
            const phoneField = document.getElementById('phone_field');

            if (contactTypeSelect && emailField && phoneField) {
                contactTypeSelect.addEventListener('change', function() {
                    if (this.value === 'email') {
                        emailField.style.display = 'block';
                        phoneField.style.display = 'none';
                        document.getElementById('email').setAttribute('required', '');
                        document.getElementById('phone').removeAttribute('required');
                    } else {
                        emailField.style.display = 'none';
                        phoneField.style.display = 'block';
                        document.getElementById('phone').setAttribute('required', '');
                        document.getElementById('email').removeAttribute('required');
                    }
                });
            }

            // نمایش بخش وضعیت سفارش پس از ارسال فرم
            const orderTrackingForm = document.getElementById('orderTrackingForm');
            const orderStatusSection = document.getElementById('orderStatusSection');

            if (orderTrackingForm && orderStatusSection) {
                orderTrackingForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // بررسی اعتبارسنجی فرم
                    let isValid = true;
                    const orderNumber = document.getElementById('order_number').value;

                    if (!orderNumber) {
                        isValid = false;
                        document.getElementById('order_number').classList.add('is-invalid');
                    } else {
                        document.getElementById('order_number').classList.remove('is-invalid');
                    }

                    const contactType = document.getElementById('contact_type').value;
                    if (contactType === 'email') {
                        const email = document.getElementById('email').value;
                        if (!email) {
                            isValid = false;
                            document.getElementById('email').classList.add('is-invalid');
                        } else {
                            document.getElementById('email').classList.remove('is-invalid');
                        }
                    } else {
                        const phone = document.getElementById('phone').value;
                        const phoneValidationRegex = /^09\d{9}$/;
                        if (!phone || !phoneValidationRegex.test(phone)) {
                            isValid = false;
                            document.getElementById('phone').classList.add('is-invalid');
                        } else {
                            document.getElementById('phone').classList.remove('is-invalid');
                        }
                    }

                    if (isValid) {
                        // در حالت واقعی اینجا یک درخواست AJAX به سرور ارسال می‌شود
                        // برای نمایش، فقط بخش وضعیت را نمایش می‌دهیم
                        orderStatusSection.style.display = 'block';

                        // اسکرول به بخش وضعیت
                        setTimeout(() => {
                            orderStatusSection.scrollIntoView({ behavior: 'smooth' });
                        }, 100);
                    }
                });
            }

            // افکت ظاهر شدن تدریجی آیتم‌ها هنگام اسکرول
            const animateItems = document.querySelectorAll('.contact-method-card, .accordion-item, .intro-message, .status-item');

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
            const buttons = document.querySelectorAll('.btn, .btn-tracking, .contact-method-card');
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
