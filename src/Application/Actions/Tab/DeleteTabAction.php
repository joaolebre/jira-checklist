<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteTabAction extends TabAction
{

    /**
     * @OA\Delete(
     *     tags={"Tab"},
     *     path="/api/tabs/{id}",
     *     summary="Deletes a tab",
     *     operationId="deleteTab",
     *     @OA\Parameter(
     *         name="tabId",
     *         in="path",
     *         description="Tab id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tab deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tab not found",
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TabNotFoundException
     */
    protected function action(): Response
    {
        $tabId = $this->resolveArg('id');
        $this->tabRepository->deleteTabById($tabId);

        $this->logger->info("Tab with id `${tabId} deleted successfully`.");

        return $this->respondWithData();
    }
}