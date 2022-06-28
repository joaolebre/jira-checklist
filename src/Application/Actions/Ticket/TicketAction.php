<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
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
     */
    protected $ticketRepository;
    protected $tabRepository;
    protected $sectionRepository;

    /**
     * @param LoggerInterface $logger
     * @param TicketRepository $ticketRepository
     * @param TabRepository $tabRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(LoggerInterface $logger,
                                TicketRepository $ticketRepository,
                                TabRepository $tabRepository,
                                SectionRepository $sectionRepository
    ) {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
        $this->tabRepository = $tabRepository;
        $this->sectionRepository = $sectionRepository;
    }
}