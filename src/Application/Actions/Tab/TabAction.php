<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Section\SectionRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use Psr\Log\LoggerInterface;

abstract class TabAction extends Action
{
    /**
     * @var TabRepository
     * @var SectionRepository
     */
    protected $tabRepository;
    protected $sectionRepository;

    /**
     * @param LoggerInterface $logger
     * @param TabRepository $tabRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(LoggerInterface $logger,
                                TabRepository $tabRepository,
                                SectionRepository $sectionRepository
    ) {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
        $this->sectionRepository = $sectionRepository;
    }

}