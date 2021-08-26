<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

use App\Entity\User;

class UserRegisteredEvent extends Event
{
    public $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}