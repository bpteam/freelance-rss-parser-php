<?php
namespace Freelance\Feed;

use Freelance\Collection\JobCollectionInterface;

interface FeedInterface
{
    public function get(string $url): JobCollectionInterface;
}