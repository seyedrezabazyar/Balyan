<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * نمایش صفحه اصلی داشبورد کاربر
     */
    public function index()
    {
        $user = Auth::user();

        // مقادیر پیش‌فرض را تنظیم می‌کنیم تا اگر مدل‌ها هنوز ایجاد نشده‌اند، خطا رخ ندهد
        $purchasedBooks = 0;
        $favoriteBooks = 0;
        $walletBalance = 0;
        $ordersCount = 0;
        $recentBooks = collect([]);
        $recentOrders = collect([]);
        $unreadMessages = 0;

        return view('dashboard.dashboard', compact(
            'user',
            'purchasedBooks',
            'favoriteBooks',
            'walletBalance',
            'ordersCount',
            'recentBooks',
            'recentOrders',
            'unreadMessages'
        ));
    }

    /**
     * نمایش صفحه کتاب‌های من
     */
    public function myBooks()
    {
        $user = Auth::user();
        $books = collect([]); // یک کالکشن خالی ایجاد می‌کنیم

        return view('dashboard.my_books', compact('books'));
    }

    /**
     * نمایش صفحه سفارشات من
     */
    public function myOrders()
    {
        $user = Auth::user();
        $orders = collect([])->paginate(10); // یک کالکشن خالی با صفحه‌بندی ایجاد می‌کنیم

        return view('dashboard.my_orders', compact('orders'));
    }

    /**
     * نمایش جزئیات یک سفارش
     */
    public function orderDetails($orderId)
    {
        $user = Auth::user();

        // فعلاً یک آبجکت خالی می‌سازیم
        $order = (object) [
            'id' => $orderId,
            'user_id' => $user->id,
            'status' => 'pending',
            'created_at' => now(),
            'books' => collect([])
        ];

        return view('dashboard.order_details', compact('order'));
    }

    /**
     * نمایش صفحه علاقه‌مندی‌ها
     */
    public function favorites()
    {
        $user = Auth::user();
        $favoriteBooks = collect([]); // یک کالکشن خالی

        return view('dashboard.favorites', compact('favoriteBooks'));
    }

    /**
     * نمایش و ویرایش اطلاعات حساب کاربری
     */
    public function accountInfo()
    {
        $user = Auth::user();

        return view('dashboard.account_info', compact('user'));
    }

    /**
     * به‌روزرسانی اطلاعات حساب کاربری
     */
    public function updateAccountInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'display_name' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'profile_image' => 'nullable|image|max:1024', // حداکثر 1MB
        ]);

        // آپلود تصویر پروفایل در صورت وجود
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->display_name = $validated['display_name'] ?? null;

        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $user->email = $validated['email'];
            $user->email_verified_at = null; // نیاز به تأیید مجدد ایمیل
            // ارسال ایمیل تأیید در اینجا
        }

        if (isset($validated['phone']) && $validated['phone'] !== $user->phone) {
            $user->phone = $validated['phone'];
            $user->phone_verified_at = null; // نیاز به تأیید مجدد تلفن
            // ارسال کد تأیید در اینجا
        }

        $user->save();

        return redirect()->route('dashboard.account-info')
            ->with('success', 'اطلاعات حساب کاربری با موفقیت به‌روزرسانی شد.');
    }

    /**
     * نمایش صفحه کیف پول
     */
    public function wallet()
    {
        $user = Auth::user();

        // مقادیر پیش‌فرض
        $wallet = (object) [
            'balance' => 0
        ];

        $transactions = collect([])->paginate(10);

        return view('dashboard.wallet', compact('wallet', 'transactions'));
    }

    /**
     * نمایش صفحه پیام‌ها
     */
    public function messages()
    {
        $user = Auth::user();
        $messages = collect([])->paginate(10);

        return view('dashboard.messages', compact('messages'));
    }

    /**
     * نمایش جزئیات یک پیام
     */
    public function messageDetails($messageId)
    {
        $user = Auth::user();

        $message = (object) [
            'id' => $messageId,
            'user_id' => $user->id,
            'subject' => 'عنوان پیام',
            'content' => 'محتوای پیام',
            'is_read' => false,
            'created_at' => now()
        ];

        return view('dashboard.message_details', compact('message'));
    }

    /**
     * تغییر رمز عبور
     */
    public function changePassword()
    {
        return view('dashboard.change_password');
    }

    /**
     * ذخیره رمز عبور جدید
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = bcrypt($validated['password']);
        $user->save();

        return redirect()->route('dashboard.change-password')
            ->with('success', 'رمز عبور با موفقیت تغییر یافت.');
    }

    /**
     * خروج از حساب کاربری
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
