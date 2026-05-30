<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/hizmet/{service:slug}', [FrontendController::class, 'serviceDetail'])->name('frontend.services.show');
Route::get('/urun/{product:slug}', [FrontendController::class, 'productDetail'])->name('frontend.products.show');
Route::post('/teklif-al', [FrontendController::class, 'storeLead'])->name('frontend.leads.store');
