<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use App\Domain\Ticket\TicketValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTicketAction extends TicketAction
{

    /**
     * @OA\Put(
     *     tags={"Ticket"},
     *     path="/api/tickets/{id}",
     *     summary="Update a specific ticket",
     *     operationId="updateTicket",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Ticket object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"title","description"},
     *              @OA\Property(property="title", type="string", format="text", example="Ticket About Things"),
     *              @OA\Property(property="description", type="string", format="text", example="This ticket is about...")
     *         )
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TicketNotFoundException
     * @throws TicketValidationException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');
        $ticket = $this->ticketRepository->findTicketById($ticketId);

        $data = $this->request->getParsedBody();

        Ticket::validateTicketData($this->request, $data);

        $ticket->setTitle($data['title']);
        $ticket->setDescription($data['description']);

        $ticket = $this->ticketRepository->updateTicket($ticket);

        $this->logger->info("Ticket with id `{$ticketId}` was updated.");

        return $this->respondWithData($ticket)->withHeader('Location', "/api/tickets/{$ticketId}");
    }
}