<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\User;

class JWTSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnJwtCreated($event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        
        if($user instanceof User){
            $data['email'] = $user->getEmail();
            //$data['exp'] = 1620213879;
        } 
        $event->setData($data); 
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
        ];
    }
}
