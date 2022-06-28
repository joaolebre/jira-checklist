<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use Psr\Log\LoggerInterface;

abstract class SectionAction extends Action
{
    /**
     * @var SectionRepository
     */
    protected $sectionRepository;
    protected $itemRepository;

    /**
     * @param LoggerInterface $logger
     * @param SectionRepository $sectionRepository
     */
    public function __construct(LoggerInterface $logger,
                                SectionRepository $sectionRepository,
                                ItemRepository $itemRepository
    ) {
        parent::__construct($logger);
        $this->sectionRepository = $sectionRepository;
        $this->itemRepository = $itemRepository;
    }

}