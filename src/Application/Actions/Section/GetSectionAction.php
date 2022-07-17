<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

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
     *             type="integer",
     *             format="int64",
     *             minimum=1
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
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($sectionId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to view this section.');
        }

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

        $this->logger->info("Section with id `${sectionId}` was viewed.");

        return $this->respondWithData($section);
    }
}