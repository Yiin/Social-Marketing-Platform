<?php

namespace App\Providers;

use App\Services\CurlService;
use App\Services\FacebookPagesService;
use App\Services\GooglePlusService;
use App\Services\LinkedInService;
use App\Services\UserService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use nxsAPI_FP;
use nxsAPI_GP;
use nxsAPI_LI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, $parameters[0]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HTTP Requests
        $this->app->singleton('App\Service\CurlService', function ($app) {
            return new CurlService;
        });

        // Users
        $this->app->singleton('App\Service\UserService', function ($app) {
            return new UserService;
        });

        // GooglePlusService
        $this->app->singleton('App\Service\GooglePlusService', function ($app) {
            return new GooglePlusService(new nxsAPI_GP);
        });

        // FacebookPagesService
        $this->app->singleton('App\Service\FacebookPagesService', function ($app) {
            return new FacebookPagesService(new nxsAPI_FP);
        });

        // LinkedInService
        $this->app->singleton('App\Service\LinkedInService', function ($app) {
            return new LinkedInService(new nxsAPI_LI);
        });
    }
}
