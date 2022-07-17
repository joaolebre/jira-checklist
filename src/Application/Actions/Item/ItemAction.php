<?php
declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Item\ItemRepository;
use App\Infrastructure\Persistence\Section\SectionRepository;
use Psr\Log\LoggerInterface;

abstract class ItemAction extends Action
{
    /**
     * @var ItemRepository
     * @var SectionRepository
     */
    protected $itemRepository;
    protected $sectionRepository;

    /**
     * @param LoggerInterface $logger
     * @param ItemRepository $itemRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(LoggerInterface $logger,
                                ItemRepository $itemRepository,
                                SectionRepository $sectionRepository)
    {
        parent::__construct($logger);
        $this->itemRepository = $itemRepository;
        $this->sectionRepository = $sectionRepository;
    }

    public function checkAuthorization(int $itemId): bool {
        $userItems = $this->itemRepository->findAllByUserId(User::getLoggedInUserId($this->request));

        if (User::getLoggedInUserRole($this->request) == 'admin') {
            return true;
        }

        foreach ($userItems as $item) {
            if ($item->getId() == $itemId) {
                return true;
            }
        }
        return false;
    }

    public function checkSectionAuthorization(int $sectionId): bool {
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

}