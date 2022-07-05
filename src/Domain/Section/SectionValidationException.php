<?php
declare(strict_types=1);

namespace App\Domain\Section;

use Slim\Exception\HttpBadRequestException;

class SectionValidationException extends HttpBadRequestException
{

}