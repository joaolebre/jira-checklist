<?php

namespace App\Domain\Ticket;

use App\Application\HttpExceptions\HttpConflictException;

class TicketDeleteConflictException extends HttpConflictException
{
    protected $message = 'Ticket can not have tabs, Sections or Items in order to be deleted.';
}