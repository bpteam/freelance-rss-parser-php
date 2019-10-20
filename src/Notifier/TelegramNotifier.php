<?php

namespace Freelance\Notifier;

use Freelance\Entity\Job;
use Freelance\Template\TemplateInterface;
use TgBotApi\BotApiBase\BotApi;
use TgBotApi\BotApiBase\Exception\BadArgumentException;
use TgBotApi\BotApiBase\Exception\ResponseException;
use TgBotApi\BotApiBase\Method\SendMessageMethod;

class TelegramNotifier implements NotifierInterface
{
    private const MESSAGE_MAX_LENGTH = 4000;
    /**
     * @var TemplateInterface
     */
    private $template;
    /**
     * @var BotApi
     */
    private $bitApi;
    /**
     * @var string
     */
    private $userId;

    public function __construct(
        TemplateInterface $template,
        BotApi $bitApi,
        string $userId
    ) {
        $this->template = $template;
        $this->bitApi = $bitApi;
        $this->userId = $userId;
    }

    /**
     * @param Job $job
     * @throws BadArgumentException
     * @throws ResponseException
     */
    public function notify(Job $job)
    {
        $this->bitApi->send(SendMessageMethod::create(
            $this->userId,
            substr($this->template->render($job), 0, self::MESSAGE_MAX_LENGTH)
        ));
    }
}