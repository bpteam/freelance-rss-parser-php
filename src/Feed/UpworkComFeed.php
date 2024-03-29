<?php

namespace Freelance\Feed;

use DateTime;
use Exception;
use Freelance\Collection\JobCollection;
use Freelance\Collection\JobCollectionInterface;
use Freelance\Entity\Job;
use Freelance\Loader\LoaderInterface;

class UpworkComFeed implements FeedInterface
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
        return 'https://www.upwork.com/ab/feed/topics/rss?securityToken=f9b6f39073e9e85fa9160a1b900401d604ec1c664177a78d37aed30e8ff55de55465a1bb030912af366986ed2728f0b58582767d173c7d9e6201c990587bf1f4&userUid=1182274724678107136&orgUid=1182274724707467265&topic=4481918&t='.time();
    }

    /**
     * @return JobCollectionInterface
     * @throws Exception
     */
    public function get(): JobCollectionInterface
    {
        $feed = $this->loader->load($this->getFeedUrl());
        $jobs = new JobCollection();
        if (preg_match_all('~<item>(?<item>.*)</item>~imsuU', $feed, $matches)) {
            foreach ($matches['item'] as $item) {
                $job = new Job();
                $options = [];
                if (preg_match('~<title><!\[CDATA\[(?<title>.*)\]\]></title>~imsuU', $item, $match)) {
                    $job->setTitle($match['title']);
                } else {
                    $job->setTitle('');
                }

                if (preg_match('~<link>(?<link>.*)</link>~imsu', $item, $match)) {
                    $job->setUrl($match['link']);
                } else {
                    $job->setUrl('');
                }

                if (preg_match('~<description><!\[CDATA\[(?<description>.*)\]\]></description>~imsuU', $item, $match)) {
                    $job->setDescription($match['description']);
                } else {
                    $job->setDescription('');
                }

                if (preg_match('~<b>Budget</b>:\s*(?<price>.*)\s*<br\s*/>~imsuU', $item, $match)) {
                    $options['price'] = $match['price'];
                }

                if (preg_match('~<pubDate>(?<publish>[^<]+)</pubDate>~imsu', $item, $match)) {
                    $job->setPublishDate(new DateTime($match['publish']));
                } else {
                    $job->setPublishDate(new DateTime());
                }

                $job->setOptions($options);

                $jobs->add($job);
            }
        }

        return $jobs;
    }
}