<?php

use App\Http\Controllers\Backend\ArsipController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\Backend\GolonganController;
use App\Http\Controllers\Backend\KontenController;
use App\Http\Controllers\Backend\PendaftaranController;
use App\Http\Controllers\Backend\PengaturanController;
use App\Http\Controllers\Backend\PenilaianController;
use App\Http\Controllers\Backend\SoalController;
use App\Http\Controllers\Backend\TimelineController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Frontend\BerandaController;
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\BerkasController;
use App\Http\Controllers\Frontend\PendaftaranController as FrontendPendaftaranController;
use App\Http\Controllers\Frontend\PengumumanController;
use App\Http\Controllers\Frontend\TimelineController as FrontendTimelineController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [BerandaController::class, 'index'])->name('beranda.index');
Route::get('/timeline-garudaku', [FrontendTimelineController::class, 'index'])->name('timeline-garudaku.index');
Route::get('/berkas-garudaku', [BerkasController::class, 'index'])->name('berkas-garudaku.index');
Route::get('/pengumuman-garudaku', [PengumumanController::class, 'index'])->name('pengumuman-garudaku.index');
Route::get('/berita-garudaku', [BeritaController::class, 'index'])->name('berita-garudaku.index');
Route::get('/berita-garudaku/detail/{slug}', [BeritaController::class, 'detail'])->name('berita-garudaku.detail');

Auth::routes();

// Peserta
Route::middleware(['auth', 'user-access:Peserta'])->group(function () {
  Route::get('/pendaftaran', [FrontendPendaftaranController::class, 'index'])->name('pendaftaran.index');
  Route::post('/pendaftaran/store', [FrontendPendaftaranController::class, 'store'])->name('pendaftaran.store');
});

