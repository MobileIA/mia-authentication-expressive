<?php

namespace Mobileia\Expressive\Auth\Handler\Role;

/**
 * Description of ListHandler
 *
 * @author matiascamiletti
 */
class ListHandler extends \Mobileia\Expressive\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtenemos información
        $rows = \Mobileia\Expressive\Auth\Model\MIARole::orderBy('title', 'asc')->get();
        // Devolvemos respuesta
        return new \Mobileia\Expressive\Diactoros\MiaJsonResponse($rows->toArray());
    }
}