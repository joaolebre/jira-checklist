<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateSectionAction extends SectionAction
{

    /**
     * @OA\Put(
     *     tags={"Section"},
     *     path="/api/sections/{id}",
     *     summary="Update a specific section",
     *     operationId="updateSection",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Section updated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\RequestBody(
     *         description="Section object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","order"},
     *              @OA\Property(property="name", type="string", format="text", example="Section 1"),
     *              @OA\Property(property="order", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws SectionNotFoundException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $section = $this->sectionRepository->findSectionById($sectionId);

        $data = $this->request->getParsedBody();

        $section->setName($data['name']);
        $section->setOrder((int) $data['order']);

        $this->sectionRepository->updateSection($section);

        $this->logger->info("Section with id `{$sectionId}` was updated.");

        return $this->respondWithData($section)->withHeader('Location', "/sections/{$sectionId}");
    }
}