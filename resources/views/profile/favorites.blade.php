@extends('layouts.app')

@section('title', 'علاقه‌مندی‌های من - کتابخانه دیجیتال بالیان')

@section('content')
    <div class="profile-container">
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
                            <a href="{{ route('profile.account-info') }}" class="profile-nav-item">
                                <i class="fas fa-user-edit"></i>
                                <span>ویرایش پروفایل</span>
                            </a>
                            <a href="{{ route('profile.my-books') }}" class="profile-nav-item">
                                <i class="fas fa-book"></i>
                                <span>کتاب‌های من</span>
                            </a>
                            <a href="{{ route('profile.favorites') }}" class="profile-nav-item active">
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

            <!-- عنوان صفحه -->
            <div class="page-title mb-4">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <div>
                            <h1>علاقه‌مندی‌های من</h1>
                            <p>کتاب‌هایی که به عنوان علاقه‌مندی ذخیره کرده‌اید</p>
                        </div>
                        <div class="favorites-counter">
                            <span class="counter-badge">{{ $favoriteBooks->count() ?? 0 }}</span>
                            <span class="counter-label">کتاب</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- پیام‌های سیستم -->
            <div class="system-messages mb-4">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- جستجو -->
            <div class="search-box-container mb-4">
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="search-box">
                            <input type="text" id="favorite-search" class="search-input" placeholder="جستجو در علاقه‌مندی‌ها...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- لیست علاقه‌مندی‌ها -->
            @if(isset($favoriteBooks) && $favoriteBooks->count() > 0)
                <div class="favorites-list">
                    <div class="row">
                        @foreach($favoriteBooks as $book)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 favorite-item">
                                <div class="book-card">
                                    <div class="book-cover">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                        @else
                                            <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                        @endif

                                        @if($book->discount_price && $book->discount_price < $book->price)
                                            <div class="discount-badge">
                                                {{ floor(($book->price - $book->discount_price) / $book->price * 100) }}%
                                            </div>
                                        @endif

                                        <div class="book-actions">
                                            <button class="book-action-btn remove-favorite" title="حذف از علاقه‌مندی‌ها" data-id="{{ $book->id }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <a href="#" class="book-action-btn detail-btn" title="مشاهده جزئیات">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <a href="#" class="book-action-btn buy-btn" title="افزودن به کتابخانه">
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h5 class="book-title">{{ $book->title }}</h5>
                                        <p class="book-author">{{ $book->author }}</p>
                                        <div class="book-category">
                                            <i class="fas fa-folder"></i>
                                            @if($book->categories && $book->categories->count() > 0)
                                                {{ $book->categories->first()->name }}
                                            @else
                                                دسته‌بندی نشده
                                            @endif
                                        </div>
                                        <div class="book-price">
                                            @if($book->discount_price && $book->discount_price < $book->price)
                                                <span class="price-value">{{ number_format($book->discount_price) }} تومان</span>
                                                <span class="old-price">{{ number_format($book->price) }}</span>
                                            @else
                                                <span class="price-value">{{ number_format($book->price) }} تومان</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>لیست علاقه‌مندی‌های شما خالی است!</h4>
                    <p class="text-muted">کتاب‌های مورد علاقه خود را با کلیک روی آیکون قلب ذخیره کنید</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-2"></i> جستجوی کتاب
                    </a>
                </div>
            @endif

            <!-- نمایش پیام عدم نتیجه جستجو -->
            <div id="no-results-message" class="empty-state" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4>کتابی یافت نشد!</h4>
                <p class="text-muted">هیچ کتابی با عبارت جستجو شده در لیست علاقه‌مندی‌های شما پیدا نشد.</p>
            </div>

            <!-- صفحه‌بندی در صورت نیاز -->
            @if(isset($favoriteBooks) && $favoriteBooks->count() > 0 && $favoriteBooks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination-container mt-4">
                    {{ $favoriteBooks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* استایل‌های اصلی پروفایل */
        .profile-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        /* نوار منوی پروفایل */
        .profile-nav {
            display: flex;
            justify-content: space-between;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .profile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px 10px;
            color: #6c757d;
            text-decoration: none;
            flex: 1;
            text-align: center;
            transition: all 0.2s ease;
        }

        .profile-nav-item i {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .profile-nav-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .profile-nav-item.active {
            color: #4270e4;
            background-color: rgba(66, 112, 228, 0.05);
            border-bottom: 3px solid #4270e4;
        }

        .profile-nav-item:hover {
            color: #4270e4;
            background-color: rgba(66, 112, 228, 0.05);
        }

        .profile-nav-item.logout-btn {
            color: #e74c3c;
        }

        .profile-nav-item.logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.05);
        }

        /* عنوان صفحه */
        .page-title h1 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #6c757d;
            font-size: 15px;
            margin-bottom: 0;
        }

        /* شمارنده علاقه‌مندی‌ها */
        .favorites-counter {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .counter-badge {
            background-color: #4270e4;
            color: white;
            font-weight: bold;
            font-size: 18px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .counter-label {
            color: #6c757d;
            font-size: 16px;
        }

        /* پیام‌های سیستم */
        .alert {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-success {
            background-color: #def7e5;
            color: #27ae60;
        }

        .alert-danger {
            background-color: #fce4e4;
            color: #e74c3c;
        }

        /* جستجو */
        .search-box-container {
            margin-bottom: 25px;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            width: 100%;
            height: 48px;
            padding: 10px 20px 10px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            background-color: white;
        }

        .search-input:focus {
            border-color: #4270e4;
            box-shadow: 0 0 0 3px rgba(66, 112, 228, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 16px;
        }

        /* کارت کتاب‌ها */
        .book-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-cover img {
            transform: scale(1.05);
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f47e20;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            z-index: 2;
        }

        .book-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 10px;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .book-cover:hover .book-actions {
            opacity: 1;
        }

        .book-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4270e4;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .book-action-btn.remove-favorite {
            color: #e74c3c;
        }

        .book-action-btn.remove-favorite:hover {
            background-color: #e74c3c;
            color: white;
        }

        .book-action-btn.detail-btn:hover {
            background-color: #4270e4;
            color: white;
        }

        .book-action-btn.buy-btn:hover {
            background-color: #27ae60;
            color: white;
        }

        .book-info {
            padding: 15px;
        }

        .book-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            height: 44px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .book-author {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .book-category {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .book-category i {
            margin-left: 5px;
            color: #4270e4;
        }

        .book-price {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .price-value {
            font-weight: 600;
            color: #27ae60;
            font-size: 15px;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 13px;
        }

        /* حالت خالی */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            margin: 20px 0;
        }

        .empty-icon {
            font-size: 50px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 15px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* انیمیشن قلب */
        @keyframes fadeHeart {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); }
            100% { transform: scale(1); opacity: 0; }
        }

        .fade-heart {
            animation: fadeHeart 0.5s ease-out;
            position: absolute;
            color: #e74c3c;
            font-size: 40px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        /* ریسپانسیو */
        @media (max-width: 767.98px) {
            .profile-nav-item span {
                display: none;
            }

            .profile-nav-item i {
                margin-bottom: 0;
                font-size: 20px;
            }

            .profile-nav-item {
                padding: 15px 5px;
            }

            .page-title {
                text-align: center;
            }

            .favorites-counter {
                margin-top: 15px;
                justify-content: center;
            }

            .page-title .row .col-12 {
                flex-direction: column;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // حذف خودکار پیام‌های موفقیت
            const alerts = document.querySelectorAll('.alert-success');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 500);
                    });
                }, 5000);
            }

            // جستجو در علاقه‌مندی‌ها
            const searchInput = document.getElementById('favorite-search');
            const favoriteItems = document.querySelectorAll('.favorite-item');
            const noResultsMessage = document.getElementById('no-results-message');
            const favoritesList = document.querySelector('.favorites-list');

            if (searchInput && favoriteItems.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim().toLowerCase();
                    let hasResult = false;

                    favoriteItems.forEach(item => {
                        const title = item.querySelector('.book-title').textContent.toLowerCase();
                        const author = item.querySelector('.book-author').textContent.toLowerCase();
                        const category = item.querySelector('.book-category').textContent.toLowerCase();

                        if (title.includes(searchTerm) || author.includes(searchTerm) || category.includes(searchTerm)) {
                            item.style.display = '';
                            hasResult = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // نمایش پیام عدم نتیجه
                    if (!hasResult) {
                        noResultsMessage.style.display = 'block';
                        favoritesList.style.display = 'none';
                    } else {
                        noResultsMessage.style.display = 'none';
                        favoritesList.style.display = 'block';
                    }

                    // پاک کردن جستجو با کلید Escape
                    if (searchTerm === '') {
                        noResultsMessage.style.display = 'none';
                        favoritesList.style.display = 'block';
                    }
                });

                // پاک کردن جستجو با کلید Escape
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        favoriteItems.forEach(item => {
                            item.style.display = '';
                        });
                        noResultsMessage.style.display = 'none';
                        favoritesList.style.display = 'block';
                    }
                });
            }

            // حذف از علاقه‌مندی‌ها
            const removeFavoriteButtons = document.querySelectorAll('.remove-favorite');
            if (removeFavoriteButtons.length > 0) {
                removeFavoriteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const bookId = this.dataset.id;
                        const bookCard = this.closest('.favorite-item');

                        // نمایش انیمیشن قلب در حال محو شدن
                        const heart = document.createElement('i');
                        heart.className = 'fas fa-heart fade-heart';
                        bookCard.querySelector('.book-cover').appendChild(heart);

                        // ارسال درخواست حذف از علاقه‌مندی‌ها
                        fetch(`/api/favorites/${bookId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // حذف کارت با انیمیشن
                                    setTimeout(() => {
                                        bookCard.style.transition = 'all 0.5s';
                                        bookCard.style.opacity = '0';
                                        bookCard.style.transform = 'scale(0.8)';

                                        setTimeout(() => {
                                            bookCard.remove();

                                            // بررسی تعداد کارت‌های باقیمانده
                                            const remainingItems = document.querySelectorAll('.favorite-item');
                                            if (remainingItems.length === 0) {
                                                // نمایش حالت خالی
                                                const emptyState = `
                                                <div class="empty-state">
                                                    <div class="empty-icon">
                                                        <i class="fas fa-heart"></i>
                                                    </div>
                                                    <h4>لیست علاقه‌مندی‌های شما خالی است!</h4>
                                                    <p class="text-muted">کتاب‌های مورد علاقه خود را با کلیک روی آیکون قلب ذخیره کنید</p>
                                                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                                                        <i class="fas fa-search me-2"></i> جستجوی کتاب
                                                    </a>
                                                </div>
                                            `;

                                                favoritesList.innerHTML = emptyState;

                                                // به‌روزرسانی شمارنده
                                                document.querySelector('.counter-badge').textContent = '0';
                                            } else {
                                                // به‌روزرسانی شمارنده
                                                document.querySelector('.counter-badge').textContent = remainingItems.length;
                                            }
                                        }, 500);
                                    }, 300);
                                } else {
                                    alert('خطا در حذف کتاب از علاقه‌مندی‌ها');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('خطا در ارتباط با سرور');
                            });
                    });
                });
            }
        });
    </script>
@endpush
