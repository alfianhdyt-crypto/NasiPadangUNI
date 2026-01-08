# Nasi Padang AI Antigravity

Aplikasi pemesanan Nasi Padang modern dengan integrasi AI untuk rekomendasi menu dan asisten virtual ("Uni").

## Fitur Utama

1.  **Rekomendasi Menu AI**:
    *   Menganalisis waktu (Pagi/Siang/Malam) dan riwayat pesanan.
    *   Memberikan saran menu yang personal.

2.  **Chatbot Asisten "Uni"**:
    *   Bot interaktif yang menjawab pertanyaan seputar menu.
    *   Gaya bicara santai khas "Uni" warung Padang.

3.  **Manajemen Pesanan**:
    *   Keranjang belanja real-time.
    *   Sistem checkout sederhana.

## Persyaratan Sistem

- **XAMPP** (PHP 7.4+).
- **Koneksi Internet** (untuk akses API OpenRouter).
- **API Key OpenRouter** (untuk fitur AI).

## Cara Instalasi & Menjalankan

1.  **Setup Folder**:
    *   Pastikan folder proyek bernama `Ai Penjualan nasi padang`.
    *   Simpan di dalam folder `htdocs` (biasanya `C:\xampp\htdocs\`).

2.  **Konfigurasi API AI**:
    *   Buka file `includes/config.php`.
    *   Masukkan API Key Anda:
        ```php
        define('OPENROUTER_API_KEY', 'sk-or-...');
        define('OPENROUTER_MODEL', 'xiaomi/mimo-v2-flash:free'); // Model default
        ```
    *   **PENTING**: Jika menggunakan model gratis, aktifkan "Allow data usage for free models" di [OpenRouter Settings](https://openrouter.ai/settings/privacy).

3.  **Jalankan Server**:
    *   Buka **XAMPP Control Panel**.
    *   Start **Apache**.

4.  **Buka Aplikasi**:
    *   Akses: [http://localhost/Ai%20Penjualan%20nasi%20padang/](http://localhost/Ai%20Penjualan%20nasi%20padang/)

## Struktur Folder

*   `admin`: Halaman dashboard (WIP).
*   `api`: Backend endpoint (`chat.php`, `recommendation.php`).
*   `assets`: CSS, JS, dan file statis.
*   `data`: `menu.json` (Database JSON).
*   `includes`: Konfigurasi dan helper PHP.
*   `tests`: Script pengujian fungsi.

## Troubleshooting

- **Chatbot Error / Tidak Merespon**:
    - Cek koneksi internet.
    - Pastikan API Key valid di `includes/config.php`.
    - Pastikan kebijakan privasi di OpenRouter sudah diatur untuk model gratis.

- **Tampilan Rusak**:
    - Cek loading file CSS di browser console (F12).
    - Pastikan path URL sesuai.

---
*Dibuat dengan ❤️ oleh Tim Antigravity*
