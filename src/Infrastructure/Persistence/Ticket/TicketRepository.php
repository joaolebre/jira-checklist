<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;

class TicketRepository extends AbstractRepository
{

    public function findAll(): array {
        $query = 'SELECT * FROM tickets';
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

        $statement->bindParam(':title', $title);
        $statement->bindParam(':description', $description);
        $statement->bindParam(':user_id', $userId);

        $statement->execute();

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