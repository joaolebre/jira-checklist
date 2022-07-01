<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use App\Infrastructure\Persistence\BaseRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use PDO;
use Throwable;

class TicketRepository extends BaseRepository
{
    private $tabRepository;

    public function __construct(PDO $database, TabRepository $tabRepository)
    {
        parent::__construct($database);
        $this->tabRepository = $tabRepository;
    }

    public function findAll(): array {
        $query = 'SELECT id, title, description, user_id FROM tickets';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @throws TicketNotFoundException
     */
    public function findTicketById(int $ticketId): Ticket {
        $query = 'SELECT * FROM tickets WHERE tickets.id = :id ';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $ticketId);
        $statement->execute();

        $ticket = $statement->fetchObject(Ticket::class);

        if (! $ticket) {
            throw new TicketNotFoundException();
        }

        return $ticket;
    }

    /**
     * @throws TicketNotFoundException
     * @throws Throwable
     */
    public function createTicket(Ticket $ticket): Ticket {
        $query = '
            INSERT INTO tickets (title, description, user_id) 
            VALUES (:title, :description, :user_id)
        ';
        $statement = $this->database->prepare($query);

        $title= $ticket->getTitle();
        $description = $ticket->getDescription();
        $userId = $ticket->getUserId();

        $tabQuery = '
            INSERT INTO tabs(name, `order`, ticket_id)
            VALUES (:name, :order, :ticket_id)
        ';

        $sectionQuery = '
            INSERT INTO sections(name, `order`, tab_id)
            VALUES (:name, :order, :tab_id)
        ';

        $tabStatement = $this->database->prepare($tabQuery);
        $sectionStatement = $this->database->prepare($sectionQuery);

        $this->database->beginTransaction();

        try {
            $statement->bindParam(':title', $title);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':user_id', $userId);
            $statement->execute();

            $tabStatement->bindValue(':name', "Tab 1");
            $tabStatement->bindValue(':order', 1);
            $tabStatement->bindValue(':ticket_id', $this->database->lastInsertId());
            $tabStatement->execute();

            $sectionStatement->bindValue(':name', "Section 1");
            $sectionStatement->bindValue(':order', 1);
            $sectionStatement->bindValue(':tab_id', $this->tabRepository->database->lastInsertId());
            $sectionStatement->execute();
        } catch (Throwable $ex) {
            $this->database->rollBack();
            throw $ex;
        }

        $this->database->commit();

        return $this->findTicketById((int) $this->database->lastInsertId());
    }

    public function updateTicket(Ticket $ticket): Ticket {
        $query = '
            UPDATE tickets
            SET title = :title,
                description = :description
            WHERE id = :id
        ';
        $statement = $this->database->prepare($query);

        $ticketId = $ticket->getId();
        $title = $ticket->getTitle();
        $description = $ticket->getDescription();

        $statement->bindParam(':id', $ticketId);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':description', $description);
        $statement->execute();

        return $ticket;
    }

    /**
     * @throws TicketNotFoundException
     */
    public function deleteTicketById(int $ticketId) {
        $this->findTicketById($ticketId);

        $query = 'DELETE FROM tickets WHERE tickets.id = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $tabId);
        $statement->execute();
    }
}