<?php

namespace Freelance\Command;

use Freelance\Checker\CheckerInterface;
use Freelance\Feed\FeedInterface;
use Freelance\Repository\JobRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CheckFeedCommand extends Command
{
    public function __construct(
        private readonly JobRepository $jobRepository,
        private readonly CheckerInterface $checker,
        private readonly FeedInterface $feed,
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $jobs = $this->feed->get();

        foreach ($jobs as $job) {
            if (
                $this->checker->isHit($job)
                && !$this->jobRepository->hasJobByUrl($job->getUrl())
            ) {
                $this->jobRepository->save($job);
            }
        }

        return Command::SUCCESS;
    }
}