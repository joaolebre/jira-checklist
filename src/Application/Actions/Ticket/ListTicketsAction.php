<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\User\User;
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
     *     @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=false,
     *          description="Set the user id to filter the tickets (admin only).",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
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
        $userId = User::getLoggedInUserId($this->request);
        $userRole = User::getLoggedInUserRole($this->request);

        if ($userRole == 'admin') {
            if (!empty($this->request->getQueryParams()['user_id'])) {
               $userIdFilter = (int) $this->request->getQueryParams()['user_id'];
               $tickets = $this->ticketRepository->findAllByUserId($userIdFilter);
            } else {
                $tickets = $this->ticketRepository->findAll();
            }
        } else {
            $tickets = $this->ticketRepository->findAllByUserId($userId);
        }

        $this->logger->info("Ticket list was viewed by ${userRole} with the id ${userId}.");

        return $this->respondWithData($tickets);
    }
}