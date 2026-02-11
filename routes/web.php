<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AcademicsController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC SITE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/academics', [AcademicsController::class, 'index'])->name('academics');
Route::get('/admission', [AdmissionController::class, 'index'])->name('admission');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|----------------------------------------------------------------------
| DISABLE /register
|----------------------------------------------------------------------
*/
Route::match(['get', 'post'], '/register', fn () => abort(404));

/*
|----------------------------------------------------------------------
| BLOCK OLD URLS
|----------------------------------------------------------------------
| Completely remove these URLs (always 404).
*/
Route::any('/admin/{any?}', fn () => abort(404))->where('any', '.*');
Route::any('/login/{any?}', fn () => abort(404))->where('any', '.*');
Route::any('/dashboard/{any?}', fn () => abort(404))->where('any', '.*');

/*
|----------------------------------------------------------------------
| ADMIN LOGIN URL (alias)
|----------------------------------------------------------------------
| Friendly URL for admins: /tpc_admin/login -> goes to route('login') = /tpc_login
*/
Route::get('/tpc_admin/login', function () {
    abort_unless(Route::has('login'), 404);
    return redirect()->route('login');
})->name('admin.login');

/*
|----------------------------------------------------------------------
| ADMIN PANEL
|----------------------------------------------------------------------
| Admin + Super Admin can access this group.
| URL is /tpc_admin
*/
Route::middleware(['auth', 'admin'])
    ->prefix('tpc_admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('programs', ProgramController::class);

        Route::resource('news-posts', NewsPostController::class)
            ->parameters(['news-posts' => 'newsPost']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/unread-count', [ContactMessageController::class, 'unreadCount'])->name('messages.unreadCount');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');

        Route::patch('messages/{message}/read', [ContactMessageController::class, 'markRead'])->name('messages.read');
        Route::patch('messages/{message}/unread', [ContactMessageController::class, 'markUnread'])->name('messages.unread');

        /*
        |----------------------------------------------------------------------
        | SUPER ADMIN ONLY: Manage Admin/Staff accounts
        |----------------------------------------------------------------------
        */
        Route::middleware('super_admin')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });
    });

/*
|----------------------------------------------------------------------
| USER PROFILE (Breeze)
|----------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
