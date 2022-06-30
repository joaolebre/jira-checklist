<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTabAction extends TabAction
{

    /**
     * @OA\Put(
     *     tags={"Tab"},
     *     path="/api/tabs/{id}",
     *     summary="Update a specific tab",
     *     operationId="updateTab",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Tab updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tab not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         description="Tab object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","order"},
     *              @OA\Property(property="name", type="string", format="text", example="Tab 1"),
     *              @OA\Property(property="order", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws TabNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $tabId = $this->resolveArg('id');
        $tab = $this->tabRepository->findTabById((int) $tabId);

        $data = $this->request->getParsedBody();

        $tab->setName($data['name']);
        $tab->setOrder((int) $data['order']);

        $this->tabRepository->updateTab($tab);

        $this->logger->info("Tab with id `{$tabId}` was updated.");

        return $this->respondWithData($tab)->withHeader('Location', "/sections/{$tabId}");
    }
}