<?php

namespace App\Domain\User;

class UserLoginFailedException extends \Exception
{
    public $message = 'Login failed! Wrong email or password.';
    public $code = 400;
}