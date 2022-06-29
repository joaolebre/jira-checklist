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

        $responseData = array('statusCode' => 200,'message' => 'Login successful!');
        $responsePayload = json_encode($responseData);

        $this->response->getBody()->write($responsePayload);

        return $this->response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}