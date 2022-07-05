<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;

class ListTicketsAction extends TicketAction
{

    /**
     * @OA\Get(
     *     tags={"Ticket"},
     *     path="/api/tickets",
     *     summary="Get a list of all tickets",
     *     operationId="listTickets",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *      @OA\Response(
     *          response=200,
     *          description="List all tickets.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Ticket")
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     */
    protected function action(): Response
    {
        $tickets = $this->ticketRepository->findAll();
        $this->logger->info("Ticket list was viewed");

        return $this->respondWithData($tickets);
    }
}