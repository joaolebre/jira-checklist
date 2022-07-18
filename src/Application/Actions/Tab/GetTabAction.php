<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

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
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="full",
     *          in="query",
     *          required=false,
     *          description="Set full to true to get the full tab information.",
     *          @OA\Schema(
     *              type="string"
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
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($tabId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to view this tab.');
        }

        $tab = $this->tabRepository->findTabById($tabId);

        if (!empty($this->request->getQueryParams()) && $this->request->getQueryParams()['full'] == 'true') {
            $tab->setSections($this->sectionRepository->findSectionsByTabId($tabId));

            foreach ($tab->getSections() as $section) {
                $items = $this->itemRepository->findItemsBySectionId($section->getId());
                $section->setTabId($tab->getId());
                $section->setItems($items);

                foreach ($items as $item) {
                    $item->setSectionId($section->getId());
                }
            }

            $this->logger->info("Tab with id `${tabId}` was viewed.");

            return $this->respondWithData($tab);
        }

        $this->logger->info("Tab with id `${tabId}` was viewed.");

        return $this->respondWithData($tab);
    }
}