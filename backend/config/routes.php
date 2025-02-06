<?php

declare(strict_types=1);

use App\Http\Action\TonAction;
use Slim\App;

return static function (App $app): void {
    $app->get('/ton', TonAction::class);
};