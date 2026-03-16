<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetFileController;
use App\Http\Controllers\AssetMaintenanceController;
use App\Http\Controllers\AssetModelController;
use App\Http\Controllers\ChangeRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documentation\DocumentationCategoryController;
use App\Http\Controllers\Documentation\DocumentationController;
use App\Http\Controllers\Documentation\DocumentationTagController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('helpdesk.index');
});

// ─── Public Helpdesk (No Auth) ─────────────────────
Route::prefix('helpdesk')->name('helpdesk.')->group(function () {
    Route::get('/', [HelpdeskController::class, 'index'])->name('index');
    Route::get('/guide', [HelpdeskController::class, 'guide'])->name('guide');
    Route::get('/create', [HelpdeskController::class, 'create'])->name('create');
    Route::post('/submit', [HelpdeskController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('submit');
    Route::get('/success', [HelpdeskController::class, 'success'])->name('success');
    Route::get('/track', [HelpdeskController::class, 'track'])->name('track');
    Route::post('/track', [HelpdeskController::class, 'trackSubmit'])->name('track.submit');
    Route::get('/track/result', [HelpdeskController::class, 'trackResult'])->name('track.result');
});

// ─── Authenticated Routes ──────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Help Desk ──────────────────────────────────────
    Route::resource('tickets', TicketController::class);
    Route::get('ticket-history', [TicketController::class, 'history'])->name('tickets.history');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comments.store');
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::patch('tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::patch('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::patch('tickets/{ticket}/complete', [TicketController::class, 'markCompleted'])->name('tickets.complete');
    Route::patch('tickets/{ticket}/sla', [TicketController::class, 'updateSla'])->name('tickets.update-sla');

    // ─── Export Routes ──────────────────────────────────
    Route::get('tickets/export/excel', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('ticket-history/export/excel', [TicketController::class, 'exportHistory'])->name('tickets.history.export');

    // ─── Asset Management ───────────────────────────────
    Route::middleware(['role:admin,it-staff'])->group(function () {
        // Assets CRUD
        Route::resource('assets', AssetController::class);
        Route::get('assets-export/excel', [AssetController::class, 'export'])->name('assets.export');

        // Asset extras
        Route::get('assets/{asset}/qrcode', [AssetController::class, 'qrcode'])->name('assets.qrcode');
        Route::get('assets-import', [AssetController::class, 'importForm'])->name('assets.import');
        Route::post('assets-import', [AssetController::class, 'importStore'])->name('assets.import.store');
        Route::post('assets/{asset}/assign', [AssetController::class, 'assign'])->name('assets.assign');
        Route::post('assets/{asset}/return', [AssetController::class, 'returnAsset'])->name('assets.return');
        Route::patch('assets/{asset}/update-tag', [AssetController::class, 'updateTag'])->name('assets.update-tag');
        // Asset Tag Format Settings
        Route::get('assets-tag-format', [AssetController::class, 'tagFormatSettings'])->name('assets.tag-format.show');
        Route::post('assets-tag-format', [AssetController::class, 'tagFormatSettingsSave'])->name('assets.tag-format.save');
        Route::get('assets-tag-format/preview', [AssetController::class, 'tagFormatPreview'])->name('assets.tag-format.preview');

        // Asset Files
        Route::post('assets/{asset}/files', [AssetFileController::class, 'store'])->name('asset-files.store');
        Route::delete('asset-files/{assetFile}', [AssetFileController::class, 'destroy'])->name('asset-files.destroy');
        Route::get('asset-files/{assetFile}/download', [AssetFileController::class, 'download'])->name('asset-files.download');

        // Manufacturers
        Route::resource('manufacturers', ManufacturerController::class)->except('show');

        // Asset Models
        Route::resource('asset-models', AssetModelController::class)->except('show');

        // Locations
        Route::resource('locations', LocationController::class)->except('show');

        // Asset Maintenance
        Route::resource('asset-maintenance', AssetMaintenanceController::class)->except('show');

        // Software & Licenses
        Route::resource('software', SoftwareController::class)->except('show');
        Route::resource('licenses', LicenseController::class)->except('show');
        Route::post('licenses/{license}/assign-user', [LicenseController::class, 'assignUser'])->name('licenses.assign-user');
        Route::delete('licenses/{license}/revoke-user/{user}', [LicenseController::class, 'revokeUser'])->name('licenses.revoke-user');
    });

    // ─── IT Operations ──────────────────────────────────
    Route::middleware(['role:admin,it-staff,manager'])->group(function () {
        Route::resource('change-requests', ChangeRequestController::class);
        Route::patch('change-requests/{change_request}/submit', [ChangeRequestController::class, 'submit'])->name('change-requests.submit');
        Route::patch('change-requests/{change_request}/approve', [ChangeRequestController::class, 'approve'])->name('change-requests.approve');
        Route::patch('change-requests/{change_request}/reject', [ChangeRequestController::class, 'reject'])->name('change-requests.reject');
        Route::patch('change-requests/{change_request}/implement', [ChangeRequestController::class, 'implement'])->name('change-requests.implement');
        Route::get('change-requests-export/excel', [ChangeRequestController::class, 'export'])->name('change-requests.export');

        Route::resource('maintenance', MaintenanceScheduleController::class);
        Route::patch('maintenance/{maintenance}/complete', [MaintenanceScheduleController::class, 'complete'])->name('maintenance.complete');
        Route::get('maintenance-export/excel', [MaintenanceScheduleController::class, 'export'])->name('maintenance.export');

        Route::resource('incidents', IncidentController::class);
        Route::post('incidents/{incident}/rca', [IncidentController::class, 'createRca'])->name('incidents.rca.store');
        Route::get('incidents-export/excel', [IncidentController::class, 'export'])->name('incidents.export');
    });

    // ─── Reports ────────────────────────────────────────
    Route::middleware(['role:admin,manager'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/tickets', [ReportController::class, 'ticketReport'])->name('tickets');
        Route::get('/assets', [ReportController::class, 'assetReport'])->name('assets');
        Route::get('/sla', [ReportController::class, 'slaReport'])->name('sla');
        Route::get('/export/excel', [ReportController::class, 'export'])->name('export');
    });

    // ─── Administration ─────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::get('users-export/excel', [UserController::class, 'export'])->name('users.export');

        // Guide & FAQ admin
        Route::prefix('guide-admin')->name('guide.')->group(function () {
            Route::get('/', [GuideController::class, 'index'])->name('index');
            Route::get('tips/create', [GuideController::class, 'createTip'])->name('tips.create');
            Route::post('tips', [GuideController::class, 'storeTip'])->name('tips.store');
            Route::get('tips/{tip}/edit', [GuideController::class, 'editTip'])->name('tips.edit');
            Route::put('tips/{tip}', [GuideController::class, 'updateTip'])->name('tips.update');
            Route::delete('tips/{tip}', [GuideController::class, 'destroyTip'])->name('tips.destroy');
            Route::get('faqs/create', [GuideController::class, 'createFaq'])->name('faqs.create');
            Route::post('faqs', [GuideController::class, 'storeFaq'])->name('faqs.store');
            Route::get('faqs/{faq}/edit', [GuideController::class, 'editFaq'])->name('faqs.edit');
            Route::put('faqs/{faq}', [GuideController::class, 'updateFaq'])->name('faqs.update');
            Route::delete('faqs/{faq}', [GuideController::class, 'destroyFaq'])->name('faqs.destroy');
        });

        // Documentation Categories (admin only)
        Route::prefix('doc-categories')->name('doc-categories.')->group(function () {
            Route::get('/', [DocumentationCategoryController::class, 'index'])->name('index');
            Route::get('/create', [DocumentationCategoryController::class, 'create'])->name('create');
            Route::post('/', [DocumentationCategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [DocumentationCategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [DocumentationCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [DocumentationCategoryController::class, 'destroy'])->name('destroy');
        });
    });

    // Documentation Tags (admin + manager)
    Route::middleware(['role:admin,manager'])->prefix('doc-tags')->name('doc-tags.')->group(function () {
        Route::get('/', [DocumentationTagController::class, 'index'])->name('index');
        Route::get('/create', [DocumentationTagController::class, 'create'])->name('create');
        Route::post('/', [DocumentationTagController::class, 'store'])->name('store');
        Route::get('/{tag}/edit', [DocumentationTagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [DocumentationTagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [DocumentationTagController::class, 'destroy'])->name('destroy');
    });

    // ─── IT Documentation ────────────────────────────────
    // Static routes MUST come before the /{documentation} wildcard param
    Route::get('docs/export/excel', [DocumentationController::class, 'export'])
        ->name('docs.export')
        ->middleware(['role:admin,manager,it-staff']);

    Route::middleware(['role:admin,manager,it-staff'])->group(function () {
        Route::get('docs/create', [DocumentationController::class, 'create'])->name('docs.create');
        Route::post('docs', [DocumentationController::class, 'store'])->name('docs.store');
    });

    Route::prefix('docs')->name('docs.')->group(function () {
        Route::get('/', [DocumentationController::class, 'index'])->name('index');
        Route::get('/{documentation}/download', [DocumentationController::class, 'download'])->name('download');
        Route::get('/{documentation}/edit', [DocumentationController::class, 'edit'])->name('edit')
            ->middleware(['role:admin,manager,it-staff']);
        Route::put('/{documentation}', [DocumentationController::class, 'update'])->name('update')
            ->middleware(['role:admin,manager,it-staff']);
        Route::delete('/{documentation}', [DocumentationController::class, 'destroy'])->name('destroy')
            ->middleware(['role:admin']);
        Route::get('/{documentation}', [DocumentationController::class, 'show'])->name('show');
    });
});


require __DIR__ . '/auth.php';
