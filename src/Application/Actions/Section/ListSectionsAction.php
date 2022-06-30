<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use Psr\Http\Message\ResponseInterface as Response;

class ListSectionsAction extends SectionAction
{

    /**
     * @OA\Get(
     *     tags={"Section"},
     *     path="/api/sections",
     *     summary="Get a list of all sections",
     *     operationId="listSections",
     *      @OA\Response(
     *          response=200,
     *          description="List all sections.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Section")
     *          )
     *      )
     * )
     */
    protected function action(): Response
    {
        $sections = $this->sectionRepository->findAll();

        $this->logger->info("Section list was viewed");

        return $this->respondWithData($sections);
    }
}