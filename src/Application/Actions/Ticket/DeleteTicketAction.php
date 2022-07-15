<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketDeleteConflictException;
use App\Domain\Ticket\TicketNotFoundException;
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
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ticket id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Could not delete ticket because of database conflict"
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TicketNotFoundException|TicketDeleteConflictException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        try {
            $this->ticketRepository->deleteTicketById($ticketId);
        } catch (\PDOException $e) {
            $this->logger->error("While deleting ticket with id ${ticketId}: " . $e->getMessage());
            throw new TicketDeleteConflictException($this->request);
        }

        $this->logger->info("Ticket with id `${ticketId}` deleted successfully.");

        return $this->respondWithData();
    }
}