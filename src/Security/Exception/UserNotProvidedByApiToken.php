<?php

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserNotProvidedByApiToken extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey():string
    {
        return 'Такой пользователь не найден!';
    }
}