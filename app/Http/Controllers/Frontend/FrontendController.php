<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\HeroButton;
use App\Models\Lead;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function home(): View
    {
        $settings = SiteSetting::query()->first();
        $portfolios = Portfolio::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        if ($portfolios->isEmpty()) {
            $portfolios = Portfolio::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(3)
                ->get();
        }

        return view('frontend.home', [
            'settings' => $settings,
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(6)
                ->get(),
            'products' => Product::query()
                ->where('is_active', true)
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->take(4)
                ->get(),
            'portfolios' => $portfolios,
            'blogPosts' => BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
            'heroButtons' => HeroButton::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'stats' => [
                [
                    'label' => 'Tamamlanan Proje',
                    'value' => Portfolio::query()->where('is_active', true)->count(),
                ],
                [
                    'label' => 'Aktif Hizmet',
                    'value' => Service::query()->where('is_active', true)->count(),
                ],
                [
                    'label' => 'Müşteri Talebi',
                    'value' => Lead::query()->count(),
                ],
                [
                    'label' => 'Blog İçeriği',
                    'value' => BlogPost::query()->where('is_active', true)->count(),
                ],
            ],
        ]);
    }

    public function serviceDetail(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('frontend.service-detail', [
            'settings' => SiteSetting::query()->first(),
            'service' => $service,
            'relatedServices' => Service::query()
                ->where('is_active', true)
                ->whereKeyNot($service->getKey())
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
        ]);
    }

    public function storeLead(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'company' => ['nullable', 'string', 'max:255'],
                'service_type' => ['nullable', 'string', 'max:255'],
                'budget_range' => ['nullable', 'string', 'max:255'],
                'message' => ['required', 'string'],
            ],
            [
                'name.required' => 'Ad Soyad alanı zorunludur.',
                'name.max' => 'Ad Soyad en fazla 255 karakter olabilir.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'email.max' => 'E-posta en fazla 255 karakter olabilir.',
                'phone.max' => 'Telefon en fazla 255 karakter olabilir.',
                'company.max' => 'Firma en fazla 255 karakter olabilir.',
                'service_type.max' => 'Hizmet Türü en fazla 255 karakter olabilir.',
                'budget_range.max' => 'Bütçe Aralığı en fazla 255 karakter olabilir.',
                'message.required' => 'Mesaj alanı zorunludur.',
            ],
            [
                'name' => 'Ad Soyad',
                'phone' => 'Telefon',
                'email' => 'E-posta',
                'company' => 'Firma',
                'service_type' => 'Hizmet Türü',
                'budget_range' => 'Bütçe Aralığı',
                'message' => 'Mesaj',
            ],
        );

        Lead::query()->create([
            ...$validated,
            'status' => Lead::STATUS_NEW,
            'source' => 'website',
        ]);

        return back()
            ->withInput([])
            ->with('success', 'Talebiniz başarıyla alındı. En kısa sürede sizinle iletişime geçeceğiz.');
    }
}
