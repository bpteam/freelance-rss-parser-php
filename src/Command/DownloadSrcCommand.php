<?php

namespace Freelance\Command;

use DateTime;
use DateTimeInterface;
use Exception;
use Freelance\Entity\Job;
use Freelance\Loader\LoaderInterface;
use Freelance\Notifier\NotifierInterface;
use Freelance\Repository\JobPositionRepository;
use Freelance\Repository\JobRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DownloadSrcCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly LoaderInterface $loader,
    ) {
        parent::__construct();
    }


    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function configure()
    {
        $this
            ->setDescription('Download src of page for parser')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $content = $this->loader->load('https://freelancehunt.com/projects.rss');

        file_put_contents('1.txt', $content);

        return Command::SUCCESS;
    }
}