# SDLC dan Jawaban Laporan — Islamic Platform

## 1. Menentukan konsep

- **Tujuan:** menyediakan website Islami yang menampilkan Al-Qur'an, doa harian, dan jadwal shalat dengan navigasi yang mudah.
- **Pengguna:** masyarakat umum yang ingin membaca surat, mencari doa, dan melihat jadwal shalat berdasarkan wilayah.
- **Nama website:** Islamic Platform.
- **Ruang lingkup:** memenuhi syarat minimal dua layanan API; proyek ini memilih ketiga layanan pada LKPD agar fungsi website saling melengkapi.

## 2. Mengenali API

Semua endpoint menggunakan host `https://equran.id`.

| Layanan | Endpoint dan metode | Parameter | Data yang digunakan |
| --- | --- | --- | --- |
| Al-Qur'an | `GET /api/v2/surat` | — | Nomor, nama Latin/Arab, arti, dan jumlah ayat untuk daftar surat. |
| Detail surat | `GET /api/v2/surat/{nomor}` | Nomor surat (1–114) | Ayat Arab, Latin, terjemahan Indonesia, audio surat, dan audio ayat. |
| Doa harian | `GET /api/doa` | Pencarian `q` dilakukan pada data yang diterima | ID, nama, grup, teks Arab, Latin, dan terjemahan doa. |
| Detail doa | `GET /api/doa/{id}` | ID doa | Teks doa lengkap, keterangan, dan tag. |
| Daftar provinsi | `GET /api/v2/shalat/provinsi` | — | Daftar provinsi untuk pilihan form. |
| Kabupaten/kota | `POST /api/v2/shalat/kabkota` | `provinsi` | Daftar kabupaten/kota sesuai provinsi. |
| Jadwal bulanan | `POST /api/v2/shalat` | `provinsi`, `kabkota`, `bulan`, `tahun` | Lokasi dan jadwal imsak sampai isya. |

Controller mengambil JSON menggunakan Laravel HTTP Client, memberi batas waktu koneksi/respons, memeriksa status respons, lalu mengirim data ke Blade.

## 3. Merancang halaman

- **Beranda:** pengenalan website dan tautan ke tiga fitur.
- **Al-Qur'an:** kartu daftar surat → halaman detail ayat, terjemahan, dan audio.
- **Doa:** daftar dan pencarian doa → halaman detail Arab, Latin, terjemahan, keterangan, dan tag.
- **Jadwal shalat:** form provinsi/kabupaten-kota/bulan/tahun → tabel jadwal per hari.
- **Navigasi:** header dan footer bersama agar pengguna dapat berpindah layanan dari setiap halaman.

Sketsa alur: `Beranda → (Al-Qur'an → Detail Surat | Doa → Detail Doa | Jadwal Shalat → Pilih Wilayah/Periode → Hasil Jadwal)`.

## 4. Membangun dan mengintegrasikan

- **Route:** `routes/web.php` menghubungkan URL ke controller.
- **Controller:** `QuranController`, `DoaController`, dan `JadwalShalatController` mengambil serta menyiapkan data API.
- **View:** Blade menampilkan daftar/detail surat, daftar/detail doa, dan tabel jadwal.
- **Interaksi:** pencarian doa memfilter hasil API; pilihan kabupaten/kota dimuat dengan request `POST` setelah provinsi dipilih.

## 5. Menguji dan memperbaiki

| Skenario uji | Hasil yang diharapkan |
| --- | --- |
| Membuka daftar surat dan detail surat | Data API dan audio/ayat tampil di layout bersama. |
| Mencari doa dan membuka detailnya | Hasil pencarian tersaring dan teks doa tampil. |
| Membuka jadwal shalat | Daftar provinsi tampil. |
| Mengirim jadwal tanpa input wajib | Form menolak input dan menampilkan kesalahan validasi. |
| Memilih jadwal dengan wilayah dan periode valid | Jadwal bulanan tampil dalam tabel. |
| Membuka detail surat di luar nomor 1–114 | Website memberi respons 404. |

Pengujian memakai HTTP fake agar hasil deterministik dan tidak membutuhkan koneksi API nyata.

## 6. Demonstrasi dan pemeliharaan

1. Buka beranda, lalu tunjukkan navigasi Al-Qur'an, Doa, dan Jadwal Shalat.
2. Buka detail surat dan putar audio jika tersedia.
3. Cari doa, lalu buka detailnya.
4. Pilih provinsi, pilih kabupaten/kota, tentukan bulan/tahun, kemudian tampilkan jadwal.
5. Jika format atau ketersediaan API berubah, perbarui pemetaan respons controller dan pengujian terkait.

## Jawaban pertanyaan laporan

### 1. Mengapa layanan yang dipilih sesuai dengan tujuan website?

Ketiga layanan berisi data yang langsung mendukung tujuan website Islami: Al-Qur'an untuk membaca surat dan ayat, doa harian untuk mencari bacaan doa, serta jadwal shalat untuk mengetahui waktu ibadah berdasarkan lokasi. Ketiganya saling melengkapi kebutuhan pengguna dalam satu website, dan sudah melampaui ketentuan minimal dua layanan pada LKPD.

### 2. Bagaimana request diproses sampai informasi tampil?

Browser membuka route Laravel. Route meneruskan request ke controller terkait. Controller mengirim request `GET` atau `POST` ke API eQuran menggunakan Laravel HTTP Client, memeriksa respons, lalu mengambil bagian `data` dari JSON. Data tersebut dikirim ke view Blade dan ditampilkan sebagai daftar, detail, atau tabel. Pada jadwal shalat, pilihan kabupaten/kota diminta melalui `POST` setelah pengguna memilih provinsi.

### 3. Masalah apa yang ditemukan saat pengujian, dan bagaimana perbaikannya?

ParseError ditemukan pada footer karena ada potongan PHP yang tidak lengkap (`\ = ...` dan `unset(\)`). Kedua potongan tersebut dihapus agar layout bersama dapat dirender. Pada jadwal shalat, daftar kabupaten/kota memang belum tersedia sebelum provinsi dipilih; antarmuka sekarang menonaktifkan pilihan tersebut sampai provinsi dipilih dan menampilkan pesan jika request gagal. Input jadwal juga divalidasi, sementara pengujian controller memakai HTTP fake untuk memeriksa alur tanpa bergantung pada jaringan API nyata.
