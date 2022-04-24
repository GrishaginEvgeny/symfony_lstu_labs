<?php

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class WrongEmailException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey():string
    {
        return 'Вы ввели неверную почту!';
    }
}