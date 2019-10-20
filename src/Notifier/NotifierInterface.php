<?php
namespace Freelance\Notifier;

use Freelance\Entity\Job;

interface NotifierInterface
{
    public function notify(Job $job);
}