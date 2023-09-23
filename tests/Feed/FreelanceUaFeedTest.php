<?php

namespace Freelance\Tests\Feed;

use Exception;
use Freelance\Feed\FreelanceComFeed;
use Freelance\Feed\FreelanceUaFeed;
use Freelance\Feed\UpworkComFeed;
use Freelance\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class FreelanceUaFeedTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__ . '/../fixtures';

    /**
     * @param LoaderInterface $loader
     * @dataProvider dataProviderGet
     * @throws Exception
     */
    public function testGet(LoaderInterface $loader)
    {
        $feed = new FreelanceUaFeed($loader);

        $jobs = $feed->get();

        foreach ($jobs as $job) {
            $this->assertNotEmpty($job->getUrl());
            $this->assertNotEmpty($job->getTitle());
            $this->assertNotEmpty($job->getDescription());
            $this->assertNotEmpty($job->getCreatedAt());
            $this->assertNotEmpty($job->getPublishDate());
        }
    }

    public function dataProviderGet()
    {
        return [
            [
                new MyLoaderFreelanceUa($this::FIXTURE_PATH . '/freelance.ua-2023-07-16-feed.xml')
            ],
        ];
    }
}


class MyLoaderFreelanceUa implements LoaderInterface
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