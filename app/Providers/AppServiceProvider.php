<?php

namespace App\Providers;

use App\Models\feedback;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        //
        $feedback = feedback::all();
        View::share([
            'feedback' => $feedback,
        ]);
    }
}
