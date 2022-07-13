<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetTicketAction extends TicketAction
{

    /**
     * @OA\Get(
     *     tags={"Ticket"},
     *     path="/api/tickets/{id}",
     *     summary="Get a ticket by id",
     *     operationId="getTicketById",
     *     security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id.",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="full",
     *          in="query",
     *          required=false,
     *          description="Set full to true to get the full ticket information.",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get a single ticket.",
     *          @OA\JsonContent(ref="#/components/schemas/Ticket")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Ticket not found."
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized / Token missing or invalid"
     *     )
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws TicketNotFoundException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');
        $ticket = $this->ticketRepository->findTicketById($ticketId);

        if (!empty($this->request->getQueryParams()) && $this->request->getQueryParams()['full'] == 'true') {
            $ticket->setTabs($this->tabRepository->findTabsByTicketId($ticketId));

            foreach ($ticket->getTabs() as $tab) {
                $sections = $this->sectionRepository->findSectionsByTabId($tab->getId());
                $tab->setTicketId($ticket->getId());
                $tab->setSections($sections);

                foreach ($sections as $section) {
                    $items = $this->itemRepository->findItemsBySectionId($section->getId());
                    $section->setTabId($tab->getId());
                    $section->setItems($items);

                    foreach ($items as $item) {
                        $item->setSectionId($section->getId());
                    }
                }
            }

            $this->logger->info("Ticket of id `${ticketId}` was viewed.");

            return $this->respondWithData($ticket);
        }

        $this->logger->info("Ticket of id `${ticketId}` was viewed.");

        return $this->respondWithData($ticket);
    }
}