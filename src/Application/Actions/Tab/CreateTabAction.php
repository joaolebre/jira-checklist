<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTabAction extends TabAction
{

    /**
     * @OA\Post(
     *     tags={"Tab"},
     *     path="/api/tabs",
     *     summary="Create a new tab",
     *     operationId="createTab",
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\RequestBody(
     *         description="Tab object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","order","ticket_id"},
     *              @OA\Property(property="name", type="string", format="text", example="Tab 1"),
     *              @OA\Property(property="order", type="integer", format="int64", example=1),
     *              @OA\Property(property="ticket_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws TabNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $newTab = new Tab();
        $newTab->setName($data['name']);
        $newTab->setOrder((int) $data['order']);
        $newTab->setTicketId((int) $data['ticket_id']);

        $createdTab = $this->tabRepository->createTab($newTab);

        $this->logger->info("New tab with id `{$createdTab->getId()}` was created.");

        return $this->respondWithData($createdTab, 201)->withHeader('Location', "/tabs/{$createdTab->getId()}");
    }
}