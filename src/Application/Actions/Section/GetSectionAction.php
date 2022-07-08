<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetSectionAction extends SectionAction
{

    /**
     * @OA\Get(
     *     tags={"Section"},
     *     path="/api/sections/{id}",
     *     summary="Get a section by id",
     *     operationId="getSectionById",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="full",
     *          in="query",
     *          required=false,
     *          description="Set full to true to get the full section information.",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single section.",
     *          @OA\JsonContent(ref="#/components/schemas/Section")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Section not found."
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     * @return Response
     * @throws SectionNotFoundException|HttpBadRequestException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $section = $this->sectionRepository->findSectionById($sectionId);

        if (!empty($this->request->getQueryParams()) && $this->request->getQueryParams()['full'] == 'true') {
            $items = $this->itemRepository->findItemsBySectionId($section->getId());
            $section->setItems($items);

            foreach ($items as $item) {
                $item->setSectionId($section->getId());
            }

            $this->logger->info("Section with id `${sectionId}` was viewed.");

            return $this->respondWithData($section);
        }

        $sectionData = [
            'id' => (string) $section->getId(),
            'name' => $section->getName(),
            'position' => (string) $section->getPosition(),
            'tab_id' => (string) $section->getTabId()
        ];

        $this->logger->info("Section with id `${sectionId}` was viewed.");

        return $this->respondWithData($sectionData);
    }
}