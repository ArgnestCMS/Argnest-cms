<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('frontend.blog.index');
Route::get('/blog/{post:slug}', [FrontendController::class, 'blogDetail'])->name('frontend.blog.show');
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
    Route::post('/musteri/cikis', [FrontendController::class, 'customerLogout'])->name('frontend.customer.logout');
});
Route::get('/hizmet/{service:slug}', [FrontendController::class, 'serviceDetail'])->name('frontend.services.show');
Route::get('/urun/{product:slug}', [FrontendController::class, 'productDetail'])->name('frontend.products.show');
Route::get('/referans/{portfolio:slug}', [FrontendController::class, 'portfolioDetail'])->name('frontend.references.show');
Route::post('/teklif-al', [FrontendController::class, 'storeLead'])->name('frontend.leads.store');
