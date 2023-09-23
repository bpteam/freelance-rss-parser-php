<?php

namespace Freelance\Checker;

use Freelance\Entity\Job;

class KeywordsChecker implements CheckerInterface
{
    private $regexp;

    public function __construct(array $keywords)
    {
        $this->regexp =
            '~(' .
            implode(
                '|',
                array_map(function ($val) {return preg_quote($val, '~');}, $keywords)
            ) .
            ')~imsu'
        ;
    }

    public function isHit(Job $job): bool
    {
        $text = '';

        $text .= $job->getTitle();
        $text .= $job->getDescription();
        $text .= $job->getOptions() ? json_encode($job->getOptions(), JSON_UNESCAPED_UNICODE) : '';

        return (bool) preg_match($this->regexp, $text);
    }
}