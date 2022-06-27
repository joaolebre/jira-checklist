<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use App\Infrastructure\Persistence\AbstractRepository;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
}