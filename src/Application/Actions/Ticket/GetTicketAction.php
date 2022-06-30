<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetTicketAction extends TicketAction
{

    /**
     * @OA\Get(
     *     tags={"Ticket"},
     *     path="/api/tickets/{id}",
     *     summary="Get a ticket by id",
     *     operationId="getTicketById",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single ticket.",
     *          @OA\JsonContent(ref="#/components/schemas/Ticket")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Ticket not found."
     *      )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TicketNotFoundException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');
        $ticket = $this->ticketRepository->findTicketById($ticketId);

        $ticket->setTabs($this->tabRepository->findTabsByTicketId($ticketId));

        $this->logger->info("Ticket of id `${ticketId}` was viewed.");

        return $this->respondWithData($ticket);
    }
}