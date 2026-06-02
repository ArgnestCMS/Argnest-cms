# Argnest

Argnest; kurumsal web sitesi, musteri paneli, destek talepleri, canli destek, yedekleme ve rol/yetki yonetimini tek Laravel uygulamasinda birlestiren bir yonetim paneli projesidir. Filament tabanli admin paneli ile icerik, musteri, destek ve sistem operasyonlari yonetilebilir.

## Ozellikler

- Kurumsal ana sayfa, hizmet, urun, referans ve blog sayfalari
- Filament admin paneli
- Musteri kayit, giris ve profil yonetimi
- Musteri hizmetleri, dosyalari, bildirimleri, adresleri ve aktivite kayitlari
- Destek talebi sistemi ve destek eki indirme
- Canli destek oturumlari ve admin yanit paneli
- Rol/yetki sistemi
- Site, mail ve guvenlik ayarlari
- Tam ZIP yedekleme, SQL yedekleme ve restore akislari
- `/install` kurulum sihirbazi

## Kurulum

Detayli adimlar icin [INSTALLATION.md](INSTALLATION.md) dosyasina bakin.

Kisa kurulum:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
npm run build
php artisan serve
```

Ardindan tarayicida `/install` adresine giderek kurulumu tamamlayin.

## /install Sihirbazi

Kurulum sihirbazi uc kurulum tipini destekler:

- Temiz kurulum: Veritabani migration ve temel seed islemleri calisir, site ve admin kullanicisi olusturulur.
- Full ZIP restore: `full-backup-...zip` formatindaki tam yedek yuklenir. ZIP icinde `database.sql` beklenir ve dosyalar ilgili storage/public alanlarina geri yuklenir.
- SQL restore: SQL dosyasi veritabanina aktarilir.

Kurulum tamamlandiginda uygulama `APP_INSTALLED=true` degerini yazar ve `storage/framework/argnest-installed.lock` dosyasini olusturur. Bu lock dosyasi commitlenmemelidir.

## Temiz Kurulum

Temiz kurulumda veritabani baglantisi girilir, site bilgileri ve ilk admin kullanicisi olusturulur. Kurulum sonunda admin kullanicisi yonetici rolune ve sistem yetkilerine baglanir.

## Full ZIP Restore

Full ZIP restore, sistem yedegi ile yeni ortama gecis icindir. Yedek dosyasi `full-backup-` ile baslamali ve `database.sql` icermelidir. Restore oncesinde uygulama guvenlik amaciyla mevcut durumun pre-restore yedegini almaya calisir.

## SQL Restore

SQL restore sadece veritabani geri yukleme icindir. Dosya yukleme alanlari, musteri dosyalari ve public uploadlar bu islemle geri gelmez; gerekirse tam ZIP yedek tercih edilmelidir.

## Admin Panel

Admin paneli Filament ile calisir. Icerik, musteri, destek, live chat, roller, yetkiler, site ayarlari, mail ayarlari, aktivite loglari ve sistem yedekleri admin panelinden yonetilir.

## Musteri Paneli

Musteriler kayit olabilir, giris yapabilir, profil ve sifre bilgilerini guncelleyebilir, hizmetlerini, dosyalarini, bildirimlerini, aktivitelerini, adreslerini, yorumlarini ve destek taleplerini takip edebilir.

## Canli Destek

Canli destek akisi frontend uzerinden baslatilir. Admin tarafinda aktif oturumlar goruntulenebilir, yanitlanabilir ve kapatilabilir. Uretim ortaminda throttle, rate limit ve log takibi aktif tutulmalidir.

## Yedekleme

Sistem yedekleri `storage/app/backups` altinda tutulur. Bu dosyalar hassas veriler, musteri dosyalari ve veritabani dump'i icerebilir; Git'e eklenmemelidir. Full yedek dosyalari varsayilan olarak `full-backup-YYYY-MM-DD-HH-mm.zip` formatindadir.

## Rol/Yetki Sistemi

Admin kullanicilari rol ve izinlerle sinirlandirilabilir. Sistem; musteri, destek, dosya, bildirim, mail ayari, site ayari, yedek, guvenlik loglari, admin, rol ve canli destek yonetimi icin ayri yetkiler icerir.

## Guvenlik Notlari

- `.env` ve `.env.*` dosyalari commitlenmemelidir. Sadece `.env.example` repoda tutulur.
- `storage/framework/argnest-installed.lock` commitlenmemelidir.
- `storage/app/backups`, `storage/app/private` ve `storage/app/public` altindaki gercek dosyalar commitlenmemelidir.
- SQL, ZIP ve database backup dosyalari repoya eklenmemelidir.
- Production ortaminda `APP_ENV=production`, `APP_DEBUG=false` ve guclu bir `APP_KEY` kullanin.
- Cloudflare veya reverse proxy arkasinda gercek IP icin trusted proxy ayarlarini dogru yapin.

## Build Notu

`public/build` ignore edilmistir. Bu projede Vite build ciktisinin deployment veya CI/CD asamasinda `npm run build` ile uretilmesi onerilir.

## Lisans

Bu proje MIT lisansi ile lisanslanmistir. Detaylar icin [LICENSE](LICENSE) dosyasina bakin.
