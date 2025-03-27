@extends('layouts.app')

@section('title', 'ویرایش اطلاعات کاربری - کتابخانه دیجیتال بالیان')

@push('styles')
    <style>
        /* ===== استایل‌های صفحه ویرایش اطلاعات کاربری ===== */

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
            position: relative;
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

        .change-avatar-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            font-size: 12px;
            text-align: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .user-avatar-wrapper:hover .change-avatar-overlay {
            opacity: 1;
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

        /* === محتوای فرم === */
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

        /* راهنمای تکمیل */
        .completion-guide {
            background-color: #f0f7ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .completion-guide-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1d75de;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .completion-guide-title i {
            font-size: 24px;
        }

        .completion-guide-text {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }

        .benefits-list {
            margin-top: 15px;
            padding-right: 20px;
        }

        .benefits-list li {
            margin-bottom: 10px;
            color: #444;
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

        /* فرم ویرایش */
        .edit-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        .form-label .required {
            color: #d9534f;
            margin-right: 5px;
        }

        .form-label .recommended {
            color: #26972b;
            margin-right: 5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #1d75de;
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 117, 222, 0.1);
        }

        .form-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            flex: 1;
        }

        .input-group-prepend {
            background-color: #f0f7ff;
            border: 1px solid #ddd;
            border-left: none;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            color: #666;
        }

        .form-submit-wrapper {
            grid-column: span 2;
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .form-submit {
            background-color: #1d75de;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .form-submit:hover {
            background-color: #0e62c7;
        }

        .profile-incomplete {
            color: #d9534f;
            font-size: 13px;
            margin-top: 5px;
        }

        .profile-complete {
            color: #26972b;
            font-size: 13px;
            margin-top: 5px;
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

            .edit-form {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .form-submit-wrapper {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding-top: 50px;
            }

            .dashboard-content {
                padding: 20px;
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
                    <h1>ویرایش اطلاعات کاربری</h1>
                    <p>لطفاً اطلاعات پروفایل خود را تکمیل کنید تا بتوانیم خدمات بهتری به شما ارائه دهیم</p>
                </div>

                <!-- بخش اصلی داشبورد -->
                <div class="dashboard-main">
                    <!-- سایدبار -->
                    <div class="dashboard-sidebar">
                        <div class="sidebar-wrapper">
                            <div class="user-info">
                                <div class="user-avatar-wrapper">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}" class="user-avatar">
                                    @else
                                        <i class="fas fa-user user-avatar-icon"></i>
                                    @endif
                                    <div class="change-avatar-overlay" onclick="document.getElementById('profile_image').click()">
                                        <i class="fas fa-camera"></i> تغییر تصویر
                                    </div>
                                </div>
                                <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="user-email">{{ $user->email ?? $user->phone }}</div>
                                <a href="{{ route('dashboard.account-info') }}" class="edit-profile-btn">
                                    <i class="fas fa-edit"></i> ویرایش اطلاعات
                                </a>
                            </div>

                            <div class="sidebar-menu">
                                <a href="{{ route('dashboard.index') }}" class="menu-item">
                                    <i class="fas fa-home"></i>
                                    صفحه اصلی
                                </a>
                                <a href="{{ route('dashboard.account-info') }}" class="menu-item active">
                                    <i class="fas fa-user-edit"></i>
                                    ویرایش اطلاعات
                                </a>
                                <a href="{{ route('dashboard.my-books') }}" class="menu-item">
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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul style="padding-right: 20px; margin-bottom: 0;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- راهنمای تکمیل پروفایل -->
                        <div class="completion-guide">
                            <div class="completion-guide-title">
                                <i class="fas fa-lightbulb"></i>
                                چرا تکمیل اطلاعات کاربری مهم است؟
                            </div>
                            <div class="completion-guide-text">
                                کاربر گرامی، تکمیل اطلاعات کاربری باعث می‌شود تا تجربه بهتری از کتابخانه دیجیتال بالیان داشته باشید.
                                <ul class="benefits-list">
                                    <li>با وارد کردن <strong>نام کاربری (یوزرنیم)</strong>، هویت منحصر به فرد خود را در سایت ایجاد کنید.</li>
                                    @if(!$user->email)
                                        <li>با ثبت <strong>ایمیل</strong>، از آخرین کتاب‌ها و پیشنهادات ویژه مطلع شوید.</li>
                                    @endif
                                    @if(!$user->phone)
                                        <li>با ثبت <strong>شماره تماس</strong>، امکان بازیابی حساب و دریافت کد تخفیف پیامکی را خواهید داشت.</li>
                                    @endif
                                    <li>با آپلود <strong>تصویر پروفایل</strong>، حساب کاربری خود را شخصی‌سازی کنید.</li>
                                </ul>
                            </div>
                        </div>

                        <h2 class="section-title">ویرایش اطلاعات شخصی</h2>

                        <form action="{{ route('dashboard.update-account-info') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                            @csrf

                            <!-- آپلود تصویر پروفایل -->
                            <div class="form-group full-width">
                                <label for="profile_image" class="form-label">تصویر پروفایل</label>
                                <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                                <p class="form-hint">حداکثر سایز فایل: 1 مگابایت. فرمت‌های قابل قبول: JPG، JPEG، PNG</p>
                                @if(!$user->profile_image)
                                    <p class="profile-incomplete"><i class="fas fa-exclamation-circle"></i> شما هنوز تصویر پروفایل انتخاب نکرده‌اید!</p>
                                @else
                                    <p class="profile-complete"><i class="fas fa-check-circle"></i> تصویر پروفایل شما ثبت شده است.</p>
                                @endif
                            </div>

                            <!-- نام کاربری -->
                            <div class="form-group">
                                <label for="username" class="form-label">
                                    نام کاربری (یوزرنیم)
                                    <span class="recommended">[توصیه شده]</span>
                                </label>
                                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" placeholder="مثال: john_doe">
                                <p class="form-hint">از حروف انگلیسی، اعداد و علامت _ استفاده کنید. حداقل 3 و حداکثر 20 کاراکتر.</p>
                                @if(!$user->username)
                                    <p class="profile-incomplete"><i class="fas fa-exclamation-circle"></i> یوزرنیم خود را وارد کنید!</p>
                                @endif
                            </div>

                            <!-- نام -->
                            <div class="form-group">
                                <label for="first_name" class="form-label">
                                    نام
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}" required>
                            </div>

                            <!-- نام خانوادگی -->
                            <div class="form-group">
                                <label for="last_name" class="form-label">
                                    نام خانوادگی
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}" required>
                            </div>

                            <!-- نام نمایشی -->
                            <div class="form-group">
                                <label for="display_name" class="form-label">نام نمایشی</label>
                                <input type="text" name="display_name" id="display_name" class="form-control" value="{{ $user->display_name }}" placeholder="نام قابل نمایش در سایت">
                                <p class="form-hint">در صورت عدم تکمیل، از نام و نام خانوادگی شما استفاده می‌شود.</p>
                            </div>

                            <!-- ایمیل -->
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    ایمیل
                                    @if(!$user->phone)
                                        <span class="required">*</span>
                                    @else
                                        <span class="recommended">[توصیه شده]</span>
                                    @endif
                                </label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" placeholder="مثال: example@gmail.com" {{ !$user->phone ? 'required' : '' }}>
                                @if(!$user->email)
                                    <p class="profile-incomplete"><i class="fas fa-exclamation-circle"></i> ثبت ایمیل برای دریافت خبرنامه و بازیابی حساب ضروری است!</p>
                                @endif
                            </div>

                            <!-- شماره تماس -->
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    شماره تماس
                                    @if(!$user->email)
                                        <span class="required">*</span>
                                    @else
                                        <span class="recommended">[توصیه شده]</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">+98</div>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ ltrim($user->phone, '+98') }}" placeholder="9123456789" {{ !$user->email ? 'required' : '' }}>
                                </div>
                                @if(!$user->phone)
                                    <p class="profile-incomplete"><i class="fas fa-exclamation-circle"></i> ثبت شماره تماس برای دریافت کد تخفیف و اطلاع‌رسانی توصیه می‌شود!</p>
                                @endif
                            </div>

                            <!-- دکمه ثبت -->
                            <div class="form-submit-wrapper">
                                <button type="submit" class="form-submit">
                                    <i class="fas fa-save"></i> ذخیره اطلاعات
                                </button>
                            </div>
                        </form>
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
                        if (!alert.classList.contains('alert-danger')) {
                            alert.style.opacity = '0';
                            alert.style.transition = 'opacity 0.5s';
                            setTimeout(() => {
                                alert.style.display = 'none';
                            }, 500);
                        }
                    });
                }, 5000);
            }

            // پیش‌نمایش تصویر پروفایل
            const profileImageInput = document.getElementById('profile_image');
            if (profileImageInput) {
                profileImageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatarImg = document.querySelector('.user-avatar');
                            const avatarIcon = document.querySelector('.user-avatar-icon');

                            if (avatarImg) {
                                avatarImg.src = e.target.result;
                            } else {
                                const newImg = document.createElement('img');
                                newImg.src = e.target.result;
                                newImg.className = 'user-avatar';

                                const avatarWrapper = document.querySelector('.user-avatar-wrapper');
                                if (avatarIcon) {
                                    avatarIcon.style.display = 'none';
                                }
                                avatarWrapper.appendChild(newImg);
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        });
    </script>
@endpush
