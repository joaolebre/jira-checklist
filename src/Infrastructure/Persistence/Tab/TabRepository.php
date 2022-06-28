<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
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

    /**
     * @throws TabNotFoundException
     */
    public function createTab(Tab $tab)
    {
        $query = '
            INSERT INTO tabs(name, order, ticket_id)
            VALUES (:name, :order, :ticket_id)
        ';
        $statement = $this->database->prepare($query);

        $name = $tab->getName();
        $order = $tab->getOrder();
        $ticketId = $tab->getTicketId();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':order', $order);
        $statement->bindParam(':ticket_id', $ticketId);

        $statement->execute();

        return $this->findTabById((int) $this->database->lastInsertId());
    }

    public function updateTab(Tab $tab): Tab {
        $query = '
            UPDATE tabs
            SET name = :name,
                `order` = :order
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $tabId = $tab->getId();
        $name = $tab->getName();
        $order = $tab->getOrder();

        $statement->bindParam(':name', $name);
        $statement->bindParam(':order', $order);
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