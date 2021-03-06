<?php

declare(strict_types=1);

namespace Mobileia\Expressive\Auth;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                
            ],
            'factories'  => [
                \MobileIA\Auth\MobileiaAuth::class => \Mobileia\Expressive\Auth\Factory\MobileiaAuthFactory::class,
                \Mobileia\Expressive\Auth\Handler\AuthHandler::class => \Mobileia\Expressive\Auth\Factory\AuthHandlerFactory::class,
                \Mobileia\Expressive\Auth\Handler\AuthOptionalHandler::class => \Mobileia\Expressive\Auth\Factory\AuthOptionalHandlerFactory::class,
                \Mobileia\Expressive\Auth\Handler\GoogleSignInHandler::class => \Mobileia\Expressive\Auth\Factory\GoogleSignInFactory::class,
                \Mobileia\Expressive\Auth\Handler\AppleSignInHandler::class => \Mobileia\Expressive\Auth\Factory\AppleSignInFactory::class,
                \Mobileia\Expressive\Auth\Handler\GoogleSignInFirebaseHandler::class => \Mobileia\Expressive\Auth\Factory\GoogleSignInFirebaseFactory::class,
            ],
        ];
    }
}
