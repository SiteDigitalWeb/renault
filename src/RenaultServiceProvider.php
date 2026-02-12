<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Support\ServiceProvider;

class RenaultServiceProvider extends ServiceProvider{
	
 public function register(){
 $this->app->bind('renault', function($app){
 return new Renault;
 });
 }

 public function boot()
{
    $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

    $this->loadViewsFrom(__DIR__ . '/../views', 'renault');

    $this->publishes([
        __DIR__ . '/migrations/2015_07_25_000000_create_usuario_table.php' 
        => database_path('migrations/2015_07_25_000000_create_usuario_table.php'),
    ], 'renault-migrations');
}

}
