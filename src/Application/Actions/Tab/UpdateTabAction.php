<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use App\Domain\Tab\TabValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateTabAction extends TabAction
{

    /**
     * @OA\Put(
     *     tags={"Tab"},
     *     path="/api/tabs/{id}",
     *     summary="Update a specific tab",
     *     operationId="updateTab",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Tab updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tab not found"
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
     *         description="Tab object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"name","order"},
     *              @OA\Property(property="name", type="string", format="text", example="Tab 1"),
     *              @OA\Property(property="position", type="integer", format="int64", example=1)
     *         )
     *     )
     * )
     * @return Response
     * @throws TabNotFoundException|HttpBadRequestException
     * @throws TabValidationException
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($tabId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this tab.');
        }

        $tab = $this->tabRepository->findTabById((int) $tabId);

        $data = $this->request->getParsedBody();

        Tab::validateTabData($this->request, $data);

        $tab->setName($data['name']);
        $tab->setPosition((int) $data['position']);

        $this->tabRepository->updateTab($tab);

        $this->logger->info("Tab with id `{$tabId}` was updated.");

        return $this->respondWithData($tab);
    }
}