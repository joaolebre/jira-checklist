<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabDeleteConflictException;
use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class DeleteTabAction extends TabAction
{

    /**
     * @OA\Delete(
     *     tags={"Tab"},
     *     path="/api/tabs/{id}",
     *     summary="Deletes a tab",
     *     operationId="deleteTab",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tab id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Could not delete tab because of database conflict"
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TabNotFoundException|TabDeleteConflictException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($tabId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to delete this tab.');
        }

        try {
            $this->tabRepository->deleteTabById($tabId);
        } catch (\PDOException $e) {
            $this->logger->error("While deleting tab with id ${tabId}: " . $e->getMessage());
            throw new TabDeleteConflictException($this->request);
        }

        $this->logger->info("Tab with id `${tabId} deleted successfully`.");

        return $this->respondWithData();
    }
}