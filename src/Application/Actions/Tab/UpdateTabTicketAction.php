<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTabTicketAction extends TabAction
{

    /**
     * @OA\Patch(
     *     tags={"Tab"},
     *     path="/api/tabs/ticket/{id}",
     *     summary="Update the ticekt of a specific tab",
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
     */
    protected function action(): Response
    {
        $tabId = $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $ticketId = $data['ticket_id'];

        Tab::validateTabData($this->request, $data);

        $tab = $this->tabRepository->updateTabTicket((int) $tabId, (int) $ticketId);

        $this->logger->info("Ticket of tab with id `{$tabId}` was updated to ticket with id `{$ticketId}`.");

        return $this->respondWithData($tab);
    }
}