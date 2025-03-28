@extends('layouts.app')

@section('title', 'پروفایل ' . $user->first_name . ' ' . $user->last_name . ' - کتابخانه دیجیتال بالیان')

@section('content')
    <div class="profile-container">
        <div class="container py-5">
            <!-- بخش هدر پروفایل -->
            <div class="profile-header mb-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="profile-card text-center">
                            <div class="profile-avatar-container">
                                <div class="profile-level-badge">سطح {{ $userLevel ?? 1 }}</div>
                                <div class="profile-avatar">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}">
                                    @else
                                        <div class="default-avatar">
                                            {{ substr($user->first_name ?? 'کاربر', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <h3 class="profile-name mt-3">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="profile-username">{{ '@' . $user->username }}</p>

                            <div class="user-rank">
                                <span class="rank-title">{{ $userRank ?? 'کتاب‌خوان فعال' }}</span>
                            </div>

                            <div class="user-stats mt-3">
                                <div class="stat-item">
                                    <div class="stat-value">{{ $readingScore ?? 72 }}</div>
                                    <div class="stat-label">امتیاز مطالعه</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $favoriteBooks ?? 0 }}</div>
                                    <div class="stat-label">علاقه‌مندی</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $daysSinceJoin ?? 0 }}</div>
                                    <div class="stat-label">روز عضویت</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="achievement-card">
                            <div class="card-header">
                                <h4><i class="fas fa-trophy me-2"></i> دستاوردهای {{ $user->first_name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="achievements-grid">
                                    <div class="achievement-item {{ ($achievements['reading_starter'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>شروع مطالعه</h5>
                                            <p>اولین کتاب خود را مطالعه کرده</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['profile_complete'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>پروفایل کامل</h5>
                                            <p>اطلاعات پروفایل را تکمیل کرده</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['five_favorites'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>کالکشن کتاب</h5>
                                            <p>۵ کتاب به علاقه‌مندی‌های خود افزوده</p>
                                        </div>
                                    </div>

                                    <div class="achievement-item {{ ($achievements['regular_reader'] ?? false) ? 'achieved' : '' }}">
                                        <div class="achievement-icon">
                                            <i class="fas fa-book-reader"></i>
                                        </div>
                                        <div class="achievement-details">
                                            <h5>مطالعه‌گر منظم</h5>
                                            <p>۳۰ روز متوالی مطالعه داشته</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بخش نشان‌ها -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="badges-card">
                        <div class="card-header">
                            <h4><i class="fas fa-certificate me-2"></i> نشان‌های {{ $user->first_name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="badges-grid">
                                <div class="badge-item active" title="کاربر فعال">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['profile_complete'] ?? false) ? 'active' : '' }}" title="پروفایل کامل">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['reading_starter'] ?? false) ? 'active' : '' }}" title="شروع مطالعه">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['five_favorites'] ?? false) ? 'active' : '' }}" title="کالکشن کتاب">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['regular_reader'] ?? false) ? 'active' : '' }}" title="مطالعه‌گر منظم">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <div class="badge-item {{ ($achievements['social_sharer'] ?? false) ? 'active' : '' }}" title="اشتراک‌گذار">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بخش کتابخانه -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="my-library-section">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h3><i class="fas fa-book-reader me-2"></i> کتابخانه {{ $user->first_name }}</h3>
                        </div>

                        <div class="books-wrapper">
                            @if($recentBooks && $recentBooks->count() > 0)
                                <div class="row">
                                    @foreach($recentBooks as $book)
                                        <div class="col-md-3 mb-4">
                                            <div class="book-card">
                                                <div class="book-cover">
                                                    @if($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                                                    @else
                                                        <img src="{{ asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                                                    @endif
                                                    <div class="book-actions">
                                                        <a href="#" class="book-action-btn detail-btn" title="جزئیات">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="book-info">
                                                    <h5 class="book-title">{{ $book->title }}</h5>
                                                    <p class="book-author">{{ $book->author }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-books text-center py-4">
                                    <div class="empty-icon mb-3">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h4>کتابخانه این کاربر خالی است</h4>
                                    <p class="text-muted">{{ $user->first_name }} هنوز کتابی به کتابخانه خود اضافه نکرده است</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تابع انیمیشن اعداد
            function animateValue(obj, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    obj.innerHTML = Math.floor(progress * (end - start) + start);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // انیمیشن اعداد در آمار کاربر
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(element => {
                const finalValue = parseInt(element.textContent);
                animateValue(element, 0, finalValue, 1000);
            });

            // نمایش tooltip برای نشان‌ها
            const badgeItems = document.querySelectorAll('.badge-item');
            badgeItems.forEach(badge => {
                const title = badge.getAttribute('title');
                const tooltip = document.createElement('div');
                tooltip.className = 'badge-tooltip';
                tooltip.textContent = title;
                badge.appendChild(tooltip);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* استایل‌های اصلی پروفایل */
        .profile-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 30px;
        }

        .profile-username {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
            direction: ltr;
            text-align: center;
        }

        /* کارت پروفایل */
        .profile-card {
            background-color: #4270e4;
            border-radius: 12px;
            padding: 25px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-container {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 15px;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background-color: #fff;
            position: relative;
        }

        .profile-avatar img {
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
            font-size: 45px;
            font-weight: bold;
            color: #4270e4;
            background-color: #e9ecef;
        }

        .profile-level-badge {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #f47e20;
            color: white;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            z-index: 2;
        }

        .profile-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-phone {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .user-rank {
            margin: 10px 0;
        }

        .rank-title {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .user-stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 22px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
        }

        /* کارت دستاوردها */
        .achievement-card, .my-library-section, .favorites-section {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-header h4 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* گرید دستاوردها */
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .achievement-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            border: 1px solid #eee;
            opacity: 0.7;
        }

        .achievement-item.achieved {
            background-color: #e8f5e9;
            border-color: #c8e6c9;
            opacity: 1;
        }

        .achievement-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 18px;
            color: #6c757d;
        }

        .achievement-item.achieved .achievement-icon {
            background-color: #27ae60;
            color: white;
        }

        .achievement-details h5 {
            margin: 0 0 5px;
            font-size: 15px;
            font-weight: 600;
        }

        .achievement-details p {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
        }

        /* نشان‌ها */
        .badges-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            padding: 10px;
        }

        .badge-item {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #adb5bd;
            margin: 0 auto;
            position: relative;
            cursor: help;
            transition: transform 0.2s ease;
        }

        .badge-item:hover {
            transform: scale(1.05);
        }

        .badge-item.active {
            background-color: #f47e20;
            color: white;
            box-shadow: 0 3px 8px rgba(244, 126, 32, 0.3);
        }

        /* نمایش tooltip برای نشان‌ها */
        .badge-tooltip {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0,0,0,0.8);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .badge-item:hover .badge-tooltip {
            opacity: 1;
            visibility: visible;
        }

        /* کارت کتاب‌ها */
        .book-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .book-cover {
            height: 180px;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
        }

        .book-info {
            padding: 12px;
        }

        .book-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-author {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }

        /* بخش کتاب‌های خالی */
        .empty-books {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 50px;
            color: #dee2e6;
        }

        /* بخش کتابخانه من و علاقه‌مندی‌ها */
        .my-library-section,
        .favorites-section {
            padding: 20px;
        }

        .section-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        /* ریسپانسیو */
        @media (max-width: 991.98px) {
            .achievements-grid {
                grid-template-columns: 1fr;
            }

            .badges-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .profile-card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .profile-container {
                padding-top: 15px;
            }

            .badges-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush
