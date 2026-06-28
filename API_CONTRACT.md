# 📚 TanamInd Backend API Documentation

**Base URL:** `http://localhost:8000/api`

**Format Response Global:**
Semua endpoint secara konsisten akan mereturn format JSON seperti ini:
```json
{
  "status": "success", // atau "error"
  "message": "Pesan dari server",
  "data": { ... } // Berisi object atau array
}
```

> **⚠️ Catatan Penting:** 
> - Semua endpoint (kecuali Login & Register) membutuhkan header `Authorization: Bearer {token}`.
> - Jika token tidak valid / belum login, server akan mereturn status HTTP `401 Unauthorized`.
> - URL gambar (`image_url`) yang direturn oleh backend sudah dalam bentuk URL utuh absolut (misal: `http://localhost:8000/storage/scans/...`).

---

## 🟢 1. Authentication (Public)

### 1.1 Register
- **Endpoint:** `POST /auth/register`
- **Body (JSON):**
  ```json
  {
    "name": "Farel",
    "email": "farel@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Response:** Mengembalikan data `user` dan `token`.

### 1.2 Login
- **Endpoint:** `POST /auth/login`
- **Body (JSON):**
  ```json
  {
    "email": "farel@example.com",
    "password": "password123"
  }
  ```
- **Response:** Mengembalikan data `user` (termasuk role) dan `token`. **Simpan token ini di `localStorage`**.

---

## 🔒 2. User & Auth (Protected)
*(Wajib Header: `Authorization: Bearer {token}`)*

### 2.1 Get Current User
- **Endpoint:** `GET /user`
- **Response:** Mengembalikan profil user yang sedang login.

### 2.2 Logout
- **Endpoint:** `POST /auth/logout`
- **Response:** Menghapus token di database backend.

---

## 📷 3. Fitur Scan ML (Protected)

### 3.1 Upload & Scan Gambar
- **Endpoint:** `POST /scan`
- **Headers:** Wajib set `Content-Type: multipart/form-data`
- **Body (Form-Data):**
  - `image` : File (jpg, jpeg, png, max 5MB) — **Wajib**
  - `notes` : Text — *Opsional*
- **Response:** Mengembalikan hasil prediksi penyakit (`disease_label`, `severity_label`) beserta object rekomendasi penanganan.

### 3.2 Ambil Riwayat Scan
- **Endpoint:** `GET /scan`
- **Response:** Mengembalikan array riwayat scan milik user yang sedang login (diurutkan terbaru).

### 3.3 Detail Scan
- **Endpoint:** `GET /scan/{id}`
- **Response:** Detail 1 scan beserta daftar rekomendasi penanganannya.

---

## 🌍 4. Komunitas (Protected)

### 4.1 Ambil Feed Komunitas
- **Endpoint:** `GET /community`
- **Response:** Mengembalikan array seluruh postingan dari semua user. Setiap postingan akan menyertakan data pembuat (user), data scan (scan_result), total komentar (`comments_count`), dan 3 komentar terbaru.

### 4.2 Buat Postingan Baru (Bagikan Scan)
- **Endpoint:** `POST /community`
- **Body (JSON):**
  ```json
  {
    "scan_result_id": 1, // ID dari hasil scan milik user
    "caption": "Tolong bantu pakcoy saya" // Opsional
  }
  ```
- **Response:** Mengembalikan data post yang baru dibuat. (Satu scan hanya bisa dibagikan 1 kali).

### 4.3 Kirim Komentar
- **Endpoint:** `POST /community/{post_id}/comments`
- **Body (JSON):**
  ```json
  {
    "body": "Coba pakai pupuk A kak."
  }
  ```
- **Response:** Mengembalikan data komentar yang baru saja dibuat.

---

## 📖 5. Data Referensi (Protected)

### 5.1 Katalog Penyakit & Rekomendasi
- **Endpoint:** `GET /diseases`
- **Response:** Mengembalikan array berisi 4 macam kategori (Sehat, Bercak Daun, Berlubang, Busuk) lengkap dengan deskripsi dan list rekomendasi per tingkat keparahannya (ringan, sedang, parah). 

---

## 👑 6. Admin Panel (Protected + Role Admin)
*(Jika user biasa mengakses ini, akan return HTTP `403 Forbidden`)*

### 6.1 Statistik Dashboard
- **Endpoint:** `GET /admin/stats`
- **Response:** Mengembalikan rangkuman data (`total_users`, `total_scans`, `scans_today`, `total_posts`), persebaran penyakit (`disease_distribution`), dan grafik scan per hari (`scans_per_day`).

### 6.2 Daftar User
- **Endpoint:** `GET /admin/users`
- **Response:** Mengembalikan daftar seluruh user berserta total jumlah scan yang pernah mereka lakukan (`scan_results_count`).
