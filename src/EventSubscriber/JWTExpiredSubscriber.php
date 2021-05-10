<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTExpiredSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnJwtExpired($event)
    {
        $response = $event->getResponse();
        $response->setMessage('Sorry, Your token has expired :/ !!');
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_expired' => 'onLexikJwtAuthenticationOnJwtExpired',
        ];
    }
}
