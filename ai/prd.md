# PRD — Aplikasi Manajemen Stok & Penjualan Dalwa Water

## Informasi Project

### Nama Aplikasi

**Dalwa Water Management System**

### Tujuan Aplikasi

Membantu operasional penjualan dan pengelolaan stok produk air minum Dalwa Water agar lebih cepat, rapi, dan terintegrasi mulai dari:

* Input barang masuk
* Pengelolaan stok
* Transaksi penjualan
* Cetak nota
* Pengelolaan kasir
* Laporan penjualan

---

# 1. Latar Belakang

Saat ini pencatatan stok dan transaksi masih dilakukan secara manual sehingga:

* Sulit memantau stok barang
* Risiko salah hitung transaksi
* Pembuatan nota lambat
* Sulit membuat laporan penjualan
* Tidak ada pembatasan akses antara admin dan kasir

Aplikasi ini dibuat untuk mendigitalisasi seluruh proses penjualan Dalwa Water.

---

# 2. User Role

## 2.1 Admin

Hak akses:

* Login
* Dashboard
* CRUD Barang
* Input Barang Masuk
* Kelola Stok
* CRUD Kasir
* Melihat semua transaksi
* Cetak nota
* Melihat laporan
* Export laporan
* Kelola metode pembayaran

Admin TIDAK melakukan pembatasan transaksi.

---

## 2.2 Kasir

Hak akses:

* Login
* Melakukan transaksi penjualan
* Cetak nota
* Melihat stok barang
* Input jumlah uang diterima
* Memilih metode pembayaran

Kasir TIDAK BISA:

* Menambah stok
* Mengedit barang
* Menghapus barang
* Mengakses laporan
* CRUD kasir

---

# 3. Fitur Utama

# 3.1 Login System

### Deskripsi

Sistem autentikasi untuk Admin dan Kasir.

### Fitur

* Login username/password
* Role access
* Logout
* Session login

---

# 3.2 Dashboard

## Dashboard Admin

Menampilkan:

* Total stok barang
* Total transaksi hari ini
* Pendapatan hari ini
* Produk terlaris
* Grafik penjualan

## Dashboard Kasir

Menampilkan:

* Transaksi hari ini
* Total penjualan
* Shortcut transaksi baru

---

# 3.3 Manajemen Barang

### CRUD Barang

Field:

* Kode Barang
* Nama Barang
* Kategori
* Harga Modal
* Harga Jual
* Stok
* Satuan
* Status aktif/nonaktif

### Fitur

* Tambah barang
* Edit barang
* Hapus barang
* Cari barang
* Filter kategori

---

# 3.4 Barang Masuk

### Fungsi

Admin dapat menambahkan stok barang masuk.

### Field

* Tanggal
* Barang
* Jumlah masuk
* Supplier
* Keterangan

### Sistem

Saat barang masuk:

* Stok otomatis bertambah

---

# 3.5 Sistem Transaksi Penjualan

### Alur

1. Kasir memilih barang
2. Input jumlah beli
3. Sistem menghitung subtotal
4. Pilih metode pembayaran
5. Input uang diterima
6. Sistem menghitung kembalian
7. Simpan transaksi
8. Cetak nota

---

## Field Transaksi

* No Transaksi
* Tanggal
* Kasir
* Barang
* Qty
* Harga
* Subtotal
* Total
* Metode Pembayaran
* Uang Diterima
* Kembalian

---

# 3.6 Metode Pembayaran

## Tunai

Field:

* Uang diterima
* Kembalian otomatis

## Transfer

Field:

* Nama Bank
* Nomor Referensi
* Status pembayaran

---

# 3.7 Nota Penjualan

### Isi Nota

* Logo Dalwa Water
* Nama toko
* Nomor transaksi
* Tanggal
* Nama kasir
* List barang
* Qty
* Harga
* Total
* Metode pembayaran
* Uang diterima
* Kembalian
* Footer ucapan terima kasih

### Format

* Print thermal 58mm/80mm
* PDF download

---

# 3.8 Manajemen Kasir

### CRUD Kasir

Field:

* Nama
* Username
* Password
* Role
* Status aktif

### Fitur

* Tambah kasir
* Edit kasir
* Nonaktifkan kasir
* Reset password

---

# 3.9 Laporan

## Laporan Harian

* Total transaksi
* Total pendapatan
* Produk terjual

## Laporan Bulanan

* Rekap penjualan
* Grafik penjualan
* Produk terlaris

## Export

