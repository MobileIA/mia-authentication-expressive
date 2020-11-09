<?php

namespace Mobileia\Expressive\Auth\Handler;

/**
 * Description of LoginInternalHandler
 *
 * @author matiascamiletti
 */
class LoginInternalHandler extends \Mobileia\Expressive\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $password = $this->getParam($request, 'password', '');
        // Verificar si ya existe la cuenta
        $account = \Mobileia\Expressive\Auth\Model\MIAUser::where('email', $email)->first();
        if($account === null){
            return new \Mobileia\Expressive\Diactoros\MiaJsonErrorResponse(-2, 'This account does not exist');
        }
        // Verificar si la contraseña coincide
        if(!\Mobileia\Expressive\Auth\Model\MIAUser::verifyPassword($password, $account->password)){
            return new \Mobileia\Expressive\Diactoros\MiaJsonErrorResponse(-3, 'Password is not correct');
        }
        // Generar nuevo AccessToken
        $token = new \Mobileia\Expressive\Auth\Model\MIAAccessToken();
        $token->user_id = $account->id;
        $token->access_token = \Mobileia\Expressive\Auth\Model\MIAAccessToken::generateAccessToken();
        $token->expires = \Mobileia\Expressive\Auth\Model\MIAAccessToken::generateExpires();
        $token->platform = $this->getParam($request, 'platform', \Mobileia\Expressive\Auth\Model\MIAAccessToken::PLATFORM_WEB);
        $token->version = $this->getParam($request, 'version', '');
        $token->device_data = $this->getParam($request, 'device_data', '');
        $token->save();
        // Devolvemos datos del usuario
        return new \Mobileia\Expressive\Diactoros\MiaJsonResponse(
                array('access_token' => $token->toArray(), 'user' => $account->toArray())
        );
    }
}