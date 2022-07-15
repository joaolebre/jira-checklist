<?php

namespace App\Domain\User;

use App\Application\HttpExceptions\HttpConflictException;

class UserDeleteConflictException extends HttpConflictException
{
    protected $message = 'User can not have any tickets associated in order to be deleted.';
}