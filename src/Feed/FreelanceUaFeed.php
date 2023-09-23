<?php

namespace Freelance\Feed;

use DateTime;
use Exception;
use Freelance\Collection\JobCollection;
use Freelance\Collection\JobCollectionInterface;
use Freelance\Entity\Job;
use Freelance\Loader\LoaderInterface;

class FreelanceUaFeed implements FeedInterface
{
    /**
     * @var LoaderInterface
     */
    private LoaderInterface $loader;

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
        return 'https://freelance.ua/orders/rss?cat_id=0&sub_id=0&t='.time();
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
                if (preg_match('~<title>(?<title>.*)</title>~imsuU', $item, $match)) {
                    $job->setTitle($match['title']);
                } else {
                    $job->setTitle('');
                }

                if (preg_match('~<link>(?<link>.*)</link>~imsu', $item, $match)) {
                    $job->setUrl($match['link']);
                } else {
                    $job->setUrl('');
                }

                if (preg_match('~<description>(?<description>.*)</description>~imsuU', $item, $match)) {
                    $job->setDescription($match['description']);
                } else {
                    $job->setDescription('');
                }

                if (preg_match('~Budget:\s*(?<price>[^,]*),~imsuU', $item, $match)) {
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