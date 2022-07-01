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
use Slim\Views\Twig;
use Symfony\Component\Yaml\Yaml;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello World!');
        return $response;
    });

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            $response->getBody()->write('Welcome to the JIRA Checklist API! Go to https://sandbox.exads.rocks/api/docs for more info.');
            return $response;
        });

        $group->get('/docs', function ($request, $response, $args) {
            $view = Twig::fromRequest($request);
            $yamlFile = __DIR__ . '/../checklist.yaml';
            return $view->render($response, 'docs/swagger.twig', [
                'spec' =>json_encode(Yaml::parseFile($yamlFile)),
            ]);
        });

        $group->group('/users', function (Group $group) {
            $group->get('', User\ListUsersAction::class);
            $group->get('/{id}', User\GetUserAction::class);
            $group->post('', User\CreateUserAction::class);
            $group->post('/login', User\LoginUserAction::class);
            $group->put('/{id}', User\UpdateUserAction::class);
            $group->patch('/password/{id}', User\UpdateUserPasswordAction::class);
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
};
