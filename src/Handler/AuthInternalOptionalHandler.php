<?php

namespace Mobileia\Expressive\Auth\Handler;

/**
 * Description of AuthInternalOptionalHandler
 *
 * @author matiascamiletti
 */
class AuthInternalOptionalHandler extends AuthInternalHandler
{
    /**
     * 
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // obtener accessToken
        $accessToken = $this->getAccessToken($request);
        // Buscamos el Token en la DB
        $row = \Mobileia\Expressive\Auth\Model\MIAAccessToken::where('access_token', $accessToken)->first();
        // Validar AccessToken
        if($row === null){
            return $handler->handle($request->withAttribute(\Mobileia\Expressive\Auth\Model\MIAUser::class, null));
        }
        // Obtener usuario
        $user = \Mobileia\Expressive\Auth\Repository\MIAUserRepository::findByID($row->user_id);
        // Obtener Usuario para guardarlo
        return $handler->handle($request->withAttribute(\Mobileia\Expressive\Auth\Model\MIAUser::class, $user));
    }
}
