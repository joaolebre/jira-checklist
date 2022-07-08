<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use Psr\Log\LoggerInterface;

abstract class TabAction extends Action
{
    /**
     * @var TabRepository
     * @var SectionRepository
     * @var ItemRepository
     */
    protected $tabRepository;
    protected $sectionRepository;
    protected $itemRepository;

    /**
     * @param LoggerInterface $logger
     * @param TabRepository $tabRepository
     * @param SectionRepository $sectionRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(LoggerInterface $logger,
                                TabRepository $tabRepository,
                                SectionRepository $sectionRepository,
                                ItemRepository $itemRepository
    ) {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
        $this->sectionRepository = $sectionRepository;
        $this->itemRepository = $itemRepository;
    }

}