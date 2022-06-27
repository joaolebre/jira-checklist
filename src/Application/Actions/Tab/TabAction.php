<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Tab\TabRepository;
use Psr\Log\LoggerInterface;

abstract class TabAction extends Action
{
    /**
     * @var TabRepository
     */
    protected $tabRepository;

    /**
     * @param LoggerInterface $logger
     * @param TabRepository $tabRepository
     */
    public function __construct(LoggerInterface $logger,
                                TabRepository $tabRepository
    ) {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

}