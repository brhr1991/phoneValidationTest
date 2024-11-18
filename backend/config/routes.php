<?php

declare(strict_types=1);

use App\Http\Action\VerifyPhoneAction;
use Slim\App;

return static function (App $app): void {
    $app->post('/verify-phone', VerifyPhoneAction::class);
};