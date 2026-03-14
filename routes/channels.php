<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('product-page', function ($user) {
    return true;
});
