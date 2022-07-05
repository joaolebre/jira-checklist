<?php
declare(strict_types=1);

namespace App\Domain\Item;

use Slim\Exception\HttpBadRequestException;

class ItemValidationException extends HttpBadRequestException
{

}