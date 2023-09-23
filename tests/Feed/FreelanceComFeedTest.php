<?php

namespace Freelance\Tests\Feed;

use Exception;
use Freelance\Feed\FreelanceComFeed;
use Freelance\Feed\UpworkComFeed;
use Freelance\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class FreelanceComFeedTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__ . '/../fixtures';

    /**
     * @param LoaderInterface $loader
     * @dataProvider dataProviderGet
     * @throws Exception
     */
    public function testGet(LoaderInterface $loader)
    {
        $feed = new FreelanceComFeed($loader);

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
                new MyLoaderFreelanceCom($this::FIXTURE_PATH . '/freelance.com-2023-07-16-feed.xml')
            ],
        ];
    }
}


class MyLoaderFreelanceCom implements LoaderInterface
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