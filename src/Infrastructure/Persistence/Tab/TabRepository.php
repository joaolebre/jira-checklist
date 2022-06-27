<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tab;

use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class TabRepository extends AbstractRepository
{

    public function findAll(): array {
        $query = 'SELECT * FROM tabs';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

}