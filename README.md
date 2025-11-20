# Library Management System Frontend

Frontend aplikasi perpustakaan berbasis CodeIgniter 4 yang menyediakan UI untuk mengelola data buku, peminjaman, pengembalian, dan pencarian dengan berbagai filter. Proyek ini dikembangkan sebagai frontend dari aplikasi backend Laravel pada tugas UTS Integrasi Aplikasi Enterprise.

## Fitur Utama

- **Manajemen Buku**: Interface lengkap untuk operasi CRUD data buku
- **Peminjaman & Pengembalian**: Sistem UI untuk pencatatan peminjaman dan pengembalian buku
- **Pencarian Real-time**: Pencarian buku dengan debounce untuk performa optimal
- **Filter & Sorting**: Custom dropdown filter berdasarkan kategori dan status
- **Pagination**: Navigasi halaman dengan desain modern dan responsif
- **Toast Notifications**: Feedback visual real-time untuk setiap user action
- **Glassmorphism UI**: Desain modern dengan backdrop blur dan gradient animations
- **Responsive Design**: Tampilan optimal di desktop, tablet, dan mobile

## Teknologi

Proyek ini dibangun menggunakan teknologi berikut:

- **CodeIgniter 4** - PHP Framework
- **CURLRequest** - HTTP client untuk API communication
- **Bootstrap 5** - CSS framework untuk navbar dan modal
- **Vanilla JavaScript** - Interactive components dan AJAX handling
- **CSS3** - Custom styling dengan glassmorphism effect
- **SVG Icons** - Inline icons untuk toast notifications

## Prasyarat

Sebelum memulai instalasi, pastikan sistem Anda memiliki:

- PHP >= 8.0
- Composer
- Laravel Backend API (running di port 8000)
- Web browser

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek:

### 1. Clone Repository

```bash
git clone https://github.com/user/project-frontend.git
cd project-frontend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file konfigurasi environment:

```bash
cp env .env
```

Sesuaikan konfigurasi di file `.env`:

```env
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8082/'
API_BASE_URL = 'http://127.0.0.1:8000/api'
```

### 4. Pastikan Backend API Berjalan

Sebelum menjalankan frontend, pastikan Laravel backend sudah running:

```bash
# Di terminal terpisah, jalankan Laravel backend
cd ../project-backend
php artisan serve
```

### 5. Jalankan Development Server

```bash
php spark serve --port=8082
```

### 6. Akses Aplikasi

Buka browser dan akses aplikasi di:

```
http://localhost:8082
```

### Daftar Routes

| Metode | Route | Controller | Deskripsi |
|--------|-------|------------|-----------|
| GET | `/` | Books::index | Halaman utama - daftar buku |
| GET | `/books` | Books::index | Daftar buku dengan filter |
| GET | `/books/create` | Books::create | Form tambah buku baru |
| POST | `/books` | Books::store | Proses simpan buku baru |
| GET | `/books/{id}/edit` | Books::edit | Form edit buku |
| POST | `/books/{id}` | Books::update | Proses update buku |
| GET | `/books/{id}` | Books::show | Detail buku |
| GET | `/books/{id}/delete` | Books::delete | Proses hapus buku |
| POST | `/books/{id}/borrow` | Books::borrow | Proses peminjaman buku |
| POST | `/books/{id}/return` | Books::return | Proses pengembalian buku |
| GET | `/api-front/books` | Books::listingAjax | AJAX endpoint untuk listing |


## Struktur Direktori

Berikut adalah struktur direktori penting dalam proyek:

```
app/
├── Config/
│   └── Routes.php              # Konfigurasi routing
├── Controllers/
│   └── Books.php               # Controller utama untuk books
└── Views/
    ├── layout.php              # Base layout template
    ├── navbar.php              # Navigation component
    ├── footer.php              # Footer dengan toast container
    └── books/
        ├── index.php           # List page dengan filter & table
        ├── form.php            # Form create/edit universal
        └── show.php            # Detail page buku
public/
└── assets/
    ├── css/
    │   └── app.css             # Main stylesheet dengan glassmorphism
    └── js/
        └── toast.js            # Custom toast notification library
.env                            # Environment configuration
```

## Pengembangan

### Environment Modes

Untuk development, set environment di `.env`:

```env
CI_ENVIRONMENT = development
```

Untuk production, ubah ke:

```env
CI_ENVIRONMENT = production
```

## Kontributor

Proyek ini dikembangkan untuk keperluan akademik UTS Integrasi Aplikasi Enterprise.

**Developer:**
- Mohamad Fikri Isfahani (1204230031)
