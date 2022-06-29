<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteTicketAction extends TicketAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws TabNotFoundException
     */
    protected function action(): Response
    {
        $ticketId = $this->resolveArg('id');
        $this->tabRepository->deleteTabById($ticketId);

        $this->logger->info("Ticket with id `${ticketId} deleted successfully`.");

        return $this->respondWithData();
    }
}