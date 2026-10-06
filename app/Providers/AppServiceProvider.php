<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LandingPageSetting;
use App\Models\ServiceHarian;
use App\Observers\ServiceHarianObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        // Register ServiceHarian Observer
        ServiceHarian::observe(ServiceHarianObserver::class);

        // Bagikan data footer ke SEMUA view secara otomatis
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with([
                'addressIconPath' => \App\Models\LandingPageSetting::getValue('contact', 'address_icon', null),
                'phoneIconPath'   => \App\Models\LandingPageSetting::getValue('contact', 'phone_icon', null),
                'socialIconPath'  => \App\Models\LandingPageSetting::getValue('contact', 'social_icon', null),
                'addressText'     => \App\Models\LandingPageSetting::getValue('contact', 'address_text', ''),
                'phoneText'       => \App\Models\LandingPageSetting::getValue('contact', 'phone_text', ''),
                'socialLink'      => \App\Models\LandingPageSetting::getValue('contact', 'social_link', '#'),
                'mapsLink'        => \App\Models\LandingPageSetting::getValue('contact', 'maps_link', '#'),
            ]);
        });

        RateLimiter::for('internal-api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(1000)->by('user:' . $request->user()->id)
                : Limit::perMinute(60)->by('ip:' . $request->ip());
        });
    }
}
