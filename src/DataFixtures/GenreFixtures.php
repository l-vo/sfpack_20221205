<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $thriller = new Genre();
        $thriller->setName('Thriller');
        $manager->persist($thriller);
        $this->addReference('genre-thriller', $thriller);

        $concert = new Genre();
        $concert->setName('Concert');
        $manager->persist($concert);
        $this->addReference('genre-concert', $concert);

        $drama = new Genre();
        $drama->setName('Drama');
        $manager->persist($drama);
        $this->addReference('genre-drama', $drama);

        $romance = new Genre();
        $romance->setName('Romance');
        $manager->persist($romance);
        $this->addReference('genre-romance', $romance);

        $manager->flush();
    }
}