<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Domain\Section\SectionValidationException;
use App\Domain\Tab\TabNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class CreateSectionAction extends SectionAction
{

    /**
     * @OA\Post(
     *     tags={"Section"},
     *     path="/api/sections",
     *     summary="Create a new section",
     *     operationId="createSection",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Response(response=201, description="Creation successful"),
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
     *              required={"name","tab_id"},
     *              @OA\Property(property="name", type="string", format="text", example="Section 1"),
     *              @OA\Property(property="tab_id", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws SectionNotFoundException|SectionValidationException|HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        Section::validateSectionData($this->request, $data);

        if (! $this->checkTabAuthorization($data['tab_id'])) {
            throw new HttpUnauthorizedException($this->request, 'You can not create a section on this tab.');
        }

        $newSection = new Section();
        $newSection->setName($data['name']);
        $newSection->setTabId((int) $data['tab_id']);

        $createdSection = $this->sectionRepository->createSection($newSection);

        $this->logger->info("New section with id `{$createdSection->getId()}` was created.");

        return $this->respondWithData($createdSection, 201)->withHeader('Location', "/sections/{$createdSection->getId()}");
    }
}