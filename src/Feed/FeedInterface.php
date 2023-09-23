<?php
namespace Freelance\Feed;

use Freelance\Collection\JobCollectionInterface;

interface FeedInterface
{
    public function get(): JobCollectionInterface;
    public function getFeedUrl(): string;
}