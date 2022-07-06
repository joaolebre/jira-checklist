<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use PDO;

class TabRepository extends BaseRepository
{

    public function findAll(): array {
        $query = 'SELECT id, name, position, ticket_id FROM tabs';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws TabNotFoundException
     */
    public function findTabById(int $tabId) {
        $query = 'SELECT * FROM tabs WHERE tabs.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $tabId);
        $statement->execute();

        $tab = $statement->fetchObject(Tab::class);

        if (! $tab) {
            throw new TabNotFoundException();
        }

        return $tab;
    }

    public function findTabsByTicketId($ticketId): array
    {
        $query = '
            SELECT id, name, position FROM tabs 
            WHERE tabs.ticket_id = :ticket_id 
            ORDER BY tabs.position
            ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':ticket_id', $ticketId);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws TabNotFoundException
     */
    public function createTab(Tab $tab)
    {
        $query = '
            INSERT INTO tabs (name, position, ticket_id)
            VALUES (:name, :position, :ticket_id)
        ';
        $statement = $this->database->prepare($query);

        $name = $tab->getName();
        $position = $tab->getPosition();
        $ticketId = $tab->getTicketId();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':position', $position);
        $statement->bindParam(':ticket_id', $ticketId);

        $statement->execute();

        return $this->findTabById((int) $this->database->lastInsertId());
    }

    public function updateTab(Tab $tab): Tab {
        $query = '
            UPDATE tabs
            SET name = :name,
                position = :position
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $tabId = $tab->getId();
        $name = $tab->getName();
        $position = $tab->getPosition();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':position', $position);
        $statement->bindParam(':id', $tabId);

        $statement->execute();

        return $tab;
    }

    /**
     * @throws TabNotFoundException
     */
    public function deleteTabById(int $tabId) {
        $this->findTabById($tabId);

        $query = 'DELETE FROM tabs WHERE tabs.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $tabId);
        $statement->execute();
    }

}