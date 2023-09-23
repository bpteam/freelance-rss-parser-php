<?php
namespace Freelance\Template;

use Freelance\Entity\Job;

class SimpleTemplate implements TemplateInterface
{

    public function render(Job $job): string
    {
        return sprintf('Job [%d]:
URL: %s
Title: %s
Options: %s
Publish date: %s
Created at: %s
Description: %s
',
            $job->getId(),
            $job->getUrl(),
            $job->getTitle(),
            json_encode($job->getOptions(), JSON_UNESCAPED_UNICODE),
            $job->getPublishDate()->format('c'),
            $job->getCreatedAt()->format('c'),
            $job->getDescription(),
        );
    }
}