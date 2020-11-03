<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
/**
 * 针对
 * Illuminate\Database\QueryException  : 
 *      SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; 
 *      max key length is 1000 bytes (SQL: alter table `roles` add unique `roles_name_unique`(`name`))
 */
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }
}
