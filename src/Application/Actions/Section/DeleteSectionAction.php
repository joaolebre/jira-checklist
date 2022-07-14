<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteSectionAction extends SectionAction
{

    /**
     * @OA\Delete(
     *     tags={"Section"},
     *     path="/api/sections/{id}",
     *     summary="Deletes a section",
     *     operationId="deleteSection",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Section id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Section deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     ),
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws SectionNotFoundException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');
        $this->sectionRepository->deleteSectionById($sectionId);

        $this->logger->info("Item with id `${sectionId} deleted successfully`.");

        return $this->respondWithData();
    }
}