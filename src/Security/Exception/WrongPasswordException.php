<?php

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class WrongPasswordException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey():string
    {
        return 'Вы ввели неверный пароль!';
    }
}
