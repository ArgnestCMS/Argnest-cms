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
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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

    public function productDetail(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('frontend.product-detail', [
            'settings' => SiteSetting::query()->first(),
            'product' => $product,
            'relatedProducts' => Product::query()
                ->where('is_active', true)
                ->whereKeyNot($product->getKey())
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
            'productStatusOptions' => Product::statusOptions(),
        ]);
    }

    public function portfolioDetail(Portfolio $portfolio): View
    {
        abort_unless($portfolio->is_active, 404);

        return view('frontend.reference-detail', [
            'settings' => SiteSetting::query()->first(),
            'portfolio' => $portfolio,
            'relatedPortfolios' => Portfolio::query()
                ->where('is_active', true)
                ->whereKeyNot($portfolio->getKey())
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
        ]);
    }

    public function blogIndex(): View
    {
        return view('frontend.blog', [
            'settings' => SiteSetting::query()->first(),
            'blogPosts' => BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at')
                ->orderBy('sort_order')
                ->paginate(9),
            'featuredPosts' => BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->where('is_featured', true)
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function blogDetail(BlogPost $post): View
    {
        abort_unless($post->is_active, 404);

        return view('frontend.blog-detail', [
            'settings' => SiteSetting::query()->first(),
            'post' => $post->load('category'),
            'latestPosts' => BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->whereKeyNot($post->getKey())
                ->orderByDesc('published_at')
                ->take(4)
                ->get(),
            'relatedPosts' => BlogPost::query()
                ->with('category')
                ->where('is_active', true)
                ->whereKeyNot($post->getKey())
                ->when($post->blog_category_id, fn ($query) => $query->where('blog_category_id', $post->blog_category_id))
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at')
                ->take(4)
                ->get(),
        ]);
    }

    public function contact(): View
    {
        return view('frontend.contact', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function kvkk(): View
    {
        return view('frontend.kvkk', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function privacyPolicy(): View
    {
        return view('frontend.privacy-policy', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function cookiePolicy(): View
    {
        return view('frontend.cookie-policy', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function sitemap()
    {
        return response()
            ->view('frontend.sitemap', [
                'staticUrls' => [
                    ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
                    ['url' => route('frontend.blog.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
                    ['url' => route('frontend.contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
                    ['url' => route('frontend.legal.kvkk'), 'priority' => '0.4', 'changefreq' => 'yearly'],
                    ['url' => route('frontend.legal.privacy'), 'priority' => '0.4', 'changefreq' => 'yearly'],
                    ['url' => route('frontend.legal.cookies'), 'priority' => '0.4', 'changefreq' => 'yearly'],
                ],
                'services' => Service::query()->where('is_active', true)->get(),
                'products' => Product::query()->where('is_active', true)->get(),
                'portfolios' => Portfolio::query()->where('is_active', true)->get(),
                'blogPosts' => BlogPost::query()->where('is_active', true)->get(),
            ])
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        return response(
            "User-agent: *\n" .
            "Disallow:\n" .
            'Sitemap: ' . route('frontend.sitemap') . "\n",
            200,
            ['Content-Type' => 'text/plain'],
        );
    }

    public function customerRegister(): View
    {
        return view('frontend.customer.register', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function storeCustomerRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'company_name' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'kvkk_accepted' => ['accepted'],
            ],
            [
                'name.required' => 'Ad soyad alanı zorunludur.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'email.unique' => 'Bu e-posta adresi ile kayıtlı bir hesap var.',
                'password.required' => 'Şifre alanı zorunludur.',
                'password.min' => 'Şifre en az 8 karakter olmalıdır.',
                'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
                'kvkk_accepted.accepted' => 'Devam etmek için KVKK onayını kabul etmelisiniz.',
            ],
        );

        $user = User::query()->create([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('frontend.customer.dashboard');
    }

    public function customerLogin(): View
    {
        return view('frontend.customer.login', [
            'settings' => SiteSetting::query()->first(),
        ]);
    }

    public function storeCustomerLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        $request->session()->regenerate();

        if (! $request->user()?->isCustomer()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Bu giriş alanı yalnızca müşteri hesapları içindir.',
            ]);
        }

        return redirect()->intended(route('frontend.customer.dashboard'));
    }

    public function customerLogout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function customerDashboard(Request $request): View
    {
        return view('frontend.customer.dashboard', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
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
