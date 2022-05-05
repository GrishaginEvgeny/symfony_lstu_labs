<?php

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiNotProvided extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey():string
    {
        return 'Такого токена нет в базе данных!';
    }
}