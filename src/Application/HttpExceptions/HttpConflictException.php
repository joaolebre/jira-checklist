<?php

namespace App\Application\HttpExceptions;

use Slim\Exception\HttpSpecializedException;

class HttpConflictException extends HttpSpecializedException
{
    protected $code = 409;
    protected $title = '409 Conflict';
    protected $description = 'Request could not be processed because of conflict in the request.';
}