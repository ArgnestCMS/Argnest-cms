<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('frontend.blog.index');
Route::get('/blog/{post:slug}', [FrontendController::class, 'blogDetail'])->name('frontend.blog.show');
Route::get('/iletisim', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::get('/hizmet/{service:slug}', [FrontendController::class, 'serviceDetail'])->name('frontend.services.show');
Route::get('/urun/{product:slug}', [FrontendController::class, 'productDetail'])->name('frontend.products.show');
Route::get('/referans/{portfolio:slug}', [FrontendController::class, 'portfolioDetail'])->name('frontend.references.show');
Route::post('/teklif-al', [FrontendController::class, 'storeLead'])->name('frontend.leads.store');
