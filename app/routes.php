<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\User;
use App\Application\Actions\Ticket;
use App\Application\Actions\ItemStatus;
use App\Application\Actions\Item;
use App\Application\Actions\Section;
use App\Application\Actions\Tab;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->group('/users', function (Group $group) {
            $group->get('', User\ListUsersAction::class);
            $group->get('/{id}', User\GetUserAction::class);
            $group->post('', User\CreateUserAction::class);
            $group->put('/{id}', User\UpdateUserAction::class);
            $group->delete('/{id}', User\DeleteUserAction::class);
        });

        $group->group('/tickets', function (Group $group) {
            $group->get('', Ticket\ListTicketsAction::class);
            $group->get('/{id}', Ticket\GetTicketAction::class);
            $group->post('', Ticket\CreateTicketAction::class);
            $group->put('/{id}', Ticket\UpdateTicketAction::class);
            $group->delete('/{id}', Ticket\DeleteTicketAction::class);
        });

        $group->group('/tabs', function (Group $group) {
            $group->get('', Tab\ListTabsAction::class);
            $group->get('/{id}', Tab\GetTabAction::class);
            $group->post('', Tab\CreateTabAction::class);
            $group->put('/{id}', Tab\UpdateTabAction::class);
            $group->delete('/{id}', Tab\DeleteTabAction::class);
        });

        $group->group('/sections', function (Group $group) {
            $group->get('', Section\ListSectionsAction::class);
            $group->get('/{id}', Section\GetSectionAction::class);
            $group->post('', Section\CreateSectionAction::class);
            $group->put('/{id}', Section\UpdateSectionAction::class);
            $group->delete('/{id}', Section\DeleteSectionAction::class);
        });

        $group->group('/items', function (Group $group) {
            $group->get('', Item\ListItemsAction::class);
            $group->get('/{id}', Item\GetItemAction::class);
            $group->post('', Item\CreateItemAction::class);
            $group->put('/{id}', Item\UpdateItemAction::class);
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
        $group->get('/{id}', GetUserAction::class);
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
