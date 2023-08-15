<?php
namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Schema::defaultStringLength(191);
        // URL::forceScheme('https');

         $verificationExpiration = 60 * 60; // 1 hour

        // Modify the email verification URL to include the expiration time
        VerifyEmail::createUrlUsing(function ($notifiable) use ($verificationExpiration) {
            $expirationTime = Carbon::now()->addSeconds($verificationExpiration);
            return URL::temporarySignedRoute(
                'verification.verify',
                $expirationTime,
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        Paginator::useBootstrap();

        view()->composer('*', function ($view) {
            $data = new ProfileController();
            $result = $data->getuserprofile();
            $view->with('profile', $result);
        });
    }
}
