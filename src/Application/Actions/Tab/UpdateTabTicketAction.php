<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateTabTicketAction extends TabAction
{

    /**
     * @OA\Patch(
     *     tags={"Tab"},
     *     path="/api/tabs/ticket/{id}",
     *     summary="Update the ticket of a specific tab",
     *     operationId="updateTabTicket",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Tab ticket updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tab not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Ticket id",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"ticket_id"},
     *              @OA\Property(property="ticket_id", type="integer", format="int64", example="1")
     *         )
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TabNotFoundException
     * @throws HttpUnauthorizedException
     * @throws TicketNotFoundException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($tabId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this tab.');
        }

        $data = $this->request->getParsedBody();
        $ticketId = $data['ticket_id'];

        $tabTicket = $this->ticketRepository->findTicketById($ticketId);
        $tabTicket->checkAuthorization($this->request, 'You can not move a tab to that ticket.');

        Tab::validateTabData($this->request, $data);

        $tab = $this->tabRepository->updateTabTicket((int) $tabId, (int) $ticketId);

        $this->logger->info("Ticket of tab with id `{$tabId}` was updated to ticket with id `{$ticketId}`.");

        return $this->respondWithData($tab);
    }
}