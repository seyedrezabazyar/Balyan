@extends('layouts.app')

@section('title', 'کتاب‌های من - کتابخانه دیجیتال بالیان')

@push('styles')
    <style>
        /* ===== استایل‌های صفحه کتاب‌های من ===== */

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

        .section-actions {
            display: flex;
            gap: 10px;
        }

        /* جستجو و فیلتر */
        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            border-color: #1d75de;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 117, 222, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .filter-dropdown {
            min-width: 180px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231d75de' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 15px center;
            background-size: 12px;
            padding-left: 35px;
        }

        .filter-dropdown:focus {
            border-color: #1d75de;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 117, 222, 0.1);
        }

        /* مشاهده کتاب‌ها */
        .view-options {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .view-btn {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px 12px;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }

        .view-btn.active {
            background-color: #1d75de;
            color: white;
            border-color: #1d75de;
        }

        /* نمایش کتاب‌ها - گرید */
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
            display: flex;
            flex-direction: column;
            height: 100%;
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
            display: flex;
            flex-direction: column;
            flex-grow: 1;
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

        .book-meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .book-meta i {
            color: #1d75de;
            width: 14px;
            text-align: center;
        }

        .progress-container {
            margin: 15px 0;
            background-color: #f0f0f0;
            border-radius: 4px;
            height: 6px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #1d75de;
            border-radius: 4px;
        }

        .book-actions {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 15px;
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
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .action-btn.secondary {
            background-color: #f0f7ff;
            color: #1d75de;
            border: 1px solid #1d75de;
        }

        .action-btn:hover {
            background-color: #0e62c7;
            color: white;
            text-decoration: none;
        }

        .action-btn.secondary:hover {
            background-color: #e0edff;
            color: #0e62c7;
        }

        /* نمایش کتاب‌ها - لیست */
        .books-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
        }

        .book-list-item {
            display: flex;
            border: 1px solid #eee;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-list-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .list-book-image {
            width: 120px;
            min-width: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
            padding: 10px;
        }

        .list-book-image img {
            max-width: 100%;
            max-height: 150px;
            object-fit: contain;
        }

        .list-book-info {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .list-book-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .list-book-author {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .list-book-details {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .list-book-meta {
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .list-book-meta i {
            color: #1d75de;
            width: 14px;
            text-align: center;
        }

        .list-progress-container {
            margin: 15px 0;
            background-color: #f0f0f0;
            border-radius: 4px;
            height: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 300px;
        }

        .list-book-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
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

        /* ریسپانسیو */
        @media (max-width: 992px) {
            .dashboard-main {
                grid-template-columns: 1fr;
            }

            .sidebar-wrapper {
                position: relative;
                top: 0;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }

            .list-book-details {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding-top: 50px;
            }

            .dashboard-content {
                padding: 20px;
            }

            .book-list-item {
                flex-direction: column;
            }

            .list-book-image {
                width: 100%;
                height: 180px;
            }

            .list-book-image img {
                max-height: 160px;
            }
        }

        @media (max-width: 576px) {
            .books-grid {
                grid-template-columns: 1fr;
            }

            .section-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
                    <h1>کتاب‌های من</h1>
                    <p>مجموعه کتاب‌های خریداری شده و در حال مطالعه شما</p>
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
                                <a href="{{ route('dashboard.my-books') }}" class="menu-item active">
                                    <i class="fas fa-book"></i>
                                    کتاب‌های من
                                </a>
                                <a href="{{ route('dashboard.favorites') }}" class="menu-item">
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
                            <span>کتاب‌های من</span>
                            <div class="section-actions">
                                <a href="{{ route('home') }}" class="action-btn">
                                    <i class="fas fa-plus"></i> خرید کتاب جدید
                                </a>
                            </div>
                        </div>

                        <!-- جستجو و فیلتر -->
                        <div class="search-filter">
                            <div class="search-box">
                                <input type="text" id="book-search" class="search-input" placeholder="جستجو در کتاب‌های من...">
                                <i class="fas fa-search search-icon"></i>
                            </div>

                            <select class="filter-dropdown" id="book-filter">
                                <option value="all">همه کتاب‌ها</option>
                                <option value="reading">در حال مطالعه</option>
                                <option value="completed">تکمیل شده</option>
                                <option value="not-started">مطالعه نشده</option>
                            </select>
                        </div>

                        <!-- نحوه نمایش -->
                        <div class="view-options">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th-large"></i> نمایش گرید
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i> نمایش لیستی
                            </button>
                        </div>

                        <!-- نمایش کتاب‌ها - حالت گرید -->
                        <div class="books-view grid-view">
                            @if($books->count() > 0)
                                <div class="books-grid">
                                    @foreach($books as $book)
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

                                                <div class="book-meta">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    خرید: {{ verta($book->pivot->created_at ?? now())->format('%d %B %Y') }}
                                                </div>

                                                <div class="book-meta">
                                                    <i class="fas fa-clock"></i>
                                                    پیشرفت مطالعه: ۲۵٪
                                                </div>

                                                <div class="progress-container">
                                                    <div class="progress-bar" style="width: 25%"></div>
                                                </div>

                                                <div class="book-actions">
                                                    <a href="#" class="action-btn">
                                                        <i class="fas fa-book-open"></i> مطالعه
                                                    </a>
                                                    <a href="#" class="action-btn secondary">
                                                        <i class="fas fa-download"></i> دانلود
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="empty-state-title">هنوز کتابی در کتابخانه شما وجود ندارد!</div>
                                    <p>کتابخانه شما خالی است. با خرید کتاب‌های مورد علاقه خود، کتابخانه شخصی‌تان را غنی کنید و از مطالعه آنلاین لذت ببرید.</p>
                                    <a href="{{ route('home') }}" class="action-btn">
                                        <i class="fas fa-shopping-cart"></i> مشاهده فروشگاه کتاب
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- نمایش کتاب‌ها - حالت لیستی (مخفی در ابتدا) -->
                        <div class="books-view list-view" style="display: none;">
                            @if($books->count() > 0)
                                <div class="books-list">
                                    @foreach($books as $book)
                                        <div class="book-list-item">
                                            <div class="list-book-image">
                                                @if($book->cover_image)
                                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                                @else
                                                    <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                                @endif
                                            </div>
                                            <div class="list-book-info">
                                                <div class="list-book-title">{{ $book->title }}</div>
                                                <div class="list-book-author">نویسنده: {{ $book->author }}</div>

                                                <div class="list-book-details">
                                                    <div class="list-book-meta">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        خرید: {{ verta($book->pivot->created_at ?? now())->format('%d %B %Y') }}
                                                    </div>

                                                    <div class="list-book-meta">
                                                        <i class="fas fa-clock"></i>
                                                        پیشرفت مطالعه: ۲۵٪
                                                    </div>

                                                    <div class="list-book-meta">
                                                        <i class="fas fa-file-alt"></i>
                                                        تعداد صفحات: {{ $book->pages ?? 250 }}
                                                    </div>
                                                </div>
                                                <div class="list-progress-container">
    <div class="progress-bar" style="width: 25%"></div>
</div>

<div class="list-book-actions">
    <a href="#" class="action-btn">
        <i class="fas fa-book-open"></i> مطالعه
    </a>
    <a href="#" class="action-btn secondary">
        <i class="fas fa-download"></i> دانلود
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
            <i class="fas fa-book"></i>
        </div>
        <div class="empty-state-title">هنوز کتابی در کتابخانه شما وجود ندارد!</div>
        <p>کتابخانه شما خالی است. با خرید کتاب‌های مورد علاقه خود، کتابخانه شخصی‌تان را غنی کنید و از مطالعه آنلاین لذت ببرید.</p>
        <a href="{{ route('home') }}" class="action-btn">
            <i class="fas fa-shopping-cart"></i> مشاهده فروشگاه کتاب
        </a>
    </div>
    @endif
    </div>

    <!-- برای زمانی که کتاب‌ها بیشتر از یک صفحه باشند، صفحه‌بندی نمایش داده می‌شود -->
    @if($books->count() > 0 && $books instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination-container">
            {{ $books->links() }}
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

                    // تغییر نحوه نمایش (گرید/لیست)
                    const viewButtons = document.querySelectorAll('.view-btn');
                    const gridView = document.querySelector('.grid-view');
                    const listView = document.querySelector('.list-view');

                    viewButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            // غیرفعال کردن همه دکمه‌ها
                            viewButtons.forEach(b => b.classList.remove('active'));

                            // فعال کردن دکمه کلیک شده
                            this.classList.add('active');

                            // نمایش/مخفی کردن محتوا بر اساس نوع نمایش
                            if (this.dataset.view === 'grid') {
                                gridView.style.display = 'block';
                                listView.style.display = 'none';
                            } else {
                                gridView.style.display = 'none';
                                listView.style.display = 'block';
                            }
                        });
                    });

                    // جستجو در کتاب‌ها
                    const searchInput = document.getElementById('book-search');
                    const bookCards = document.querySelectorAll('.book-card');
                    const bookListItems = document.querySelectorAll('.book-list-item');

                    searchInput.addEventListener('input', function() {
                        const searchTerm = this.value.trim().toLowerCase();

                        // جستجو در حالت گرید
                        bookCards.forEach(card => {
                            const title = card.querySelector('.book-title').textContent.toLowerCase();
                            const author = card.querySelector('.book-author').textContent.toLowerCase();

                            if (title.includes(searchTerm) || author.includes(searchTerm)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        // جستجو در حالت لیست
                        bookListItems.forEach(item => {
                            const title = item.querySelector('.list-book-title').textContent.toLowerCase();
                            const author = item.querySelector('.list-book-author').textContent.toLowerCase();

                            if (title.includes(searchTerm) || author.includes(searchTerm)) {
                                item.style.display = '';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });

                    // فیلتر کتاب‌ها
                    const filterDropdown = document.getElementById('book-filter');

                    filterDropdown.addEventListener('change', function() {
                        const filterValue = this.value;

                        // فیلتر در حالت گرید
                        bookCards.forEach(card => {
                            const progressText = card.querySelector('.book-meta:nth-of-type(2)').textContent;
                            const progressPercent = parseInt(progressText.match(/\d+/)[0]);

                            switch (filterValue) {
                                case 'all':
                                    card.style.display = '';
                                    break;
                                case 'reading':
                                    card.style.display = (progressPercent > 0 && progressPercent < 100) ? '' : 'none';
                                    break;
                                case 'completed':
                                    card.style.display = (progressPercent === 100) ? '' : 'none';
                                    break;
                                case 'not-started':
                                    card.style.display = (progressPercent === 0) ? '' : 'none';
                                    break;
                            }
                        });

                        // فیلتر در حالت لیست
                        bookListItems.forEach(item => {
                            const progressText = item.querySelector('.list-book-meta:nth-of-type(2)').textContent;
                            const progressPercent = parseInt(progressText.match(/\d+/)[0]);

                            switch (filterValue) {
                                case 'all':
                                    item.style.display = '';
                                    break;
                                case 'reading':
                                    item.style.display = (progressPercent > 0 && progressPercent < 100) ? '' : 'none';
                                    break;
                                case 'completed':
                                    item.style.display = (progressPercent === 100) ? '' : 'none';
                                    break;
                                case 'not-started':
                                    item.style.display = (progressPercent === 0) ? '' : 'none';
                                    break;
                            }
                        });
                    });
                });
            </script>
        @endpush
