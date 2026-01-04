# Cara Mendapatkan Sanctum Token

## Kredensial untuk Testing

Gunakan kredensial berikut untuk login:

### Admin Account
- **Email**: `admin@example.com`
- **Password**: `admin`

### Superadmin Account
- **Email**: `superadmin@example.com`  
- **Password**: (cek password di database atau gunakan admin account)

### User Accounts
- **Email**: `djoelfikri34@gmail.com` (amel)
- **Password**: (cek di database)

## Cara Mendapatkan Token

### Opsi  1: Via API Login Endpoint (RECOMMENDED)

**⚠️ Note**: Endpoint ini mungkin error karena issue dengan ULID dan Sanctum. Jika error, gunakan Opsi 2.

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"admin"}'
```

### Opsi 2: Via Laravel Tinker (TIDAK BISA KARENA UUID ISSUE)

Karena ada issue dengan UUID/ULID di PostgreSQL saat create token, metode ini tidak bisa dipakai.

### Opsi 3: Generate Token Manual (SOLUSI SEMENTARA)

Untuk testing Swagger, Anda bisa menggunakan **session authentication** yang sudah ada di Laravel 11 + Jetstream.

#### Langkah-langkah:

1. **Login via Web Browser**
   - Buka `http://localhost:8000/login`
   - Login dengan email: `admin@example.com`, password: `admin`

2. **Get CSRF Token dan Session Cookie**
   - Setelah login, buka browser DevTools
   - Copy cookie `laravel_session`

3. **Testing di Swagger**
   - Buka Swagger UI di `http://localhost:8000/api/documentation`
   - Untuk endpoints yang butuh auth, gunakan session yang sama

## Alternatif: Testing Tanpa Token

Untuk testing cepat tanpa authentication, Anda bisa:

1. Tambahkan route testing tanpa middleware auth
2. Atau disable middleware auth sementara untuk development

## Solusi Permanent: Fix UUID Issue

Issue terjadi karena `personal_access_tokens` table menggunakan UUID tapi Laravel mencoba insert ULID. Perbaikan:

1. **Cek Migration Table**
```bash
php artisan tinker --execute="echo \Schema::getColumnType('personal_access_tokens', 'tokenable_id');"
```

2. **Jika Perlu, Update Migration**
- Ubah column type dari `uuid` ke `string` atau `ulid`

3. **Atau Install Package UUID**
```bash
composer require ramsey/uuid
```

## Testing di Browser

Cara paling mudah untuk testing Swagger adalah:

1. Login dulu via web browser
2. Kemudian buka Swagger UI di tab yang sama
3. Session cookie akan otomatis terpakai
