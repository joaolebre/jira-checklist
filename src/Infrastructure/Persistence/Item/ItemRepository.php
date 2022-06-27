<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class ItemRepository extends AbstractRepository
{
    public function findAll(): array {
        $query = 'SELECT * FROM items';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws ItemNotFoundException
     */
    public function findItemById(int $itemId): Item {
        $query = 'SELECT * FROM items WHERE items.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $itemId);
        $statement->execute();

        $item = $statement->fetchObject(Item::class);

        if (! $item) {
            throw new ItemNotFoundException();
        }

        return $item;
    }

    public function createItem(Item $item): Item {
        
    }

}