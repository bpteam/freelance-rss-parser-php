<?php

namespace Freelance\Notifier;

use Freelance\Entity\Job;
use Freelance\Template\TemplateInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface as SymfonyNotifier;

class TelegramNotifier implements NotifierInterface
{
    private const MESSAGE_MAX_LENGTH = 4000;

    public function __construct(
        private readonly SymfonyNotifier $notifier,
        private readonly TemplateInterface $template,
    ) {
    }

    /**
     * @param Job $job
     */
    public function notify(Job $job)
    {
        $this->notifier->send(
            new Notification(substr($this->escape($this->template->render($job)), 0, self::MESSAGE_MAX_LENGTH)),
        );
    }

    private function escape(string $string): string
    {
        $patterns = [
            '_' => ' ',
            '*' => ' ',
            '[' => ' ',
            ']' => ' ',
            '(' => ' ',
            ')' => ' ',
            '~' => ' ',
            '`' => ' ',
            '>' => ' ',
            '#' => ' ',
            '+' => ' ',
            '-' => ' ',
            '=' => ' ',
            '|' => ' ',
            '{' => ' ',
            '}' => ' ',
            '.' => ' ',
        ];

        return str_replace(
            array_keys($patterns),
            array_values($patterns),
            $string,
        );
    }
}