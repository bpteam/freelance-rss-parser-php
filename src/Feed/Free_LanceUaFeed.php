<?php

namespace Freelance\Feed;

use DateTime;
use Exception;
use Freelance\Collection\JobCollection;
use Freelance\Collection\JobCollectionInterface;
use Freelance\Entity\Job;
use Freelance\Loader\LoaderInterface;

class Free_LanceUaFeed implements FeedInterface
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
        return 'https://free-lance.ua/projects/?find=парсинг,парсер,сбор,парсити,парсить,cобрать,зібрати&t='.time();
    }

    /**
     * @return JobCollectionInterface
     * @throws Exception
     */
    public function get(): JobCollectionInterface
    {
        $feed = $this->loader->load($this->getFeedUrl());
        $jobs = new JobCollection();
        if (preg_match_all('~<div class="list_item_project(?<item>.*)<div class="list_item_project~imsuU', $feed, $matches)) {
            foreach ($matches['item'] as $item) {
                $job = new Job();
                $options = [];
                if (preg_match('~<a class="title" href="[^"]*">(?<title>[^<]*)</a>~imsuU', $item, $match)) {
                    $job->setTitle($match['title']);
                } else {
                    $job->setTitle('');
                }

                if (preg_match('~<a class="title" href="(?<link>[^"]*)">[^<]*</a>~imsu', $item, $match)) {
                    $job->setUrl('https://free-lance.ua/' . $match['link']);
                } else {
                    $job->setUrl('');
                }

                if (preg_match('~<td class="description">(?<description>.*)</td>~imsuU', $item, $match)) {
                    $job->setDescription(strip_tags($match['description']));
                } else {
                    $job->setDescription('');
                }

                if (preg_match('~<div class="price">(?<price>.*)</div>~imsuU', $item, $match)) {
                    $options['price'] = $match['price'];
                }

                if (preg_match('~<div>Добавлено: (?<publish>[^<]+)</div>~imsu', $item, $match)) {
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