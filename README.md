# Informatics-Final-Project-RC6
"Web-based secure document management system for The Bellagio Mansion, implementing the RC6 (Rivest Cipher 6) cryptographic algorithm for high-level data confidentiality."

# 🏢 The Bellagio Mansion - Secure Document System (RC6)

Sistem Manajemen Dokumen Terenkripsi berbasis Web yang dirancang untuk menjaga kerahasiaan data administrasi di Apartemen The Bellagio Mansion. Aplikasi ini mengimplementasikan algoritma kriptografi **RC6 (Rivest Cipher 6)** untuk memastikan setiap berkas yang disimpan tidak dapat diakses oleh pihak yang tidak berwenang.

## 📸 Preview Interface
### Login (Login Page)
![Halaman Login](assets/Screenshots/Login.png)

### Dashboard Admin
![Dashboard Statistik Admin](assets/Screenshots/Dashboard_Admin.png)

### Enkripsi (Enkripsi Page)
![Halaman Enkripsi](assets/Screenshots/Enkrip.png)

### Dekripsi (Dekripsi Page)
![Halaman Dekripsi](assets/Screenshots/Dekrip.png)

### Daftar Berkas (Daftar Berkas Page)
![Daftar Berkas](assets/Screenshots/List_File.png)

### Panduan Sistem (Help Page)
![Halaman Bantuan](assets/Screenshots/help.png)

### Dashboard User
![Dashboard Statistik User](assets/Screenshots/Dashboard_User.png)


## 🚀 Fitur Utama
- **Advanced Encryption Standard:** Menggunakan algoritma **RC6** dengan kunci simetris untuk enkripsi dan dekripsi berkas (.pdf, .docx, .jpg, .png, dll).
- **Modern Dashboard:** Statistik real-time untuk memantau total pengguna dan status keamanan berkas (Terenkripsi/Terdekripsi).
- **Secure Session Management:** Proteksi halaman yang ketat untuk mencegah *unauthorized access*.
- **Activity Log & History:** Pencatatan riwayat pemrosesan berkas untuk audit keamanan.
- **Modern UI/UX:** Antarmuka responsif menggunakan perpaduan tema profesional (Blue/Dark) dengan fitur pratinjau foto profil (Lightbox).

## 🛠️ Tech Stack
- **Backend:** PHP (Native)
- **Database:** MySQL (MariaDB)
- **Frontend:** HTML5, CSS3 (Custom Modern Theme), Bootstrap 3
- **Library & Icons:** jQuery, Font Awesome 4.7, Pace.js
- **Algorithm:** RC6 (Block Cipher)

## 🔑 Algoritma RC6
Proyek ini mengimplementasikan RC6 dengan parameter standar:
- **Word size (w):** 32 bits
- **Rounds (r):** 20
- **Key size (b):** 16/24/32 bytes

Algoritma ini dipilih karena efisiensinya dalam pemrosesan data pada arsitektur modern dan tingkat keamanan yang tinggi terhadap serangan kriptanalisis linear maupun diferensial.

## ⚙️ Instalasi
1. Clone repository ini:
   ```bash
   git clone https://github.com/Radityaputrazz/Informatics-Final-Project-RC6.git
   
3. Import file database .sql (tersedia di folder database) ke MySQL Anda.

4. Sesuaikan konfigurasi database pada file config.php.

5. Jalankan aplikasi di server lokal (XAMPP/Laragon/WAMP).
