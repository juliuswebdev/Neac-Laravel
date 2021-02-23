<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Notification;


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
 
        view()->composer('*', function ($view)
        {
            $view->with([
                'bell_notifications' => $this->get_notifications(),
            ]);
            
        });
    }

    function get_notifications() {
        $notifications = null;
        if(auth()->user()) {
            $notifications = Notification::orderBy('created_at', 'DESC')->limit(10)->get();
        }
        return $notifications;
    }



}