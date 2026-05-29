<?php

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::post('/teklif-al', [FrontendController::class, 'storeLead'])->name('frontend.leads.store');
