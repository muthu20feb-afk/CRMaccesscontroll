<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('app');
// });
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/dashboard', [LoginController::class, 'Dashboard'])->name('dashboard')->middleware('auth');
Route::get('registration', [LoginController::class, 'registration'])->name('register');
Route::post('post-registration', [LoginController::class, 'postRegistration'])->name('register.post'); 
Route::post('post-login', [LoginController::class, 'postLogin'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/check-email', [LoginController::class, 'checkEmail'])->name('check.email');
Route::post('/check-password', [LoginController::class, 'checkPassword'])->name('check.password');



Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index')->middleware(['auth', 'permission:view-role']);
Route::post('/roles/store', [RolePermissionController::class, 'storeRole'])->name('roles.store');
Route::post('/permissions/store', [RolePermissionController::class, 'storePermission'])->name('permissions.store');
Route::post('/roles/{id}/assign', [RolePermissionController::class, 'assignPermission'])->name('roles.assign');

Route::middleware(['auth', 'permission'])->group(function () {
Route::get('/user-roles', [UserController::class, 'userRoleIndex'])->name('user.roles.index');
Route::post('/user-roles/{id}/assign', [UserController::class, 'assignUserRole'])->name('user.roles.assign');
Route::post('/user-roles/store', [UserController::class, 'storeUser'])->name('user.roles.store');
Route::post('/user/{id}/assign-roles', [UserController::class, 'assignRoles'])->name('user.roles.assign');
Route::get('/user-roles/filter', [UserController::class, 'filter'])->name('user.roles.filter');
Route::get('/users/ajax', [UserController::class, 'getUsers'])->name('users.ajax');
});
Route::get('roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
Route::put('roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
Route::delete('roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy');
Route::get('/roles/ajax', [RolePermissionController::class, 'RolesandPermissionData'])->name('roles.ajax');

Route::get('permissions/{permission}/edit', [RolePermissionController::class, 'editPermission'])->name('permissions.edit');
Route::put('permissions/{permission}', [RolePermissionController::class, 'updatePermission'])->name('permissions.update');
Route::delete('permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');

Route::get('/permissions/manage', [PermissionController::class, 'manage'])->name('permissions.manage');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/save', [SettingsController::class, 'storeOrUpdate'])->name('settings.save');