// Administrator, Juri & Panitia
Route::middleware(['auth', 'user-access:Administrator,Juri,Panitia'])->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

  // Pendaftaran all
  Route::get('/pendaftaran/semua', [PendaftaranController::class, 'indexAll'])->name('pendaftaran.semua.index');
  Route::post('/pendaftaran/semua/detail/{id}', [PendaftaranController::class, 'detailAll'])->name('pendaftaran.semua.detail');
  Route::post('/pendaftaran/semua/terima/{id}', [PendaftaranController::class, 'terimaAll'])->name('pendaftaran.semua.terima');
  Route::post('/pendaftaran/semua/tolak/{id}', [PendaftaranController::class, 'tolakAll'])->name('pendaftaran.semua.tolak');
  Route::delete('/pendaftaran/semua/delete/{id}', [PendaftaranController::class, 'destroyAll'])->name('pendaftaran.semua.delete');
  Route::get('/pendaftaran/semua/print', [PendaftaranController::class, 'printAll'])->name('pendaftaran.semua.print');
  Route::get('/pendaftaran/semua/printPDF', [PendaftaranController::class, 'printPDFAll'])->name('pendaftaran.semua.printPDF');

  // Pendaftaran siaga
  Route::get('/pendaftaran/siaga', [PendaftaranController::class, 'indexSiaga'])->name('pendaftaran.siaga.index');
  Route::post('/pendaftaran/siaga/detail/{id}', [PendaftaranController::class, 'detailSiaga'])->name('pendaftaran.siaga.detail');
  Route::post('/pendaftaran/siaga/terima/{id}', [PendaftaranController::class, 'terimaSiaga'])->name('pendaftaran.siaga.terima');
  Route::post('/pendaftaran/siaga/tolak/{id}', [PendaftaranController::class, 'tolakSiaga'])->name('pendaftaran.siaga.tolak');
  Route::delete('/pendaftaran/siaga/delete/{id}', [PendaftaranController::class, 'destroySiaga'])->name('pendaftaran.siaga.delete');
  Route::get('/pendaftaran/siaga/printPDF', [PendaftaranController::class, 'printPDFSiaga'])->name('pendaftaran.siaga.printPDF');

  // Pendaftaran penggalang
  Route::get('/pendaftaran/penggalang', [PendaftaranController::class, 'indexPenggalang'])->name('pendaftaran.penggalang.index');
  Route::post('/pendaftaran/penggalang/detail/{id}', [PendaftaranController::class, 'detailPenggalang'])->name('pendaftaran.penggalang.detail');
  Route::post('/pendaftaran/penggalang/terima/{id}', [PendaftaranController::class, 'terimaPenggalang'])->name('pendaftaran.penggalang.terima');
  Route::post('/pendaftaran/penggalang/tolak/{id}', [PendaftaranController::class, 'tolakPenggalang'])->name('pendaftaran.penggalang.tolak');
  Route::delete('/pendaftaran/penggalang/delete/{id}', [PendaftaranController::class, 'destroyPenggalang'])->name('pendaftaran.penggalang.delete');
  Route::get('/pendaftaran/penggalang/printPDF', [PendaftaranController::class, 'printPDFPenggalang'])->name('pendaftaran.penggalang.printPDF');

  // Pendaftaran penegak
  Route::get('/pendaftaran/penegak', [PendaftaranController::class, 'indexPenegak'])->name('pendaftaran.penegak.index');
  Route::post('/pendaftaran/penegak/detail/{id}', [PendaftaranController::class, 'detailPenegak'])->name('pendaftaran.penegak.detail');
  Route::post('/pendaftaran/penegak/terima/{id}', [PendaftaranController::class, 'terimaPenegak'])->name('pendaftaran.penegak.terima');
  Route::post('/pendaftaran/penegak/tolak/{id}', [PendaftaranController::class, 'tolakPenegak'])->name('pendaftaran.penegak.tolak');
  Route::delete('/pendaftaran/penegak/delete/{id}', [PendaftaranController::class, 'destroyPenegak'])->name('pendaftaran.penegak.delete');
  Route::get('/pendaftaran/penegak/printPDF', [PendaftaranController::class, 'printPDFPenegak'])->name('pendaftaran.penegak.printPDF');

  // Pendaftaran pandega
  Route::get('/pendaftaran/pandega', [PendaftaranController::class, 'indexPandega'])->name('pendaftaran.pandega.index');
  Route::post('/pendaftaran/pandega/detail/{id}', [PendaftaranController::class, 'detailPandega'])->name('pendaftaran.pandega.detail');
  Route::post('/pendaftaran/pandega/terima/{id}', [PendaftaranController::class, 'terimaPandega'])->name('pendaftaran.pandega.terima');
  Route::post('/pendaftaran/pandega/tolak/{id}', [PendaftaranController::class, 'tolakPandega'])->name('pendaftaran.pandega.tolak');
  Route::delete('/pendaftaran/pandega/delete/{id}', [PendaftaranController::class, 'destroyPandega'])->name('pendaftaran.pandega.delete');
  Route::get('/pendaftaran/pandega/printPDF', [PendaftaranController::class, 'printPDFPandega'])->name('pendaftaran.pandega.printPDF');

  // Surat kelulusan
  Route::get('/surat-kelulusan', [ArsipController::class, 'indexKelulusan'])->name('surat-kelulusan.index');
  Route::get('/surat-kelulusan/tambah', [ArsipController::class, 'createKelulusan'])->name('surat-kelulusan.create');
  Route::post('/surat-kelulusan/store', [ArsipController::class, 'storeKelulusan'])->name('surat-kelulusan.store');
  Route::get('/surat-kelulusan/{id}/edit', [ArsipController::class, 'editKelulusan'])->name('surat-kelulusan.edit');
  Route::post('/surat-kelulusan/update/{id}', [ArsipController::class, 'updateKelulusan'])->name('surat-kelulusan.update');
  Route::delete('/surat-kelulusan/delete/{id}', [ArsipController::class, 'destroyKelulusan'])->name('surat-kelulusan.delete');

  // Berkas Pendaftaran
  Route::get('/berkas-pendaftaran', [ArsipController::class, 'indexPendaftaran'])->name('berkas-pendaftaran.index');
  Route::get('/berkas-pendaftaran/tambah', [ArsipController::class, 'createPendaftaran'])->name('berkas-pendaftaran.create');
  Route::post('/berkas-pendaftaran/store', [ArsipController::class, 'storePendaftaran'])->name('berkas-pendaftaran.store');
  Route::get('/berkas-pendaftaran/{id}/edit', [ArsipController::class, 'editPendaftaran'])->name('berkas-pendaftaran.edit');
  Route::post('/berkas-pendaftaran/update/{id}', [ArsipController::class, 'updatePendaftaran'])->name('berkas-pendaftaran.update');
  Route::delete('/berkas-pendaftaran/delete/{id}', [ArsipController::class, 'destroyPendaftaran'])->name('berkas-pendaftaran.delete');

  // Berkas lain-lain
  Route::get('/berkas-lain', [ArsipController::class, 'indexLain'])->name('berkas-lain.index');
  Route::get('/berkas-lain/tambah', [ArsipController::class, 'createLain'])->name('berkas-lain.create');
  Route::post('/berkas-lain/store', [ArsipController::class, 'storeLain'])->name('berkas-lain.store');
  Route::get('/berkas-lain/{id}/edit', [ArsipController::class, 'editLain'])->name('berkas-lain.edit');
  Route::post('/berkas-lain/update/{id}', [ArsipController::class, 'updateLain'])->name('berkas-lain.update');
  Route::delete('/berkas-lain/delete/{id}', [ArsipController::class, 'destroyLain'])->name('berkas-lain.delete');

  // Pengaturan
  Route::get('/pengaturan/profile', [PengaturanController::class, 'profile'])->name('pengaturan.profile');
  Route::post('/pengaturan/profile/{id}', [PengaturanController::class, 'updateProfile'])->name('pengaturan.updateProfile');
  Route::post('/pengaturan/hapus-foto', [PengaturanController::class, 'hapusFoto'])->name('pengaturan.hapusFoto');

  Route::get('/pengaturan/ganti-password', [PengaturanController::class, 'gantiPassword'])->name('pengaturan.gantiPassword');
  Route::post('/pengaturan/ganti-password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.updatePassword');

  Route::get('/pengaturan/nonaktif-akun', [PengaturanController::class, 'nonaktif'])->name('pengaturan.nonaktifAkun');
  Route::post('/pengaturan/nonaktif-akun', [PengaturanController::class, 'updateStatus'])->name('pengaturan.updateStatus');
});

