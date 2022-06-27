<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Ticket;
use App\Application\Actions\ItemStatus;
use App\Application\Actions\Item;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id}', ViewUserAction::class);
        });

        $group->group('/tickets', function (Group $group) {
            $group->get('', Ticket\ListTicketsAction::class);
            $group->get('/{id}', Ticket\GetTicketAction::class);
        });

        $group->group('/items', function (Group $group) {
            $group->get('', Item\ListItemsAction::class);
            $group->get('/{id}', Item\GetItemAction::class);
            $group->post('', Item\CreateItemAction::class);
            $group->delete('/{id}', Item\DeleteItemAction::class);
        });

        $group->group('/item-statuses', function (Group $group) {
            $group->get('', ItemStatus\ListItemStatusesAction::class);
            $group->get('/{id}', ItemStatus\GetItemStatusAction::class);
        });
    });

    /*
    $app->group('/users', function () use ($app): void {
        $app->get('', User\GetAll::class)->add(new Auth());
        $app->post('', User\Create::class);
        $app->get('/{id}', User\GetOne::class)->add(new Auth());
        $app->put('/{id}', User\Update::class)->add(new Auth());
        $app->delete('/{id}', User\Delete::class)->add(new Auth());
    });

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });


    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/statuses', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
        $sth = $db->prepare("SELECT * FROM item_statuses");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });
    */
};
