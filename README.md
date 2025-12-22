# ☕ Kalcer.ID — Jaksel’s Ultimate Hangout Guide

<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&customColorList=a78bfa,f472b6,fb923c&height=300&section=header&text=Kalcer.ID&fontSize=70&fontAlign=42&animation=fadeIn&desc=No%20Gatekeeping.%20Just%20Vibes.&descAlign=50&descAlignY=75&descSize=20" width="100%">

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-Volt-4e56a6?style=for-the-badge&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)](https://alpinejs.dev)

**[🌐 Live Demo (Railway)](https://kalcerid-pemweb-production.up.railway.app)**

</div>

---

## 📖 About The Project

**Kalcer.ID** adalah platform kurasi tempat nongkrong (*Coffee Shop, Public Space, Creative Hub*) yang berfokus pada **validitas data**. Kami lelah dengan *review* palsu dan tempat yang cuma bagus di foto tapi aslinya zonk.

Fitur Unggulan:
- **🗺️ Interactive Heatmap:** Peta real-time yang menunjukkan mana tempat "Hype" (Merah) dan mana yang "Chill" (Hijau).
- **🔥 Trend Analysis:** Algoritma sederhana untuk menghitung tingkat keramaian berdasarkan rating & jumlah review.
- **✨ Curated Lists:** Rekomendasi mingguan yang dipilih manual (bukan bot).

---

## 📂 Project Structure (The Important Stuff)

Project ini menggunakan **Laravel + Livewire Volt**. Tidak banyak Controller, karena logic ada di View. Berikut peta folder pentingnya:

```bash
kalcer-id/
├── app/
│   ├── Models/
│   │   └── HangoutPlace.php   # Model utama + Logika Heatmap (Accessor)
├── resources/
│   ├── views/
│   │   ├── components/        # Layouts utama (Navbar, Footer)
│   │   ├── livewire/          # ❤️ JANTUNG APLIKASI (Logic + View jadi satu)
│   │   │   ├── pages/
│   │   │   │   ├── home.blade.php    # Landing Page
│   │   │   │   ├── maps.blade.php    # Fitur Peta & Heatmap Logic
│   │   │   │   ├── about.blade.php   # Team & Manifesto
│   │   │   │   └── show.blade.php    # Detail Tempat
├── routes/
│   └── web.php                # Routing sederhana (langsung ke Volt Component)
├── public/                    # Aset gambar static (kalau ada)
└── database/                  # Migrations & Seeders

```

---

## 🛠️ Installation Guide

Pastikan kamu sudah install **PHP**, **Composer**, dan **Node.js** di laptop kamu.

1. **Clone Repository**
```bash
git clone [https://github.com/username/kalcer.id-pemweb.git](https://github.com/username/kalcer.id-pemweb.git)
cd kalcer.id-pemweb

```


2. **Install Dependencies (Backend & Frontend)**
```bash
composer install
npm install

```


3. **Setup Environment**
Duplikat file `.env.example` menjadi `.env`, lalu atur database kamu.
```bash
cp .env.example .env
php artisan key:generate

```


4. **Database Setup**
Pastikan aplikasi database (MySQL/XAMPP/DBngin) sudah **Start**.
```bash
php artisan migrate:fresh --seed

```



---

## 🚀 How to Run (Development)

Karena project ini menggunakan **Vite** untuk *hot-reload* CSS/JS, kamu perlu menjalankan **Dua Terminal** secara bersamaan:

**Terminal 1 (Laravel Server):**

```bash
php artisan serve

```

*Akan jalan di: http://127.0.0.1:8000*

**Terminal 2 (Vite/Tailwind Watcher):**

```bash
npm run dev

```

*Ini memantau perubahan CSS/JS secara real-time.*

> **Tips:** Biarkan kedua terminal ini tetap menyala selama kamu ngoding.

---

## 📦 How to Deploy & Push

Project ini sudah terintegrasi dengan **Railway**. Setiap kali kamu melakukan Push ke GitHub, Railway akan otomatis melakukan re-deploy.

**Workflow Update Code:**

1. Cek status file yang berubah:
```bash
git status

```


2. Tambahkan semua perubahan:
```bash
git add .

```


3. Simpan (Commit) dengan pesan jelas:
```bash
git commit -m "Menambahkan fitur heatmap dan fix bug layout"

```


4. Kirim ke GitHub:
```bash
git push origin main

```



*Tunggu 2-3 menit, cek link Live Demo di atas. Perubahan akan muncul otomatis!*

---

<div align="center">

Made with ❤️ by **Kelompok Kalcer** (Pemrograman Web)





*Farhan • Alphard • Ali • Irfan • Audrey*

</div>

Langsung gaskeuun commit `README.md` ini\! 🚀

```
