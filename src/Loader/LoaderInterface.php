<?php
namespace Freelance\Loader;

interface LoaderInterface
{
    public function load(string $feedUrl): string;
}