<?php
declare(strict_types=1);

namespace App\Domain\ItemStatus;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ItemStatusNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The item status you requested does not exist.';
}
