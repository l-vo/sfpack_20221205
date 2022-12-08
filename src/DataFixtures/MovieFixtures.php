<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setSlug('le-torrent');
        $movie->setTitle('Le torrent');
        $movie->setReleased(new \DateTimeImmutable('2022-11-30'));
        $movie->setPoster('torrent.jpg');
        $movie->setCountry('FR');
        $movie->setPrice('15.90');
        $movie->addGenre($this->getReference('genre-thriller'));
        $movie->setRated('NC-17');
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setSlug('johnny-hallyday');
        $movie->setTitle('Johnny Hallyday');
        $movie->setReleased(new \DateTimeImmutable('2022-12-05'));
        $movie->setPoster('johnny.jpg');
        $movie->setCountry('FR');
        $movie->setPrice('17.90');
        $movie->addGenre($this->getReference('genre-concert'));
        $movie->setRated('G');
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setSlug('days');
        $movie->setTitle('Days');
        $movie->setReleased(new \DateTimeImmutable('2022-11-30'));
        $movie->setPoster('days.jpg');
        $movie->setCountry('FR');
        $movie->setPrice('13.90');
        $movie->addGenre($this->getReference('genre-drama'));
        $movie->addGenre($this->getReference('genre-romance'));
        $movie->setRated('G');
        $manager->persist($movie);

        $manager->flush();
    }
}
