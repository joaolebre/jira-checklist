<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserException;
use App\Domain\User\UserLoginFailedException;
use App\Domain\User\UserValidationException;
use Psr\Http\Message\ResponseInterface as Response;

class LoginUserAction extends UserAction
{

    /**
     * @OA\Post(
     *     tags={"User"},
     *     path="/api/users/login",
     *     summary="Login with a certain user",
     *     operationId="loginUser",
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request / Validation Error / Login Failed"
     *     ),
     *     @OA\RequestBody(
     *         description="Login data",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="email", format="text", example="pedro666@example.org"),
     *              @OA\Property(property="password", type="string", format="text", example="password123456")
     *         )
     *     )
     * )
     * @return Response
     * @throws UserLoginFailedException
     * @throws UserValidationException
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        if (! isset($data['email'])) {
            throw new UserValidationException($this->request, 'Email is required.');
        }

        if (! isset($data['password'])) {
            throw new UserValidationException($this->request, 'Password is required.');
        }

        $user = $this->userRepository->loginUser($data['email'], $data['password']);

        if (! $user) {
            throw new UserLoginFailedException($this->request);
        } else {
            $hashedPassword = $user->getPassword();

            if (! password_verify($data['password'], $hashedPassword)) {
                throw new UserLoginFailedException($this->request);
            }
        }

        $responseData = array('statusCode' => 200,'message' => 'Login successful!');
        $responsePayload = json_encode($responseData);

        $this->response->getBody()->write($responsePayload);

        return $this->response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}