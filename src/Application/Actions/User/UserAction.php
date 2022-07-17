<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Infrastructure\Persistence\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger,
                                UserRepository $userRepository
    ) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }

    public function checkAuthorization(int $userId): bool {
        return User::getLoggedInUserRole($this->request) == 'admin' || User::getLoggedInUserId($this->request) == $userId;
    }
}
