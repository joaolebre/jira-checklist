<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Section\Section;
use App\Domain\Section\SectionNotFoundException;
use App\Domain\Tab\Tab;
use App\Domain\Tab\TabNotFoundException;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTicketAction extends TicketAction
{

    /**
     * @return Response
     * @throws TicketNotFoundException
     * @throws TabNotFoundException
     * @throws SectionNotFoundException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $newTicket = new Ticket();
        $newTicket->setName($data['name']);
        $newTicket->setUserId($data['user_id']);

        $createdTicket = $this->ticketRepository->createTicket($newTicket);
        $createdTicketId = $createdTicket->getId();

        $ticketFirstTab = new Tab();
        $ticketFirstTab->setName('Tab 1');
        $ticketFirstTab->setOrder(1);
        $ticketFirstTab->setTicketId($createdTicketId);

        $createdTab = $this->tabRepository->createTab($ticketFirstTab);

        $ticketFirstSection = new Section();
        $ticketFirstSection->setName('Section 1');
        $ticketFirstSection->setOrder(1);
        $ticketFirstSection->setTabId($createdTab->getId());
        
        $this->sectionRepository->createSection($ticketFirstSection);

        $this->logger->info("New ticket with id `{$createdTicketId}` was created.");

        return $this->respondWithData($createdTicket, 201)->withHeader('Location', "/tickets/{$createdTicketId}");
    }
}