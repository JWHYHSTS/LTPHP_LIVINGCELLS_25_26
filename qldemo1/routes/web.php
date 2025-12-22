<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\CtctController;
use App\Http\Controllers\KhaothiController;
use App\Http\Controllers\DoanController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\Admin\StaffProfileController;
use App\Http\Controllers\AiEventChatController;

// ✅ AI endpoint (web middleware mặc định + yêu cầu đăng nhập)
Route::post('/ai/event-chat', [AiEventChatController::class, 'chat'])
    ->middleware(['auth.session', 'active'])
    ->name('ai.eventChat');

Route::get('/', fn() => redirect()->route('login.show'));

// Auth
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

Route::get('/forgot', [AuthController::class, 'showForgot'])->name('forgot.show');
Route::post('/forgot',[AuthController::class, 'handleForgot'])->name('forgot.handle');
Route::get('/reset',  [AuthController::class, 'showReset'])->name('reset.show');
Route::post('/reset', [AuthController::class, 'handleReset'])->name('reset.handle');

// Dashboards theo vai trò
Route::middleware(['auth.session', 'active'])->group(function () {
    Route::get('/admin',      [DashboardController::class, 'admin'])->name('admin.home');
    Route::get('/sinhvien',   [DashboardController::class, 'sinhvien'])->name('sv.home');
    Route::get('/ctct-hssv',  [DashboardController::class, 'ctct'])->name('ctct.home');
    Route::get('/khaothi',    [DashboardController::class, 'khaothi'])->name('khaothi.home');
    Route::get('/doantruong', [DashboardController::class, 'doan'])->name('doan.home');
});

// Admin routes
Route::prefix('admin')
    ->middleware(['auth.session', 'active', 'role:Admin'])
    ->name('admin.')
    ->group(function () {

        Route::redirect('/', '/admin/accounts')->name('home');

        Route::controller(AccountController::class)
            ->prefix('accounts')
            ->name('accounts.')
            ->group(function () {
                Route::get('/',        'index')->name('index');
                Route::post('/store',  'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'delete')->name('delete');
                Route::post('/import', 'import')->name('import');
                Route::get('/template', 'downloadTemplate')->name('template');
            });

        Route::controller(StaffProfileController::class)
            ->prefix('staff')
            ->name('staff.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/upsert', 'upsert')->name('upsert');
            });
    });

// Khao Thi routes
Route::prefix('khaothi')
    ->middleware(['auth.session', 'active', 'role:KhaoThi'])
    ->name('khaothi.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('khaothi.sinhvien.index'))->name('home');
        Route::get('/sinhvien', [KhaothiController::class, 'sinhVienIndex'])->name('sinhvien.index');

        Route::get('/gpa',          [KhaothiController::class, 'gpaIndex'])->name('gpa.index');
        Route::post('/gpa/update',  [KhaothiController::class, 'gpaUpdate'])->name('gpa.update');
        Route::post('/gpa/delete',  [KhaothiController::class, 'gpaDelete'])->name('gpa.delete');
        Route::post('/gpa/import',  [KhaothiController::class, 'gpaImport'])->name('gpa.import');
        Route::get('/gpa/export',   [KhaothiController::class, 'gpaExport'])->name('gpa.export');
        Route::get('/gpa/template', [KhaothiController::class, 'gpaTemplate'])->name('gpa.template');

        Route::post('/password/change', [KhaothiController::class, 'changePassword'])
            ->middleware('throttle:5,1')
            ->name('password.change');
    });

// CTCT routes
Route::prefix('ctct')
    ->middleware(['auth.session', 'active', 'role:CTCTHSSV'])
    ->name('ctct.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('ctct.sinhvien.index'))->name('home');

        Route::get('/sinhvien', [CtctController::class, 'sinhVienIndex'])->name('sinhvien.index');
        Route::post('/sinhvien/store',  [CtctController::class, 'svStore'])->name('sv.store');
        Route::post('/sinhvien/update', [CtctController::class, 'svUpdate'])->name('sv.update');
        Route::post('/sinhvien/delete', [CtctController::class, 'svDelete'])->name('sv.delete');
        Route::post('/sinhvien/import', [CtctController::class, 'svImport'])->name('sv.import');
        Route::get('/sinhvien/template', [CtctController::class, 'svTemplate'])->name('sv.template');

        Route::get('/drl',        [CtctController::class, 'drlIndex'])->name('drl.index');
        Route::post('/drl/update',[CtctController::class, 'drlUpdate'])->name('drl.update');
        Route::post('/drl/delete',[CtctController::class, 'drlDelete'])->name('drl.delete');
        Route::post('/drl/import',[CtctController::class, 'drlImport'])->name('drl.import');
        Route::get('/drl/export', [CtctController::class, 'drlExport'])->name('drl.export');
        Route::get('/drl/template', [CtctController::class, 'drlTemplate'])->name('drl.template');

        Route::post('/doimatkhau', [CtctController::class, 'changePassword'])
            ->middleware('throttle:5,1')
            ->name('password.change');
    });

