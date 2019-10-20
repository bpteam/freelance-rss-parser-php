<?php
namespace Freelance\Collection;

use Freelance\Entity\Job;
use Iterator;

/**
 * Interface JobCollectionInterface
 * @package Freelance\Collection
 * @method Job current()
 */
interface JobCollectionInterface extends Iterator
{
    public function add(Job $post);
}