<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Ticket;

use App\Infrastructure\Persistence\AbstractRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketRepository extends AbstractRepository
{

    public function findAll(): array {
        $query = 'SELECT * FROM item_statuses';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }
}