// Administrator & Panitia
Route::middleware(['auth', 'user-access:Administrator,Panitia'])->group(function () {
  // Golongan
  Route::get('/golongan', [GolonganController::class, 'index'])->name('golongan.index');
  Route::get('/golongan/tambah', [GolonganController::class, 'create'])->name('golongan.create');
  Route::post('/golongan/store', [GolonganController::class, 'store'])->name('golongan.store');
  Route::get('/golongan/{id}/edit', [GolonganController::class, 'edit'])->name('golongan.edit');
  Route::delete('/golongan/delete/{id}', [GolonganController::class, 'destroy'])->name('golongan.delete');

  // Soal
  Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');
  Route::get('/soal/tambah', [SoalController::class, 'create'])->name('soal.create');
  Route::post('/soal/store', [SoalController::class, 'store'])->name('soal.store');
  Route::get('/soal/{id}/edit', [SoalController::class, 'edit'])->name('soal.edit');
  Route::delete('/soal/delete/{id}', [SoalController::class, 'destroy'])->name('soal.delete');

  // Timeline
  Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline.index');
  Route::get('/timeline/tambah', [TimelineController::class, 'create'])->name('timeline.create');
  Route::post('/timeline/store', [TimelineController::class, 'store'])->name('timeline.store');
  Route::get('/timeline/{id}/edit', [TimelineController::class, 'edit'])->name('timeline.edit');
  Route::post('/timeline/update/{id}', [TimelineController::class, 'update'])->name('timeline.update');
  Route::delete('/timeline/delete/{id}', [TimelineController::class, 'destroy'])->name('timeline.delete');

  // Konten
  Route::get('/konten', [KontenController::class, 'index'])->name('konten.index');
  Route::post('konten/publish/{konten}', [KontenController::class, 'publish'])->name('konten.publish');
  Route::post('konten/pending/{konten}', [KontenController::class, 'pending'])->name('konten.pending');
  Route::get('/konten/tambah', [KontenController::class, 'create'])->name('konten.create');
  Route::post('/konten/store', [KontenController::class, 'store'])->name('konten.store');
  Route::get('/konten/{id}/edit', [KontenController::class, 'edit'])->name('konten.edit');
  Route::post('/konten/update/{id}', [KontenController::class, 'update'])->name('konten.update');
  Route::delete('/konten/delete/{id}', [KontenController::class, 'destroy'])->name('konten.delete');
});

// Administrator & Juri
Route::middleware(['auth', 'user-access:Administrator,Juri'])->group(function () {
  // Penilaian
  Route::get('/penilaian-siaga', [PenilaianController::class, 'indexSiaga'])->name('penilaian-siaga.index');
  Route::get('/penilaian-siaga/{id}', [PenilaianController::class, 'nilaiSiaga'])->name('penilaian-siaga.nilai');
  Route::post('/penilaian-siaga/simpan-penilaian', [PenilaianController::class, 'simpanPenilaianSiaga'])->name('penilaian-siaga.simpan');

  // Penilaian
  Route::get('/penilaian-penggalang', [PenilaianController::class, 'indexPenggalang'])->name('penilaian-penggalang.index');
  Route::get('/penilaian-penggalang/{id}', [PenilaianController::class, 'nilaiPenggalang'])->name('penilaian-penggalang.nilai');
  Route::post('/penilaian-penggalang/simpan-penilaian', [PenilaianController::class, 'simpanPenilaianPenggalang'])->name('penilaian-penggalang.simpan');

  // Penilaian
  Route::get('/penilaian-penegak', [PenilaianController::class, 'indexPenegak'])->name('penilaian-penegak.index');
  Route::get('/penilaian-penegak/{id}', [PenilaianController::class, 'nilaiPenegak'])->name('penilaian-penegak.nilai');
  Route::post('/penilaian-penegak/simpan-penilaian', [PenilaianController::class, 'simpanPenilaianPenegak'])->name('penilaian-penegak.simpan');

  // Penilaian
  Route::get('/penilaian-pandega', [PenilaianController::class, 'indexPandega'])->name('penilaian-pandega.index');
  Route::get('/penilaian-pandega/{id}', [PenilaianController::class, 'nilaiPandega'])->name('penilaian-pandega.nilai');
  Route::post('/penilaian-pandega/simpan-penilaian', [PenilaianController::class, 'simpanPenilaianPandega'])->name('penilaian-pandega.simpan');
});

// Administrator
Route::middleware(['auth', 'user-access:Administrator'])->group(function () {
  // Pengguna all
  Route::get('/pengguna/semua', [UserController::class, 'indexAll'])->name('pengguna.semua.index');
  Route::get('/pengguna/semua/tambah', [UserController::class, 'createAll'])->name('pengguna.semua.create');
  Route::post('/pengguna/semua/store', [UserController::class, 'storeAll'])->name('pengguna.semua.store');
  Route::get('/pengguna/semua/edit/{id}', [UserController::class, 'editAll'])->name('pengguna.semua.edit');
  Route::post('/pengguna/semua/update/{id}', [UserController::class, 'updateAll'])->name('pengguna.semua.update');
  Route::delete('/pengguna/semua/delete/{id}', [UserController::class, 'destroyAll'])->name('pengguna.semua.delete');

  // Pengguna juri
  Route::get('/pengguna/juri', [UserController::class, 'indexJuri'])->name('pengguna.juri.index');
  Route::get('/pengguna/juri/tambah', [UserController::class, 'createJuri'])->name('pengguna.juri.create');
  Route::post('/pengguna/juri/store', [UserController::class, 'storeJuri'])->name('pengguna.juri.store');
  Route::get('/pengguna/juri/edit/{id}', [UserController::class, 'editJuri'])->name('pengguna.juri.edit');
  Route::post('/pengguna/juri/update/{id}', [UserController::class, 'updateJuri'])->name('pengguna.juri.update');
  Route::delete('/pengguna/juri/delete/{id}', [UserController::class, 'destroyJuri'])->name('pengguna.juri.delete');

  // Pengguna panitia
  Route::get('/pengguna/panitia', [UserController::class, 'indexPanitia'])->name('pengguna.panitia.index');
  Route::get('/pengguna/panitia/tambah', [UserController::class, 'createPanitia'])->name('pengguna.panitia.create');
  Route::post('/pengguna/panitia/store', [UserController::class, 'storePanitia'])->name('pengguna.panitia.store');
  Route::get('/pengguna/panitia/edit/{id}', [UserController::class, 'editPanitia'])->name('pengguna.panitia.edit');
  Route::post('/pengguna/panitia/update/{id}', [UserController::class, 'updatePanitia'])->name('pengguna.panitia.update');
  Route::delete('/pengguna/panitia/delete/{id}', [UserController::class, 'destroyPanitia'])->name('pengguna.panitia.delete');

  // Pengguna peserta
  Route::get('/pengguna/peserta', [UserController::class, 'indexPeserta'])->name('pengguna.peserta.index');
  Route::get('/pengguna/peserta/tambah', [UserController::class, 'createPeserta'])->name('pengguna.peserta.create');
  Route::post('/pengguna/peserta/store', [UserController::class, 'storePeserta'])->name('pengguna.peserta.store');
  Route::get('/pengguna/peserta/edit/{id}', [UserController::class, 'editPeserta'])->name('pengguna.peserta.edit');
  Route::post('/pengguna/peserta/update/{id}', [UserController::class, 'updatePeserta'])->name('pengguna.peserta.update');
  Route::delete('/pengguna/peserta/delete/{id}', [UserController::class, 'destroyPeserta'])->name('pengguna.peserta.delete');
});
