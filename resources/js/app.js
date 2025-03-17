/**
 * اسکریپت‌های اصلی وب‌سایت کتابخانه دیجیتال
 */

document.addEventListener('DOMContentLoaded', function() {
    // تنظیم کتابخانه AOS برای انیمیشن‌های اسکرول
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }

    // بررسی و اصلاح تصاویر خراب
    document.querySelectorAll('img').forEach(function(img) {
        img.onerror = function() {
            if (!this.src.includes('book-cover.png')) {
                this.src = '/images/book-cover.png';
            }
            this.onerror = null; // جلوگیری از حلقه بی‌نهایت
        };
    });

    // تنظیمات Lightbox
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'تصویر %1 از %2'
        });
    }

    // جستجو در هدر
    const searchToggle = document.querySelector('.search-toggle');
    const searchDropdown = document.querySelector('.search-dropdown');

    if (searchToggle && searchDropdown) {
        searchToggle.addEventListener('click', function(event) {
            event.stopPropagation();
            searchDropdown.classList.toggle('show');
            if (searchDropdown.classList.contains('show')) {
                searchDropdown.querySelector('input').focus();
            }
        });

        // بستن جستجو با کلیک بیرون
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-wrapper')) {
                searchDropdown.classList.remove('show');
            }
        });

        // جلوگیری از بسته شدن منوی جستجو با کلیک روی خود منو
        searchDropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // اعتبارسنجی فرم جستجو
        const searchForm = searchDropdown.querySelector('form');
        const searchInput = searchDropdown.querySelector('input');

        if (searchForm) {
            searchForm.addEventListener('submit', function(event) {
                const value = searchInput.value.trim();

                // بررسی برای کاراکترهای خطرناک
                if (/[<>]/.test(value)) {
                    event.preventDefault();
                    alert('لطفاً از کاراکترهای مجاز استفاده کنید.');
                    return false;
                }

                // بررسی طول ورودی
                if (value.length > 100) {
                    event.preventDefault();
                    alert('متن جستجو بیش از حد طولانی است.');
                    return false;
                }

                // بررسی خالی بودن ورودی
                if (value.length === 0) {
                    event.preventDefault();
                    alert('لطفاً عبارت جستجو را وارد کنید.');
                    return false;
                }
            });
        }
    }

    // تنظیم CSRF توکن برای درخواست‌های AJAX
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // تنظیم CSRF توکن برای jQuery AJAX (اگر jQuery استفاده می‌شود)
    if (typeof $.ajaxSetup === 'function') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
    }

    // حذف لودر صفحه پس از بارگذاری کامل
    const pageLoader = document.querySelector('.page-loader');
    if (pageLoader) {
        setTimeout(() => {
            pageLoader.style.opacity = '0';
            setTimeout(() => {
                pageLoader.style.display = 'none';
            }, 300);
        }, 500);
    }

    // اضافه کردن ویژگی noopener و noreferrer به تمام لینک‌های خارجی
    document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])').forEach(link => {
        link.setAttribute('rel', 'noopener noreferrer');
        link.setAttribute('target', '_blank');
    });

    // دکمه اسکرول به بالا
    const scrollToTopBtn = document.getElementById('scrollToTop');
    if (scrollToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

/**
 * اسکریپت‌های مربوط به صفحه ورود
 */
function initLoginPage() {
    // مدیریت تغییر روش ورود
    const toggleOptions = document.querySelectorAll('.toggle-option');
    const inputSections = document.querySelectorAll('.input-section');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const loginForm = document.querySelector('.login-form');

    if (!toggleOptions.length || !inputSections.length || !loginForm) {
        return; // اگر در صفحه لاگین نیستیم، اجرا نشود
    }

    // تبدیل اعداد فارسی به انگلیسی
    const persianToEnglish = (str) => {
        const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        if (!str) return str;

        for (let i = 0; i < 10; i++) {
            const regex = new RegExp(persianNumbers[i], 'g');
            str = str.toString().replace(regex, englishNumbers[i]);
        }

        return str;
    };

    // پاکسازی شماره موبایل
    const normalizePhoneNumber = (phone) => {
        phone = persianToEnglish(phone);
        phone = phone.replace(/\D/g, '');

        if (phone.startsWith('98')) {
            phone = phone.substring(2);
        }

        if (phone.length > 0 && !phone.startsWith('0')) {
            phone = '0' + phone;
        }

        return phone;
    };

    // تغییر روش ورود
    toggleOptions.forEach(option => {
        option.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            // فعال کردن گزینه انتخاب شده
            toggleOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');

            // نمایش بخش مربوطه
            inputSections.forEach(section => section.classList.remove('active'));
            document.getElementById(target).classList.add('active');

            // تنظیم فوکوس
            if (target === 'phone-input-section' && phoneInput) {
                phoneInput.focus();
                phoneInput.setAttribute('required', 'required');
                if (emailInput) emailInput.removeAttribute('required');
            } else if (emailInput) {
                emailInput.focus();
                emailInput.setAttribute('required', 'required');
                if (phoneInput) phoneInput.removeAttribute('required');
            }
        });
    });

    // مدیریت فیلد شماره موبایل
    if (phoneInput) {
        // تنظیم مقدار اولیه
        if (phoneInput.value) {
            phoneInput.value = normalizePhoneNumber(phoneInput.value);
        }

        // به روزرسانی مقدار هنگام تایپ
        phoneInput.addEventListener('input', function(e) {
            let value = normalizePhoneNumber(this.value);

            // محدود کردن به 11 رقم (با احتساب 0 اول)
            if (value.length > 11) {
                value = value.substring(0, 11);
            }

            this.value = value;
            this.classList.remove('is-invalid');

            // حذف پیام خطا
            const feedback = document.querySelector('.phone-input-container .invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        });
    }

    // مدیریت فیلد ایمیل
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');

            // حذف پیام خطا
            const feedback = document.querySelector('.email-input-container .invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        });
    }

    // اعتبارسنجی فرم هنگام ارسال
    loginForm.addEventListener('submit', function(e) {
        const activeMethod = document.querySelector('.toggle-option.active')?.getAttribute('data-target');

        if (activeMethod === 'phone-input-section' && phoneInput) {
            // اعتبارسنجی شماره موبایل
            const phoneValue = phoneInput.value.trim();
            const phonePattern = /^09\d{9}$/;

            if (!phonePattern.test(phoneValue)) {
                e.preventDefault();
                phoneInput.classList.add('is-invalid');

                let feedback = document.querySelector('.phone-input-container .invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    phoneInput.parentNode.parentNode.appendChild(feedback);
                }

                feedback.textContent = 'لطفاً یک شماره موبایل معتبر وارد کنید (مثال: 09123456789)';
            }
        } else if (emailInput) {
            // اعتبارسنجی ایمیل
            const emailValue = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(emailValue)) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');

                let feedback = document.querySelector('.email-input-container .invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    emailInput.parentNode.parentNode.appendChild(feedback);
                }

                feedback.textContent = 'لطفاً یک آدرس ایمیل معتبر وارد کنید';
            }
        }
    });
}

// فراخوانی تابع راه‌اندازی صفحه ورود پس از بارگذاری صفحه
document.addEventListener('DOMContentLoaded', initLoginPage);
