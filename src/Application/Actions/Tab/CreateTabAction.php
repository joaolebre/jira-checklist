<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use App\Domain\Tab\TabValidationException;
use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class CreateTabAction extends TabAction
{

    /**
     * @OA\Post(
     *     tags={"Tab"},
     *     path="/api/tabs",
     *     summary="Create a new tab",
     *     operationId="createTab",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Tab object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","ticket_id"},
     *              @OA\Property(property="name", type="string", format="text", example="Tab 1"),
     *              @OA\Property(property="ticket_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws TabNotFoundException
     * @throws TabValidationException
     * @throws HttpUnauthorizedException|TicketNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        Tab::validateTabData($this->request, $data);

        $tabTicket = $this->ticketRepository->findTicketById($data['ticket_id']);
        $tabTicket->checkAuthorization($this->request, 'You can not create a tab on this ticket');

        $newTab = new Tab();
        $newTab->setName($data['name']);
        $newTab->setTicketId((int) $data['ticket_id']);

        $createdTab = $this->tabRepository->createTab($newTab);

        $this->logger->info("New tab with id `{$createdTab->getId()}` was created.");

        return $this->respondWithData($createdTab, 201)->withHeader('Location', "/tabs/{$createdTab->getId()}");
    }
}