<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetTicketAction extends TicketAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');
        $ticket = $this->ticketRepository->findTicketById($ticketId);

        $this->logger->info("User of id `${ticketId}` was viewed.");

        return $this->respondWithData($ticket);
    }
}