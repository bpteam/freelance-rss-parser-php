<?php

namespace Freelance\Tests\Checker;

use DateTime;
use Freelance\Checker\KeywordsChecker;
use Freelance\Entity\Job;
use PHPUnit\Framework\TestCase;

class KeywordCheckerTest extends TestCase
{
    /**
     * @param array $keywords
     * @param Job $job
     * @param $expect
     * @dataProvider dataProviderHit
     */
    public function testHit(array $keywords, Job $job, $expect)
    {
        $checker = new KeywordsChecker($keywords);

        $this->assertEquals($expect, $checker->isHit($job));
    }

    public function dataProviderHit()
    {
        return [
            [
                ['парсе','парса','парси','сбор','собрать','скрапер','скраппер','екстрактор','экстрактор','parser','scraper','extract'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('hello')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['Hello' => 'world'])
                ,
                false
            ],
            [
                ['парсе','парса','парси','сбор','собрать','скрапер','скраппер','екстрактор','экстрактор','parser','scraper','extract'],
                (new Job())
                    ->setTitle('Сборщик данных')
                    ->setDescription('hello')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['Hello' => 'world'])
                ,
                true
            ],
            [
                ['парсе','парса','парси','сбор','собрать','скрапер','скраппер','екстрактор','экстрактор','parser','scraper','extract'],
                (new Job())
                    ->setTitle('Нужен парсер сайта на php')
                    ->setDescription('hello')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['Hello' => 'world'])
                ,
                true
            ],
            [
                ['парсе','парса','парси','сбор','собрать','скрапер','скраппер','екстрактор','экстрактор','parser','scraper','extract'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('Тут клчевое слово парсить данные из източника')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['Hello' => 'world'])
                ,
                true
            ],
            [
                ['парсе','парса','парси','сбор','собрать','скрапер','скраппер','екстрактор','экстрактор','parser','scraper','extract'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('Hello world')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['дополнительно' => 'парсер'])
                ,
                true
            ],
            [
                ['scraper','scraping','crawler','crawling','extract','parser','parsing','data mining','collect'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('Hello world')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['additional' => 'crawler'])
                ,
                true
            ],
            [
                ['scraper','scraping','crawler','crawling','extract','parser','parsing','data mining','collect'],
                (new Job())
                    ->setTitle('Hello world scraper')
                    ->setDescription('Hello world')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['additional' => 'ddddddddd'])
                ,
                true
            ],
            [
                ['scraper','scraping','crawler','crawling','extract','parser','parsing','data mining','collect'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('Helscraperlo world')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['additional' => 'ddddddddd'])
                ,
                true
            ],
            [
                ['scraper','scraping','crawler','crawling','extract','parser','parsing','data mining','collect'],
                (new Job())
                    ->setTitle('Hello world')
                    ->setDescription('hello world')
                    ->setPublishDate(new DateTime())
                    ->setUrl('https://example.com')
                    ->setOptions(['additional' => 'ddddddddd'])
                ,
                false
            ]
        ];
    }
}