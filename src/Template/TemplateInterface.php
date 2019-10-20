<?php

namespace Freelance\Template;

use Freelance\Entity\Job;

interface TemplateInterface
{
    public function render(Job $job): string;
}