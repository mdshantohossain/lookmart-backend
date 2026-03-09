<?php

$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
];

if(app()->environment('local')){
    $providers[] = Barryvdh\Debugbar\ServiceProvider::class;
}


return $providers;