// Doan Truong routes
Route::prefix('doantruong')
    ->middleware(['auth.session', 'active', 'role:DoanTruong'])
    ->name('doan.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('doan.khenthuong.index'))->name('home');

        Route::get('/khenthuong', [DoanController::class, 'khenThuongIndex'])->name('khenthuong.index');
        Route::get('/tinhnguyen', [DoanController::class, 'tinhNguyenIndex'])->name('tinhnguyen.index');
        Route::get('/danhhieu',   [DoanController::class, 'danhHieuIndex'])->name('danhhieu.index');

        Route::get('/tinhnguyen/template', [DoanController::class, 'ntnTemplate'])->name('tinhnguyen.template');
        Route::get('/khenthuong/export',   [DoanController::class, 'exportExcel'])->name('khenthuong.export');

        Route::post('/danhhieu/store',  [DoanController::class, 'dhStore'])->name('danhhieu.store');
        Route::post('/danhhieu/update', [DoanController::class, 'dhUpdate'])->name('danhhieu.update');
        Route::post('/danhhieu/delete', [DoanController::class, 'dhDelete'])->name('danhhieu.delete');

        Route::post('/tinhnguyen/store',  [DoanController::class, 'ntnStore'])->name('tinhnguyen.store');
        Route::post('/tinhnguyen/update', [DoanController::class, 'ntnUpdate'])->name('tinhnguyen.update');
        Route::post('/tinhnguyen/delete', [DoanController::class, 'ntnDelete'])->name('tinhnguyen.delete');
        Route::post('/tinhnguyen/import', [DoanController::class, 'ntnImport'])->name('tinhnguyen.import');

        Route::post('/doimatkhau', [DoanController::class, 'changePassword'])
            ->middleware('throttle:5,1')
            ->name('password.change');

        Route::get('/sukien',              [DoanController::class, 'suKienIndex'])->name('sukien.index');
        Route::post('/sukien/store',       [DoanController::class, 'suKienStore'])->name('sukien.store');
        Route::post('/sukien/update',      [DoanController::class, 'suKienUpdate'])->name('sukien.update');
        Route::post('/sukien/delete',      [DoanController::class, 'suKienDelete'])->name('sukien.delete');
        Route::post('/sukien/toggle',      [DoanController::class, 'suKienToggle'])->name('sukien.toggle');

        Route::get('/sukien/dangky',       [DoanController::class, 'suKienDangKyIndex'])->name('sukien.dangky.index');
        Route::post('/sukien/diemdanh',    [DoanController::class, 'suKienDiemDanh'])->name('sukien.diemdanh');
    });

// Sinh Vien routes
Route::prefix('sinhvien')
    ->middleware(['auth.session', 'active', 'role:SinhVien'])
    ->group(function () {
        Route::get('/', [SinhVienController::class, 'index'])->name('sv.home');
        Route::get('/caidat', [SinhVienController::class, 'settings'])->name('sv.settings');

        Route::post('/caidat/doimk', [SinhVienController::class, 'changePassword'])
            ->middleware('throttle:5,1')
            ->name('sv.settings.password');

        Route::get('/sukien', [SinhVienController::class, 'suKienTinhNguyenIndex'])->name('sv.sukien.index');
        Route::post('/sukien/dangky',  [SinhVienController::class, 'suKienDangKy'])->name('sv.sukien.dangky');
        Route::get('/sukien/dadangky', [SinhVienController::class, 'suKienDaDangKy'])->name('sv.sukien.dadangky');
    });
