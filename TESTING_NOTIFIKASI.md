# Panduan Testing Notifikasi FCM via Postman

## Checklist Sebelum Testing

### 1. Setup Laravel (.env)
Pastikan di file `.env` Laravel kamu ada:
```env
FCM_SERVER_KEY=ISI_SERVER_KEY_DARI_FIREBASE
```

**Cara dapat Server Key:**
1. Buka Firebase Console: https://console.firebase.google.com
2. Pilih project `servify-5eba8`
3. Settings (⚙️) → Project Settings
4. Tab "Cloud Messaging"
5. Copy "Server key" (bukan Sender ID)

### 2. Pastikan FCM Token Tersimpan
1. Buka aplikasi Flutter di HP
2. Login dengan akun yang akan di-test
3. Biarkan app terbuka minimal 5 detik (FCM token akan otomatis dikirim)
4. Cek di database: tabel `users`, kolom `fcm_token` harus ada isinya

**Cek via API:**
```
GET http://192.168.100.7:8000/api/user
Headers: Authorization: Bearer TOKEN_DARI_LOGIN
```

Response harus ada `fcm_token` (bukan null/empty).

### 3. Pastikan Permission Notifikasi di HP
- Settings → Apps → Servify → Notifications → **Enabled**

---

## Langkah Testing

### Step 1: Login via Postman

**Request:**
```
POST http://192.168.100.7:8000/api/login
Content-Type: application/json

Body:
{
  "email": "email_anda@example.com",
  "password": "password_anda"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "token": "1|xxxxxxxxxxxxx...",
    ...
  }
}
```

**Copy token ini!**

---

### Step 2: Cek FCM Token User

**Request:**
```
GET http://192.168.100.7:8000/api/user
Authorization: Bearer TOKEN_DARI_STEP_1
```

**Response harus ada:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "...",
    "fcm_token": "dxxxxxxxxxxxxx..."  ← HARUS ADA INI!
  }
}
```

**Jika `fcm_token` null/empty:**
- Buka app Flutter
- Login lagi
- Biarkan app terbuka 10 detik
- Cek lagi via API

---

### Step 3: Test Notifikasi

**Request:**
```
POST http://192.168.100.7:8000/api/notifications/test
Authorization: Bearer TOKEN_DARI_STEP_1
Content-Type: application/json

Body (opsional):
{
  "title": "🧪 Test Notifikasi",
  "body": "Ini adalah notifikasi test dari Postman!",
  "type": "system"
}
```

**Response yang benar:**
```json
{
  "success": true,
  "message": "Test notification sent! Cek log Laravel untuk detail.",
  "data": {
    "user_id": 1,
    "fcm_token_exists": true,
    "fcm_token_preview": "dxxxxxxxxxxxxx...",
    "server_key_exists": true,
    ...
  }
}
```

**Jika response error:**
- `fcm_token_exists: false` → User belum punya FCM token, buka app Flutter dan login
- `server_key_exists: false` → FCM_SERVER_KEY belum di-set di .env

---

### Step 4: Cek Log Laravel

Setelah kirim notifikasi, cek log:
```bash
tail -f storage/logs/laravel.log
```

**Log yang benar:**
```
[2024-xx-xx] local.INFO: FCM notification sent successfully {"fcm_token":"dxxxxx...","title":"🧪 Test Notifikasi"}
```

**Log error:**
```
[2024-xx-xx] local.ERROR: FCM push failed {"status":401,"response":"...","fcm_token":"dxxxxx..."}
```

**Jika error 401:**
- FCM_SERVER_KEY salah atau expired
- Cek lagi di Firebase Console

**Jika error 404:**
- FCM token tidak valid (user mungkin uninstall/reinstall app)
- User harus login lagi di app Flutter

---

## Testing Scenarios

### Test 1: App Ditutup (Killed) ✅
1. Tutup aplikasi sepenuhnya (swipe dari recent apps)
2. Pastikan app benar-benar closed
3. Kirim request dari Postman
4. **Notifikasi HARUS muncul di notification bar HP**

### Test 2: App di Background ✅
1. Minimize aplikasi (jangan tutup)
2. Kirim request dari Postman
3. **Notifikasi HARUS muncul di notification bar HP**

### Test 3: App Terbuka (Foreground) ✅
1. Buka aplikasi
2. Kirim request dari Postman
3. **Notifikasi HARUS muncul di notification bar HP** (bukan hanya di dalam app)

---

## Troubleshooting

### Notifikasi tidak muncul sama sekali

1. **Cek FCM Token:**
   ```sql
   SELECT id, name, email, fcm_token FROM users WHERE email = 'email_anda@example.com';
   ```
   - Jika `fcm_token` NULL → Buka app Flutter, login, tunggu 10 detik

2. **Cek FCM_SERVER_KEY:**
   ```bash
   php artisan tinker
   >>> config('services.firebase.fcm_server_key')
   ```
   - Harus return string (bukan null)

3. **Cek Log Laravel:**
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```
   - Cari error terkait FCM

4. **Cek Permission di HP:**
   - Settings → Apps → Servify → Notifications → **Enabled**

5. **Test FCM Token Manual:**
   - Buka: https://console.firebase.google.com/project/servify-5eba8/settings/cloudmessaging
   - Scroll ke bawah → "Send test message"
   - Masukkan FCM token dari database
   - Send → Jika muncul notifikasi, berarti FCM token valid

### Notifikasi muncul tapi tidak di notification bar

- Pastikan app sudah di-rebuild setelah tambah `flutter_local_notifications`
- Cek apakah permission notifikasi sudah diizinkan
- Cek log Flutter untuk error

---

## Endpoint Test yang Tersedia

1. **Test Notifikasi Sederhana:**
   ```
   POST /api/notifications/test
   ```

2. **Test Notifikasi Pesanan:**
   ```
   POST /api/notifications/test-job
   Body: {"job_title": "Bersihkan Rumah"}
   ```

---

## Quick Debug Commands

```bash
# Cek FCM_SERVER_KEY
php artisan tinker
>>> config('services.firebase.fcm_server_key')

# Cek user FCM token
>>> \App\Models\User::find(1)->fcm_token

# Test kirim FCM manual
>>> \App\Services\NotificationService::sendFcmNotification('FCM_TOKEN_DISINI', 'Test', 'Body test');
```

