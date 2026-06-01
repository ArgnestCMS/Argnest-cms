<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CustomerActivityLog;
use App\Models\CustomerFile;
use App\Models\CustomerNotification;
use App\Models\HeroButton;
use App\Models\CustomerReview;
use App\Models\Lead;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\CustomerActivityLogger;
use App\Services\SupportTicketMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'customerReviews' => CustomerReview::query()
                ->with('customer')
                ->approved()
                ->latest('approved_at')
                ->latest()
                ->take(3)
                ->get(),
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

    public function customerReviewsIndex(): View
    {
        $currentUser = Auth::user();
        $reviewButtonUrl = match (true) {
            $currentUser?->isCustomer() => url('/musteri/yorumlarim/yeni'),
            ($currentUser?->role ?? null) === User::ROLE_ADMIN => url('/admin'),
            default => url('/musteri/giris'),
        };

        return view('frontend.customer-reviews', [
            'settings' => SiteSetting::query()->first(),
            'reviews' => CustomerReview::query()
                ->with('user')
                ->where('status', CustomerReview::STATUS_APPROVED)
                ->latest('approved_at')
                ->latest()
                ->paginate(12),
            'reviewButtonUrl' => $reviewButtonUrl,
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
                'identity_number' => ['required', 'digits:11'],
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
                'identity_number' => $validated['identity_number'],
                'registration_ip' => $request->ip(),
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'password' => $validated['password'],
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        app(CustomerActivityLogger::class)->log(
            $user,
            CustomerActivityLog::ACTION_REGISTERED,
            'Musteri hesabi olusturuldu.',
            $request,
        );

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

        $request->user()->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        app(CustomerActivityLogger::class)->log(
            $request->user(),
            CustomerActivityLog::ACTION_LOGIN,
            'Musteri paneline giris yapti.',
            $request,
        );

        return redirect()->intended(route('frontend.customer.dashboard'));
    }

    public function customerLogout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            app(CustomerActivityLogger::class)->log(
                $user,
                CustomerActivityLog::ACTION_LOGOUT,
                'Musteri panelinden cikis yapti.',
                $request,
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function customerDashboard(Request $request): View
    {
        $customer = $request->user()->load('customerServices');
        $services = $customer->customerServices;
        $expiredServices = $services
            ->filter(fn ($service): bool => $service->is_active && $service->isExpired());
        $upcomingRenewals = $services
            ->filter(fn ($service): bool => $service->is_active && $service->isExpiringSoon())
            ->count();

        return view('frontend.customer.dashboard', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $customer,
            'services' => $services,
            'totalServices' => $services->count(),
            'activeServices' => $services->where('is_active', true)->count(),
            'upcomingRenewals' => $upcomingRenewals,
            'expiredServices' => $expiredServices,
            'expiredServicesCount' => $expiredServices->count(),
            'visibleFilesCount' => $customer->customerFiles()
                ->where('is_visible', true)
                ->count(),
            'unreadNotificationsCount' => $customer->customerNotifications()
                ->unread()
                ->count(),
            'openSupportTickets' => $customer->supportTickets()
                ->whereIn('status', [SupportTicket::STATUS_OPEN, SupportTicket::STATUS_ANSWERED, SupportTicket::STATUS_PENDING])
                ->count(),
        ]);
    }

    public function customerNotifications(Request $request): View
    {
        return view('frontend.customer.notifications', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'notifications' => $request->user()
                ->customerNotifications()
                ->latest()
                ->paginate(12),
        ]);
    }

    public function markCustomerNotificationAsRead(Request $request, CustomerNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 404);

        $notification->markAsRead();

        return back()->with('success', 'Bildirim okundu olarak isaretlendi.');
    }

    public function openCustomerNotification(Request $request, CustomerNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 404);

        $notification->markAsRead();

        return $notification->link
            ? redirect()->to($notification->link)
            : back()->with('success', 'Bildirim okundu olarak isaretlendi.');
    }

    public function customerServices(Request $request): View
    {
        app(CustomerActivityLogger::class)->logForRequest(
            CustomerActivityLog::ACTION_SERVICES_VIEWED,
            'Hizmetlerini goruntuledi.',
            $request,
        );

        return view('frontend.customer.services', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'services' => $request->user()
                ->customerServices()
                ->orderByDesc('is_active')
                ->orderByRaw('expiry_date is null')
                ->orderBy('expiry_date')
                ->get(),
        ]);
    }

    public function customerFiles(Request $request): View
    {
        app(CustomerActivityLogger::class)->logForRequest(
            CustomerActivityLog::ACTION_FILE_VIEWED,
            'Dosya merkezi goruntulendi.',
            $request,
        );

        return view('frontend.customer.files', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'files' => $request->user()
                ->customerFiles()
                ->where('is_visible', true)
                ->latest()
                ->paginate(12),
            'categoryOptions' => CustomerFile::categoryOptions(),
        ]);
    }

    public function downloadCustomerFile(Request $request, CustomerFile $file)
    {
        abort_unless($file->user_id === $request->user()->id, 404);
        abort_unless($file->is_visible, 404);
        abort_unless(Storage::disk('local')->exists($file->file_path), 404);

        app(CustomerActivityLogger::class)->log(
            $request->user(),
            CustomerActivityLog::ACTION_FILE_DOWNLOADED,
            'Dosya indirildi: ' . $file->title,
            $request,
        );

        return Storage::disk('local')->download($file->file_path, $file->file_name);
    }

    public function customerActivities(Request $request): View
    {
        return view('frontend.customer.activities.index', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'logs' => $request->user()
                ->customerActivityLogs()
                ->latest('created_at')
                ->paginate(15),
            'actionOptions' => CustomerActivityLog::actionOptions(),
        ]);
    }

    public function customerReviews(Request $request): View
    {
        return view('frontend.customer.reviews.index', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'reviews' => $request->user()
                ->customerReviews()
                ->latest()
                ->paginate(10),
            'statusOptions' => CustomerReview::statusOptions(),
        ]);
    }

    public function customerReviewCreate(Request $request): View
    {
        return view('frontend.customer.reviews.create', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
        ]);
    }

    public function storeCustomerReview(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10'],
            'hide_name' => ['nullable', 'boolean'],
            'hide_contact' => ['nullable', 'boolean'],
        ]);

        $review = $request->user()->customerReviews()->create([
            'rating' => $validated['rating'] ?? null,
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'hide_name' => $request->boolean('hide_name'),
            'hide_contact' => $request->boolean('hide_contact', true),
            'status' => CustomerReview::STATUS_PENDING,
        ]);

        app(CustomerActivityLogger::class)->log(
            $request->user(),
            CustomerActivityLog::ACTION_REVIEW_SUBMITTED,
            'Yorum gonderdi: ' . ($review->title ?: 'Basliksiz yorum'),
            $request,
        );

        return redirect()
            ->route('frontend.customer.reviews.index')
            ->with('success', 'Yorumunuz alindi. Admin onayindan sonra sitede yayinlanabilir.');
    }

    public function customerSupportTickets(Request $request): View
    {
        return view('frontend.customer.support.index', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'tickets' => $request->user()
                ->supportTickets()
                ->withCount('messages')
                ->latest()
                ->paginate(10),
            'statusOptions' => SupportTicket::statusOptions(),
            'priorityOptions' => SupportTicket::priorityOptions(),
        ]);
    }

    public function customerSupportCreate(Request $request): View
    {
        return view('frontend.customer.support.create', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'priorityOptions' => SupportTicket::priorityOptions(),
        ]);
    }

    public function storeCustomerSupportTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->supportTicketValidationRules());

        $ticket = $request->user()->supportTickets()->create([
            'subject' => $validated['subject'],
            'category' => $validated['category'] ?? null,
            'status' => SupportTicket::STATUS_OPEN,
            'priority' => $validated['priority'] ?? SupportTicket::PRIORITY_NORMAL,
        ]);

        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'is_admin' => false,
            'message' => $validated['message'],
            'created_at' => now(),
        ]);

        $attachmentCount = $this->storeSupportAttachments($request, $message);

        app(CustomerActivityLogger::class)->log(
            $request->user(),
            CustomerActivityLog::ACTION_SUPPORT_TICKET_CREATED,
            'Destek talebi olusturdu: ' . $ticket->ticket_no,
            $request,
        );

        if ($attachmentCount > 0) {
            app(CustomerActivityLogger::class)->log(
                $request->user(),
                CustomerActivityLog::ACTION_FILE_UPLOADED,
                $attachmentCount . ' dosya yukledi: ' . $ticket->ticket_no,
                $request,
            );
        }

        app(SupportTicketMailService::class)->ticketCreated($ticket, $message);

        return redirect()
            ->route('frontend.customer.support.show', $ticket)
            ->with('success', 'Destek biletiniz olusturuldu. Ekibimiz en kisa surede yanitlayacak.');
    }

    public function showCustomerSupportTicket(Request $request, SupportTicket $ticket): View
    {
        abort_unless($ticket->user_id === $request->user()->id, 404);

        return view('frontend.customer.support.show', [
            'settings' => SiteSetting::query()->first(),
            'customer' => $request->user(),
            'ticket' => $ticket->load(['customer', 'messages.user', 'messages.attachments']),
            'statusOptions' => SupportTicket::statusOptions(),
            'priorityOptions' => SupportTicket::priorityOptions(),
        ]);
    }

    public function replyCustomerSupportTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp,zip,rar', 'max:20480'],
        ]);

        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'is_admin' => false,
            'message' => $validated['message'],
            'created_at' => now(),
        ]);

        $attachmentCount = $this->storeSupportAttachments($request, $message);

        if ($ticket->status !== SupportTicket::STATUS_CLOSED) {
            $ticket->forceFill(['status' => SupportTicket::STATUS_PENDING])->save();
        }

        app(CustomerActivityLogger::class)->log(
            $request->user(),
            CustomerActivityLog::ACTION_SUPPORT_TICKET_REPLIED,
            'Destek talebine cevap yazdi: ' . $ticket->ticket_no,
            $request,
        );

        if ($attachmentCount > 0) {
            app(CustomerActivityLogger::class)->log(
                $request->user(),
                CustomerActivityLog::ACTION_FILE_UPLOADED,
                $attachmentCount . ' dosya yukledi: ' . $ticket->ticket_no,
                $request,
            );
        }

        app(SupportTicketMailService::class)->customerReplied($ticket, $message);

        return back()->with('success', 'Cevabiniz destek biletine eklendi.');
    }

    public function downloadSupportAttachment(Request $request, SupportAttachment $attachment)
    {
        $attachment->load('message.ticket');

        abort_unless($attachment->message?->ticket?->user_id === $request->user()->id, 404);
        abort_unless(Storage::disk('local')->exists($attachment->file_path), 404);

        return Storage::disk('local')->download($attachment->file_path, $attachment->original_name);
    }

    private function supportTicketValidationRules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', 'in:' . implode(',', array_keys(SupportTicket::priorityOptions()))],
            'message' => ['required', 'string'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp,zip,rar', 'max:20480'],
        ];
    }

    private function storeSupportAttachments(Request $request, SupportMessage $message): int
    {
        $storedCount = 0;

        foreach ($request->file('attachments', []) as $file) {
            $path = $file->store('support', 'local');

            $message->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize() ?: 0,
                'mime_type' => $file->getClientMimeType(),
                'created_at' => now(),
            ]);

            $storedCount++;
        }

        return $storedCount;
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
