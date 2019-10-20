<?php

namespace Freelance\Notifier;

use Freelance\Entity\Job;
use Freelance\Template\TemplateInterface;

class StdOutNotifier implements NotifierInterface
{
    /**
     * @var TemplateInterface
     */
    private $template;

    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function notify(Job $job)
    {
        echo $this->template->render($job);
    }
}