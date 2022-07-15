<?php

namespace App\Domain\Section;

use App\Application\HttpExceptions\HttpConflictException;

class SectionDeleteConflictException extends HttpConflictException
{
    protected $message = 'Section can not have items in order to be deleted.';
}