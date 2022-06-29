<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserException;
use App\Domain\User\UserLoginFailedException;
use Psr\Http\Message\ResponseInterface as Response;

class LoginUserAction extends UserAction
{

    /**
     * @return Response
     * @throws UserException
     * @throws UserLoginFailedException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        if (! isset($data['email'])) {
            throw new UserException('Email is required.', 400);
        }

        if (! isset($data['password'])) {
            throw new UserException('Password is required.', 400);
        }

        $this->userRepository->loginUser($data['email'], $data['password']);

        $this->response->getBody()->write('Login successfull!');

        return $this->respondWithData();
    }
}