@extends('layouts.app')

@section('title', 'جزئیات سفارش - کتابخانه دیجیتال بالیان')

@section('content')
    <div class="order-container">
        <div class="container py-5">
            <!-- نوار منوی پروفایل -->
            <div class="profile-navbar mb-4">
                <div class="row">
                    <div class="col-12">
                        <div class="profile-nav">
                            <a href="{{ route('profile.index') }}" class="profile-nav-item">
                                <i class="fas fa-home"></i>
                                <span>داشبورد</span>
                            </a>
                            <a href="{{ route('profile.my-orders') }}" class="profile-nav-item active">
                                <i class="fas fa-shopping-cart"></i>
                                <span>سفارشات من</span>
                            </a>
                            <a href="{{ route('profile.my-books') }}" class="profile-nav-item">
                                <i class="fas fa-book"></i>
                                <span>کتابخانه من</span>
                            </a>
                            <a href="{{ route('profile.favorites') }}" class="profile-nav-item">
                                <i class="fas fa-heart"></i>
                                <span>علاقه‌مندی‌ها</span>
                            </a>
                            <a href="{{ route('logout') }}" class="profile-nav-item logout-btn"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>خروج</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جزئیات سفارش -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="order-details-card">
                        <div class="card-header">
                            <h4>
                                <i class="fas fa-receipt me-2"></i>
                                جزئیات سفارش
                                <span class="order-number">شماره سفارش: xxxxx</span>
                            </h4>
                        </div>

                        <div class="card-body">
                            <!-- محصولات سفارش -->
                            <div class="order-items">
                                @foreach($order->items as $item)
                                    <div class="order-item">
                                        <div class="order-item-cover">
                                            <img src="{{ asset('storage/' . $item->book->cover_image) }}"
                                                 alt="{{ $item->book->title }}">
                                        </div>
                                        <div class="order-item-details">
                                            <h5 class="book-title">{{ $item->book->title }}</h5>
                                            <p class="book-author">{{ $item->book->author }}</p>
                                            <div class="book-type">
                                                <span class="badge bg-primary">نسخه الکترونیکی</span>
                                                <span class="book-format">{{ $item->format }}</span>
                                            </div>
                                        </div>
                                        <div class="order-item-price">
                                            <span class="price">{{ number_format($item->price) }} تومان</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- اطلاعات تکمیلی سفارش -->
                            <div class="order-info-section">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <div class="info-content">
                                                <span class="info-label">تاریخ سفارش</span>
                                                <span class="info-value">{{ $order->created_at->format('Y/m/d') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-item">
                                            <i class="fas fa-check-circle"></i>
                                            <div class="info-content">
                                                <span class="info-label">وضعیت سفارش</span>
                                                <span class="info-value {{ $order->status_class }}">
                                                    {{ $order->status_text }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-item">
                                            <i class="fas fa-hashtag"></i>
                                            <div class="info-content">
                                                <span class="info-label">کد پیگیری</span>
                                                <span class="info-value">{{ $order->tracking_code }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-item">
                                            <i class="fas fa-percent"></i>
                                            <div class="info-content">
                                                <span class="info-label">تخفیف اعمال شده</span>
                                                <span class="info-value">
                                                    {{ $order->discount_amount ? number_format($order->discount_amount) . ' تومان' : 'بدون تخفیف' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- جمع کل سفارش -->
                            <div class="order-total-section">
                                <div class="total-breakdown">
                                    <div class="total-row">
                                        <span>مجموع</span>
                                        <span>{{ number_format($order->subtotal) }} تومان</span>
                                    </div>
                                    <div class="total-row">
                                        <span>تخفیف</span>
                                        <span class="text-danger">
                                            - {{ number_format($order->discount_amount) }} تومان
                                        </span>
                                    </div>
                                    <div class="total-row total-final">
                                        <span>مبلغ نهایی</span>
                                        <span>{{ number_format($order->total) }} تومان</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="order-actions">
                                @if($order->status == 'completed')
                                    <a href="{{ route('download.books', ['order_id' => $order->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-download me-2"></i> دانلود محصولات
                                    </a>
                                @endif
                                <a href="{{ route('profile.my-orders') }}" class="btn btn-outline-secondary">
                                    بازگشت به لیست سفارشات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- سایدبار راهنما -->
                <div class="col-lg-4">
                    <div class="order-help-card">
                        <div class="card-header">
                            <h4>
                                <i class="fas fa-info-circle me-2"></i> راهنمای سفارش
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="help-section">
                                <h5>نکات مهم درباره سفارش</h5>
                                <ul class="help-list">
                                    <li>
                                        <i class="fas fa-check-circle text-success"></i>
                                        پس از تکمیل سفارش، لینک دانلود برای شما ارسال می‌شود
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-success"></i>
                                        محصولات الکترونیکی بلافاصله قابل دانلود هستند
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-success"></i>
                                        فایل‌ها در فرمت‌های متداول EPUB، PDF و MOBI موجود هستند
                                    </li>
                                    <li>
                                        <i class="fas fa-info-circle text-warning"></i>
                                        محدودیت دانلود: ۳ بار در ۳۰ روز
                                    </li>
                                </ul>
                            </div>

                            <div class="support-section">
                                <h5>پشتیبانی</h5>
                                <div class="support-contact">
                                    <i class="fas fa-headset"></i>
                                    <div>
                                        <span>پشتیبانی</span>
                                        <a href="tel:02191095000">۰۲۱-۹۱۰۹۵۰۰۰</a>
                                    </div>
                                </div>
                                <div class="support-contact">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <span>ایمیل پشتیبانی</span>
                                        <a href="mailto:support@balian.ir">support@balian.ir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 30px;
        }

        .order-details-card, .order-help-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h4 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }

        .order-number {
            font-size: 14px;
            color: #6c757d;
            margin-right: 10px;
        }

        .order-items {
            padding: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .order-item-cover {
            width: 80px;
            height: 110px;
            margin-left: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .order-item-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-item-details {
            flex: 1;
        }

        .book-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .book-author {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .book-type {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .book-type .badge {
            font-size: 12px;
            padding: 3px 8px;
        }

        .book-format {
            color: #6c757d;
            font-size: 12px;
        }

        .order-item-price {
            font-weight: bold;
            color: #2c3e50;
        }

        .order-info-section {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .info-item {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .info-item i {
            color: #4270e4;
            margin-left: 15px;
            font-size: 24px;
        }

        .info-content {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
        }

        .order-total-section {
            padding: 20px;
        }

        .total-breakdown {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .total-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .total-final {
            font-weight: bold;
            color: #4270e4;
        }

        .order-actions {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            background-color: #f8f9fa;
        }

        .order-help-card .card-body {
            padding: 20px;
        }

        .help-section, .support-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .help-section h5, .support-section h5 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .help-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .help-list li i {
            margin-left: 10px;
            font-size: 18px;
        }

        .support-contact {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .support-contact i {
            font-size: 24px;
            color: #4270e4;
            margin-left: 15px;
        }

        .support-contact div {
            display: flex;
            flex-direction: column;
        }

        .support-contact span {
            font-size: 12px;
            color: #6c757d;
        }

        .support-contact a {
            color: #2c3e50;
            font-weight: 500;
            text-decoration: none;
        }

        .support-contact a:hover {
            color: #4270e4;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .order-details-card, .order-help-card {
                margin-bottom: 20px;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .order-item-cover {
                margin-left: 0;
                margin-bottom: 15px;
            }

            .order-actions {
                flex-direction: column;
                gap: 10px;
            }

            .order-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // اضافه کردن انیمیشن به جزئیات سفارش
            const orderItems = document.querySelectorAll('.order-item');
            orderItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // کپی کردن کد پیگیری
            const trackingCodeElement = document.querySelector('.order-number');
            if (trackingCodeElement) {
                trackingCodeElement.addEventListener('click', function() {
                    const trackingCode = this.textContent.split(': ')[1];

                    // کپی کردن به کلیپ بورد
                    navigator.clipboard.writeText(trackingCode).then(() => {
                        // نمایش توضیح موقت
                        const tooltip = document.createElement('div');
                        tooltip.textContent = 'کپی شد!';
                        tooltip.style.position = 'absolute';
                        tooltip.style.backgroundColor = '#28a745';
                        tooltip.style.color = 'white';
                        tooltip.style.padding = '5px 10px';
                        tooltip.style.borderRadius = '5px';
                        tooltip.style.top = '-30px';
                        tooltip.style.left = '0';
                        tooltip.style.fontSize = '12px';

                        this.style.position = 'relative';
                        this.appendChild(tooltip);

                        // حذف توضیح بعد از ۲ ثانیه
                        setTimeout(() => {
                            this.removeChild(tooltip);
                        }, 2000);
                    });
                });
            }
        });
    </script>
@endpush
