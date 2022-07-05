<?php
declare(strict_types=1);

namespace App\Domain\Ticket;

use Slim\Exception\HttpBadRequestException;

class TicketValidationException extends HttpBadRequestException
{

}