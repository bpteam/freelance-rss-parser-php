<?php
namespace Freelance\Template;

use Freelance\Entity\Job;

class SimpleTemplate implements TemplateInterface
{

    public function render(Job $job): string
    {
        return sprintf('Job %d:
URL: %s
Title: %s
Description: %s
Publish date: %s
Created at: %s
Options: %s',
            $job->getId(),
            $job->getUrl(),
            $job->getTitle(),
            $job->getDescription(),
            $job->getPublishDate()->format('c'),
            $job->getCreatedAt()->format('c'),
            json_encode($job->getOptions(), JSON_UNESCAPED_UNICODE)
        );
    }
}