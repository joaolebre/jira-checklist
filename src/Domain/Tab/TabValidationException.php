<?php
declare(strict_types=1);

namespace App\Domain\Tab;

use Slim\Exception\HttpBadRequestException;

class TabValidationException extends HttpBadRequestException
{

}