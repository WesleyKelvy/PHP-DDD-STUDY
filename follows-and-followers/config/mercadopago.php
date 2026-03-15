<?php

declare(strict_types=1);

return [
    'acess_token'   => env('MP_ACCESS_TOKEN'),
    'webbook_secret'=> env('MP_WEBHOOK_SECRET'),
    'price'         => (float) env('APP_PRICE'),
];
