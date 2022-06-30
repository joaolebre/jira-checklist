<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use Psr\Http\Message\ResponseInterface as Response;

class ListTabsAction extends TabAction
{

    /**
     * @OA\Get(
     *     tags={"Tab"},
     *     path="/api/tabs",
     *     summary="Get a list of all tabs",
     *     operationId="listTabs",
     *      @OA\Response(
     *          response=200,
     *          description="List all tabs.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Tab")
     *          )
     *      )
     * )
     */
    protected function action(): Response
    {
        $tabs = $this->tabRepository->findAll();
        $this->logger->info("Tab list was viewed");

        return $this->respondWithData($tabs);
    }
}