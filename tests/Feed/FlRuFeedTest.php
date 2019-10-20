<?php

namespace Freelance\Tests\Feed;

use Exception;
use Freelance\Feed\FlRuFeed;
use Freelance\Loader\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FlRuFeedTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__ . '/../fixtures';

    /**
     * @param LoaderInterface $loader
     * @dataProvider dataProviderGet
     * @throws Exception
     */
    public function testGet(LoaderInterface $loader)
    {
        $feed = new FlRuFeed($loader);

        $jobs = $feed->get('dummy');
        foreach ($jobs as $job) {
            $this->assertNotEmpty($job->getUrl());
            $this->assertNotEmpty($job->getTitle());
            $this->assertNotEmpty($job->getDescription());
            $this->assertNotEmpty($job->getCreatedAt());
            $this->assertNotEmpty($job->getPublishDate());
            $this->assertNotEmpty($job->getOptions());
        }
    }

    public function dataProviderGet()
    {
        return [
            [
                new MyLoaderFlRu($this::FIXTURE_PATH . '/2019-10-11-fl.ru-feed.xml')
            ],
        ];
    }
}

class MyLoaderFlRu implements LoaderInterface
{
    private $fixture;
    public function __construct($fixture)
    {
        $this->fixture = $fixture;
    }

    public function load(string $feedUrl): string
    {
        return file_get_contents($this->fixture);
    }
}