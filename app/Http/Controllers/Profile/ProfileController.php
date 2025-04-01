<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * نمایش صفحه اصلی داشبورد کاربر
     */
    public function index()
    {
        $user = Auth::user();

        // مقادیر پیش‌فرض را تنظیم می‌کنیم تا اگر مدل‌ها هنوز ایجاد نشده‌اند، خطا رخ ندهد
        $purchasedBooks = Book::where('user_id', $user->id)->count();
        $favoriteBooks = Book::where('user_id', $user->id)->where('is_favorite', true)->count();
        $walletBalance = $user->wallet_balance ?? 0;
        $ordersCount = Order::where('user_id', $user->id)->count();
        $recentBooks = Book::where('user_id', $user->id)->latest()->take(5)->get();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $unreadMessages = 0; // فرض کنید پیام‌ها در سیستم دیگری مدیریت می‌شوند

        return view('profile.profile', compact(
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
     * نمایش پروفایل عمومی کاربر با نام کاربری
     */
    public function showPublicProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // دریافت داده‌های عمومی پروفایل
        $favoriteBooks = Book::where('user_id', $user->id)->where('is_favorite', true)->count();
        $recentBooks = Book::where('user_id', $user->id)->latest()->take(4)->get();

        return view('profile.public_profile', compact(
            'user',
            'favoriteBooks',
            'recentBooks'
        ));
    }
    /**
     * نمایش صفحه کتاب‌های من
     */
    public function myBooks()
    {
        $user = Auth::user();

        // واکشی کتاب‌های مرتبط با کاربر با صفحه‌بندی
        $books = Book::where('user_id', $user->id)->paginate(10);

        // اضافه کردن متغیر $orders برای استفاده در ویو
        $orders = Order::where('user_id', $user->id)->get();

        return view('profile.my_books', compact('books', 'orders'));
    }

    /**
     * نمایش صفحه سفارشات من
     */
    public function myOrders()
    {
        $user = Auth::user();

        // واکشی سفارش‌های مرتبط با کاربر با صفحه‌بندی
        $orders = Order::where('user_id', $user->id)->paginate(10);

        return view('profile.my_orders', compact('orders'));
    }

    /**
     * نمایش جزئیات یک سفارش
     */
    public function orderDetails($orderId)
    {
        $user = Auth::user();

        // واکشی جزئیات سفارش
        $order = Order::where('id', $orderId)->where('user_id', $user->id)->firstOrFail();

        return view('profile.order_details', compact('order'));
    }

    /**
     * نمایش صفحه علاقه‌مندی‌ها
     */
    public function favorites()
    {
        $user = Auth::user();

        // واکشی کتاب‌های مورد علاقه کاربر
        $favoriteBooks = Book::where('user_id', $user->id)->where('is_favorite', true)->paginate(10);

        return view('profile.favorites', compact('favoriteBooks'));
    }

    /**
     * نمایش صفحه کیف پول
     */
    public function wallet()
    {
        $user = Auth::user();

        // مقادیر پیش‌فرض
        $wallet = (object) [
            'balance' => $user->wallet_balance ?? 0
        ];

        $transactions = collect([])->paginate(10);

        return view('profile.wallet', compact('wallet', 'transactions'));
    }

    /**
     * نمایش صفحه پیام‌ها
     */
    public function messages()
    {
        $user = Auth::user();
        $messages = collect([])->paginate(10);

        return view('profile.messages', compact('messages'));
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

        return view('profile.message_details', compact('message'));
    }

    /**
     * تغییر رمز عبور
     */
    public function changePassword()
    {
        return view('profile.change_password');
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

        return redirect()->route('profile.change-password')
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
