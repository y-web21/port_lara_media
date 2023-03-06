<?php

namespace App\Library;

class Api implements fetchWebApi
{
    /**
     * @param string|array<stirng> $url
     */
    public function __construct(
        protected string|array $url,
        protected string $localStoragePath = '',
    ) {
        $this->url = $this->strToArray($url);
    }

    private function strToArray(string $var) : array
    {
        if (gettype($var) === 'string') {
            return [$var];
        } else {
            $this->localStoragePath = $var;
        }
    }

    public function fetchData()
    {
    }
}
