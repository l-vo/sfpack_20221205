<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class UserFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    public function load(ObjectManager $manager)
    {
        $adult = (new User())
            ->setUsername('adult_user')
            ->setBirthdate(new \DateTimeImmutable('1990-12-08'))
        ;
        $adult->setPassword(
            $this->passwordHasherFactory->getPasswordHasher($adult)->hash('pass'),
        );
        $manager->persist($adult);

        $child = (new User())
            ->setUsername('child_user')
            ->setBirthdate(new \DateTimeImmutable('2013-06-12'))
        ;
        $child->setPassword(
            $this->passwordHasherFactory->getPasswordHasher($child)->hash('pass'),
        );
        $manager->persist($child);

        $teen = (new User())
            ->setUsername('teen_user')
            ->setBirthdate(new \DateTimeImmutable('2006-11-14'))
        ;
        $teen->setPassword(
            $this->passwordHasherFactory->getPasswordHasher($teen)->hash('pass'),
        );
        $manager->persist($teen);

        $admin = (new User())
            ->setUsername('admin')
            ->setBirthdate(new \DateTimeImmutable('1985-12-11'))
        ;
        $admin->setPassword(
            $this->passwordHasherFactory->getPasswordHasher($admin)->hash('pass'),
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}