<?php

namespace App\Listener;

use App\MovieOrderedEvent;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(MovieOrderedEvent::class)]
final class NotifyNotRecommandedMovieListener
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
    )
    {
    }

    public function __invoke(MovieOrderedEvent $movieOrderedEvent): void
    {
        if ($this->security->isGranted('ORDER_MOVIE', $movieOrderedEvent->movie)) {
            return;
        }

        $admins = $this->userRepository->findAllAdmins();

        foreach ($admins as $admin) {
            dump(sprintf(
                'Mail to %s: Not recommanded movie "%s" ordred by %s',
                $admin->getUserIdentifier(),
                $movieOrderedEvent->movie->getTitle(),
                $this->security->getUser()->getUserIdentifier(),
            ));
        }
    }
}