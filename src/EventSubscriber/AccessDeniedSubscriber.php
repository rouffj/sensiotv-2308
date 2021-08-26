<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class AccessDeniedSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        dump($event);
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $previousException = $event->getThrowable()->getPrevious();
        if (!$previousException instanceof AccessDeniedException) {
            return;
        }

        $homepageUrl = '/?msg=' . $previousException->getMessage();
        $response = new RedirectResponse($homepageUrl);
     
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
            'user_registered' => 'onUserRegistered',
        ];
    }
}
