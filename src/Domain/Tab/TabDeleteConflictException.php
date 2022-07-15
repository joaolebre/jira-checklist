<?php

namespace App\Domain\Tab;

use App\Application\HttpExceptions\HttpConflictException;

class TabDeleteConflictException extends HttpConflictException
{
    protected $message = 'Tab can not have sections or Items in order to be deleted.';
}