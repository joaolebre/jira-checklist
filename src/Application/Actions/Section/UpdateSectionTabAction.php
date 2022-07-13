<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Domain\Section\SectionValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateSectionTabAction extends SectionAction
{

    /**
     * @OA\Patch(
     *     tags={"Section"},
     *     path="/api/sections/tab/{id}",
     *     summary="Update the tab of a specific section",
     *     operationId="updateSectionTab",
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
     *     @OA\Response(
     *         response=200,
     *         description="Section tab updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     *     @OA\RequestBody(
     *         description="Tab id",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"tab_id"},
     *              @OA\Property(property="tab_id", type="integer", format="int64", example="1")
     *         )
     *     )
     * )
     * @return Response
     * @throws SectionValidationException
     * @throws HttpBadRequestException|SectionNotFoundException
     */
    protected function action(): Response
    {
        $sectionId = $this->resolveArg('id');
        $data = $this->request->getParsedBody();
        $tabId = $data['tab_id'];

        Section::validateSectionData($this->request,$data);

        $section = $this->sectionRepository->updateSectionTab((int) $sectionId, (int) $tabId);

        $this->logger->info("Tab of section with id `{$sectionId}` was updated to tab with id `{$tabId}`.");

        return $this->respondWithData($section);
    }
}