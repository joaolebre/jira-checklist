<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\ItemStatus;

use App\Domain\ItemStatus\ItemStatus;
use App\Domain\ItemStatus\ItemStatusNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class ItemStatusRepository extends AbstractRepository
{

    public function findAll(): array {
        $query = 'SELECT * FROM item_statuses';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws ItemStatusNotFoundException
     */
    public function findItemStatusById(int $itemStatusId): ItemStatus {
        $query = 'SELECT * FROM item_statuses WHERE item_statuses.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $itemStatusId);
        $statement->execute();

        $itemStatus = $statement->fetchObject(ItemStatus::class);

        if (! $itemStatus) {
            throw new ItemStatusNotFoundException();
        }

        return $itemStatus;
    }
}