<?php

namespace Freelance\Checker;

use Freelance\Entity\Job;

interface CheckerInterface
{
    public function isHit(Job $job): bool;
}