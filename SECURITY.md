# Security Policy

## Hassas Dosyalar

`.env` ve `.env.*` dosyalari asla paylasilmamali ve Git'e eklenmemelidir. Repoda sadece gizli deger icermeyen `.env.example` dosyasi bulunmalidir.

Yedek dosyalari, SQL dump dosyalari, ZIP arsivleri, musteri dosyalari ve gercek upload dosyalari hassas veri icerebilir. Bu dosyalar commitlenmemeli ve public web kokunde tutulmamalidir.

## Kurulum Kilidi

Kurulum tamamlandiktan sonra uygulama:

```env
APP_INSTALLED=true
```

degerini yazar ve su lock dosyasini olusturur:

```text
storage/framework/argnest-installed.lock
```

Bu lock dosyasi `/install` sihirbazinin tekrar acilmasini engellemek icindir ve commitlenmemelidir.

## Backup Guvenligi

`storage/app/backups` altindaki dosyalar veritabani dump'i ve kullanici dosyalari icerebilir. Bu dizin Git tarafindan ignore edilir. Yedekleri sadece yetkili kisilerin erisebildigi private storage alanlarinda saklayin.

## Musteri Dosyalari

`storage/app/private` musteriye veya sisteme ait private dosyalar icin kullanilir. `storage/app/public` ise uygulamanin runtime uploadlari icindir. Bu alanlardaki gercek dosyalar repoya eklenmemelidir.

## Cloudflare ve Gercek IP

Cloudflare veya reverse proxy arkasinda calisirken uygulamanin gercek ziyaretci IP adresini dogru okuyabilmesi icin Laravel trusted proxy ayarlarini ve sunucu header yapilandirmasini kontrol edin. Aksi halde rate limit, guvenlik loglari ve admin aktivite kayitlari proxy IP adreslerini gosterebilir.

## Production Kontrol Listesi

- `APP_ENV=production`
- `APP_DEBUG=false`
- Guclu ve benzersiz `APP_KEY`
- Guvenli dosya izinleri
- HTTPS aktif
- Backup dizinleri public erisime kapali
- Admin hesaplarinda guclu parola
- Log ve aktivite kayitlari duzenli takip ediliyor
