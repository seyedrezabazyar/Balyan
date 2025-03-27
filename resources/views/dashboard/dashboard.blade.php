@extends('layouts.app')

@section('title', 'داشبورد کاربری - کتابخانه دیجیتال بالیان')

@push('styles')
    <style>
        /* ===== استایل‌های مخصوص داشبورد کاربران ===== */

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

        /* نوتیفیکیشن‌ها */
        .notification {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            background-color: #fff8e6;
            border: 1px solid #ffe0a3;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-icon {
            font-size: 24px;
            color: #e6ac00;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .notification-text {
            color: #333;
            font-size: 14px;
        }

        .notification-action {
            background-color: #1d75de;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .notification-action:hover {
            background-color: #0e62c7;
            color: white;
            text-decoration: none;
        }

        /* عنوان بخش */
        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f7ff;
            color: #333;
        }

        /* آمار و اطلاعات خلاصه */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #f0f7ff;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-icon i {
            color: #1d75de;
            font-size: 24px;
        }

        .stat-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* لیست کتاب‌ها */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .book-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #eee;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .book-image {
            height: 200px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid #eee;
        }

        .book-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .book-info {
            padding: 20px;
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            height: 45px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .book-author {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }

        .book-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: bold;
            color: #1d75de;
            font-size: 15px;
        }

        .action-btn {
            background-color: #1d75de;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #0e62c7;
            color: white;
            text-decoration: none;
        }

        /* حالت خالی بودن */
        .empty-state {
            text-align: center;
            padding: 60px 0;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
        }

        .empty-state p {
            margin-bottom: 25px;
            color: #666;
            font-size: 16px;
        }

        .empty-state .action-btn {
            padding: 10px 20px;
            font-size: 14px;
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

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }

            .dashboard-content {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .books-grid {
                grid-template-columns: 1fr;
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
                    <h1>داشبورد کاربری</h1>
                    <p>به حساب کاربری خود در کتابخانه دیجیتال بالیان خوش آمدید</p>
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
                                <a href="{{ route('dashboard.index') }}" class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i>
                                    صفحه اصلی
                                </a>
                                <a href="{{ route('dashboard.account-info') }}" class="menu-item {{ request()->routeIs('dashboard.account-info') ? 'active' : '' }}">
                                    <i class="fas fa-user-edit"></i>
                                    ویرایش اطلاعات
                                </a>
                                <a href="{{ route('dashboard.my-books') }}" class="menu-item {{ request()->routeIs('dashboard.my-books') ? 'active' : '' }}">
                                    <i class="fas fa-book"></i>
                                    کتاب‌های من
                                </a>
                                <a href="{{ route('dashboard.favorites') }}" class="menu-item {{ request()->routeIs('dashboard.favorites') ? 'active' : '' }}">
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

                        <!-- نوتیفیکیشن برای تشویق کاربر به تکمیل اطلاعات -->
                        @if(!Auth::user()->profile_image || !Auth::user()->email || !Auth::user()->phone)
                            <div class="notification">
                                <div class="notification-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">تکمیل اطلاعات کاربری</div>
                                    <div class="notification-text">
                                        برای استفاده بهتر از امکانات سایت و بهره‌مندی از پیشنهادات ویژه، اطلاعات پروفایل خود را تکمیل کنید.
                                    </div>
                                </div>
                                <a href="{{ route('dashboard.account-info') }}" class="notification-action">تکمیل اطلاعات</a>
                            </div>
                        @endif

                        <h2 class="section-title">خلاصه وضعیت</h2>

                        <!-- آمار -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="stat-title">کتاب‌های خریداری شده</div>
                                <div class="stat-value">{{ $purchasedBooks }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="stat-title">علاقه‌مندی‌ها</div>
                                <div class="stat-value">{{ $favoriteBooks }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-title">آخرین بازدید</div>
                                <div class="stat-value">{{ Auth::user()->last_login_at ? verta(Auth::user()->last_login_at)->format('%d %B') : 'امروز' }}</div>
                            </div>
                        </div>

                        <!-- کتاب‌های اخیر -->
                        <h2 class="section-title">کتاب‌های اخیر شما</h2>

                        @if($recentBooks && $recentBooks->count() > 0)
                            <div class="books-grid">
                                @foreach($recentBooks as $book)
                                    <div class="book-card">
                                        <div class="book-image">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                            @else
                                                <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                            @endif
                                        </div>
                                        <div class="book-info">
                                            <div class="book-title">{{ $book->title }}</div>
                                            <div class="book-author">نویسنده: {{ $book->author }}</div>
                                            <div class="book-price">
                                                <span class="price">{{ number_format($book->price) }} تومان</span>
                                                <a href="#" class="action-btn">مطالعه</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <p>شما هنوز هیچ کتابی خریداری نکرده‌اید.</p>
                                <a href="{{ route('home') }}" class="action-btn">جستجوی کتاب</a>
                            </div>
                        @endif

                        <!-- علاقه‌مندی‌ها -->
                        <h2 class="section-title">علاقه‌مندی‌های شما</h2>

                        @if($favoriteBooks > 0)
                            <div class="books-grid">
                                {{-- این بخش باید با داده‌های واقعی علاقه‌مندی‌ها پر شود --}}
                                <div class="book-card">
                                    <div class="book-image">
                                        <img src="{{ asset('images/book-placeholder.png') }}" alt="کتاب علاقه‌مندی">
                                    </div>
                                    <div class="book-info">
                                        <div class="book-title">عنوان کتاب مورد علاقه</div>
                                        <div class="book-author">نویسنده: نام نویسنده</div>
                                        <div class="book-price">
                                            <span class="price">99,000 تومان</span>
                                            <a href="#" class="action-btn">خرید</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <p>شما هنوز هیچ کتابی را به علاقه‌مندی‌ها اضافه نکرده‌اید.</p>
                                <a href="{{ route('home') }}" class="action-btn">جستجوی کتاب</a>
                            </div>
                        @endif
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
        });
    </script>
@endpush
