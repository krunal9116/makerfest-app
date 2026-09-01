<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MakerFestController;

// Auth Routes (Per PDF 4 Auth Specification)
Route::get('/login', [AuthController::class, 'showParticipantLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginParticipant'])->middleware('throttle:6,1')->name('login.post');

// Maker Public Registration Routes
Route::get('/register', [AuthController::class, 'showMakerRegister'])->name('maker.register');
Route::post('/register', [AuthController::class, 'registerMaker'])->middleware('throttle:6,1')->name('maker.register.post');

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->middleware('throttle:6,1')->name('admin.login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Language Switcher
Route::get('/lang/{lang}', [MakerFestController::class, 'setLocale'])->name('setLocale');

// Dashboards
Route::get('/', [MakerFestController::class, 'index'])->name('dashboard');
Route::get('/admin/dashboard', [MakerFestController::class, 'index'])->name('admin.dashboard');
Route::get('/maker/dashboard', [MakerFestController::class, 'index'])->name('maker.dashboard');
Route::get('/judge/dashboard', [MakerFestController::class, 'index'])->name('judge.dashboard');
Route::get('/volunteer/dashboard', [MakerFestController::class, 'index'])->name('volunteer.dashboard');

// OTP Prototype Routes for Maker Registration
Route::post('/send-otp', [AuthController::class, 'sendRegistrationOtp'])->middleware('throttle:5,1')->name('otp.send');
Route::post('/verify-otp', [AuthController::class, 'verifyRegistrationOtp'])->middleware('throttle:5,1')->name('otp.verify');

// Profile View & Update Routes
Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
Route::post('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password');

// Forgot Password via OTP Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password/send-otp', [AuthController::class, 'sendForgotOtp'])->name('password.forgot.sendOtp');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPasswordWithOtp'])->name('password.forgot.reset');

// Admin Staff Creation Route (Judges & Volunteers)
Route::post('/admin/create-user', [AuthController::class, 'createStaffUser'])->name('admin.createUser');

// Project Form Page Route
Route::get('/maker/project/new', [MakerFestController::class, 'showNewProjectForm'])->name('maker.newProject');

// Endpoints
Route::post('/maker/project', [MakerFestController::class, 'saveProject'])->name('maker.saveProject');
Route::post('/maker/project/{id}/delete', [MakerFestController::class, 'deleteDraft'])->name('maker.deleteDraft');
Route::post('/admin/project/{id}/status', [MakerFestController::class, 'updateProjectStatus'])->name('admin.updateProjectStatus');

// New Admin Feature Routes
Route::post('/admin/event', [MakerFestController::class, 'storeEvent'])->name('admin.storeEvent');
Route::post('/admin/judge/assign', [MakerFestController::class, 'assignJudge'])->name('admin.assignJudge');
Route::post('/judge/evaluate', [MakerFestController::class, 'submitEvaluation'])->name('judge.evaluate');
Route::post('/admin/task/assign', [MakerFestController::class, 'assignTask'])->name('admin.assignTask');
Route::post('/admin/broadcast', [MakerFestController::class, 'broadcastMail'])->name('admin.broadcastMail');
Route::post('/admin/user/{id}/delete', [MakerFestController::class, 'deleteUser'])->name('admin.deleteUser');

