<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MediaController;
use App\Livewire\Admin\CategoriesManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EventsManager;
use App\Livewire\Admin\MediaLibrary;
use App\Livewire\Admin\SeriesManager;
use App\Livewire\Admin\SermonsManager;
use App\Livewire\Admin\InscriptionsManager;
use App\Livewire\Admin\ProfileManager;
use App\Livewire\Admin\ServicesManager;
use App\Livewire\Admin\UsersManager;
use App\Livewire\Admin\SettingsManager;
use App\Livewire\SermonsPage;
use App\Livewire\SermonDetail;
use App\Livewire\EventsPage;
use App\Livewire\EventDetail;
use App\Livewire\LiveStream;
use App\Livewire\PageDetail;
use App\Livewire\ServiciosPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sermones', SermonsPage::class)->name('sermons.index');
Route::get('/sermones/{slug}', SermonDetail::class)->name('sermons.show');
Route::get('/eventos', EventsPage::class)->name('events.index');
Route::get('/eventos/{slug}', EventDetail::class)->name('events.show');
Route::get('/en-vivo', LiveStream::class)->name('live');
Route::get('/p/{slug}', PageDetail::class)->name('page.show');

// Servidores — authenticated users with server roles only
Route::get('/servidores', ServiciosPage::class)
    ->middleware(['auth', 'verified', 'role:servidor,pastor,lider_alabanza,lider_ujieres,lider_tecnicos,superadmin,admin,editor,member'])
    ->name('servidores');

Route::get('/dashboard', function () {
    $user = auth()->user();
    // Leaders who only have service roles go straight to services
    if (
        ! $user->hasAnyRole(['superadmin', 'admin', 'editor', 'member']) &&
        $user->hasAnyRole(['pastor', 'lider_alabanza', 'lider_ujieres', 'lider_tecnicos'])
    ) {
        return redirect()->route('admin.services');
    }
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin panel — standard roles ──
Route::middleware(['auth', 'verified', 'role:superadmin,admin,editor,member'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/sermons', SermonsManager::class)->name('sermons');
    Route::get('/series', SeriesManager::class)->name('series');
    Route::get('/events', EventsManager::class)->name('events');
    Route::get('/inscriptions', InscriptionsManager::class)->name('inscriptions');
    Route::get('/pages', \App\Livewire\Admin\PagesManager::class)->name('pages');
    Route::get('/media', MediaLibrary::class)->name('media');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/users', UsersManager::class)->name('users');
    Route::get('/categories', CategoriesManager::class)->name('categories');
    Route::get('/settings', SettingsManager::class)->name('settings');
    Route::get('/profile', ProfileManager::class)->name('profile');
});

// ── Admin panel — service planning (leaders + admins) ──
Route::middleware(['auth', 'verified', 'role:superadmin,admin,pastor,lider_alabanza,lider_ujieres,lider_tecnicos'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/services', ServicesManager::class)->name('services');
});

require __DIR__.'/auth.php';
