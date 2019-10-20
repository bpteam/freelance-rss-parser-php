<?php

namespace Freelance\Command;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Freelance\Checker\CheckerInterface;
use Freelance\Entity\Job;
use Freelance\Feed\FeedInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CheckFeedCommand extends Command
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var FeedInterface
     */
    private $feed;
    /**
     * @var CheckerInterface
     */
    private $checker;
    /**
     * @var string
     */
    private $feedUrl;
    /**
     * @var int
     */
    private $sleepSeconds;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RegistryInterface $doctrine,
        CheckerInterface $checker,
        FeedInterface $feed,
        LoggerInterface $logger,
        string $feedUrl,
        int $sleepSeconds = 30
    ) {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->checker = $checker;
        $this->feedUrl = $feedUrl;
        $this->feed = $feed;
        $this->sleepSeconds = $sleepSeconds;
        $this->logger = $logger;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->doctrine->getRepository(Job::class);
        $em = $this->doctrine->getManager();
        do {
            try {
                $jobs = $this->feed->get($this->feedUrl);

                foreach ($jobs as $job) {
                    if (
                        $this->checker->isHit($job)
                        && !$repository->hasJobByUrl($job->getUrl())
                    ) {
                        $em->persist($job);
                        $em->flush();
                    }
                }
            } catch (ORMException | DBALException $e) {
                $this->logger->error($e->getMessage());

                return $e->getCode();
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        } while (sleep($this->sleepSeconds) === 0);
    }
}