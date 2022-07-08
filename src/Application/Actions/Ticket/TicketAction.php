<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Item\Item;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use App\Infrastructure\Persistence\Ticket\TicketRepository;
use Psr\Log\LoggerInterface;

abstract class TicketAction extends Action
{
    /**
     * @var TicketRepository
     * @var TabRepository
     * @var SectionRepository
     * @var ItemRepository
     */
    protected $ticketRepository;
    protected $tabRepository;
    protected $sectionRepository;
    protected $itemRepository;

    /**
     * @param LoggerInterface $logger
     * @param TicketRepository $ticketRepository
     * @param TabRepository $tabRepository
     * @param SectionRepository $sectionRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(LoggerInterface $logger,
                                TicketRepository $ticketRepository,
                                TabRepository $tabRepository,
                                SectionRepository $sectionRepository,
                                ItemRepository $itemRepository
    ) {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
        $this->tabRepository = $tabRepository;
        $this->sectionRepository = $sectionRepository;
        $this->itemRepository = $itemRepository;
    }
}