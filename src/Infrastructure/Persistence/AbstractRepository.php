<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;

abstract class AbstractRepository
{
    protected $database;

    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }
}