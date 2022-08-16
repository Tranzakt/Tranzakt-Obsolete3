<?php

namespace Tranzakt\Developer\Providers;

use Illuminate\Support\ServiceProvider;

class DeveloperServiceProvider extends ServiceProvider
{
	public function register()
	{
	}
	
	public function boot()
	{
        if ( $this->app->runningInConsole() )
        {
            // $this->mergeConfigFrom(
            //     __DIR__ . '/../config/laravel-migration-paths.php',
            //     'laravel-migration-paths'
            // );

			$this->loadMigrationsFrom(SubmoduleDirs(from: __DIR__ . '/../../..', subdir: 'database/migrations'));
        }
	}
}
