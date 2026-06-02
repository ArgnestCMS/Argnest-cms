# Installation

Bu dokuman Argnest projesini lokal veya production ortamina kurmak icin temel adimlari icerir.

## Gereksinimler

- PHP 8.2 veya uzeri
- Composer
- Node.js ve npm
- MySQL/MariaDB veya desteklenen Laravel veritabani
- PHP eklentileri: PDO, OpenSSL, Fileinfo, ZIP

## Lokal Kurulum

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan serve
```

Frontend varliklarini gelistirme modunda calistirmak icin:

```bash
npm run dev
```

Production build almak icin:

```bash
npm run build
```

## .env Ayarlari

`.env.example` dosyasini `.env` olarak kopyalayin ve asagidaki alanlari ortaminiza gore duzenleyin:

```env
APP_NAME=Argnest
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_INSTALLED=false
APP_TIMEZONE=Europe/Istanbul

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=argnest
DB_USERNAME=root
DB_PASSWORD=
```

`php artisan key:generate` komutu `APP_KEY` degerini olusturur.

## /install ile Kurulum

Sunucuyu baslattiktan sonra:

```text
http://localhost:8000/install
```

Kurulum sihirbazinda kurulum tipini secin:

- Temiz kurulum
- Full ZIP restore
- SQL restore

Kurulum tamamlandiginda uygulama `APP_INSTALLED=true` degerini yazar ve `storage/framework/argnest-installed.lock` dosyasini olusturur.

## Storage Link

Public storage dosyalari icin su komut gereklidir:

```bash
php artisan storage:link
```

Production ortaminda web sunucusunun `storage` ve `bootstrap/cache` dizinlerine yazma izni olmalidir.

# Önerilen Canlı Sunucu Kurulumu

Argnest CMS için önerilen yapı subdomain kurulumudur.

Örnek:

Domain:

```text
crm.siteadi.com
```

Dosya Yapısı:

```text
/home/kullanici/web/crm.siteadi.com/
├── private/
│   └── argnest-cms/
│       ├── app
│       ├── bootstrap
│       ├── config
│       ├── database
│       ├── public
│       ├── resources
│       ├── routes
│       ├── storage
│       ├── vendor
│       └── .env
│
└── public_html -> private/argnest-cms/public
```

Kurulum Adımları:

1. Subdomain oluştur

   Örnek:

   ```text
   crm.siteadi.com
   ```

2. SSL aktif et

3. Projeyi şu klasöre kur:

   ```text
   private/argnest-cms
   ```

4. `public_html` klasörünü Laravel `public` klasörüne yönlendir:

   ```bash
   ln -s /home/kullanici/web/crm.siteadi.com/private/argnest-cms/public public_html
   ```

5. `.env` düzenle:

   ```env
   APP_URL=https://crm.siteadi.com
   ```

6. Kurulum sonrası çalıştır:

   ```bash
   php artisan storage:link
   php artisan optimize
   ```

7. Tarayıcıdan aç:

   ```text
   https://crm.siteadi.com/install
   ```

Notlar:

- Alt klasör (`/crm`) kurulumu teorik olarak mümkündür ancak Filament, session ve cookie yapıları nedeniyle önerilmez.
- Subdomain kurulumu önerilir.
- Kurulum tamamlandıktan sonra `APP_INSTALLED=true` ve lock dosyası otomatik oluşturulur.
- `storage:link` komutu mutlaka çalıştırılmalıdır, aksi halde medya dosyaları görüntülenmeyebilir.

## Production Notlari

- `APP_ENV=production` kullanin.
- `APP_DEBUG=false` yapin.
- `APP_URL` degerini gercek domain ile ayarlayin.
- `composer install --no-dev --optimize-autoloader` ile bagimliliklari kurun.
- `npm ci` ve `npm run build` ile frontend build alin.
- `php artisan optimize:clear` ve ardindan gerekirse `php artisan optimize` calistirin.
- Queue kullaniliyorsa worker/supervisor kurulumu yapin.
- Cron icin Laravel scheduler'i ekleyin.
- `.env`, backup, SQL, ZIP ve musteri dosyalarini public dizin disinda tutun.
