<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AcademicsController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrgChartController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\ProgramDetailController;
use App\Http\Controllers\Admin\ProgramPersonController;
use App\Http\Controllers\Admin\ProgramAchievementController;
use App\Http\Controllers\Admin\AboutSlideController;
use App\Http\Controllers\Admin\NewsReviewController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceContentController;
use App\Http\Controllers\Admin\OrgChartController as AdminOrgChartController;

/*
|--------------------------------------------------------------------------
| PUBLIC SITE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/academics', [AcademicsController::class, 'index'])->name('academics');
Route::get('/academics/{program:slug}', [AcademicsController::class, 'show'])->name('academics.show');

Route::get('/admission', [AdmissionController::class, 'index'])->name('admission');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/about/{aboutSlide}', [HomeController::class, 'showSlide'])->name('about.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/services/{service:slug}', [ServiceController::class, 'show'])
    ->name('services.show');

Route::get('/org-chart', [OrgChartController::class, 'index'])->name('org-chart');

Route::post('/news/{newsPost}/like',   [NewsController::class, 'like'])  ->name('news.like');
Route::post('/news/{newsPost}/unlike', [NewsController::class, 'unlike'])->name('news.unlike');

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
*/
Route::any('/admin/{any?}', fn () => abort(404))->where('any', '.*');
Route::any('/login/{any?}', fn () => abort(404))->where('any', '.*');
Route::any('/dashboard/{any?}', fn () => abort(404))->where('any', '.*');

/*
|----------------------------------------------------------------------
| ADMIN LOGIN URL (alias)
|----------------------------------------------------------------------
*/
Route::get('/tpc_admin/login', function () {
    abort_unless(Route::has('login'), 404);
    return redirect()->route('login');
})->name('admin.login');

