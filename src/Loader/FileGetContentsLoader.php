<?php

namespace Freelance\Loader;

class FileGetContentsLoader implements LoaderInterface
{
    public function load(string $feedUrl): string
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" =>
                    "Accept-language: en\r\n"
                    . "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/77.0.3865.90 Chrome/77.0.3865.90 Safari/537.36\r\n"
            ]
        ];

        return file_get_contents($feedUrl, false, stream_context_create($opts));
    }
}