<?php

namespace App\Application\Actions\ItemStatus;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\ItemStatus\ItemStatusRepository;
use Psr\Log\LoggerInterface;

abstract class ItemStatusAction extends Action
{
    /**
     * @var ItemStatusRepository
     */
    protected $itemStatusRepository;

    /**
     * @param LoggerInterface $logger
     * @param ItemStatusRepository $itemStatusRepository
     */
    public function __construct(LoggerInterface $logger,
                                ItemStatusRepository $itemStatusRepository
    ) {
        parent::__construct($logger);
        $this->itemStatusRepository = $itemStatusRepository;
    }
}