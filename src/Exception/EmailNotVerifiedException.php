<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
class EmailNotVerifiedException extends AuthenticationException
{
    public function __construct($user, $message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setUser($user);
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    public function getUser()
    {
        return $this->user;
    }
}