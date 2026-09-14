# Blade + CSS biasa — Baitul Digital

Salin folder `resources` ke project Laravel `MasjidAnnur`.

Salin isi `routes-web.php` ke `routes/web.php` (atau sesuaikan route yang sudah ada).

File:
- `resources/views/welcome.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/css/masjid.css`
- `resources/js/masjid.js`

Karena halaman memakai `asset('css/masjid.css')` dan `asset('js/masjid.js')`, jalankan:
`php artisan` tidak otomatis menyalin resources. Untuk paling mudah, buat:
`public/css/masjid.css` dari `resources/css/masjid.css`
dan
`public/js/masjid.js` dari `resources/js/masjid.js`.
