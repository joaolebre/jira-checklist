<?php
declare(strict_types=1);

namespace App\Domain\User;

use Slim\Exception\HttpBadRequestException;

class UserValidationException extends HttpBadRequestException
{

}