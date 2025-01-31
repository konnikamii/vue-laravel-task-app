<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        Validator::extend('at_least_one_digit', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/\d/', $value);
        });

        Validator::replacer('at_least_one_digit', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute must contain at least one digit.');
        });
    }
}
