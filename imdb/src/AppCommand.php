<?php

namespace App;

use App\Omdb\OmdbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCommand extends Command
{
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var OmdbClient
     */
    private $omdb;

    public function __construct(OmdbClient $omdb)
    {
        $this->omdb = $omdb;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setName('app:movie:search')
            ->addArgument('keyword', InputArgument::OPTIONAL, 'Keyword of the movie you are looking for')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $keyword = $input->getArgument('keyword');
        if (!$keyword) {
            $keyword = $io->ask('Which movie do you want to search', 'Harry Potter', function($answer) {
                $answer = strtolower($answer);
                $forbiddenKeywords = ['hassle', 'shit', 'fuck'];

                array_walk($forbiddenKeywords, function($keyword) use ($answer) {
                    if (false !== strpos($answer, $keyword)) {
                        throw new \InvalidArgumentException('Your keyword is not valid, please try again');
                    }
                });

                return $answer;
            });
        }

        $search = $this->omdb->requestBySearch($keyword, ['type' => 'movie']);

        $io->success(sprintf('%s movies are matching the keyword "%s"', $search['totalResults'], $keyword));

        $io->progressStart(count($search['Search']));
        $movies = [];
        foreach ($search['Search'] as $movie) {
            $movies[] = [$movie['Title'], $movie['Year'], $movie['Type'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/', '<href='.$movie['Poster'].'>Preview</>'];

            usleep(100000);
            $io->progressAdvance(1);
        }
        $io->write("/\r");

        // https://www.imdb.com/title/tt0064418/
        $io->table(['Title', 'Year', 'Type', 'URL', 'Poster'], $movies);
        //dump($search);

        return Command::SUCCESS;
    }
}
