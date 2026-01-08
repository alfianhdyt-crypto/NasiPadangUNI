# Panduan Menjalankan Aplikasi Nasi Padang AI

Aplikasi ini adalah web berbasis PHP yang menggunakan data JSON, sehingga **tidak memerlukan database MySQL**.

## Cara Menjalankan

1.  **Pastikan XAMPP Terinstall dan Aktif**:
    *   Buka **XAMPP Control Panel**.
    *   Klik tombol **Start** pada baris **Apache**. (MySQL tidak perlu dijalankan).

2.  **Akses Aplikasi**:
    *   Buka browser (Chrome, Edge, Firefox).
    *   Kunjungi alamat berikut: [http://localhost/Ai%20Penjualan%20nasi%20padang/](http://localhost/Ai%20Penjualan%20nasi%20padang/)

## Struktur Folder

*   `assets`: CSS, Gambar, JavaScript.
*   `data`: Berisi file `menu.json` sebagai sumber data menu.
*   `includes`: File PHP pendukung (DB adapter, komponen view).
*   `index.php`: Halaman utama aplikasi.

## Troubleshooting

*   Jika muncul "Object not found" (404), pastikan nama folder di `htdocs` benar-benar `Ai Penjualan nasi padang`.
*   Jika tampilan rusak, pastikan file CSS di `assets/css/style.css` terpanggil dengan benar.
