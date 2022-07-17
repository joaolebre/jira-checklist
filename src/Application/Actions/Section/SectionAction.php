<?php
declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use App\Infrastructure\Persistence\Tab\TabRepository;
use Psr\Log\LoggerInterface;

abstract class SectionAction extends Action
{
    /**
     * @var SectionRepository
     * @var ItemRepository
     * @var TabRepository
     */
    protected $sectionRepository;
    protected $itemRepository;
    protected $tabRepository;

    /**
     * @param LoggerInterface $logger
     * @param SectionRepository $sectionRepository
     * @param ItemRepository $itemRepository
     * @param TabRepository $tabRepository
     */
    public function __construct(LoggerInterface $logger,
                                SectionRepository $sectionRepository,
                                ItemRepository $itemRepository,
                                TabRepository $tabRepository
    ) {
        parent::__construct($logger);
        $this->sectionRepository = $sectionRepository;
        $this->itemRepository = $itemRepository;
        $this->tabRepository = $tabRepository;
    }

    public function checkAuthorization(int $sectionId): bool {
        $userSections = $this->sectionRepository->findAllByUserId(User::getLoggedInUserId($this->request));

        if (User::getLoggedInUserRole($this->request) == 'admin') {
            return true;
        }

        foreach ($userSections as $section) {
            if ($section->getId() == $sectionId) {
                return true;
            }
        }
        return false;
    }

    public function checkTabAuthorization(int $tabId): bool {
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