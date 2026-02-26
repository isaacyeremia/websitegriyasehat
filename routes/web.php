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
use App\Http\Controllers\Admin\DoctorManagementController;

// ============================================================
// ROOT / HOMEPAGE - Tampilkan home.blade.php
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return view('home');
    }
    return view('home');
})->name('welcome');

// ============================================================
// AUTH ROUTES - USER (Guest only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // FORGOT PASSWORD ROUTES
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.send');
    Route::get('/verify-token', [AuthController::class, 'showVerifyToken'])->name('password.verify');
    Route::post('/verify-token', [AuthController::class, 'verifyToken'])->name('password.verify.submit');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
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
    
    // Konfirmasi Kedatangan
    Route::post('/antrian/{id}/confirm-arrival', [AntrianController::class, 'confirmArrival'])->name('booking.confirm-arrival');
    Route::post('/antrian/{id}/cancel-arrival', [AntrianController::class, 'cancelArrival'])->name('booking.cancel-arrival');
    
    // API routes for AJAX - NEW ENDPOINTS
    Route::get('/api/doctor/{id}/schedule', [AntrianController::class, 'getDoctorSchedule'])->name('api.doctor.schedule');
    Route::get('/api/doctor/{id}/available-dates', [AntrianController::class, 'getAvailableDates'])->name('api.doctor.dates');
    Route::get('/api/available-doctors/{date}', [AntrianController::class, 'getAvailableDoctors'])->name('api.available.doctors');
    Route::get('/api/booked-slots/{doctorName}/{date}', [AntrianController::class, 'getBookedSlots'])->name('api.booked.slots');
    Route::get('/api/service-durations', [AntrianController::class, 'getServiceDurations'])->name('api.service.durations');
});

// ============================================================
// ADMIN ROUTES (Auth + Admin only)
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/live-data', [AdminController::class, 'liveData'])->name('live.data');
    
    // Kelola Antrian
    Route::put('/antrian/{id}/status', [AdminController::class, 'updateStatus'])->name('antrian.update');
    Route::delete('/antrian/{id}', [AdminController::class, 'destroy'])->name('antrian.delete');
    
    // Manajemen Pasien (Admin)
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients.index');
    Route::get('/patients/{id}', [AdminController::class, 'showPatient'])->name('patients.show');
    Route::get('/patients/{id}/edit', [AdminController::class, 'editPatient'])->name('patients.edit');
    Route::put('/patients/{id}', [AdminController::class, 'updatePatient'])->name('patients.update');
    Route::delete('/patients/{id}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');
    
    // Manajemen AKUN terapis
    Route::get('/terapis', [AdminController::class, 'terapisIndex'])->name('terapis.index');
    Route::get('/terapis/create', [AdminController::class, 'createTerapis'])->name('terapis.create');
    Route::post('/terapis', [AdminController::class, 'storeTerapis'])->name('terapis.store');
    Route::get('/terapis/{id}/edit', [AdminController::class, 'editTerapis'])->name('terapis.edit');
    Route::put('/terapis/{id}', [AdminController::class, 'updateTerapis'])->name('terapis.update');
    Route::delete('/terapis/{id}', [AdminController::class, 'destroyTerapis'])->name('terapis.destroy');
    
    // Manajemen Jadwal Praktek (Admin)
    Route::get('/schedules', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{id}/edit', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{id}', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{id}', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::patch('/schedules/{id}/toggle', [\App\Http\Controllers\Admin\DoctorScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
    
    // Medical records (Admin can access)
    Route::get('/patients/{patientId}/medical-record/create', [AdminController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/patients/{patientId}/medical-record', [AdminController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{recordId}', [AdminController::class, 'showMedicalRecord'])->name('medical-records.show');
    
    // All Medical Records View (NEW)
    Route::get('/all-medical-records', [AdminController::class, 'allMedicalRecords'])->name('medical-records.all');

    // Manajemen Produk Apotek (Admin)
    Route::get('/pharmacy', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'index'])->name('pharmacy.index');
    Route::get('/pharmacy/create', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'create'])->name('pharmacy.create');
    Route::post('/pharmacy', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'store'])->name('pharmacy.store');
    Route::get('/pharmacy/{id}/edit', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'edit'])->name('pharmacy.edit');
    Route::put('/pharmacy/{id}', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'update'])->name('pharmacy.update');
    Route::delete('/pharmacy/{id}', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'destroy'])->name('pharmacy.destroy');
    Route::patch('/pharmacy/{id}/toggle', [\App\Http\Controllers\Admin\PharmacyProductController::class, 'toggleStatus'])->name('pharmacy.toggle');

   // Manajemen PROFIL terapis
    Route::get('/terapis', [DoctorManagementController::class, 'index'])->name('terapis.index');
    Route::get('/terapis/create', [DoctorManagementController::class, 'create'])->name('terapis.create');
    Route::post('/terapis', [DoctorManagementController::class, 'store'])->name('terapis.store');
    Route::get('/terapis/{id}/edit', [DoctorManagementController::class, 'edit'])->name('terapis.edit');
    Route::put('/terapis/{id}', [DoctorManagementController::class, 'update'])->name('terapis.update');
    Route::delete('/terapis/{id}', [DoctorManagementController::class, 'destroy'])->name('terapis.destroy');
    Route::patch('/terapis/{id}/toggle', [DoctorManagementController::class, 'toggleActive'])->name('terapis.toggle');
    Route::patch('/terapis/{id}/toggle-about', [DoctorManagementController::class, 'toggleAbout'])->name('terapis.toggle-about');
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
    Route::get('/patients/{id}/edit', [PatientManagementController::class, 'editPatient'])->name('patients.edit');
    Route::put('/patients/{id}', [PatientManagementController::class, 'updatePatient'])->name('patients.update');
    
    // Medical records
    Route::get('/medical-records/create/{patientId}', [PatientManagementController::class, 'createMedicalRecord'])->name('medical-records.create');
    Route::post('/medical-records/store/{patientId}', [PatientManagementController::class, 'storeMedicalRecord'])->name('medical-records.store');
    Route::get('/medical-records/{recordId}', [PatientManagementController::class, 'showMedicalRecord'])->name('medical-records.show');
    
    // EDIT & UPDATE MEDICAL RECORDS - NEW ROUTES
    Route::get('/medical-records/{recordId}/edit', [PatientManagementController::class, 'editMedicalRecord'])->name('medical-records.edit');
    Route::put('/medical-records/{recordId}', [PatientManagementController::class, 'updateMedicalRecord'])->name('medical-records.update');
});