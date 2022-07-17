<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use App\Domain\Ticket\TicketValidationException;
use App\Domain\User\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

class CreateTicketAction extends TicketAction
{

    /**
     * @OA\Post(
     *     tags={"Ticket"},
     *     path="/api/tickets",
     *     summary="Create a new ticket",
     *     operationId="createTicket",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Ticket object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", format="text", example="Ticket 1"),
     *              @OA\Property(property="description", type="string", format="text", example="This ticket is about this...")
     *         )
     *     )
     * )
     * @return Response
     * @throws TicketNotFoundException
     * @throws TicketValidationException|Throwable
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        Ticket::validateTicketData($this->request, $data);

        $newTicket = new Ticket();
        $newTicket->setTitle($data['title']);
        $newTicket->setDescription($data['description']);
        $newTicket->setUserId(User::getLoggedInUserId($this->request));

        $createdTicket = $this->ticketRepository->createTicket($newTicket);
        $createdTicketId = $createdTicket->getId();

        $this->logger->info("New ticket with id `{$createdTicketId}` was created.");

        return $this->respondWithData($createdTicket, 201)->withHeader('Location', "/tickets/{$createdTicketId}");
    }
}