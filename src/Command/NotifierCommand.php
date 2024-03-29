<?php

namespace Freelance\Command;

use DateTime;
use DateTimeInterface;
use Exception;
use Freelance\Entity\Job;
use Freelance\Notifier\NotifierInterface;
use Freelance\Repository\JobPositionRepository;
use Freelance\Repository\JobRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotifierCommand extends Command
{
    private JobRepository $jobRepository;
    private JobPositionRepository $jobPositionRepository;
    private NotifierInterface $notifier;
    private LoggerInterface $logger;
    private SymfonyStyle $io;

    public function __construct(
        JobRepository $jobRepository,
        NotifierInterface $notifier,
        LoggerInterface $logger,
        JobPositionRepository $jobPositionRepository,
    ) {
        parent::__construct();
        $this->jobRepository = $jobRepository;
        $this->jobPositionRepository = $jobPositionRepository;
        $this->notifier = $notifier;
        $this->logger = $logger;
    }


    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate new partitions for tables')
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
        foreach ($this->jobRepository->findAfterId($this->jobPositionRepository->getPosition()) as $job) {
            try {
                $this->notifier->notify($job);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
                return Command::FAILURE;
            } finally {
                $this->jobPositionRepository->setPosition($job->getId());
            }
            usleep(500);
        }

        return Command::SUCCESS;
    }
}