<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class ListSectionsAction extends SectionAction
{

    /**
     * @OA\Get(
     *     tags={"Section"},
     *     path="/api/sections",
     *     summary="Get a list of all sections",
     *     operationId="listSections",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=false,
     *          description="Set the user id to filter the sections (admin only).",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List all sections.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Section")
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     */
    protected function action(): Response
    {
        $userId = User::getLoggedInUserId($this->request);
        $userRole = User::getLoggedInUserRole($this->request);

        if ($userRole == 'admin') {
            if (!empty($this->request->getQueryParams()['user_id'])) {
                $userIdFilter = (int) $this->request->getQueryParams()['user_id'];
                $sections = $this->sectionRepository->findAllByUserId($userIdFilter);
            } else {
                $sections = $this->sectionRepository->findAll();
            }
        } else {
            $sections = $this->sectionRepository->findAllByUserId($userId);
        }

        $this->logger->info("Section list was viewed by ${userRole} with the id ${userId}.");

        return $this->respondWithData($sections);
    }
}