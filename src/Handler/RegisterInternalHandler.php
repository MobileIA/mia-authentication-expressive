<?php

namespace Mobileia\Expressive\Auth\Handler;

/**
 * Description of RegisterInternalHandler
 *
 * @author matiascamiletti
 */
class RegisterInternalHandler extends \Mobileia\Expressive\Request\MiaRequestHandler
{
    protected $sendMail = false;
    
    public function __construct($sendMail = false)
    {
        $this->sendMail = $sendMail;
    }
    
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // Obtener parametros obligatorios
        $email = $this->getParam($request, 'email', '');
        $password = $this->getParam($request, 'password', '');
        // Verificar si ya existe la cuenta
        $account = \Mobileia\Expressive\Auth\Model\MIAUser::where('email', $email)->first();
        if($account !== null||$email == ''){
            return new \Mobileia\Expressive\Diactoros\MiaJsonErrorResponse(-1, 'Este email ya se encuentra registrado');
        }
        // Creamos cuenta
        $account = new \Mobileia\Expressive\Auth\Model\MIAUser();
        $account->mia_id = 0;
        $account->firstname = $this->getParam($request, 'firstname', '');
        if($account->firstname == ''){
            $account->firstname = 'empty';
        }
        $account->lastname = $this->getParam($request, 'lastname', '');
        $account->email = $email;
        $account->phone = $this->getParam($request, 'phone', '');
        $account->photo = $this->getParam($request, 'photo', '');
        $account->password = \Mobileia\Expressive\Auth\Model\MIAUser::encryptPassword($password);
        $account->role = \Mobileia\Expressive\Auth\Model\MIAUser::ROLE_GENERAL;
        $account->save();
        
        if($this->sendMail){
            // Generar registro de token
            $token = \Mobileia\Expressive\Auth\Model\MIAUser::encryptPassword($email . '_' . time() . '_' . $email);
            $recovery = new \Mobileia\Expressive\Auth\Model\MIAActive();
            $recovery->user_id = $account->id;
            $recovery->status = \Mobileia\Expressive\Auth\Model\MIAActive::STATUS_PENDING;
            $recovery->token = $token;
            $recovery->save();
            
            /* @var $sendgrid \Mobileia\Expressive\Mail\Service\Sendgrid */
            $sendgrid = $request->getAttribute('Sendgrid');
            $sendgrid->send($account->email, 'New User', 'newUser.phtml', [
                'firstname' => $account->firstname,
                'email' => $account->email,
                'account' => $account,
                'token' => $token
            ]);
        }
        
        // Devolvemos datos del usuario
        return new \Mobileia\Expressive\Diactoros\MiaJsonResponse($account->toArray());
    }
}
