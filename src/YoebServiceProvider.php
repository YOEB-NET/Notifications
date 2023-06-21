<?php
namespace Yoeb\Notifications;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yoeb\Notifications\Controller\YoebNotificationController;

class YoebServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

}

// Route
Route::get("/yoeb/notification/read/email", [YoebNotificationController::class, "readEmail"]);    


?>
