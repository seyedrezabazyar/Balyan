@extends('layouts.app')

@section('title', 'علاقه‌مندی‌های من - کتابخانه دیجیتال بالیان')

@push('styles')
    <style>
        /* ===== استایل‌های صفحه علاقه‌مندی‌ها ===== */

        /* جلوگیری از تداخل با نوار بالای سایت */
        .dashboard-wrapper {
            padding-top: 50px; /* فاصله زیاد از بالا برای جلوگیری از تداخل با هدر */
            margin-bottom: 50px;
        }

        /* ساختار کلی داشبورد */
        .dashboard-layout {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* بنر داشبورد */
        .dashboard-banner {
            background: linear-gradient(135deg, #1d75de, #0e90de);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-banner h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .dashboard-banner p {
            font-size: 14px;
            opacity: 0.9;
        }

        /* کانتینر اصلی داشبورد */
        .dashboard-main {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* === سایدبار === */
        .dashboard-sidebar {
            position: relative;
        }

        .sidebar-wrapper {
            background: white;
            border-radius: 10px;
            padding: 25px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 120px; /* چسبیدن به بالای صفحه با فاصله از هدر */
        }

        /* اطلاعات کاربر */
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            margin-bottom: 25px;
        }

        .user-avatar-wrapper {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #f0f7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar-icon {
            font-size: 50px;
            color: #1d75de;
        }

        .user-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 8px;
            text-align: center;
        }

        .user-email {
            color: #666;
            font-size: 14px;
            text-align: center;
            word-break: break-word;
            margin-bottom: 15px;
        }

        .edit-profile-btn {
            background-color: #f0f7ff;
            color: #1d75de;
            border: 1px solid #1d75de;
            border-radius: 6px;
            padding: 8px 15px;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin-bottom: 10px;
        }

        .edit-profile-btn:hover {
            background-color: #1d75de;
            color: white;
            text-decoration: none;
        }

        /* منوی سایدبار */
        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            color: #333;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s;
        }

        .menu-item i {
            margin-left: 15px;
            width: 20px;
            text-align: center;
            color: #1d75de;
            font-size: 18px;
        }

        .menu-item.active {
            background-color: #f0f7ff;
            color: #1d75de;
            font-weight: bold;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
            color: #1d75de;
        }

        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: right;
            cursor: pointer;
            margin-top: 15px;
        }

        /* === محتوای اصلی === */
        .dashboard-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* هشدارها */
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #e6f7e6;
            color: #26972b;
            border: 1px solid #c8e6c8;
        }

        .alert-danger {
            background-color: #ffe6e6;
            color: #d9534f;
            border: 1px solid #ffcccc;
        }

        /* عنوان بخش */
        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f7ff;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-counter {
            font-size: 16px;
            color: #666;
            font-weight: normal;
        }

        /* جستجو و فیلتر - بهبود یافته */
        .search-filter {
            margin-bottom: 30px;
            width: 100%;
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 450px;
        }

        .search-input {
            width: 100%;
            height: 46px;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #f9f9f9;
            color: #333;
        }

        .search-input:focus {
            border-color: #1d75de;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 117, 222, 0.1);
            background-color: white;
        }

        .search-input::placeholder {
            color: #999;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1d75de;
            font-size: 16px;
        }

        /* کتاب‌های مورد علاقه */
        .favorites-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .favorite-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #eee;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .favorite-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .book-image {
            height: 220px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid #eee;
            position: relative;
        }

        .book-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .favorite-actions {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

        .favorite-btn {
            background: rgba(255, 255, 255, 0.9);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #d9534f;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .favorite-btn:hover {
            background: #d9534f;
            color: white;
        }

        .book-info {
            padding: 20px;
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            height: 45px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .book-author {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
        }

        .book-price {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .regular-price {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
            margin-left: 10px;
        }

        .discount-price {
            font-weight: bold;
            color: #26972b;
            font-size: 18px;
        }

        .book-category {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .book-category i {
            margin-left: 5px;
            color: #1d75de;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .action-btn.primary {
            background-color: #1d75de;
            color: white;
            border: none;
        }

        .action-btn.primary:hover {
            background-color: #0e62c7;
            color: white;
            text-decoration: none;
        }

        .action-btn.secondary {
            background-color: #f0f7ff;
            color: #1d75de;
            border: 1px solid #1d75de;
        }

        .action-btn.secondary:hover {
            background-color: #e0edff;
            color: #0e62c7;
            text-decoration: none;
        }

        /* نشان‌های کتاب */
        .book-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #d9534f;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .discount-badge {
            background-color: #d9534f;
        }

        .new-badge {
            background-color: #26972b;
        }

        .popular-badge {
            background-color: #e6ac00;
        }

        /* حالت خالی بودن */
        .empty-state {
            text-align: center;
            padding: 60px 0;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
        }

        .empty-state-icon {
            font-size: 60px;
            color: #1d75de;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .empty-state p {
            margin-bottom: 25px;
            color: #666;
            font-size: 16px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state .action-btn {
            padding: 10px 20px;
            font-size: 14px;
            display: inline-flex;
        }

        /* صفحه‌بندی */
        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .page-item.active .page-link {
            background-color: #1d75de;
            color: white;
            border-color: #1d75de;
        }

        .page-link:hover:not(.active) {
            background-color: #f0f0f0;
        }

        /* انیمیشن */
        .fade-heart {
            animation: fadeHeart 0.5s ease-out;
        }

        @keyframes fadeHeart {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.5);
            }
            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        /* ریسپانسیو */
        @media (max-width: 992px) {
            .dashboard-main {
                grid-template-columns: 1fr;
            }

            .sidebar-wrapper {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding-top: 50px;
            }

            .dashboard-content {
                padding: 20px;
            }

            .favorites-list {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .search-box {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .favorites-list {
                grid-template-columns: 1fr;
            }

            .section-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="dashboard-layout">
                <!-- بنر داشبورد -->
                <div class="dashboard-banner">
                    <h1>علاقه‌مندی‌های من</h1>
                    <p>کتاب‌هایی که به عنوان علاقه‌مندی ذخیره کرده‌اید</p>
                </div>

                <!-- بخش اصلی داشبورد -->
                <div class="dashboard-main">
                    <!-- سایدبار -->
                    <div class="dashboard-sidebar">
                        <div class="sidebar-wrapper">
                            <div class="user-info">
                                <div class="user-avatar-wrapper">
                                    @if(Auth::user()->profile_image)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->first_name }}" class="user-avatar">
                                    @else
                                        <i class="fas fa-user user-avatar-icon"></i>
                                    @endif
                                </div>
                                <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="user-email">{{ Auth::user()->email ?? Auth::user()->phone }}</div>
                                <a href="{{ route('dashboard.account-info') }}" class="edit-profile-btn">
                                    <i class="fas fa-edit"></i> ویرایش اطلاعات
                                </a>
                            </div>

                            <div class="sidebar-menu">
                                <a href="{{ route('dashboard.index') }}" class="menu-item">
                                    <i class="fas fa-home"></i>
                                    صفحه اصلی
                                </a>
                                <a href="{{ route('dashboard.account-info') }}" class="menu-item">
                                    <i class="fas fa-user-edit"></i>
                                    ویرایش اطلاعات
                                </a>
                                <a href="{{ route('dashboard.my-books') }}" class="menu-item">
                                    <i class="fas fa-book"></i>
                                    کتاب‌های من
                                </a>
                                <a href="{{ route('dashboard.favorites') }}" class="menu-item active">
                                    <i class="fas fa-heart"></i>
                                    علاقه‌مندی‌ها
                                </a>
                                <form action="{{ route('dashboard.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="menu-item logout-btn">
                                        <i class="fas fa-sign-out-alt"></i>
                                        خروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- محتوای اصلی -->
                    <div class="dashboard-content">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="section-title">
                            <span>علاقه‌مندی‌های من <span class="section-counter">({{ $favoriteBooks->count() }} کتاب)</span></span>
                        </div>

                        <!-- جستجو با استایل بهینه شده -->
                        <div class="search-filter">
                            <div class="search-box">
                                <input type="text" id="favorite-search" class="search-input" placeholder="جستجو در علاقه‌مندی‌ها...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>

                        <!-- لیست علاقه‌مندی‌ها -->
                        @if($favoriteBooks->count() > 0)
                            <div class="favorites-list">
                                @foreach($favoriteBooks as $book)
                                    <div class="favorite-card">
                                        @if($book->discount_price && $book->discount_price < $book->price)
                                            <div class="book-badge discount-badge">{{ floor(($book->price - $book->discount_price) / $book->price * 100) }}% تخفیف</div>
                                        @endif

                                        <div class="book-image">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                            @else
                                                <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                            @endif

                                            <div class="favorite-actions">
                                                <button class="favorite-btn remove-favorite" data-id="{{ $book->id }}">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="book-info">
                                            <div class="book-title">{{ $book->title }}</div>
                                            <div class="book-author">نویسنده: {{ $book->author }}</div>

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
                                                    <div class="discount-price">{{ number_format($book->discount_price) }} تومان</div>
                                                    <div class="regular-price">{{ number_format($book->price) }}</div>
                                                @else
                                                    <div class="discount-price">{{ number_format($book->price) }} تومان</div>
                                                @endif
                                            </div>

                                            <div class="card-actions">
                                                <a href="#" class="action-btn primary">
                                                    <i class="fas fa-shopping-cart"></i> خرید
                                                </a>
                                                <a href="#" class="action-btn secondary">
                                                    <i class="fas fa-info-circle"></i> جزئیات
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="empty-state-title">لیست علاقه‌مندی‌های شما خالی است!</div>
                                <p>هنوز هیچ کتابی به لیست علاقه‌مندی‌های خود اضافه نکرده‌اید. کتاب‌های مورد علاقه خود را در فروشگاه پیدا کنید و با کلیک روی آیکون قلب، آن‌ها را به این لیست اضافه کنید.</p>
                                <a href="{{ route('home') }}" class="action-btn primary">
                                    <i class="fas fa-search"></i> جستجو در کتاب‌ها
                                </a>
                            </div>
                        @endif

                        <!-- صفحه‌بندی در صورت نیاز -->
                        @if($favoriteBooks->count() > 0 && $favoriteBooks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pagination-container">
                                {{ $favoriteBooks->links() }}
                            </div>
                        @endif

                        <!-- پیام برای زمانی که نتیجه‌ای در جستجو یافت نشد -->
                        <div id="no-results-message" style="display: none;" class="empty-state">
                            <p>هیچ کتابی با عبارت جستجو شده یافت نشد.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // حذف خودکار هشدارها بعد از چند ثانیه
            const alerts = document.querySelectorAll('.alert');
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

            // جستجوی بهبود یافته در علاقه‌مندی‌ها
            const searchInput = document.getElementById('favorite-search');
            const favoriteCards = document.querySelectorAll('.favorite-card');
            const noResultsMessage = document.getElementById('no-results-message');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim().toLowerCase();

                    // وقتی جستجو خالی است، همه نتایج را نمایش بده
                    if (searchTerm === '') {
                        favoriteCards.forEach(card => {
                            card.style.display = '';
                        });
                        noResultsMessage.style.display = 'none';
                        return;
                    }

                    let hasResults = false;

                    favoriteCards.forEach(card => {
                        const title = card.querySelector('.book-title').textContent.toLowerCase();
                        const author = card.querySelector('.book-author').textContent.toLowerCase();
                        const category = card.querySelector('.book-category').textContent.toLowerCase();

                        if (title.includes(searchTerm) || author.includes(searchTerm) || category.includes(searchTerm)) {
                            card.style.display = '';
                            hasResults = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // نمایش پیام اگر نتیجه‌ای یافت نشد
                    if (!hasResults && favoriteCards.length > 0) {
                        noResultsMessage.style.display = 'block';
                        noResultsMessage.querySelector('p').textContent = `هیچ کتابی با عبارت "${searchTerm}" یافت نشد.`;
                    } else {
                        noResultsMessage.style.display = 'none';
                    }
                });
            }

            // حذف از علاقه‌مندی‌ها
            const removeFavoriteButtons = document.querySelectorAll('.remove-favorite');

            removeFavoriteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookId = this.dataset.id;
                    const card = this.closest('.favorite-card');

                    // نمایش انیمیشن قلب در حال محو شدن
                    const heart = document.createElement('i');
                    heart.className = 'fas fa-heart fade-heart';
                    heart.style.position = 'absolute';
                    heart.style.color = '#d9534f';
                    heart.style.fontSize = '40px';
                    heart.style.top = '50%';
                    heart.style.left = '50%';
                    heart.style.transform = 'translate(-50%, -50%)';
                    heart.style.zIndex = '3';

                    card.querySelector('.book-image').appendChild(heart);

                    // ارسال درخواست AJAX برای حذف از علاقه‌مندی‌ها
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
card.style.transition = 'all 0.5s';
card.style.transform = 'scale(0.8)';
card.style.opacity = '0';

setTimeout(() => {
card.remove();

// بررسی اگر همه کارت‌ها حذف شدند، نمایش حالت خالی
const remainingCards = document.querySelectorAll('.favorite-card');
if (remainingCards.length === 0) {
const favoritesList = document.querySelector('.favorites-list');

// ایجاد حالت خالی
const emptyState = document.createElement('div');
emptyState.className = 'empty-state';
emptyState.innerHTML = `
<div class="empty-state-icon">
    <i class="fas fa-heart"></i>
</div>
<div class="empty-state-title">لیست علاقه‌مندی‌های شما خالی است!</div>
<p>هنوز هیچ کتابی به لیست علاقه‌مندی‌های خود اضافه نکرده‌اید. کتاب‌های مورد علاقه خود را در فروشگاه پیدا کنید و با کلیک روی آیکون قلب، آن‌ها را به این لیست اضافه کنید.</p>
<a href="${window.location.origin}" class="action-btn primary">
    <i class="fas fa-search"></i> جستجو در کتاب‌ها
</a>
`;

// جایگزینی لیست با حالت خالی
favoritesList.parentNode.replaceChild(emptyState, favoritesList);

// به‌روزرسانی شمارنده
const sectionCounter = document.querySelector('.section-counter');
if (sectionCounter) {
sectionCounter.textContent = '(0 کتاب)';
}
} else {
// به‌روزرسانی شمارنده
const sectionCounter = document.querySelector('.section-counter');
if (sectionCounter) {
sectionCounter.textContent = `(${remainingCards.length} کتاب)`;
}
}

// حذف عبارت جستجو در صورت نمایش پیام خالی
const searchInput = document.getElementById('favorite-search');
if (searchInput && remainingCards.length === 0) {
searchInput.value = '';
const noResultsMessage = document.getElementById('no-results-message');
if (noResultsMessage) {
noResultsMessage.style.display = 'none';
}
}
}, 500);
}, 300);
} else {
// نمایش خطا
alert('خطا در حذف کتاب از علاقه‌مندی‌ها. لطفاً مجدد تلاش کنید.');
}
})
.catch(error => {
console.error('Error removing favorite:', error);
alert('خطا در ارتباط با سرور. لطفاً مجدد تلاش کنید.');
});
});
});
});
</script>
@endpush
