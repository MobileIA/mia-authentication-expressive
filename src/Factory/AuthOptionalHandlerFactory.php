<?php

namespace Mobileia\Expressive\Auth\Factory;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

/**
 * Description of AuthOptionalHandlerFactory
 *
 * @author matiascamiletti
 */
class AuthOptionalHandlerFactory
{
    public function __invoke(ContainerInterface $container) : \Mobileia\Expressive\Auth\Handler\AuthHandler
    {
        // Creamos servicio
        $service   = $container->get(\MobileIA\Auth\MobileiaAuth::class);
        // Generamos el handler
        return new \Mobileia\Expressive\Auth\Handler\AuthOptionalHandler($service);
    }
}
