<?php

namespace App\Listener;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(LoginSuccessEvent::class)]
final class LastLoginListener
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $user->setLastLogin(new \DateTimeImmutable());

        $this->userRepository->save($user, true);
    }
}