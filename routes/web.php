<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApotekController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Terapis\TerapisAuthController;
use App\Http\Controllers\Terapis\PatientManagementController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// ============================================================
// AUTH ROUTES - USER (Guest only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ============================================================
// AUTH ROUTES - ADMIN (Guest only)
// ============================================================
Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');
    Route::post('/generate-code', [AdminAuthController::class, 'generateAdminCode'])->name('generate.code');
});

// ============================================================
// AUTH ROUTES - TERAPIS (Guest only)
// ============================================================
Route::prefix('terapis')->name('terapis.')->middleware('guest')->group(function () {
    Route::get('/login', [TerapisAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [TerapisAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [TerapisAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [TerapisAuthController::class, 'register'])->name('register.submit');
    Route::post('/generate-code', [TerapisAuthController::class, 'generateTerapisCode'])->name('generate.code');
});

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/apotek', [ApotekController::class, 'index'])->name('apotek.index');

// Route antrian - accessible to all (controller will redirect if not auth)
Route::get('/antrian', [AntrianController::class, 'index'])->name('booking.index');

// ============================================================
// AUTHENTICATED USER ROUTES
// ============================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/medical-record/{id}', [ProfileController::class, 'showMedicalRecord'])->name('profile.medical-record.show');
    
    // Antrian routes (require auth)
    Route::post('/antrian/store', [AntrianController::class, 'store'])->name('booking.store');
    Route::post('/antrian/cek', [AntrianController::class, 'cek'])->name('booking.cek');
});

// ============================================================
// ADMIN ROUTES (Auth + Admin only)
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Antrian management
    Route::put('/antrian/{id}/status', [AdminController::class, 'updateStatus'])->name('antrian.update');
    Route::delete('/antrian/{id}', [AdminController::class, 'destroy'])->name('antrian.delete');
    
    // Patient management (Admin can also access)
    Route::get('/patients', [PatientManagementController::class, 'index'])->name('patients.index');
    Route::get('/patients/{id}', [PatientManagementController::class, 'show'])->name('patients.show');
    
    // Medical records (Admin)
    Route::get('/medical-records/create/{patientId}', [PatientManagementController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records/store/{patientId}', [PatientManagementController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{recordId}', [PatientManagementController::class, 'showMedicalRecord'])->name('medical-records.show');
});

// ============================================================
// TERAPIS ROUTES (Auth + Terapis/Admin)
// ============================================================
Route::middleware(['auth', 'terapis'])->prefix('terapis')->name('terapis.')->group(function () {
    Route::post('/logout', [TerapisAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [PatientManagementController::class, 'dashboard'])->name('dashboard');
    
    // Patient management
    Route::get('/patients', [PatientManagementController::class, 'index'])->name('patients.index');
    Route::get('/patients/{id}', [PatientManagementController::class, 'show'])->name('patients.show');
    
    // Medical records
    Route::get('/medical-records/create/{patientId}', [PatientManagementController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records/store/{patientId}', [PatientManagementController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{recordId}', [PatientManagementController::class, 'showMedicalRecord'])->name('medical-records.show');
});
    
// HAPUS ATAU COMMENT SEMUA BARIS DI BAWAH INI:
/*    
Route::post('/ambil-antrian', [QueueController::class, 'store'])->name('antrian.store');
Route::get('/antrian', [QueueController::class, 'index'])->name('antrian.index');
Route::post('/antrian/{id}/call', [QueueController::class, 'call'])->name('antrian.call');
Route::post('/antrian/{id}/done', [QueueController::class, 'done'])->name('antrian.done');

Route::get('/laporan-transaksi', [TransactionReportController::class, 'index'])->name('laporan.index');
Route::get('/laporan-transaksi/pdf', [TransactionReportController::class, 'exportPdf'])->name('laporan.pdf');

Route::get('/rekam-medis/{queueId}', [MedicalRecordController::class, 'create'])->name('rekam-medis.create');
Route::post('/rekam-medis/{queueId}', [MedicalRecordController::class, 'store'])->name('rekam-medis.store');
Route::get('/pasien/{patientId}/rekam-medis', [MedicalRecordController::class, 'history'])->name('rekam-medis.history');
*/
