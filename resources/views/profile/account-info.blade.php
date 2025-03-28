@extends('layouts.app')

@section('title', 'ویرایش اطلاعات کاربری - کتابخانه دیجیتال بالیان')

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
                            <a href="{{ route('profile.account-info') }}" class="profile-nav-item active">
                                <i class="fas fa-user-edit"></i>
                                <span>ویرایش پروفایل</span>
                            </a>
                            <a href="{{ route('profile.my-books') }}" class="profile-nav-item">
                                <i class="fas fa-book"></i>
                                <span>کتاب‌های من</span>
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

            <!-- عنوان صفحه -->
            <div class="page-title mb-4">
                <div class="row">
                    <div class="col-12">
                        <h1>ویرایش اطلاعات کاربری</h1>
                        <p>لطفاً اطلاعات پروفایل خود را تکمیل کنید تا بتوانیم خدمات بهتری به شما ارائه دهیم</p>
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

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pr-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- بخش محتوا -->
            <div class="row">
                <!-- اطلاعات کاربر -->
                <div class="col-lg-4 mb-4">
                    <div class="user-profile-card">
                        <div class="user-avatar-container">
                            <div class="profile-avatar">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}" class="user-avatar-img">
                                @else
                                    <div class="default-avatar">
                                        {{ substr($user->first_name ?? 'کاربر', 0, 1) }}
                                    </div>
                                @endif
                                <label for="profile_image" class="avatar-edit-btn" title="تغییر تصویر">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                        </div>

                        <h3 class="user-name mt-3">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="user-contact" dir="ltr">{{ $user->email ?? $user->phone }}</p>

                        <div class="completion-status mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>تکمیل پروفایل</span>
                                <span>{{ $profileCompletionPercentage ?? 65 }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $profileCompletionPercentage ?? 65 }}%"
                                     aria-valuenow="{{ $profileCompletionPercentage ?? 65 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- محدودیت تغییر نام کاربری -->
                        @if(!$canChangeUsername && $user->username)
                            <div class="username-restriction-notice mt-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    شما فقط یک بار در ماه می‌توانید نام کاربری خود را تغییر دهید. {{ (int)$daysUntilUsernameChange }} روز دیگر می‌توانید مجدداً تغییر دهید.
                                </div>
                            </div>
                        @endif

                        <!-- راهنمای تکمیل پروفایل -->
                        <div class="profile-guideline mt-4">
                            <div class="guideline-title">
                                <i class="fas fa-lightbulb"></i>
                                <span>مزایای تکمیل پروفایل</span>
                            </div>
                            <ul class="guideline-list">
                                <li>دریافت کد تخفیف ویژه 20%</li>
                                <li>امتیاز ویژه برای ارتقای سطح کاربری</li>
                                <li>دسترسی به محتوای اختصاصی</li>
                                <li>اطلاع از تخفیف‌های ویژه</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- فرم ویرایش اطلاعات -->
                <div class="col-lg-8">
                    <div class="edit-profile-card">
                        <div class="card-header">
                            <h4><i class="fas fa-user-edit me-2"></i> ویرایش اطلاعات شخصی</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update-account-info') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="profile_image" id="profile_image" style="display: none;" accept="image/*">

                                <div class="row">

                                    <!-- نام کاربری -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="username" class="form-label">
                                                نام کاربری
                                                <span class="text-warning">[توصیه شده]</span>
                                            </label>
                                            <input type="text" name="username" id="username" class="form-control"
                                                   value="{{ $user->username }}" placeholder="مثال: user123"
                                                {{ !$canChangeUsername ? 'readonly' : '' }}>

                                            <small class="form-text text-muted">
                                                از حروف انگلیسی، اعداد و علامت _ استفاده کنید.
                                                @if($canChangeUsername)
                                                    <span class="text-warning">توجه: شما فقط یک بار در ماه می‌توانید نام کاربری خود را تغییر دهید.</span>
                                                @endif
                                            </small>

                                            @if(!$user->username)
                                                <div class="missing-field">
                                                    <i class="fas fa-exclamation-circle"></i> هنوز تکمیل نشده
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- نام نمایشی -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="display_name" class="form-label">نام نمایشی</label>
                                            <input type="text" name="display_name" id="display_name" class="form-control"
                                                   value="{{ $user->display_name }}" placeholder="نام قابل نمایش در سایت">
                                            <small class="form-text text-muted">
                                                در صورت عدم تکمیل، نام و نام خانوادگی نمایش داده می‌شود
                                            </small>
                                        </div>
                                    </div>

                                    <!-- نام -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">
                                                نام
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                   value="{{ $user->first_name }}" required>
                                        </div>
                                    </div>

                                    <!-- نام خانوادگی -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">
                                                نام خانوادگی
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                   value="{{ $user->last_name }}" required>
                                        </div>
                                    </div>

                                    <!-- ایمیل -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="email" class="form-label">
                                                ایمیل
                                                @if(!$user->phone)
                                                    <span class="text-danger">*</span>
                                                @else
                                                    <span class="text-warning">[توصیه شده]</span>
                                                @endif
                                            </label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                   value="{{ $user->email }}" placeholder="مثال: example@gmail.com"
                                                {{ !$user->phone ? 'required' : '' }}>
                                            @if(!$user->email)
                                                <div class="missing-field">
                                                    <i class="fas fa-exclamation-circle"></i> برای دریافت خبرنامه و پیشنهادات ویژه
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- شماره تماس -->
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">
                                                شماره تماس
                                                @if(!$user->email)
                                                    <span class="text-danger">*</span>
                                                @else
                                                    <span class="text-warning">[توصیه شده]</span>
                                                @endif
                                            </label>
                                            <input type="tel" name="phone" id="phone" class="form-control"
                                                   value="{{ ltrim($user->phone, '+98') }}" placeholder="09123456789"
                                                {{ !$user->email ? 'required' : '' }}>
                                            <small class="form-text text-muted">
                                                شماره را با فرمت 09123456789 وارد کنید
                                            </small>
                                            @if(!$user->phone)
                                                <div class="missing-field">
                                                    <i class="fas fa-exclamation-circle"></i> برای دریافت کد تخفیف پیامکی
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- دکمه ثبت تغییرات -->
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i> ذخیره تغییرات
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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

        .alert-info {
            background-color: #e8f4fd;
            color: #3498db;
        }

        /* کارت اطلاعات کاربر */
        .user-profile-card {
            background-color: #4270e4;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            color: white;
            text-align: center;
            height: 100%;
        }

        .user-avatar-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
            position: relative;
            background-color: #fff;
        }

        .user-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .default-avatar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            font-weight: bold;
            color: #4270e4;
            background-color: #e9ecef;
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #fff;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 2;
        }

        .avatar-edit-btn i {
            color: #4270e4;
            font-size: 16px;
        }

        .user-name {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-contact {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .completion-status .progress {
            height: 8px;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            overflow: visible;
        }

        .completion-status .progress-bar {
            background-color: #f47e20;
            border-radius: 5px;
        }

        /* محدودیت تغییر نام کاربری */
        .username-restriction-notice .alert {
            padding: 10px 15px;
            font-size: 13px;
            margin-bottom: 0;
        }

        /* راهنمای تکمیل پروفایل */
        .profile-guideline {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: right;
        }

        .guideline-title {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 16px;
        }

        .guideline-title i {
            color: #f47e20;
            margin-left: 8px;
            font-size: 18px;
        }

        .guideline-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .guideline-list li {
            position: relative;
            padding-right: 20px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .guideline-list li:before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 0;
            color: #f47e20;
        }

        /* کارت ویرایش پروفایل */
        .edit-profile-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .edit-profile-card .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .edit-profile-card .card-header h4 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }

        .edit-profile-card .card-body {
            padding: 25px;
        }

        /* فرم ویرایش */
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 15px;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 10px 15px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.2s;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: #4270e4;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 112, 228, 0.1);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 12px;
        }

        .text-warning {
            color: #f47e20 !important;
        }

        .missing-field {
            color: #f47e20;
            font-size: 12px;
            margin-top: 5px;
        }

        /* دکمه ثبت */
        .btn-primary {
            background-color: #4270e4;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #3560d0;
        }

        /* ریسپانسیو */
        @media (max-width: 991.98px) {
            .user-profile-card {
                margin-bottom: 20px;
            }
        }

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
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // پیش‌نمایش تصویر پروفایل
            const profileImageInput = document.getElementById('profile_image');
            const avatarEditBtn = document.querySelector('.avatar-edit-btn');

            if (avatarEditBtn && profileImageInput) {
                avatarEditBtn.addEventListener('click', function() {
                    profileImageInput.click();
                });
            }

            if (profileImageInput) {
                profileImageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const defaultAvatar = document.querySelector('.default-avatar');
                            const userAvatar = document.querySelector('.user-avatar-img');

                            if (userAvatar) {
                                userAvatar.src = e.target.result;
                            } else if (defaultAvatar) {
                                defaultAvatar.style.display = 'none';

                                const avatarImg = document.createElement('img');
                                avatarImg.src = e.target.result;
                                avatarImg.className = 'user-avatar-img';

                                const profileAvatar = document.querySelector('.profile-avatar');
                                profileAvatar.appendChild(avatarImg);
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

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
        });
    </script>
@endpush
