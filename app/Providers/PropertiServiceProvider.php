<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PropertiServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once app_path() . '/Helpers/Properti.php';
    }
    public function boot()
    {
        //
    }
}
