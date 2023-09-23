<?php

namespace Freelance\Template;

use Freelance\Entity\Job;
use League\HTMLToMarkdown\HtmlConverterInterface;

class MarkdownTemplate implements TemplateInterface
{
    /**
     * @var HtmlConverterInterface
     */
    private $htmlConverter;

    public function __construct(
        HtmlConverterInterface $htmlConverter
    ) {
        $this->htmlConverter = $htmlConverter;
    }

    public function render(Job $job): string
    {
        return sprintf(
'# [%s](%s)
Options: %s
Publish: %s
%s
',
            $this->htmlConverter->convert($job->getTitle()),
            $job->getUrl(),
            json_encode($job->getOptions(), JSON_UNESCAPED_UNICODE),
            $job->getPublishDate()->format('c'),
            $this->htmlConverter->convert($job->getDescription()),
        );
    }
}