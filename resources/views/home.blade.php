@extends('layouts.app')

@section('title', 'صفحه اصلی - کتابخانه دیجیتال')

@section('content')
    <div class="container">
        <!-- بنر تبلیغاتی -->
        <div class="promo-banner mb-5">
            <div class="promo-shape"></div>
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
