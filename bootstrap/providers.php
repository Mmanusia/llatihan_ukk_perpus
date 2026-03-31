<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    
    // Tambahkan FortifyServiceProvider ke dalam array provider
    FortifyServiceProvider::class,
];
