<?php

namespace App\Providers;

use App\Models\Documentation;
use App\Models\DocumentationCategory;
use App\Models\DocumentationTag;
use App\Policies\DocumentationCategoryPolicy;
use App\Policies\DocumentationPolicy;
use App\Policies\DocumentationTagPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');

        // Register Documentation Policies
        Gate::policy(Documentation::class, DocumentationPolicy::class);
        Gate::policy(DocumentationCategory::class, DocumentationCategoryPolicy::class);
        Gate::policy(DocumentationTag::class, DocumentationTagPolicy::class);
    }
}
