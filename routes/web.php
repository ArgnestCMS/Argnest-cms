<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('frontend.blog.index');
Route::get('/blog/{post:slug}', [FrontendController::class, 'blogDetail'])->name('frontend.blog.show');
Route::get('/musteri-yorumlari', [FrontendController::class, 'customerReviewsIndex'])->name('frontend.customer-reviews.index');
Route::get('/iletisim', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::get('/kvkk', [FrontendController::class, 'kvkk'])->name('frontend.legal.kvkk');
Route::get('/gizlilik-politikasi', [FrontendController::class, 'privacyPolicy'])->name('frontend.legal.privacy');
Route::get('/cerez-politikasi', [FrontendController::class, 'cookiePolicy'])->name('frontend.legal.cookies');
Route::get('/sitemap.xml', [FrontendController::class, 'sitemap'])->name('frontend.sitemap');
Route::get('/robots.txt', [FrontendController::class, 'robots'])->name('frontend.robots');
Route::middleware('guest')->group(function (): void {
    Route::get('/musteri/kayit', [FrontendController::class, 'customerRegister'])->name('frontend.customer.register');
    Route::post('/musteri/kayit', [FrontendController::class, 'storeCustomerRegister'])->name('frontend.customer.register.store');
    Route::get('/musteri/giris', [FrontendController::class, 'customerLogin'])->name('login');
    Route::post('/musteri/giris', [FrontendController::class, 'storeCustomerLogin'])->name('frontend.customer.login.store');
});
Route::middleware(['auth', 'customer'])->group(function (): void {
    Route::get('/musteri/panel', [FrontendController::class, 'customerDashboard'])->name('frontend.customer.dashboard');
    Route::get('/musteri/profil', [FrontendController::class, 'customerProfile'])->name('frontend.customer.profile');
    Route::post('/musteri/profil', [FrontendController::class, 'customerProfileUpdate'])->name('frontend.customer.profile.update');
    Route::get('/musteri/sifre-degistir', [FrontendController::class, 'customerPassword'])->name('frontend.customer.password');
    Route::post('/musteri/sifre-degistir', [FrontendController::class, 'customerPasswordUpdate'])->name('frontend.customer.password.update');
    Route::get('/musteri/guvenlik', [FrontendController::class, 'customerSecurity'])->name('frontend.customer.security');
    Route::get('/musteri/hizmetlerim', [FrontendController::class, 'customerServices'])->name('frontend.customer.services');
    Route::get('/musteri/hizmet-gecmisi', [FrontendController::class, 'customerServiceHistory'])->name('frontend.customer.service-history');
    Route::get('/musteri/dosyalarim', [FrontendController::class, 'customerFiles'])->name('frontend.customer.files.index');
    Route::get('/musteri/dosyalarim/{file}/indir', [FrontendController::class, 'downloadCustomerFile'])->name('frontend.customer.files.download');
    Route::get('/musteri/bildirimlerim', [FrontendController::class, 'customerNotifications'])->name('frontend.customer.notifications.index');
    Route::post('/musteri/bildirimlerim/{notification}/okundu', [FrontendController::class, 'markCustomerNotificationAsRead'])->name('frontend.customer.notifications.read');
    Route::post('/musteri/bildirimlerim/{notification}/ac', [FrontendController::class, 'openCustomerNotification'])->name('frontend.customer.notifications.open');
    Route::get('/musteri/aktivitelerim', [FrontendController::class, 'customerActivities'])->name('frontend.customer.activities.index');
    Route::get('/musteri/yorumlarim', [FrontendController::class, 'customerReviews'])->name('frontend.customer.reviews.index');
    Route::get('/musteri/yorumlarim/yeni', [FrontendController::class, 'customerReviewCreate'])->name('frontend.customer.reviews.create');
    Route::post('/musteri/yorumlarim/yeni', [FrontendController::class, 'storeCustomerReview'])->name('frontend.customer.reviews.store');
    Route::get('/musteri/destek', [FrontendController::class, 'customerSupportTickets'])->name('frontend.customer.support.index');
    Route::get('/musteri/destek/yeni', [FrontendController::class, 'customerSupportCreate'])->name('frontend.customer.support.create');
    Route::post('/musteri/destek/yeni', [FrontendController::class, 'storeCustomerSupportTicket'])->name('frontend.customer.support.store');
    Route::get('/musteri/destek/dosya/{attachment}', [FrontendController::class, 'downloadSupportAttachment'])->name('frontend.customer.support.attachments.download');
    Route::get('/musteri/destek/{ticket}', [FrontendController::class, 'showCustomerSupportTicket'])->name('frontend.customer.support.show');
    Route::post('/musteri/destek/{ticket}/cevap', [FrontendController::class, 'replyCustomerSupportTicket'])->name('frontend.customer.support.reply');
    Route::post('/musteri/cikis', [FrontendController::class, 'customerLogout'])->name('frontend.customer.logout');
});
Route::get('/hizmet/{service:slug}', [FrontendController::class, 'serviceDetail'])->name('frontend.services.show');
Route::get('/urun/{product:slug}', [FrontendController::class, 'productDetail'])->name('frontend.products.show');
Route::get('/referans/{portfolio:slug}', [FrontendController::class, 'portfolioDetail'])->name('frontend.references.show');
Route::post('/teklif-al', [FrontendController::class, 'storeLead'])->name('frontend.leads.store');
