<?php

namespace App\Domain\User;

use Slim\Exception\HttpBadRequestException;

class UserLoginFailedException extends HttpBadRequestException
{
    public $message = 'Login failed! Wrong email or password.';
}