@extends('layouts.app')

@section('title', $book->title ?? 'جزئیات کتاب')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <style>
        /* متغیرهای رنگی */
        :root {
            --bs-primary-rgb: 74, 105, 189; /* رنگ اصلی جذاب‌تر */
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 46, 204, 113;
            --bs-info-rgb: 52, 152, 219;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 231, 76, 60;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark-rgb: 52, 58, 64;

            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-secondary: rgb(var(--bs-secondary-rgb));
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light: rgb(var(--bs-light-rgb));
            --bs-dark: rgb(var(--bs-dark-rgb));

            --card-bg-color: #ffffff;
            --text-muted-color: #6c757d;
            --body-bg: #f5f7fa;
            --section-bg: #ffffff;
            --book-cover-shadow-color: rgba(0, 0, 0, 0.2);
        }

        /* استایل‌های کلی صفحه */
        .book-detail-page {
            background-color: var(--body-bg);
            font-family: 'Vazirmatn', -apple-system, sans-serif;
            color: var(--bs-dark);
            padding-bottom: 50px;
            min-height: 100vh;
        }

        .book-detail-page a {
            color: var(--bs-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .book-detail-page a:hover {
            color: var(--bs-primary);
            text-decoration: none;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-muted-color);
        }

        .breadcrumb-item.active {
            color: var(--text-muted-color);
        }

        /* بخش اصلی کتاب - تصویر بالای صفحه */
        .book-hero {
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
        }

        .book-cover-section {
            position: relative;
            padding: 40px;
            text-align: center;
        }

        .book-cover-wrapper {
            position: relative;
            perspective: 1500px;
            margin: 0 auto;
            max-width: 280px;
        }

        .book-cover-container {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            transform-style: preserve-3d;
            transform: rotateY(5deg) translateZ(0);
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2), 0 5px 15px rgba(0,0,0,0.1);
        }

        .book-cover-container:hover {
            transform: rotateY(0deg) translateZ(0) scale(1.05);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3), 0 10px 20px rgba(0,0,0,0.1);
        }

        .book-cover {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 10px;
            transition: filter 0.5s ease;
        }

        .book-cover-container:hover .book-cover {
            filter: brightness(1.05);
        }

        .book-format-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            backdrop-filter: blur(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10;
            transition: all 0.3s ease;
        }

        .book-cover-container:hover .book-format-badge {
            background: var(--bs-primary);
            transform: translateY(-3px);
        }

        .book-hero-info {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .book-title {
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #2d3748;
            line-height: 1.3;
        }

        .book-author {
            font-size: 1.2rem;
            color: var(--text-muted-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .book-author i {
            color: var(--bs-primary);
            margin-left: 8px;
        }

        /* میانبرهای سریع - اطلاعات کلیدی */
        .key-highlights {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .key-highlight-item {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .key-highlight-item:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .key-highlight-icon {
            color: var(--bs-primary);
            font-size: 1.2rem;
            margin-left: 10px;
        }

        .key-highlight-text {
            font-size: 0.95rem;
            font-weight: 500;
        }

        .key-highlight-text span {
            font-weight: 400;
            color: var(--text-muted-color);
            margin-left: 5px;
        }

        /* بخش خرید */
        .purchase-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .purchase-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 5px;
            width: 100%;
            background: linear-gradient(to right, var(--bs-primary), var(--bs-info));
        }

        .purchase-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .price-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }

        .price-badge {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 10px;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(255, 75, 43, 0.2);
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2d3748;
        }

        .current-price.free {
            color: var(--bs-success);
        }

        .current-price small {
            font-size: 1rem;
            color: var(--text-muted-color);
            font-weight: normal;
        }

        .original-price {
            color: var(--text-muted-color);
            font-size: 1.1rem;
            margin-top: 5px;
        }

        .original-price del {
            margin-left: 10px;
        }

        .file-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-muted-color);
            font-size: 0.9rem;
        }

        .purchase-action {
            padding: 20px 0;
        }

        .purchase-btn {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--bs-primary) 0%, #3a5bd8 100%);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(var(--bs-primary-rgb), 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .purchase-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(var(--bs-primary-rgb), 0.4);
            color: white;
        }

        .purchase-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.6s ease;
        }

        .purchase-btn:hover::before {
            left: 100%;
        }

        .purchase-btn.free-btn {
            background: linear-gradient(135deg, var(--bs-success) 0%, #27ae60 100%);
            box-shadow: 0 8px 20px rgba(var(--bs-success-rgb), 0.3);
        }

        .purchase-btn.free-btn:hover {
            box-shadow: 0 12px 25px rgba(var(--bs-success-rgb), 0.4);
        }

        .purchase-btn i {
            margin-left: 10px;
        }

        .pulse-btn {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0.5);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(var(--bs-primary-rgb), 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0);
            }
        }

        .purchase-status {
            margin-top: 15px;
            padding: 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .purchase-status.success {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: var(--bs-success);
        }

        .purchase-status.warning {
            background-color: rgba(var(--bs-warning-rgb), 0.1);
            color: #d69e2e;
        }

        .purchase-status i {
            font-size: 1.2rem;
        }

        .purchase-benefits {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
            gap: 20px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--text-muted-color);
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .benefit-item i {
            color: var(--bs-primary);
        }

        /* آمار کتاب */
        .book-stats {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--text-muted-color);
        }

        .stat-item i {
            color: var(--bs-primary);
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .stat-item .stat-value {
            font-weight: 600;
            font-size: 1.2rem;
            color: #2d3748;
        }

        .stat-item .stat-label {
            font-size: 0.85rem;
        }

        /* بخش اطلاعات کتاب */
        .book-content-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            padding: 0;
            margin-bottom: 30px;
        }

        .content-nav {
            background: #f8fafc;
            padding: 15px 20px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .content-nav-tabs {
            display: flex;
            gap: 5px;
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .content-nav-tabs::-webkit-scrollbar {
            display: none;
        }

        .content-nav-item {
            flex: 0 0 auto;
        }

        .content-nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-muted-color);
            font-weight: 500;
            border-bottom: 3px solid transparent;
            position: relative;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav-link.active {
            color: var(--bs-primary);
            border-bottom-color: var(--bs-primary);
        }

        .content-nav-link:hover {
            color: #2d3748;
        }

        .content-nav-link i {
            margin-left: 8px;
        }

        .content-nav-link .badge {
            margin-right: 8px;
            background: var(--text-muted-color);
            color: white;
            font-size: 0.75rem;
            font-weight: 400;
            padding: 3px 8px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .content-nav-link.active .badge {
            background: var(--bs-primary);
        }

        .content-body {
            padding: 30px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* تب توضیحات */
        .book-description {
            line-height: 1.9;
            text-align: justify;
            color: #4a5568;
        }

        .no-description {
            text-align: center;
            padding: 40px;
            color: var(--text-muted-color);
        }

        .no-description i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #e2e8f0;
        }

        /* تب مشخصات */
        .book-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .detail-label {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: var(--text-muted-color);
            font-size: 0.9rem;
        }

        .detail-label i {
            color: var(--bs-primary);
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .detail-value {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.05rem;
        }

        /* تب موضوعات */
        .content-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .content-title i {
            color: var(--bs-primary);
            margin-left: 10px;
        }

        .topics-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
        }

        .topic-tag {
            padding: 8px 18px;
            background: #f8fafc;
            border-radius: 30px;
            color: var(--bs-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(var(--bs-primary-rgb), 0.2);
        }

        .topic-tag:hover {
            background: var(--bs-primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(var(--bs-primary-rgb), 0.2);
        }

        /* تب کتاب‌های مشابه */
        .similar-books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .similar-book-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .similar-book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .similar-book-cover {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .similar-book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .similar-book-card:hover .similar-book-cover img {
            transform: scale(1.1);
        }

        .similar-book-info {
            padding: 15px;
        }

        .similar-book-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
            color: #2d3748;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .similar-book-author {
            font-size: 0.85rem;
            color: var(--text-muted-color);
            margin-bottom: 10px;
        }

        .similar-book-price {
            font-weight: 700;
            color: var(--bs-primary);
        }

        /* تب نظرات */
        .reviews-summary {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .average-rating {
            text-align: center;
        }

        .rating-value {
            font-size: 3rem;
            font-weight: 800;
            color: var(--bs-primary);
            margin-bottom: 10px;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .rating-count {
            color: var(--text-muted-color);
            font-size: 0.9rem;
        }

        .rating-bars {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rating-bar-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .rating-label {
            flex: 0 0 70px;
            font-size: 0.9rem;
            color: var(--text-muted-color);
        }

        .progress {
            flex: 1;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #f59e0b;
            border-radius: 20px;
        }

        .rating-count-number {
            flex: 0 0 40px;
            text-align: right;
            color: var(--text-muted-color);
            font-size: 0.9rem;
        }

        .review-form-section {
            margin-bottom: 30px;
        }

        .review-form-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
            margin-bottom: 15px;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #e2e8f0;
            transition: all 0.2s ease;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #f59e0b;
            transform: scale(1.2);
        }

        .guest-review-message {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            color: var(--text-muted-color);
            margin-bottom: 30px;
        }

        .guest-review-message i {
            font-size: 2.5rem;
            color: var(--bs-primary);
            margin-bottom: 15px;
        }

        .review-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .review-item {
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .review-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bs-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .reviewer-details {
            display: flex;
            flex-direction: column;
        }

        .reviewer-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .review-date {
            font-size: 0.85rem;
            color: var(--text-muted-color);
        }

        .review-rating {
            color: #e2e8f0;
            font-size: 1.1rem;
            display: flex;
            gap: 3px;
        }

        .review-rating .fas {
            color: #f59e0b;
        }

        .review-content {
            color: #4a5568;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* دکمه‌های اکشن کتاب */
        .book-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px;
            border-radius: 30px;
            background: white;
            border: 1px solid #e2e8f0;
            color: var(--text-muted-color);
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
        }

        .action-btn i {
            color: var(--bs-primary);
            transition: all 0.3s ease;
        }

        .action-btn:hover i {
            transform: scale(1.2);
        }

        .action-btn.favorite-btn.active {
            background: rgba(var(--bs-danger-rgb), 0.1);
            border-color: rgba(var(--bs-danger-rgb), 0.3);
            color: var(--bs-danger);
        }

        .action-btn.favorite-btn.active i {
            color: var(--bs-danger);
            animation: heartbeat 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }

        /* مدال اشتراک‌گذاری */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
            padding: 25px 30px 10px;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--bs-primary), var(--bs-info));
        }

        .modal-title {
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title i {
            color: var(--bs-primary);
        }

        .modal-title i {
            color: var(--bs-primary);
        }

        .modal-body {
            padding: 20px 30px 30px;
        }

        .share-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .share-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 15px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .share-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .share-option i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .share-link-container {
            margin-top: 20px;
        }

        .share-link-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .share-link-input {
            display: flex;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .share-link-input input {
            flex: 1;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .share-link-input button {
            padding: 0 20px;
            background: var(--bs-primary);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .share-link-input button:hover {
            background: #3a5bd8;
        }

        /* مدال ورود */
        .login-modal .modal-body {
            padding: 30px;
        }

        .login-icon {
            font-size: 5rem;
            color: var(--bs-primary);
            margin-bottom: 20px;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: var(--text-muted-color);
            margin-bottom: 30px;
        }

        .login-form .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .login-form .input-group {
            margin-bottom: 20px;
        }

        .login-form .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: var(--bs-primary);
        }

        .login-form .form-control {
            border-color: #e2e8f0;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .login-form .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.2);
        }

        .verification-timer {
            color: var(--text-muted-color);
            font-size: 0.9rem;
            margin-top: 10px;
            text-align: center;
        }

        .login-form .btn {
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-benefits {
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }

        .login-benefits h6 {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            text-align: center;
        }

        .login-benefits ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .login-benefits li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #4a5568;
        }

        .login-benefits li i {
            color: var(--bs-success);
        }

        /* نوتیفیکیشن‌ها */
        .toast-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 1000;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-notification.success {
            background-color: var(--bs-success);
        }

        .toast-notification.error {
            background-color: var(--bs-danger);
        }

        .toast-notification.info {
            background-color: var(--bs-info);
        }

        .toast-notification i {
            font-size: 1.3rem;
        }

        /* پاسخگویی */
        @media (max-width: 991.98px) {
            .book-hero {
                flex-direction: column;
            }

            .book-title {
                font-size: 1.8rem;
            }

            .book-cover-section {
                padding: 30px;
            }

            .book-hero-info {
                padding: 30px;
            }

            .similar-books-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .book-cover-wrapper {
                max-width: 220px;
            }

            .key-highlights {
                flex-direction: column;
            }

            .key-highlight-item {
                width: 100%;
            }

            .book-stats {
                flex-wrap: wrap;
                gap: 20px;
            }

            .stat-item {
                width: 45%;
            }

            .book-details-grid {
                grid-template-columns: 1fr;
            }

            .book-actions {
                flex-wrap: wrap;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .reviews-summary .col-md-4 {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 575.98px) {
            .book-hero-info {
                padding: 20px;
            }

            .book-title {
                font-size: 1.5rem;
            }

            .purchase-box {
                padding: 15px;
            }

            .current-price {
                font-size: 2rem;
            }

            .content-body {
                padding: 20px;
            }

            .share-options {
                grid-template-columns: 1fr;
            }

            .reviewer-avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="book-detail-page">
        <div class="container py-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home ms-1"></i> خانه</a></li>
                    @if($book->topic && trim(explode(',', $book->topic)[0]))
                        <li class="breadcrumb-item">
                            <a href="{{ route('search', ['q' => trim(explode(',', $book->topic)[0])]) }}">
                                {{ trim(explode(',', $book->topic)[0]) }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->title, 50) }}</li>
                </ol>
            </nav>

            <!-- بخش اصلی کتاب - بنر بالای صفحه -->
            <div class="book-hero mb-4" data-aos="fade-up" data-aos-duration="800">
                <div class="row g-0">
                    <!-- تصویر کتاب -->
                    <div class="col-lg-4">
                        <div class="book-cover-section">
                            <div class="book-cover-wrapper">
                                <div class="book-cover-container">
                                    @if($book->coverurl)
                                        <img src="{{ $book->coverurl }}" alt="{{ $book->title }}" class="book-cover">
                                    @else
                                        <img src="{{ asset('images/no-cover-placeholder.png') }}" alt="بدون تصویر" class="book-cover no-cover-img">
                                    @endif

                                    @if($book->extension)
                                        <span class="book-format-badge">{{ strtoupper($book->extension) }}</span>
                                    @endif
                                    <div class="cover-overlay"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات کتاب -->
                    <div class="col-lg-8">
                        <div class="book-hero-info">
                            <h1 class="book-title">{{ $book->title }}</h1>

                            @if($book->author_text)
                                <div class="book-author">
                                    <i class="fas fa-pen-nib"></i> {{ $book->author_text }}
                                </div>
                            @endif

                            <!-- اطلاعات کلیدی کتاب -->
                            <div class="key-highlights">
                                @if($book->year)
                                    <div class="key-highlight-item">
                                        <div class="key-highlight-icon"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="key-highlight-text">{{ $book->year }} <span>سال انتشار</span></div>
                                    </div>
                                @endif

                                @if($book->pages_numeric)
                                    <div class="key-highlight-item">
                                        <div class="key-highlight-icon"><i class="fas fa-file-alt"></i></div>
                                        <div class="key-highlight-text">{{ $book->pages_numeric }} <span>صفحه</span></div>
                                    </div>
                                @endif

                                @if($book->language_code)
                                    <div class="key-highlight-item">
                                        <div class="key-highlight-icon"><i class="fas fa-language"></i></div>
                                        <div class="key-highlight-text">{{ $book->language_code }} <span>زبان</span></div>
                                    </div>
                                @endif

                                @if($book->publisher)
                                    <div class="key-highlight-item">
                                        <div class="key-highlight-icon"><i class="fas fa-building"></i></div>
                                        <div class="key-highlight-text">{{ $book->publisher }} <span>ناشر</span></div>
                                    </div>
                                @endif
                            </div>

                            <!-- بخش خرید -->
                            <div class="purchase-box" data-aos="fade-up" data-aos-delay="200">
                                <div class="purchase-header">
                                    @php
                                        $discountRate = 0.20; // مثال
                                        $originalPrice = ($book->price > 0 && $discountRate > 0) ? $book->price / (1 - $discountRate) : 0;
                                        $hasDiscount = $originalPrice > $book->price;
                                    @endphp

                                    <div class="price-section">
                                        @if($hasDiscount)
                                            <div class="price-badge">{{ round($discountRate * 100) }}% تخفیف ویژه</div>
                                        @endif

                                        @if($book->price == 0)
                                            <div class="current-price free">رایگان</div>
                                        @else
                                            <div class="current-price">{{ number_format($book->price) }} <small>تومان</small></div>

                                            @if($hasDiscount)
                                                <div class="original-price">
                                                    <del>{{ number_format($originalPrice) }} تومان</del>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    @if($book->filesize)
                                        <div class="file-info">
                                            <i class="fas fa-file-download"></i> حجم فایل: {{ formatFileSize($book->filesize) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="purchase-action">
                                    @auth
                                        @php
                                            $buttonText = $book->price == 0 ? 'دریافت رایگان کتاب' : 'افزودن به سبد خرید';
                                            $buttonIcon = $book->price == 0 ? 'fa-download' : 'fa-shopping-cart';
                                            $buttonClass = $book->price == 0 ? 'free-btn' : '';
                                            $buttonAction = '#';
                                            $buttonPulse = true;
                                            $isInLibrary = false; // Placeholder
                                            $downloadLink = '#';
                                            $orderExpired = false; // Placeholder
                                        @endphp

                                        @if($isInLibrary)
                                            <a href="{{ $downloadLink }}" class="purchase-btn {{ $buttonClass }}">
                                                <i class="fas fa-cloud-download-alt"></i> دانلود کتاب
                                            </a>
                                            <div class="purchase-status success">
                                                <i class="fas fa-check-circle"></i>
                                                <span>این کتاب در کتابخانه شما موجود است</span>
                                            </div>
                                        @elseif($orderExpired)
                                            <a href="{{ $buttonAction }}" class="purchase-btn {{ $buttonClass }}">
                                                <i class="fas fa-redo-alt"></i> {{ $book->price == 0 ? 'دریافت مجدد' : 'خرید مجدد' }}
                                            </a>
                                            <div class="purchase-status warning">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>دسترسی شما به این کتاب منقضی شده است</span>
                                            </div>
                                        @else
                                            <a href="{{ $buttonAction }}" class="purchase-btn {{ $buttonClass }} {{ $buttonPulse ? 'pulse-btn' : '' }}">
                                                <i class="fas {{ $buttonIcon }}"></i> {{ $buttonText }}
                                            </a>
                                        @endif
                                    @else
                                        <button type="button" class="purchase-btn pulse-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="fas fa-sign-in-alt"></i> ورود / ثبت‌نام برای دریافت
                                        </button>
                                        <div class="purchase-status warning">
                                            <i class="fas fa-info-circle"></i>
                                            <span>برای دسترسی به کتاب وارد حساب کاربری خود شوید</span>
                                        </div>
                                    @endauth
                                </div>

                                <div class="purchase-benefits">
                                    <div class="benefit-item">
                                        <i class="fas fa-shield-alt"></i> دسترسی دائمی
                                    </div>
                                    <div class="benefit-item">
                                        <i class="fas fa-sync-alt"></i> دانلود مجدد
                                    </div>
                                    <div class="benefit-item">
                                        <i class="fas fa-mobile-alt"></i> سازگار با تمام دستگاه‌ها
                                    </div>
                                </div>
                            </div>

                            <!-- دکمه‌های اکشن -->
                            <div class="book-actions">
                                <button class="action-btn favorite-btn" data-book-id="{{ $book->id }}" data-bs-toggle="tooltip" title="افزودن به علاقه‌مندی‌ها">
                                    <i class="far fa-heart"></i> افزودن به علاقه‌مندی‌ها
                                </button>
                                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#shareModal" title="اشتراک‌گذاری">
                                    <i class="fas fa-share-alt"></i> اشتراک‌گذاری
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- آمار کتاب -->
            <div class="book-stats" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <i class="fas fa-download"></i>
                    <div class="stat-value">{{ rand(100, 999) }}</div>
                    <div class="stat-label">دانلود</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <div class="stat-value">{{ rand(1000, 9999) }}</div>
                    <div class="stat-label">بازدید</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-star"></i>
                    <div class="stat-value">{{ number_format(rand(35, 50) / 10, 1) }}</div>
                    <div class="stat-label">امتیاز</div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <div class="stat-value">{{ rand(50, 200) }}</div>
                    <div class="stat-label">کاربران</div>
                </div>
            </div>

            <!-- بخش اصلی محتوا -->
            <div class="book-content-section" data-aos="fade-up" data-aos-delay="400">
                <div class="content-nav">
                    <ul class="content-nav-tabs" id="bookContentNav">
                        <li class="content-nav-item">
                            <a class="content-nav-link active" data-target="description">
                                <i class="fas fa-file-alt"></i> توضیحات کتاب
                            </a>
                        </li>
                        <li class="content-nav-item">
                            <a class="content-nav-link" data-target="details">
                                <i class="fas fa-info-circle"></i> مشخصات کتاب
                            </a>
                        </li>
                        <li class="content-nav-item">
                            <a class="content-nav-link" data-target="topics">
                                <i class="fas fa-tags"></i> موضوعات
                            </a>
                        </li>
                        <li class="content-nav-item">
                            <a class="content-nav-link" data-target="similar">
                                <i class="fas fa-book"></i> کتاب‌های مشابه
                            </a>
                        </li>
                        <li class="content-nav-item">
                            <a class="content-nav-link" data-target="reviews">
                                <i class="fas fa-comments"></i> نظرات
                                <span class="badge">{{ $book->reviews_count ?? count($book->reviews ?? []) }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="content-body">
                    <!-- توضیحات کتاب -->
                    <div class="content-section active" id="description-section">
                        @if($book->description)
                            <div class="book-description">
                                {!! nl2br(e($book->description)) !!}
                            </div>
                        @else
                            <div class="no-description">
                                <i class="fas fa-info-circle"></i>
                                <p>توضیحاتی برای این کتاب ثبت نشده است.</p>
                            </div>
                        @endif
                    </div>

                    <!-- مشخصات کتاب -->
                    <div class="content-section" id="details-section">
                        <div class="book-details-grid">
                            @if($book->author_text)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-user-edit"></i> نویسنده</div>
                                    <div class="detail-value">{{ $book->author_text }}</div>
                                </div>
                            @endif

                            @if($book->publisher)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-building"></i> ناشر</div>
                                    <div class="detail-value">{{ $book->publisher }}</div>
                                </div>
                            @endif

                            @if($book->year)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-calendar-alt"></i> سال انتشار</div>
                                    <div class="detail-value">{{ $book->year }}</div>
                                </div>
                            @endif

                            @if($book->language_code)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-language"></i> زبان</div>
                                    <div class="detail-value">{{ $book->language_code }}</div>
                                </div>
                            @endif

                            @if($book->edition)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-bookmark"></i> ویرایش</div>
                                    <div class="detail-value">{{ $book->edition }}</div>
                                </div>
                            @endif

                            @if($book->pages_numeric)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-copy"></i> تعداد صفحات</div>
                                    <div class="detail-value">{{ $book->pages_numeric }}</div>
                                </div>
                            @endif

                            @if($book->extension)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-file-zipper"></i> فرمت فایل</div>
                                    <div class="detail-value">{{ strtoupper($book->extension) }}</div>
                                </div>
                            @endif

                            @if($book->filesize)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-database"></i> حجم فایل</div>
                                    <div class="detail-value">{{ formatFileSize($book->filesize) }}</div>
                                </div>
                            @endif

                            @if($book->isbn)
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-barcode"></i> شابک</div>
                                    <div class="detail-value">{{ $book->isbn }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- موضوعات کتاب -->
                    <div class="content-section" id="topics-section">
                        <h3 class="content-title"><i class="fas fa-tags"></i> موضوعات مرتبط</h3>

                        @if($book->topic && count(array_filter(explode(',', $book->topic))))
                            <div class="topics-list">
                                @foreach(explode(',', $book->topic) as $topic)
                                    @php $trimmedTopic = trim($topic); @endphp
                                    @if($trimmedTopic)
                                        <a href="{{ route('search', ['q' => $trimmedTopic]) }}" class="topic-tag">
                                            {{ $trimmedTopic }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="no-description">
                                <i class="fas fa-info-circle"></i>
                                <p>موضوعی برای این کتاب ثبت نشده است.</p>
                            </div>
                        @endif
                    </div>

                    <!-- کتاب‌های مشابه -->
                    <div class="content-section" id="similar-section">
                        <h3 class="content-title"><i class="fas fa-book"></i> کتاب‌های مشابه</h3>

                        <div class="similar-books-grid">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="similar-book-card">
                                    <div class="similar-book-cover">
                                        <img src="https://via.placeholder.com/200x270" alt="کتاب مشابه">
                                    </div>
                                    <div class="similar-book-info">
                                        <h4 class="similar-book-title">عنوان کتاب مشابه {{ $i }}</h4>
                                        <div class="similar-book-author">نویسنده {{ $i }}</div>
                                        <div class="similar-book-price">{{ number_format(rand(20000, 90000)) }} تومان</div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- نظرات -->
                    <div class="content-section" id="reviews-section">
                        <h3 class="content-title"><i class="fas fa-comments"></i> نظرات و امتیازات کاربران</h3>

                        <div class="reviews-summary">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="average-rating">
                                        <div class="rating-value">{{ number_format(rand(35, 50) / 10, 1) }}</div>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <div class="rating-count">از {{ rand(10, 50) }} نظر</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="rating-bars">
                                        @for($i = 5; $i >= 1; $i--)
                                            <div class="rating-bar-item">
                                                <div class="rating-label">{{ $i }} ستاره</div>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ rand(10, 90) }}%"></div>
                                                </div>
                                                <div class="rating-count-number">{{ rand(1, 20) }}</div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="review-form-section">
                                <h4 class="review-form-title">نظر خود را ثبت کنید</h4>
                                <form action="#" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">امتیاز شما:</label>
                                        <div class="rating-input">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                                <label for="star{{ $i }}"><i class="far fa-star"></i></label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">نظر شما:</label>
                                        <textarea name="comment" id="comment" rows="4" class="form-control" required placeholder="نظر خود را بنویسید..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane ms-2"></i> ثبت نظر
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="guest-review-message">
                                <i class="fas fa-user-lock"></i>
                                <p>برای ثبت نظر، لطفاً <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">وارد حساب کاربری</a> خود شوید.</p>
                            </div>
                        @endauth

                        <div class="review-list">
                            @forelse($book->reviews ?? [] as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ substr($review->user->name ?? 'کاربر', 0, 1) }}
                                            </div>
                                            <div class="reviewer-details">
                                                <div class="reviewer-name">{{ $review->user->name ?? 'کاربر' }}</div>
                                                <div class="review-date">{{ optional($review->created_at)->diffForHumans() ?? 'اخیراً' }}</div>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= ($review->rating ?? 0) ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p>{{ $review->comment ?? 'بدون نظر' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="far fa-comment-dots fs-1 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-2">هنوز نظری برای این کتاب ثبت نشده است.</p>
                                    <p>اولین نفری باشید که درباره این کتاب نظر می‌دهد!</p>
                                </div>
                            @endforelse

                            @if(($book->reviews_count ?? count($book->reviews ?? [])) > 3)
                                <div class="text-center mt-4">
                                    <a href="#" class="btn btn-outline-primary">
                                        <i class="fas fa-comments ms-2"></i> مشاهده همه نظرات ({{ $book->reviews_count ?? count($book->reviews ?? []) }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مدال اشتراک‌گذاری -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel"><i class="fas fa-share-alt ms-2"></i> اشتراک‌گذاری کتاب</h5>
                    <button type="button" class="btn-close me-auto ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>این کتاب را با دوستان خود به اشتراک بگذارید:</p>

                    <div class="share-options">
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($book->title ?? 'کتاب') }}" target="_blank" class="share-option" style="background-color: #0088cc;">
                            <i class="fab fa-telegram-plane"></i>
                            <span>تلگرام</span>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode(($book->title ?? 'کتاب') . ' - ' . url()->current()) }}" target="_blank" class="share-option" style="background-color: #25D366;">
                            <i class="fab fa-whatsapp"></i>
                            <span>واتساپ</span>
                        </a>
                        <a href="mailto:?subject={{ urlencode('معرفی کتاب: ' . ($book->title ?? 'کتاب')) }}&body={{ urlencode('سلام، این کتاب را ببینید: ' . ($book->title ?? 'کتاب') . "\n" . url()->current()) }}" class="share-option" style="background-color: #F4B400;">
                            <i class="far fa-envelope"></i>
                            <span>ایمیل</span>
                        </a>
                        <a href="{{ url()->current() }}" class="share-option copy-link" style="background-color: #4a69bd;">
                            <i class="far fa-copy"></i>
                            <span>کپی لینک</span>
                        </a>
                    </div>

                    <div class="share-link-container">
                        <div class="share-link-label">لینک مستقیم:</div>
                        <div class="share-link-input">
                            <input type="text" value="{{ url()->current() }}" id="shareLink" readonly>
                            <button type="button" id="copyLinkBtn" data-clipboard-target="#shareLink">کپی</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مدال ورود -->
    @guest
        <div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel"><i class="fas fa-sign-in-alt ms-2"></i> ورود به حساب کاربری</h5>
                        <button type="button" class="btn-close me-auto ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="login-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h4 class="login-title">دسترسی به کتاب‌های دیجیتال</h4>
                            <p class="login-subtitle">برای دانلود کتاب، لطفاً وارد حساب کاربری خود شوید</p>
                        </div>

                        <form class="login-form" method="POST" action="#">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                            <div class="mb-3">
                                <label for="phone" class="form-label">شماره موبایل</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="مثال: 09123456789" required>
                                </div>
                            </div>

                            <div id="verificationCodeContainer" class="mb-3" style="display: none;">
                                <label for="verification_code" class="form-label">کد تأیید</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="کد تأیید را وارد کنید">
                                </div>
                                <div class="verification-timer">
                                    <span id="timer">02:00</span> مانده تا ارسال مجدد کد
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" id="sendCodeBtn" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane ms-2"></i> ارسال کد تأیید
                                </button>
                                <button type="button" id="verifyCodeBtn" class="btn btn-success btn-lg" style="display: none;">
                                    <i class="fas fa-check-circle ms-2"></i> تأیید و دانلود کتاب
                                </button>
                            </div>

                            <div class="login-benefits">
                                <h6>مزایای عضویت در کتابخانه دیجیتال:</h6>
                                <ul>
                                    <li><i class="fas fa-check-circle"></i> دسترسی به هزاران کتاب رایگان</li>
                                    <li><i class="fas fa-check-circle"></i> امکان دانلود نامحدود</li>
                                    <li><i class="fas fa-check-circle"></i> دریافت پیشنهادات شخصی‌سازی شده</li>
                                    <li><i class="fas fa-check-circle"></i> بهره‌مندی از تخفیف‌های ویژه</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection

@push('scripts')
    <!-- AOS (Animate On Scroll) برای انیمیشن‌های زیبا -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- کتابخانه Clipboard.js برای دکمه کپی -->
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // راه‌اندازی AOS برای انیمیشن‌ها
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true
            });

            // فعال‌سازی تول‌تیپ‌ها
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // تب‌های محتوا
            var contentLinks = document.querySelectorAll('.content-nav-link');
            contentLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // حذف کلاس active از همه لینک‌ها و بخش‌ها
                    contentLinks.forEach(function(l) {
                        l.classList.remove('active');
                    });

                    var contentSections = document.querySelectorAll('.content-section');
                    contentSections.forEach(function(section) {
                        section.classList.remove('active');
                    });

                    // اضافه کردن کلاس active به لینک و بخش مورد نظر
                    this.classList.add('active');
                    var target = this.getAttribute('data-target');
                    document.getElementById(target + '-section').classList.add('active');
                });
            });

            // کپی لینک اشتراک‌گذاری
            var clipboard = new ClipboardJS('#copyLinkBtn');

            clipboard.on('success', function(e) {
                const btn = e.trigger;

                // تغییر متن دکمه به "کپی شد"
                btn.textContent = 'کپی شد!';
                btn.style.backgroundColor = '#27ae60';
                btn.style.color = 'white';

                // بازگشت به حالت اولیه بعد از 2 ثانیه
                setTimeout(function() {
                    btn.textContent = 'کپی';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }, 2000);

                // نمایش نوتیفیکیشن
                showNotification('success', 'لینک با موفقیت کپی شد.');

                e.clearSelection();
            });

            // دکمه کپی لینک در گزینه‌های اشتراک‌گذاری
            document.querySelector('.copy-link').addEventListener('click', function(e) {
                e.preventDefault();

                // کپی لینک به کلیپ‌بورد
                var tempInput = document.createElement('input');
                tempInput.value = this.getAttribute('href');
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // نمایش نوتیفیکیشن
                showNotification('success', 'لینک با موفقیت کپی شد.');
            });

            // دکمه علاقه‌مندی
            const favoriteBtn = document.querySelector('.favorite-btn');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');

                    this.classList.toggle('active');

                    if (this.classList.contains('active')) {
                        icon.classList.replace('far', 'fas');
                        showNotification('success', 'کتاب به علاقه‌مندی‌های شما اضافه شد.');
                    } else {
                        icon.classList.replace('fas', 'far');
                        showNotification('info', 'کتاب از علاقه‌مندی‌های شما حذف شد.');
                    }
                });
            }

            // فرم ورود
            const loginForm = document.querySelector('.login-form');
            if (loginForm) {
                const sendCodeBtn = document.getElementById('sendCodeBtn');
                const verifyCodeBtn = document.getElementById('verifyCodeBtn');
                const verificationCodeContainer = document.getElementById('verificationCodeContainer');
                const timerDisplay = document.getElementById('timer');

                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (verificationCodeContainer.style.display === 'none') {
                        // نمایش فیلد کد تأیید
                        verificationCodeContainer.style.display = 'block';
                        sendCodeBtn.style.display = 'none';
                        verifyCodeBtn.style.display = 'block';
                        document.getElementById('phone').readOnly = true;

                        // شروع تایمر
                        startTimer(120, timerDisplay);

                        // نمایش نوتیفیکیشن
                        showNotification('success', 'کد تأیید به شماره موبایل شما ارسال شد.');
                    }
                });

                if (verifyCodeBtn) {
                    verifyCodeBtn.addEventListener('click', function() {
                        const verificationCode = document.getElementById('verification_code').value;

                        if (verificationCode && verificationCode.length > 0) {
                            showNotification('success', 'ورود با موفقیت انجام شد. در حال انتقال...');

                            // انتقال به صفحه دانلود کتاب بعد از 2 ثانیه
                            setTimeout(function() {
                                window.location.href = document.querySelector('input[name="redirect_to"]').value;
                            }, 2000);
                        } else {
                            showNotification('error', 'لطفاً کد تأیید را وارد کنید.');
                        }
                    });
                }
            }

            // تابع نمایش نوتیفیکیشن
            function showNotification(type, message) {
                const toast = document.createElement('div');
                toast.className = `toast-notification ${type}`;

                let icon = '';
                switch(type) {
                    case 'success':
                        icon = '<i class="fas fa-check-circle"></i>';
                        break;
                    case 'error':
                        icon = '<i class="fas fa-exclamation-circle"></i>';
                        break;
                    case 'info':
                        icon = '<i class="fas fa-info-circle"></i>';
                        break;
                }

                toast.innerHTML = `${icon} ${message}`;
                document.body.appendChild(toast);

                setTimeout(function() {
                    toast.classList.add('show');
                }, 100);

                setTimeout(function() {
                    toast.classList.remove('show');
                    setTimeout(function() {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            }

            // تابع تایمر برای کد تأیید
            function startTimer(duration, display) {
                let timer = duration, minutes, seconds;
                let interval = setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;

                    if (--timer < 0) {
                        clearInterval(interval);
                        display.parentElement.innerHTML = '<a href="#" id="resendCode" class="text-primary">ارسال مجدد کد</a>';

                        // اضافه کردن رویداد کلیک برای ارسال مجدد کد
                        document.getElementById('resendCode').addEventListener('click', function(e) {
                            e.preventDefault();
                            startTimer(120, display);
                            showNotification('info', 'کد تأیید جدید ارسال شد.');
                        });
                    }
                }, 1000);
            }
        });
    </script>
@endpush
