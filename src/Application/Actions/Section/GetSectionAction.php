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

        $section->setItems($this->itemRepository->findItemsBySectionId($sectionId));

        $this->logger->info("Section with id `${sectionId}` was viewed.");

        return $this->respondWithData($section);
    }
}