/*
|----------------------------------------------------------------------
| ADMIN PANEL
|----------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('tpc_admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('programs', ProgramController::class);

        Route::delete('news-posts/bulk-destroy', [NewsPostController::class, 'bulkDestroy'])
            ->name('news-posts.bulkDestroy');

        Route::post('news-posts/{newsPost}/repost', [NewsPostController::class, 'repost'])
            ->name('news-posts.repost');
        Route::resource('news-posts', NewsPostController::class)
            ->parameters(['news-posts' => 'newsPost']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        // Messages — unread-count MUST be before {message} wildcard
        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/unread-count', [ContactMessageController::class, 'unreadCount'])->name('messages.unreadCount');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/read', [ContactMessageController::class, 'markRead'])->name('messages.read');
        Route::patch('messages/{message}/unread', [ContactMessageController::class, 'markUnread'])->name('messages.unread');

        Route::get('admission', [AdminAdmissionController::class, 'index'])
            ->name('admission.index');

        // Section edit
        Route::get('admission/sections/{section}/edit', [AdminAdmissionController::class, 'editSection'])
            ->name('admission.sections.edit');
        Route::patch('admission/sections/{section}', [AdminAdmissionController::class, 'updateSection'])
            ->name('admission.sections.update');

        // Section items
        Route::get('admission/sections/{section}/items/create', [AdminAdmissionController::class, 'createItem'])
            ->name('admission.sections.items.create');
        Route::post('admission/sections/{section}/items', [AdminAdmissionController::class, 'storeItem'])
            ->name('admission.sections.items.store');
        Route::get('admission/sections/{section}/items/{item}/edit', [AdminAdmissionController::class, 'editItem'])
            ->name('admission.sections.items.edit');
        Route::patch('admission/sections/{section}/items/{item}', [AdminAdmissionController::class, 'updateItem'])
            ->name('admission.sections.items.update');
        Route::delete('admission/sections/{section}/items/{item}', [AdminAdmissionController::class, 'destroyItem'])
            ->name('admission.sections.items.destroy');
        Route::delete('admission/sections/{section}/items', [AdminAdmissionController::class, 'bulkDestroyItems'])
            ->name('admission.sections.items.bulkDestroy');
        Route::post('admission/sections/{section}/reorder', [AdminAdmissionController::class, 'reorderItems'])
            ->name('admission.sections.reorder');

        // Program Details
        Route::prefix('programs/{program}/details')->name('programs.details.')->group(function () {
            Route::get('/',               [ProgramDetailController::class, 'index'])   ->name('index');
            Route::get('/create',         [ProgramDetailController::class, 'create'])  ->name('create');
            Route::post('/',              [ProgramDetailController::class, 'store'])   ->name('store');
            Route::get('/{detail}/edit',  [ProgramDetailController::class, 'edit'])    ->name('edit');
            Route::patch('/{detail}',     [ProgramDetailController::class, 'update'])  ->name('update');
            Route::delete('/{detail}',    [ProgramDetailController::class, 'destroy']) ->name('destroy');
            Route::post('/reorder',       [ProgramDetailController::class, 'reorder']) ->name('reorder');
        });

        // Program People
        Route::prefix('programs/{program}/people')->name('programs.people.')->group(function () {
            Route::get('/create',         [ProgramPersonController::class, 'create'])  ->name('create');
            Route::post('/',              [ProgramPersonController::class, 'store'])   ->name('store');
            Route::get('/{person}/edit',  [ProgramPersonController::class, 'edit'])    ->name('edit');
            Route::patch('/{person}',     [ProgramPersonController::class, 'update'])  ->name('update');
            Route::delete('/{person}',    [ProgramPersonController::class, 'destroy']) ->name('destroy');
            Route::post('/reorder',       [ProgramPersonController::class, 'reorder']) ->name('reorder');
        });

        // Program Achievements
        Route::prefix('programs/{program}/achievements')->name('programs.achievements.')->group(function () {
            Route::get('/create',                [ProgramAchievementController::class, 'create'])  ->name('create');
            Route::post('/',                     [ProgramAchievementController::class, 'store'])   ->name('store');
            Route::get('/{achievement}/edit',    [ProgramAchievementController::class, 'edit'])    ->name('edit');
            Route::patch('/{achievement}',       [ProgramAchievementController::class, 'update'])  ->name('update');
            Route::delete('/{achievement}',      [ProgramAchievementController::class, 'destroy']) ->name('destroy');
            Route::post('/reorder',              [ProgramAchievementController::class, 'reorder']) ->name('reorder');
        });

        // About Slides
        Route::resource('about-slides', AboutSlideController::class)
            ->parameters(['about-slides' => 'aboutSlide'])
            ->except(['show']);

        Route::resource('services', AdminServiceController::class)
            ->parameters(['services' => 'service']);

        Route::prefix('services/{service}/contents')
            ->name('services.contents.')
            ->group(function () {
                Route::get('/create',           [ServiceContentController::class, 'create'])  ->name('create');
                Route::post('/',                [ServiceContentController::class, 'store'])   ->name('store');
                Route::get('/{content}/edit',   [ServiceContentController::class, 'edit'])    ->name('edit');
                Route::patch('/{content}',      [ServiceContentController::class, 'update'])  ->name('update');
                Route::delete('/{content}',     [ServiceContentController::class, 'destroy']) ->name('destroy');
                Route::post('/reorder',         [ServiceContentController::class, 'reorder']) ->name('reorder');
            });

        Route::resource('org-chart', AdminOrgChartController::class)
            ->parameters(['org-chart' => 'orgChart'])
            ->except(['show']);

        Route::post('org-chart/reorder', [AdminOrgChartController::class, 'reorder'])
            ->name('org-chart.reorder');

        /*
        |----------------------------------------------------------------------
        | SUPER ADMIN ONLY
        |----------------------------------------------------------------------
        */
        Route::middleware('super_admin')->group(function () {

            Route::resource('users', UserController::class)->except(['show']);

             Route::delete('messages/bulk-destroy', [ContactMessageController::class, 'bulkDestroy'])
                ->name('messages.bulkDestroy');
            Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])
                ->name('messages.destroy');

            Route::prefix('news-review')->name('news-review.')->group(function () {
                Route::get('/',                    [NewsReviewController::class, 'index'])   ->name('index');
                Route::get('/{newsPost}',          [NewsReviewController::class, 'show'])    ->name('show');
                Route::post('/{newsPost}/approve', [NewsReviewController::class, 'approve']) ->name('approve');
                Route::post('/{newsPost}/decline', [NewsReviewController::class, 'decline']) ->name('decline');
                Route::post('/{newsPost}/pending', [NewsReviewController::class, 'pending']) ->name('pending');
            });

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
