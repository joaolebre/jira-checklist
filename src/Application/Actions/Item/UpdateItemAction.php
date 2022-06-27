<?php

namespace App\Application\Actions\Item;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateItemAction extends ItemAction
{

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

    }
}