* PDF
* Excel

---

# 4. Flow Sistem

## Flow Barang Masuk

Admin → Input Barang Masuk → Stok Bertambah

---

## Flow Penjualan

Kasir → Pilih Barang → Input Qty → Pilih Pembayaran → Input Uang → Cetak Nota → Stok Berkurang

---

# 5. Struktur Database

## Tabel users

* id
* name
* username
* password
* role
* status

---

## Tabel products

* id
* kode_barang
* nama_barang
* kategori
* harga_modal
* harga_jual
* stok
* satuan
* status

---

## Tabel stock_ins

* id
* product_id
* qty
* supplier
* tanggal
* keterangan

---

## Tabel transactions

* id
* kode_transaksi
* user_id
* total
* payment_type
* uang_diterima
* kembalian
* created_at

---

## Tabel transaction_details

* id
* transaction_id
* product_id
* qty
* harga
* subtotal

---

# 6. Validasi Sistem

## Validasi Stok

* Tidak bisa menjual melebihi stok

## Validasi Pembayaran

* Tunai harus ≥ total transaksi

## Validasi Role

* Kasir tidak bisa akses menu admin

---

# 7. Teknologi yang Digunakan

## Backend

* PHP 8.5
* Laravel 13

## Frontend

* Tailwind CSS
* Blade Template
* Alpine.js

## Database

* MySQL

## Authentication

* Laravel Breeze

## Export

* Laravel Excel
* DomPDF

---

# 8. UI/UX Concept

## Style

Modern Dashboard POS

## Warna

* Biru
* Putih
* Abu modern

## Karakteristik

* Clean
* Minimalis
* Responsive
* Cepat digunakan kasir

---

# 9. Future Feature (Opsional)

* Barcode Scanner
* QRIS Payment
* Multi Cabang
* Customer Member
* Riwayat Hutang
* WhatsApp Nota
* Monitoring stok minimum

---

# 10. Target Output

Aplikasi siap digunakan untuk:

* Manajemen stok Dalwa Water
* Penjualan harian
* Cetak nota otomatis
* Monitoring laporan penjualan
* Multi user Admin & Kasir


# 11. struktur

 resources/
└── views/
    ├── layouts/
    │   ├── app.blade.php
    │   ├── auth.blade.php
    │   ├── guest.blade.php
    │   └── print.blade.php
    │
    ├── partials/
    │   ├── navbar.blade.php
    │   ├── sidebar.blade.php
    │   ├── footer.blade.php
    │   ├── breadcrumbs.blade.php
    │   ├── alerts.blade.php
    │   └── scripts.blade.php
    │
    ├── components/
    │   ├── cards/
    │   │   ├── stat-card.blade.php
    │   │   ├── chart-card.blade.php
    │   │   └── table-card.blade.php
    │   │
    │   ├── forms/
    │   │   ├── input.blade.php
    │   │   ├── select.blade.php
    │   │   ├── textarea.blade.php
    │   │   ├── checkbox.blade.php
    │   │   └── button.blade.php
    │   │
    │   ├── modals/
    │   │   ├── delete-modal.blade.php
    │   │   └── confirm-modal.blade.php
    │   │
    │   └── tables/
    │       ├── table.blade.php
    │       └── pagination.blade.php
    │
    ├── auth/
    │   ├── login.blade.php
    │   ├── forgot-password.blade.php
    │   ├── reset-password.blade.php
    │   └── verify-email.blade.php
    │
    ├── dashboard/
    │   ├── admin.blade.php
    │   └── kasir.blade.php
    │
    ├── products/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   ├── show.blade.php
    │   └── partials/
    │       └── form.blade.php
    │
    ├── stock-in/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── partials/
    │       └── form.blade.php
    │
    ├── transactions/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── show.blade.php
    │   ├── receipt.blade.php
    │   └── partials/
    │       ├── cart-table.blade.php
    │       ├── payment-form.blade.php
    │       └── product-search.blade.php
    │
    ├── cashiers/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── partials/
    │       └── form.blade.php
    │
    ├── reports/
    │   ├── daily.blade.php
    │   ├── monthly.blade.php
    │   ├── sales.blade.php
    │   └── export.blade.php
    │
    ├── settings/
    │   ├── profile.blade.php
    │   ├── application.blade.php
    │   └── printer.blade.php
    │
    ├── errors/
    │   ├── 403.blade.php
    │   ├── 404.blade.php
    │   └── 500.blade.php
    │
    └── welcome.blade.php