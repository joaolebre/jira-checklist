<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use App\Infrastructure\Persistence\Ticket\TicketRepository;
use Psr\Log\LoggerInterface;

abstract class TabAction extends Action
{
    /**
     * @var TabRepository
     * @var SectionRepository
     * @var ItemRepository
     * @var TicketRepository
     */
    protected $tabRepository;
    protected $sectionRepository;
    protected $itemRepository;
    protected $ticketRepository;

    /**
     * @param LoggerInterface $logger
     * @param TabRepository $tabRepository
     * @param SectionRepository $sectionRepository
     * @param ItemRepository $itemRepository
     * @param TicketRepository $ticketRepository
     */
    public function __construct(LoggerInterface $logger,
                                TabRepository $tabRepository,
                                SectionRepository $sectionRepository,
                                ItemRepository $itemRepository,
                                TicketRepository $ticketRepository
    ) {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
        $this->sectionRepository = $sectionRepository;
        $this->itemRepository = $itemRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function checkAuthorization(int $tabId): bool {
        $userTabs = $this->tabRepository->findAllByUserId(User::getLoggedInUserId($this->request));

        if (User::getLoggedInUserRole($this->request) == 'admin') {
            return true;
        }

        foreach ($userTabs as $tab) {
            if ($tab->getId() == $tabId) {
                return true;
            }
        }
        return false;
    }
}