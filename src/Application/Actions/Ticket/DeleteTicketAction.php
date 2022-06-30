<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteTicketAction extends TicketAction
{

    /**
     * @OA\Delete(
     *     tags={"Ticket"},
     *     path="/api/tickets/{id}",
     *     summary="Deletes a ticket",
     *     operationId="deleteTicket",
     *     @OA\Parameter(
     *         name="ticketId",
     *         in="path",
     *         description="Ticket id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found",
     *     )
     * )
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