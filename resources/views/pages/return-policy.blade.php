@extends('layouts.app')

@section('title', 'رویه بازگردانی محصول - کتابخانه دیجیتال بلیان')

@push('styles')
    <style>
        /* هدر صفحه */
        .return-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        .return-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .return-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .return-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .return-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* بخش‌ها */
        .return-section {
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

        .return-card {
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

        .policy-list {
            padding-right: 1.5rem;
            margin: 0;
        }

        .policy-list li {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* بخش پایانی */
        .return-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .return-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .return-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .return-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .return-footer .btn {
            transition: all 0.3s ease;
            border: 2px solid #fff;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .return-footer .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background-color: rgba(255, 255, 255, 0.9);
            color: #2563eb;
        }

        /* پاسخگویی */
        @media (max-width: 768px) {
            .return-hero-title {
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
    <!-- هدر صفحه -->
    <div class="return-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-undo-alt return-hero-icon"></i>
                <h1 class="return-hero-title">رویه بازگردانی محصول</h1>
                <p class="return-hero-desc">شرایط و ضوابط بازگردانی محصولات دیجیتال بلیان</p>
                <div class="return-hero-update">
                    آخرین به‌روزرسانی: {{ date('Y/m/d') }}
                </div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- جدول خلاصه قوانین -->
                <div class="alert alert-primary p-3 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>خلاصه قوانین بازگردانی محصول</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered border-primary mb-0">
                            <thead class="bg-primary bg-opacity-10">
                            <tr>
                                <th scope="col">نوع محصول</th>
                                <th scope="col">شرایط بازگردانی</th>
                                <th scope="col">مدت زمان</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>فایل‌های دانلودی</td>
                                <td>تفاوت محتوا با توضیحات</td>
                                <td>24 ساعت پس از خرید</td>
                            </tr>
                            <tr>
                                <td>پیش خرید محصولات</td>
                                <td>تاخیر در ارائه محصول</td>
                                <td>تا زمان ارائه محصول</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- بخش نکات مهم -->
                <section class="return-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle section-icon"></i>
                        <h2>نکات مهم</h2>
                    </div>
                    <div class="return-card">
                        <p>در حال حاضر تمامی محصولات موجود در سایت بلیان، الکترونیکی (دانلودی) هستند. لطفا قبل از خرید از سایت، اطلاعات کتاب ها را با دقت بخوانید و سپس اقدام به خرید آن کتاب، مقاله یا جزوه نمایید.</p>

                        <div class="info-highlight">
                            <p>برای فایل‌های مجازی (دانلودی)، فقط در صورتی که اطلاعات محصول با محتوای دانلود شده تفاوت داشته باشند، این امکان وجود خواهد داشت.</p>
                        </div>
                    </div>
                </section>

                <!-- بخش پیش خرید -->
                <section class="return-section">
                    <div class="section-header">
                        <i class="fas fa-shopping-cart section-icon"></i>
                        <h2>پیش خرید محصولات</h2>
                    </div>
                    <div class="return-card">
                        <p>در صورتی که کتابی را پیش خرید کرده باشید و در زمان تعیین شده به دست شما نرسیده باشد، در صورتی که تمایل نداشته باشید زمان آن را تمدید کنید، میتوانید از لیست انتظار انصراف داده و هزینه آن را پس بگیرید.</p>
                    </div>
                </section>

                <!-- بخش مدت زمان دانلود -->
                <section class="return-section">
                    <div class="section-header">
                        <i class="fas fa-download section-icon"></i>
                        <h2>مدت زمان و تعداد دانلود</h2>
                    </div>
                    <div class="return-card">
                        <p>محصولات مجازی و دانلودی سایت بلیان تا 100 روز پس از پرداخت، به تعداد 50 بار قابل دانلود هستند. پس از آن باید دوباره محصول را خریداری نمایید.</p>
                    </div>
                </section>

                <!-- بخش کد تخفیف -->
                <section class="return-section">
                    <div class="section-header">
                        <i class="fas fa-tag section-icon"></i>
                        <h2>کد تخفیف برای خرید مجدد</h2>
                    </div>
                    <div class="return-card">
                        <p>برای برخی از محصولات ممکن است بتوانیم کد تخفیف برای خرید مجدد آن محصول به شما ارائه دهیم. پس از طریق صفحه تماس با ما، اطلاعات محصول خریداری شده را ارسال نمایید و درخواست خود برای کد تخفیف را بنویسید. پس از بررسی توسط کارشناسان سایت بلیان، در صورتی که محصول قابلیت ثبت کد تخفیف برای خرید مجدد را داشته باشد، کد تخفیف برای شما ارسال خواهد شد.</p>
                        <div class="info-highlight mt-3">
                            <p><i class="fas fa-info-circle me-2"></i> برای درخواست کد تخفیف خرید مجدد، لطفاً شماره سفارش و ایمیل خود را در فرم تماس با ما ذکر کنید تا کارشناسان ما سریع‌تر به درخواست شما رسیدگی کنند.</p>
                        </div>
                    </div>
                </section>

                <!-- بخش پایانی -->
                <div class="return-footer">
                    <i class="fas fa-headset"></i>
                    <h3>پشتیبانی و راهنمایی</h3>
                    <p>کتابخانه دیجیتال بلیان همواره آماده پاسخگویی به سوالات و رسیدگی به درخواست‌های شماست. برای کسب اطلاعات بیشتر و راهنمایی می‌توانید با ما تماس بگیرید.</p>
                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold">تماس با ما</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
