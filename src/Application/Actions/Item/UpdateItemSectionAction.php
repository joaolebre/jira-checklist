<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemNotFoundException;
use App\Domain\Item\ItemValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UpdateItemSectionAction extends ItemAction
{

    /**
     * @OA\Patch(
     *     tags={"Item"},
     *     path="/api/items/section/{id}",
     *     summary="Update the section of a specific item",
     *     operationId="updateItemSection",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Item section updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
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
     *         description="Section id",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"section_id"},
     *              @OA\Property(property="section_id", type="integer", format="int64", example="1")
     *         )
     *     )
     * )
     * @return Response
     * @throws ItemValidationException|HttpBadRequestException
     * @throws ItemNotFoundException|HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');

        if (! $this->checkAuthorization($itemId)) {
            throw new HttpUnauthorizedException($this->request, 'You are not authorized to modify this item.');
        }

        $data = $this->request->getParsedBody();
        $sectionId = $data['section_id'];

        if (! $this->checkSectionAuthorization($sectionId)) {
            throw new HttpUnauthorizedException($this->request, 'You can not move an item to that section.');
        }

        Item::validateItemData($this->request, $data);

        $item = $this->itemRepository->updateItemSection((int) $itemId, (int) $sectionId);

        $this->logger->info("Section of item with id `{$itemId}` was updated to section with id `{$sectionId}`.");

        return $this->respondWithData($item);
    }
}