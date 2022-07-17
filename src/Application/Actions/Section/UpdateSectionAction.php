<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Domain\Section\SectionValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateSectionAction extends SectionAction
{

    /**
     * @OA\Put(
     *     tags={"Section"},
     *     path="/api/sections/{id}",
     *     summary="Update a specific section",
     *     operationId="updateSection",
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
     *     @OA\Response(
     *         response=200,
     *         description="Section updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Section object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","order"},
     *              @OA\Property(property="name", type="string", format="text", example="Section 1"),
     *              @OA\Property(property="position", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws SectionNotFoundException
     * @throws HttpBadRequestException
     * @throws SectionValidationException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($sectionId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this section.');
        }

        $section = $this->sectionRepository->findSectionById($sectionId);

        $data = $this->request->getParsedBody();

        Section::validateSectionData($this->request, $data);

        $section->setName($data['name']);
        $section->setPosition((int) $data['position']);

        $this->sectionRepository->updateSection($section);

        $this->logger->info("Section with id `{$sectionId}` was updated.");

        return $this->respondWithData($section);
    }
}