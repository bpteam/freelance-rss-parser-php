<?php

namespace Freelance\Collection;

use Freelance\Entity\Job;
use SplObjectStorage;

class JobCollection extends SplObjectStorage implements JobCollectionInterface
{
    public function add(Job $post)
    {
        $this->attach($post);
    }
}