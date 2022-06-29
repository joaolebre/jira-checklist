<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTicketAction extends TicketAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws TicketNotFoundException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');
        $ticket = $this->ticketRepository->findTicketById($ticketId);

        $data = $this->request->getParsedBody();

        $ticket->setTitle($data['title']);
        $ticket->setDescription($data['description']);

        $ticket = $this->ticketRepository->updateTicket($ticket);

        $this->logger->info("Ticket with id `{$ticketId}` was updated.");

        return $this->respondWithData($ticket)->withHeader('Location', "/items/{$ticketId}");
    }
}