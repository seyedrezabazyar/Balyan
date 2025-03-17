@extends('layouts.app')

@section('title', 'صفحه اصلی - کتابخانه دیجیتال')

@section('content')
    <div class="container">
        <!-- بنر تبلیغاتی -->
        <div class="promo-banner mb-5">
            <div class="promo-shape promo-shape-1"></div>
            <div class="promo-shape promo-shape-2"></div>
            <div class="promo-content">
                <h2 class="promo-title">به کتابخانه دیجیتال خوش آمدید</h2>
                <p class="promo-text">هزاران کتاب الکترونیکی و صوتی در موضوعات مختلف</p>
                <a href="#" class="btn promo-btn">مشاهده کتاب‌های ویژه</a>
            </div>
            <img src="{{ asset('images/books.jpg') }}" alt="کتاب‌ها" class="promo-image">
        </div>

        <!-- کتاب‌های پرفروش -->
        <div class="mb-5">
            <h2 class="section-title">کتاب‌های پرفروش</h2>
            <p class="section-subtitle">محبوب‌ترین کتاب‌های هفته گذشته</p>

            <div class="row">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <div class="product-img-container">
                                <img src="{{ asset('images/book' . $i . '.jpg') }}" alt="کتاب {{ $i }}" class="product-img">
                                <div class="product-badge">پرفروش</div>
                                <button type="button" class="product-bookmark" aria-label="افزودن به علاقه‌مندی‌ها">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="product-body">
                                <h5 class="product-title">عنوان کتاب {{ $i }}</h5>
                                <p class="product-author">نویسنده: نام نویسنده</p>
                                <p class="product-desc">توضیحات کوتاه در مورد کتاب و محتوای آن که می‌تواند چند خط باشد...</p>
                                <div class="product-footer">
                                    <div class="product-price">
                                        <span class="discount">۱۲۰,۰۰۰</span>
                                        ۹۸,۰۰۰ تومان
                                    </div>
                                    <a href="#" class="btn btn-add-cart">
                                        <i class="fas fa-shopping-cart me-1"></i> خرید
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- کتاب‌های جدید -->
        <div class="mb-5">
            <h2 class="section-title">کتاب‌های جدید</h2>
            <p class="section-subtitle">تازه‌ترین کتاب‌های اضافه شده</p>

            <div class="row">
                @for ($i = 5; $i <= 8; $i++)
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <div class="product-img-container">
                                <img src="{{ asset('images/book' . $i . '.jpg') }}" alt="کتاب {{ $i }}" class="product-img">
                                <div class="product-badge">جدید</div>
                                <button type="button" class="product-bookmark" aria-label="افزودن به علاقه‌مندی‌ها">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="product-body">
                                <h5 class="product-title">عنوان کتاب {{ $i }}</h5>
                                <p class="product-author">نویسنده: نام نویسنده</p>
                                <p class="product-desc">توضیحات کوتاه در مورد کتاب و محتوای آن که می‌تواند چند خط باشد...</p>
                                <div class="product-footer">
                                    <div class="product-price">
                                        ۸۵,۰۰۰ تومان
                                    </div>
                                    <a href="#" class="btn btn-add-cart">
                                        <i class="fas fa-shopping-cart me-1"></i> خرید
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* استایل‌های تکمیلی برای صفحه اصلی */
        /* -- بنر تبلیغاتی -- */
        .promo-banner {
            position: relative;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            height: 320px;
            display: flex;
            align-items: center;
            background: var(--gradient-blue);
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
        }

        .promo-content {
            position: relative;
            z-index: 1;
            padding: 2.5rem;
            color: white;
            max-width: 60%;
        }

        .promo-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .promo-text {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .promo-btn {
            background: white;
            color: var(--primary);
            border: none;
            border-radius: var(--border-radius-full);
            padding: 0.7rem 1.8rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all var(--transition-medium);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .promo-btn:hover {
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-hover);
        }

        .promo-image {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 45%;
            object-fit: cover;
            opacity: 0.2;
        }

        .promo-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .promo-shape-1 {
            top: -150px;
            right: -50px;
            width: 300px;
            height: 300px;
        }

        .promo-shape-2 {
            bottom: -150px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* -- کارت محصول -- */
        .product-card {
            border: none;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            transition: all var(--transition-medium);
            background-color: white;
            box-shadow: var(--shadow-md);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .product-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-6px);
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-img-container {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--gradient-teal);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: var(--border-radius-full);
            font-size: 0.8rem;
            font-weight: 500;
            box-shadow: var(--shadow-md);
            z-index: 1;
        }

        .product-bookmark {
            position: absolute;
            top: 10px;
            left: 10px;
            background: white;
            color: var(--gray-500);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all var(--transition-medium);
            z-index: 1;
            border: none;
        }

        .product-bookmark:hover {
            color: var(--danger);
            transform: scale(1.1);
        }

        .product-bookmark.active {
            color: var(--danger);
        }

        .product-body {
            padding: 1.2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
            transition: color var(--transition-medium);
        }

        .product-card:hover .product-title {
            color: var(--primary);
        }

        .product-author {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin-bottom: 0.8rem;
        }

        .product-desc {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin-bottom: 1rem;
            flex-grow: 1;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 0.8rem;
            border-top: 1px solid var(--gray-100);
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .product-price .discount {
            font-size: 0.8rem;
            color: var(--gray-500);
            text-decoration: line-through;
            margin-right: 0.5rem;
        }

        .btn-add-cart {
            background: var(--gradient-blue);
            color: white;
            border: none;
            border-radius: var(--border-radius-full);
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all var(--transition-medium);
        }

        .btn-add-cart:hover {
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
            transform: translateY(-3px);
            color: white;
        }

        /* -- عناوین بخش‌ها -- */
        .section-title {
            position: relative;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: auto;
            right: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background: var(--gradient-blue);
            border-radius: 3px;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
            margin-bottom: 1.5rem;
        }

        /* استایل‌های تکمیلی برای فوتر */
        .footer-links-row {
            display: flex;
            flex-wrap: wrap;
        }

        .footer-links-column {
            padding-right: 15px;
            padding-left: 15px;
        }

        /* استایل برای متن توضیحات */
        .footer-desc p {
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 1rem;
            text-align: justify;
        }

        .footer-desc .dmca-link {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .footer-desc .dmca-link:hover {
            text-decoration: underline;
        }

        /* بهبود نمایش در حالت تبلت */
        @media (min-width: 768px) and (max-width: 991px) {
            .footer-links-column {
                margin-bottom: 0;
            }
        }

        /* بهبود نمایش در حالت موبایل */
        @media (max-width: 767px) {
            .promo-banner {
                height: auto;
                padding: 2rem 0;
            }

            .promo-content {
                max-width: 100%;
                padding: 0 1.5rem;
            }

            .promo-image {
                opacity: 0.1;
                width: 100%;
            }

            .promo-title {
                font-size: 1.5rem;
            }

            .footer-links li {
                margin-bottom: 10px;
            }

            .footer-links a {
                font-size: 0.9rem;
            }

            .footer-title {
                margin-top: 10px;
            }

            .certificates-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .footer-links-column {
                margin-bottom: 15px;
            }

            .footer-desc p {
                text-align: right;
            }
        }

        /* تنظیم فاصله‌ها در حالت دسکتاپ */
        @media (min-width: 992px) {
            .footer-links-row {
                margin-right: -10px;
                margin-left: -10px;
            }
        }

        /* برای متن طولانی در کارت محصول */
        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
