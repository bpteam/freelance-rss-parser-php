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
%s
Publish: %s
Options: %s',
            $this->htmlConverter->convert($job->getTitle()),
            $job->getUrl(),
            $this->htmlConverter->convert($job->getDescription()),
            $job->getPublishDate()->format('c'),
            json_encode($job->getOptions(), JSON_UNESCAPED_UNICODE)
        );
    }
}