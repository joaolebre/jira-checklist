<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionDeleteConflictException;
use App\Domain\Section\SectionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

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
     *     @OA\Response(
     *         response=409,
     *         description="Could not delete section because of database conflict"
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws SectionNotFoundException|SectionDeleteConflictException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($sectionId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to delete this section.');
        }

        try {
            $this->sectionRepository->deleteSectionById($sectionId);
        } catch (\PDOException $e) {
            $this->logger->error("While deleting section with id ${sectionId}: " . $e->getMessage());
            throw new SectionDeleteConflictException($this->request);
        }

        $this->logger->info("Item with id `${sectionId} deleted successfully`.");

        return $this->respondWithData();
    }
}