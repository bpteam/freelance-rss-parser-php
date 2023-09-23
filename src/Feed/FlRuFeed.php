<?php

namespace Freelance\Feed;

use DateTime;
use Exception;
use Freelance\Collection\JobCollection;
use Freelance\Collection\JobCollectionInterface;
use Freelance\Entity\Job;
use Freelance\Loader\LoaderInterface;

class FlRuFeed implements FeedInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * FlRuFeed constructor.
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function getFeedUrl(): string
    {
        return 'https://www.fl.ru/rss/all.xml?category=5&t='.time();
    }

    /**
     * @param string $url
     * @return JobCollectionInterface
     * @throws Exception
     */
    public function get(): JobCollectionInterface
    {
        $feed = $this->loader->load($this->getFeedUrl());
        $jobs = new JobCollection();
        if (preg_match_all('~<item>\s*<title><!\[CDATA\[(?<title>.*)\]\]></title>\s*<link>(?<link>.*)</link>\s*<description><!\[CDATA\[(?<description>.*)\]\]></description>\s*<guid>.*</guid>\s*<category><!\[CDATA\[(?<category>.*)\]\]></category>\s*<pubDate>(?<pubDate>.*)</pubDate>\s*</item>~imsuU', $feed, $matches)) {
            foreach ($matches['title'] as $key => $value) {
                $job = new Job();
                $options = [];
                if (preg_match('~\(Бюджет:\s*(?<price>\d+)\s+[^\)]+\)~imsu', $matches['title'][$key], $match)) {
                    $options['price'] = $match['price'];
                }
                $job->setTitle($matches['title'][$key]);
                $job->setUrl($matches['link'][$key]);

                if (preg_match('~\.\.\.$~imsu', $matches['description'][$key])) {
                    $page = $this->loader->load($job->getUrl());
                    if(
                        preg_match('~<div[^>]*id="project_info_\d+"[^>]*>.*id="anonOfferPopup"~imsu', $page, $match)
                        && preg_match('~<div[^>]*id="projectp\d+"[^>]*>(?<description>.+)</div>~imsuU', $match[0], $match)
                    ) {
                        $job->setDescription($match['description']);
                    }
                }

                if (!$job->getDescription()) {
                    $job->setDescription($matches['description'][$key]);
                }

                $job->setPublishDate(new DateTime($matches['pubDate'][$key]));

                $options['category'] = $matches['category'][$key];

                $job->setOptions($options);

                $jobs->add($job);
            }
        }

        return $jobs;
    }
}