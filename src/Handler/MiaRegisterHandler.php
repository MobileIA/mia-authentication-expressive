<?php

namespace Mobileia\Expressive\Auth\Handler;

/**
 * Description of MiaRegisterHandler
 *
 * @author matiascamiletti
 */
class MiaRegisterHandler extends \Mobileia\Expressive\Request\MiaRequestHandler
{
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener cuenta seleccionada
        $miaId = $this->getParam($request, 'id', 0);
        // Verificamos que se haya enviado los datos del usuario
        if($miaId <= 0){
            return new Mobileia\Expressive\Diactoros\MiaJsonErrorResponse(-1, 'No se ha enviado el ID');
        }
        // Buscar por el MIA_ID
        $user = \Mobileia\Expressive\Auth\Model\MIAUser::where('mia_id', $miaId)->first();
        // Verificamos si ya existe este MIA_ID
        if($user === null){
            $user = new \Mobileia\Expressive\Auth\Model\MIAUser();
        }
        // Actualizamos los parametros
        $user->mia_id = $miaId;
        $user->firstname = ucfirst($this->getParam($request, 'firstname', ''));
        if($user->firstname == ''){
            $user->firstname= 'empty';
        }
        $user->lastname = ucfirst($this->getParam($request, 'lastname', ''));
        $user->email = $this->getParam($request, 'email', '');
        $user->photo = $this->getParam($request, 'photo', '');
        $user->phone = $this->getParam($request, 'phone', '');
        $user->facebook_id = $this->getParam($request, 'facebook_id', '');
        if($user->facebook_id == null){
            $user->facebook_id = '';
        }
        $user->role = $this->getParam($request, 'role', \Mobileia\Expressive\Auth\Model\MIAUser::ROLE_GENERAL);
        // Guardamos el nuevo usuario
        $user->save();
        // Devolvemos datos del usuario
        return new \Mobileia\Expressive\Diactoros\MiaJsonResponse($user->toArray());
    }
}