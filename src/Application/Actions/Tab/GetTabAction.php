<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetTabAction extends TabAction
{

    /**
     * @OA\Get(
     *     tags={"Tab"},
     *     path="/api/tabs/{id}",
     *     summary="Get a tab by id",
     *     operationId="getTabById",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single tab.",
     *          @OA\JsonContent(ref="#/components/schemas/Tab")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Tab not found."
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     * )
     * @return Response
     * @throws TabNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');
        $tab = $this->tabRepository->findTabById($tabId);

        $tab->setSections($this->sectionRepository->findSectionsByTabId($tabId));

        $this->logger->info("Tab with id `${tabId}` was viewed.");

        return $this->respondWithData($tab);
    }
}