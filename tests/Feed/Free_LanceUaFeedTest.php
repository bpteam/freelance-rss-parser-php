<?php

namespace Freelance\Tests\Feed;

use Exception;
use Freelance\Feed\FlRuFeed;
use Freelance\Feed\Free_LanceUaFeed;
use Freelance\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class Free_LanceUaFeedTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__ . '/../fixtures';

    /**
     * @param LoaderInterface $loader
     * @dataProvider dataProviderGet
     * @throws Exception
     */
    public function testGet(LoaderInterface $loader)
    {
        $feed = new Free_LanceUaFeed($loader);

        $jobs = $feed->get();
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
                new MyLoaderFree_LanceUa($this::FIXTURE_PATH . '/free-lance.ua-2023-07-16-page.html')
            ],
        ];
    }
}

class MyLoaderFree_LanceUa implements LoaderInterface
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