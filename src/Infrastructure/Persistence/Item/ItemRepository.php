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

    /**
     * @throws ItemNotFoundException
     */
    public function createItem(Item $item): Item {
        $query = '
            INSERT INTO items (summary, `order`, section_id)
            VALUES (:summary, :order, :section_id)
        ';
        $statement = $this->database->prepare($query);

        $summary = $item->getSummary();
        $order = $item->getOrder();
        $sectionId = $item->getSectionId();

        $statement->bindParam(':summary', $summary);
        $statement->bindParam(':order', $order);
        $statement->bindParam(':section_id', $sectionId);

        $statement->execute();

        return $this->findItemById((int) $this->database->lastInsertId());
    }


    public function updateItem(Item $item): Item {

        $query = '
            UPDATE items
            SET summary = :summary,
                is_checked = :is_checked,
                is_important = :is_important,
                `order` = :order,
                status_id = :status_id
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $itemId = $item->getId();
        $summary = $item->getSummary();
        $isChecked = $item->getIsChecked();
        $isImportant = $item->getIsImportant();
        $order = $item->getOrder();
        $statusId = $item->getStatusId();

        $statement->bindParam('id', $itemId);
        $statement->bindParam(':summary', $summary);
        $statement->bindParam(':is_checked', $isChecked);
        $statement->bindParam(':is_important', $isImportant);
        $statement->bindParam(':order', $order);
        $statement->bindParam(':status_id', $statusId);

        $statement->execute();

        return $item;
    }

    /**
     * @throws ItemNotFoundException
     */
    public function deleteItemById(int $itemId) {
        $this->findItemById($itemId);

        $query = 'DELETE FROM items WHERE items.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $itemId);
        $statement->execute();
    }
}