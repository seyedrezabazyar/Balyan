@extends('layouts.app')

@section('title', 'موضوعات کتاب‌ها - کتابخانه دیجیتال بلیان')

@push('styles')
    <style>
        /* استایل کلی */
        body {
            background-color: #f9fafb;
        }

        /* استایل بخش جستجو */
        .topics-search-section {
            background: #0ea5e9;
            padding: 3rem 0;
            color: #fff;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .topics-search-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
        }

        .topics-search-desc {
            font-size: 1rem;
            max-width: 650px;
            margin: 0 auto 1.5rem;
            text-align: center;
            line-height: 1.6;
        }

        .topics-search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .topics-search-input {
            width: 100%;
            height: 50px;
            padding: 0 1.5rem 0 3rem;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .topics-search-input:focus {
            outline: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.12);
        }

        .topics-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #0ea5e9;
        }

        .topics-search-suggestions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 1rem;
        }

        .topics-search-suggestion {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 30px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .topics-search-suggestion:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: #fff;
        }

        /* استایل کارت های موضوع */
        .topic-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #f0f0f0;
            display: block;
            text-decoration: none;
            position: relative;
        }

        .topic-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.15);
            text-decoration: none;
        }

        .topic-card:hover .topic-card-icon {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.25);
        }

        .topic-card-header {
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            background-color: #ffffff;
        }

        .topic-card-icon {
            width: 70px;
            height: 70px;
            background: #f0f9ff;
            border-radius: 50%;
            margin: 0 auto 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #0ea5e9;
            box-shadow: 0 3px 12px rgba(14, 165, 233, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .topic-card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
            transition: color 0.2s;
        }

        .topic-card:hover .topic-card-title {
            color: #0ea5e9;
        }

        .topic-card-footer {
            padding: 1rem;
            text-align: center;
            background-color: #f8fafc;
            border-top: 1px solid #f0f0f0;
        }

        .topic-view-link {
            color: #0ea5e9;
            font-weight: 500;
            display: block;
            transition: color 0.2s;
        }

        /* عناصر تزئینی */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .bubble-1 {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation: float 8s ease-in-out infinite;
        }

        .bubble-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation: float 10s ease-in-out infinite;
        }

        .bubble-3 {
            width: 80px;
            height: 80px;
            bottom: 10%;
            left: 20%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* واکنش‌گرایی */
        @media (max-width: 768px) {
            .topics-search-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .topics-search-section {
                padding: 2rem 0;
            }
        }

        .topics-title {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.8rem;
            font-size: 1.8rem;
        }

        .topics-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background-color: #0ea5e9;
            border-radius: 2px;
        }

        /* افکت های اضافی */
        .topic-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #0ea5e9, #38bdf8);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .topic-card:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
    </style>
@endpush

@section('content')
    <!-- بخش جستجو -->
    <section class="topics-search-section">
        <div class="container">
            <h1 class="topics-search-title">موضوعات و دسته‌بندی کتاب‌ها</h1>
            <p class="topics-search-desc">از میان بیش از ۵۰ دسته‌بندی و هزاران کتاب، موضوع مورد علاقه خود را پیدا کنید.</p>

            <div class="topics-search-container">
                <input type="text" class="topics-search-input" placeholder="جستجوی موضوع یا کتاب..." aria-label="جستجوی موضوع یا کتاب">
                <i class="fas fa-search topics-search-icon"></i>
            </div>

            <div class="topics-search-suggestions">
                <a href="#" class="topics-search-suggestion">رمان</a>
                <a href="#" class="topics-search-suggestion">روانشناسی</a>
                <a href="#" class="topics-search-suggestion">مدیریت</a>
                <a href="#" class="topics-search-suggestion">تاریخ</a>
                <a href="#" class="topics-search-suggestion">علمی</a>
                <a href="#" class="topics-search-suggestion">فلسفه</a>
                <a href="#" class="topics-search-suggestion">کودک</a>
            </div>
        </div>

        <!-- المان‌های تزیینی -->
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
    </section>

    <!-- بخش موضوعات -->
    <div class="container mt-5">
        <h1 class="topics-title">موضوعات کتاب‌ها</h1>

        <div class="row">
            <!-- موضوع: رمان -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h2 class="topic-card-title">رمان و داستان</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: روانشناسی -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h2 class="topic-card-title">روانشناسی</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: مدیریت -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h2 class="topic-card-title">مدیریت و کسب‌وکار</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: تاریخ -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <h2 class="topic-card-title">تاریخ</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: علمی -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-atom"></i>
                        </div>
                        <h2 class="topic-card-title">علمی</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: فلسفه -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h2 class="topic-card-title">فلسفه و منطق</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: کودک -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h2 class="topic-card-title">کودک و نوجوان</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: هنر -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h2 class="topic-card-title">هنر و معماری</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: مذهبی -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-pray"></i>
                        </div>
                        <h2 class="topic-card-title">مذهبی و عرفانی</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: سیاسی -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h2 class="topic-card-title">سیاسی و اجتماعی</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: زندگی‌نامه -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h2 class="topic-card-title">زندگی‌نامه</h2>
                    </div>
                </a>
            </div>

            <!-- موضوع: علوم کامپیوتر -->
            <div class="col-md-3 col-6 mb-4">
                <a href="#" class="topic-card">
                    <div class="topic-card-header">
                        <div class="topic-card-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h2 class="topic-card-title">علوم کامپیوتر</h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // اسکریپت برای جستجوی موضوعات
            const searchInput = document.querySelector('.topics-search-input');
            searchInput.addEventListener('input', function() {
                const searchText = this.value.trim().toLowerCase();
                const topicCards = document.querySelectorAll('.topic-card');

                topicCards.forEach(card => {
                    const title = card.querySelector('.topic-card-title').textContent.toLowerCase();
                    const parent = card.closest('.col-md-3');

                    if (title.includes(searchText)) {
                        parent.style.display = 'block';
                    } else {
                        parent.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpush
