<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnggotaTransaksiController;
use App\Http\Controllers\AnggotaNotifController;
use App\Http\Controllers\AnggotaBukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AnggotaDashboardController;
use App\Http\Controllers\ProfilAnggotaController;
use App\Http\Controllers\KepalaBookController;
use App\Http\Controllers\KepalaTransaksiController;
use App\Http\Controllers\KepalaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\PetugasPengajuanController;
use App\Http\Controllers\PetugasProfilController;
use App\Http\Controllers\PetugasPengembalianController;
use App\Http\Controllers\PetugasBukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.post');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Dashboard routes (auth only)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/anggota', [AnggotaDashboardController::class, 'index'])->name('dashboard.anggota');
    Route::get('/anggota/transaksi', [AnggotaTransaksiController::class, 'index'])->name('anggota.transaksi');
    Route::patch('/anggota/transaksi/{loan}/kembalikan', [AnggotaTransaksiController::class, 'kembalikan'])->name('anggota.transaksi.kembalikan');
    Route::get('/anggota/notif', [AnggotaNotifController::class, 'index'])->name('anggota.notif');
    Route::patch('/anggota/notif/{notification}/read', [AnggotaNotifController::class, 'markRead'])->name('anggota.notif.read');
    Route::get('/anggota/buku', [AnggotaBukuController::class, 'index'])->name('anggota.buku.index');
    Route::get('/anggota/buku/{book}', [AnggotaBukuController::class, 'show'])->name('anggota.buku.show');
    Route::post('/anggota/buku/{book}/ajukan', [AnggotaBukuController::class, 'ajukan'])->name('anggota.buku.ajukan');
    Route::get('/anggota/profil', [ProfilAnggotaController::class, 'index'])->name('anggota.profil');
    Route::put('/anggota/profil', [ProfilAnggotaController::class, 'update'])->name('anggota.profil.update');
    Route::delete('/anggota/profil/photo', [ProfilAnggotaController::class, 'deletePhoto'])->name('anggota.profil.deletePhoto');
    Route::get('/dashboard/petugas', [PetugasDashboardController::class, 'index'])->name('dashboard.petugas');
    Route::get('/petugas/pengajuan', [PetugasPengajuanController::class, 'index'])->name('petugas.pengajuan');
    Route::post('/petugas/pengajuan/{loan}/tolak', [PetugasPengajuanController::class, 'tolak'])->name('petugas.pengajuan.tolak');
    Route::patch('/petugas/pengajuan/{loan}/pinjamkan', [PetugasPengajuanController::class, 'pinjamkan'])->name('petugas.pengajuan.pinjamkan');
    Route::get('/petugas/pengajuan/{loan}/detail', [PetugasPengajuanController::class, 'detail'])->name('petugas.pengajuan.detail');
    Route::get('/petugas/pengajuan/{loan}/cetak', [PetugasPengajuanController::class, 'cetak'])->name('petugas.pengajuan.detail.cetak');

    Route::get('/petugas/profil', [PetugasProfilController::class, 'index'])->name('petugas.profil');
    Route::get('/petugas/profil/edit', [PetugasProfilController::class, 'edit'])->name('petugas.profil.edit');
    Route::put('/petugas/profil', [PetugasProfilController::class, 'update'])->name('petugas.profil.update');

    Route::get('/petugas/pengembalian', [PetugasPengembalianController::class, 'index'])->name('petugas.pengembalian');
    Route::post('/petugas/pengembalian/{loan}/konfirmasi', [PetugasPengembalianController::class, 'konfirmasi'])->name('petugas.pengembalian.konfirmasi');
    Route::get('/petugas/pengembalian/{loan}/hitung-denda', [PetugasPengembalianController::class, 'hitungDenda'])->name('petugas.pengembalian.hitung');
    Route::post('/petugas/pengembalian/{loan}/kirim-denda', [PetugasPengembalianController::class, 'kirimDenda'])->name('petugas.pengembalian.kirim');
    Route::patch('/petugas/pengembalian/{bookReturn}/lunas', [PetugasPengembalianController::class, 'lunasDenda'])->name('petugas.pengembalian.lunas');
    Route::get('/petugas/pengembalian/{bookReturn}/detail', [PetugasPengembalianController::class, 'detail'])->name('petugas.pengembalian.detail');
    Route::get('/petugas/pengembalian/{bookReturn}/cetak', [PetugasPengembalianController::class, 'cetak'])->name('petugas.pengembalian.cetak');

    Route::get('/petugas/buku', [PetugasBukuController::class, 'index'])->name('petugas.buku.index');
    Route::get('/petugas/buku/create', [PetugasBukuController::class, 'create'])->name('petugas.buku.create');
    Route::post('/petugas/buku', [PetugasBukuController::class, 'store'])->name('petugas.buku.store');
    Route::get('/petugas/buku/{book}/edit', [PetugasBukuController::class, 'edit'])->name('petugas.buku.edit');
    Route::put('/petugas/buku/{book}', [PetugasBukuController::class, 'update'])->name('petugas.buku.update');
    Route::delete('/petugas/buku/{book}', [PetugasBukuController::class, 'destroy'])->name('petugas.buku.destroy');
    Route::get('/kepala/buku', [KepalaBookController::class, 'index'])->name('kepala.buku.index');
    Route::get('/kepala/buku/create', [KepalaBookController::class, 'create'])->name('kepala.buku.create');
    Route::post('/kepala/buku', [KepalaBookController::class, 'store'])->name('kepala.buku.store');
    Route::get('/kepala/buku/{book}/edit', [KepalaBookController::class, 'edit'])->name('kepala.buku.edit');
    Route::put('/kepala/buku/{book}', [KepalaBookController::class, 'update'])->name('kepala.buku.update');
    Route::delete('/kepala/buku/{book}', [KepalaBookController::class, 'destroy'])->name('kepala.buku.destroy');

    Route::get('/kepala/transaksi', [KepalaTransaksiController::class, 'index'])->name('kepala.transaksi');
    Route::get('/dashboard/kepala', [KepalaController::class, 'dashboard'])->name('dashboard.kepala');

    Route::get('kepala/anggota', [AnggotaController::class, 'index'])->name('kepala.anggota.index');
    Route::get('kepala/anggota/{user}', [AnggotaController::class, 'show'])->name('kepala.anggota.show');
    Route::delete('kepala/anggota/{user}', [AnggotaController::class, 'destroy'])->name('kepala.anggota.destroy');

    Route::resource('kepala/petugas', PetugasController::class)
        ->parameters(['petugas' => 'user'])
        ->names([
            'index'   => 'kepala.petugas.index',
            'create'  => 'kepala.petugas.create',
            'store'   => 'kepala.petugas.store',
            'show'    => 'kepala.petugas.show',
            'edit'    => 'kepala.petugas.edit',
            'update'  => 'kepala.petugas.update',
            'destroy' => 'kepala.petugas.destroy',
        ]);
});
