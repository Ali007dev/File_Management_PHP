<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        Route::macro('feature', function ($feature, $controller) {
            $routes = Route::group([], function () use ($controller, $feature) {
                Route::get("/{$feature}/index", [$controller, 'index'])->name("{$feature}.index");
                Route::get("/{$feature}/all", [$controller, 'all'])->name("{$feature}.all");
                Route::post("/{$feature}/create", [$controller, 'create'])->name("{$feature}.store");
                Route::get("/{$feature}/show/{id}", [$controller, 'show'])->name("{$feature}.show");
                Route::put("/{$feature}/update/{id}", [$controller, 'update'])->name("{$feature}.update");
                Route::delete("/{$feature}/delete/{ids}", [$controller, 'delete'])->name("{$feature}.delete");
            });
            return $routes;
        });
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(2000)->by($request->user()?->id ?: $request->ip());
        });

        $directory = base_path('routes');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                // Get the relative path
                $relativePath = str_replace(base_path('routes') . DIRECTORY_SEPARATOR, '', $file->getPathname());

                // Extract the prefix from the folder and file structure
                $prefix = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
                $prefix = str_replace('.php', '', $prefix); // Remove '.php'

                // Define the route group
                Route::prefix('api/' . $prefix)
                    ->middleware(['transactional', 'api'])
                    ->namespace($this->namespace)
                    ->group($file->getPathname());
            }
        }
    }
}
