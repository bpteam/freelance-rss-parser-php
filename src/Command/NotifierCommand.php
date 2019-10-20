<?php

namespace Freelance\Command;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;
use Exception;
use Freelance\Entity\Job;
use Freelance\Notifier\NotifierInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotifierCommand extends Command
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var NotifierInterface
     */
    private $notifier;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(
        RegistryInterface $doctrine,
        NotifierInterface $notifier,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->doctrine = $doctrine;
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
            ->addOption('start', null, InputArgument::OPTIONAL, 'start date')
            ->addOption('end', null, InputArgument::OPTIONAL, 'end date', '2999-01-01')
            ->addOption('repeat', null, InputArgument::OPTIONAL, 'repeat interval for request', 'PT1M')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getOption('start');
        if (!isset($value)) {
            $this->io->title('Set start date');
            $this->io->text([
                'Enter date in format "Y-m-d" or "Y-m-d H:i:s" or "yesterday"',
            ]);
            $value = $this->io->ask('start', (new DateTime('today'))->format('c'));
            $input->setOption('start', $value);
        }

        $this->io->writeln('<info>Start</info>: ' . $input->getOption('start'), OutputInterface::VERBOSITY_VERBOSE);
        $this->io->writeln('<info>End</info>: ' . $input->getOption('end'), OutputInterface::VERBOSITY_VERBOSE);
        $this->io->writeln('<info>Repeat</info>: ' . $input->getOption('repeat'), OutputInterface::VERBOSITY_VERBOSE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->doctrine->getRepository(Job::class);
        $start = new DateTime($input->getOption('start'));
        $end = new DateTimeImmutable($input->getOption('end'));
        $interval = new DateInterval($input->getOption('repeat'));
        $now = new DateTimeImmutable('now');
        $withInterval = $now->add($interval);
        $intervalSeconds = $withInterval->getTimestamp() - $now->getTimestamp();
        do {
            foreach ($repository->findByCreatedAtRange(clone $start, $start->add($interval)) as $job) {
                $this->notifier->notify($job);
            }
        } while ($this->waitNextIteration($start, $end, $intervalSeconds));
    }

    private function waitNextIteration(DateTimeInterface $start, DateTimeInterface $end, int $interval): bool
    {
        $now = time();
        $sleepSeconds = $interval - ($now - $start->getTimestamp());
        $isPast = $start->getTimestamp() < ($now - $interval);
        $canContinue = $start->getTimestamp() < $end->getTimestamp();
        return $isPast || ($canContinue && ($sleepSeconds < 0 || (sleep($sleepSeconds) === 0)));
    }
}