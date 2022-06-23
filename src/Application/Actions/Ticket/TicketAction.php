<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Ticket\TicketRepository;
use Psr\Log\LoggerInterface;

abstract class TicketAction extends Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * @param LoggerInterface $logger
     * @param TicketRepository $ticketRepository
     */
    public function __construct(LoggerInterface $logger,
                                TicketRepository $ticketRepository
    ) {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }
}