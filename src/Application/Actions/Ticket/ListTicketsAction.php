<?php

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;

class ListTicketsAction extends TicketAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $tickets = $this->ticketRepository->findAll();
        $this->logger->info("Ticket list was viewed");

        return $this->respondWithData($tickets);
    }
}