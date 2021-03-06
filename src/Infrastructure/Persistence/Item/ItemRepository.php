<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use PDO;

class ItemRepository extends BaseRepository
{
    public function findAll(): array {
        $query = 'SELECT id, summary, is_checked, is_important, position, section_id, status_id FROM items';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Item\Item');
    }

    public function findAllByUserId(int $userId): array {
        $query = '
            SELECT * FROM items
            WHERE section_id IN
                (SELECT id FROM sections
                WHERE tab_id IN 
                    (SELECT id FROM tabs
                    WHERE ticket_id IN
                        (SELECT id FROM tickets
                        WHERE user_id = :user_id
                    )))   
        ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Item\Item');
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

    public function findItemsBySectionId(int $sectionId): array {
        $query = '
            SELECT id, summary, is_checked, is_important, position, status_id FROM items 
            WHERE items.section_id = :section_id
            ORDER BY items.position
        ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':section_id', $sectionId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS, 'App\Domain\Item\Item');
    }

    /**
     * @throws ItemNotFoundException
     */
    public function createItem(Item $item): Item {
        $query = '
            INSERT INTO items (summary, position, section_id)
            SELECT :summary, MAX(position) + 1, :section_id FROM items
            WHERE section_id = :section_id
        ';
        $statement = $this->database->prepare($query);

        $summary = $item->getSummary();
        $sectionId = $item->getSectionId();

        $statement->bindParam(':summary', $summary);
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
                position = :position,
                status_id = :status_id
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $itemId = $item->getId();
        $summary = $item->getSummary();
        $isChecked = $item->getIsChecked();
        $isImportant = $item->getIsImportant();
        $position = $item->getPosition();
        $statusId = $item->getStatusId();

        $statement->bindParam(':id', $itemId);
        $statement->bindParam(':summary', $summary);
        $statement->bindParam(':is_checked', $isChecked);
        $statement->bindParam(':is_important', $isImportant);
        $statement->bindParam(':position', $position);
        $statement->bindParam(':status_id', $statusId);

        $statement->execute();

        return $item;
    }

    /**
     * @throws ItemNotFoundException
     */
    public function updateItemSection(int $itemId, int $sectionId): Item
    {
        $query = '
            UPDATE items
            SET section_id = :section_id
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $statement->bindParam(':id', $itemId);
        $statement->bindParam(':section_id', $sectionId);

        $statement->execute();

        return $this->findItemById((int) $itemId);
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