<?php

namespace App\Command;

use App\Api\OmdbConsumer;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:movies:import',
    description: 'Import movies data from OMDB api to database',
)]
class MoviesImportCommand extends Command
{
    public function __construct(
        private OmdbConsumer $omdbConsumer,
        private SluggerInterface $slugger,
        private MovieRepository $movieRepository,
        private GenreRepository $genreRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title-or-id', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'Movie title or id')
            ->setHelp(<<<EOT
            The <info>%command.name%</info> import movies data from OMDB api to database:

            <info>php %command.full_name% movie1-title movie2-title</info>
            <info>php %command.full_name% movie1-id movie2-id</info>
            <info>php %command.full_name% movie1-id movie2-title</info>
            EOT)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $rows = [];
        foreach ($input->getArgument('title-or-id') as $titleOrId) {
            $io->info(sprintf('Trying to retrieve movie with title or id %s', $titleOrId));

            $data = null;
            try {
                $data = $this->omdbConsumer->getById($titleOrId);
            } catch (ClientException) {
                try {
                    $data = $this->omdbConsumer->getByTitle($titleOrId);
                } catch (ClientException) {
                }
            }

            if (null === $data) {
                $io->warning(sprintf('No movie found for id or title %s', $titleOrId));

                continue;
            }

            $io->info(sprintf('Inserting movie %s in database', $titleOrId));

            $movie = (new Movie())
                ->setTitle($data['Title'])
                ->setSlug($this->slugger->slug($data['Title']))
                ->setReleased(new \DateTimeImmutable($data['Released']))
                ->setPoster($data['Poster'])
                ->setCountry($data['Country'])
                ->setRated($data['Rated'])
            ;

            foreach (explode(', ', $data['Genre']) as $genreName) {
                $genre = $this->genreRepository->findOneByName($genreName);

                if (null === $genre) {
                    $genre = new Genre();
                    $genre->setName($genreName);
                    $this->genreRepository->save($genre, true);
                }

                $movie->addGenre($genre);
            }

            $this->movieRepository->save($movie, true);

            $rows[] = [$data['imdbID'], $movie->getId(), $data['Title'], $data['Rated']];
        }

        if (count($rows) > 0) {
            $io->success('Movies inserted !');
        }

        $io->table(
            ['Id in api', 'Id in database', 'Title', 'Rated'],
            $rows,
        );

        return Command::SUCCESS;
    }
}
